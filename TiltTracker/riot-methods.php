<?php
include('php-riot-api.php');
require_once "config.php";
//testing classes
//using double quotes seems to make all names work (see issue: https://github.com/kevinohashi/php-riot-api/issues/33)
function in_summoners($summoner_name){
    if(valid_summoner($summoner_name)){
        $link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
        $sql =  "SELECT summoner_name FROM summoners WHERE summoner='$summoner_name' ";
        $result = $link -> query($sql);
        if ($result -> fetch_assoc() == NULL){
            echo "not in summoners";
            return false;
        }
    }
    echo "in summoners";
    return true;
}

function add_summoner($summoner_name){
    //if(!(in_summoners($summoner_name))){
        $link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
        $sql = "INSERT INTO summoners (summoner) 
        VALUES ('$summoner_name')";
        $link->query($sql);
        echo "inserted into summoners";
    //}
}
function valid_summoner($summoner_name){
    try
    {
        $api = new riotapi('na1');
        $summoner_id = $api->getSummonerID($summoner_name);
        return true;
    }
    catch(Exception $e)
    {
        return false;
    }
}

function last_match_time($summoner_name){
    $link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    $sql = "SELECT lastgametime FROM summoners WHERE summoner='$summoner_name' ";
    $result = $link->query($sql);
    $lastmatchtime = $result->fetch_assoc()["lastgametime"];
    if ($lastmatchtime == NULL){
        $lastmatchtime = 0;
    }
    return $lastmatchtime;
}

function updateTilt($summoner_name){
    if(isset($_COOKIE["summoner"])) 
    {
        $link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
        $api = new riotapi('na1');
        $summoner_name = $_COOKIE["summoner"];
        $summoner_id = $api->getSummonerID($summoner_name);
        $account_id = $api->getSummonerAccountID($summoner_name);
        $lastmatchtime = last_match_time($summoner_name);
            
        $max = $lastmatchtime;

        $sql = "SELECT tilt FROM summoners WHERE summoner='$summoner_name' ";
        $result = $link->query($sql);
        $tilt = $result->fetch_assoc()["tilt"];

        try {
            $r = $api->getMatchList($account_id);
            $netimprovement = 0;
            $count = 0;
            $increment = $_SESSION["increment"];
            foreach($r['matches'] as $match){
                $count += $increment;
                $increment += 1;
                if($count > 9){
                    break;
                }
        
                $match_id = $match['gameId'];
                $match_details = $api->getMatch($match_id,false);
                //print_r($match_id);
                //echo "<br>";
                //print_r($api->getMatch($match_id,false)['participantIdentities']);
                //echo "<br>";
        
                if($lastmatchtime!= NULL){
                    if ($match_details['gameCreation'] <= $lastmatchtime){ 
                    echo "no new data";
                        break;
                    }
                }
                if($match_details['gameCreation'] > $max){
                    $max = $match_details['gameCreation'];
                }
        
                $Sum_player = NULL;
                foreach($match_details['participantIdentities'] as $participant){
                    //print_r($participant);
                    if ($summoner_id == $participant['player']['summonerId']){
                        $Sum_player = $participant['participantId'];
                    }
                }
                //print($Sum_player);
                //echo "<br>";
                $Sum_team = NULL;
                foreach($match_details['participants'] as $participant){
                    //print_r($participant);
                    if ($Sum_player == $participant['participantId']){
                        $Sum_team = $participant['teamId'];
                    }
                }
                //print($Sum_team);
                //echo "<br>";
                $Sum_win = NULL;
                foreach($match_details['teams'] as $team){
                    //print_r($team);
                    if ($Sum_team == $team['teamId']){
                        if($team['win'] == "Win"){
                            $Sum_win = true;
                        }
                        else{
                            $Sum_win = false; 
                        }
                       
                    }
                }
                if($Sum_win){
                    echo "<br> You've Won!" ;
                    $netimprovement -= $increment;
                }
                else{
                    echo "<br> You've Failed!" ;
                    $netimprovement += $increment;
                }
            }
            $change = $tilt + $netimprovement;
            if ($change > 100){
                $change = 100;
            }
            if ($change < 0 ){
                $change = 0;
            }
            $_SESSION["increment"] = $increment;
            $sql = "UPDATE summoners SET tilt=$change WHERE summoner = '$summoner_name' ";
            $link->query($sql);
        
            $sql = "UPDATE summoners SET lastgametime=$max WHERE summoner = '$summoner_name' ";
            $link->query($sql);
        
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        };
    }
}
    if( isset($_POST["settilt"]) ) {
        if(isset($_COOKIE["summoner"])) 
        {
        $tilt = $_POST["settilt"];
        $sql = "UPDATE users SET tilt=$tilt WHERE summoner = '$summoner_name' ";
        $link->query($sql);
        header("location:home.php");
        }
        else{
            echo '<script language="javascript">';
            echo 'alert("Cookie has expired, Please login again.")';
            echo '</script>';
            header("location:login.php");
        }
    }

    if( isset($_GET["refresh"]) ) {
        if(isset($_COOKIE["summoner"])){
            updateTilt($_COOKIE["summoner"]);
        }
        else{
            echo '<script language="javascript">';
            echo 'alert("Cookie has expired, Please login again.")';
            echo '</script>';
            header("location:login.php");
        }
    }

?>