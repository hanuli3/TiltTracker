<?php
include('php-riot-api.php');
require_once "config.php";
//testing classes
//using double quotes seems to make all names work (see issue: https://github.com/kevinohashi/php-riot-api/issues/33)
//Sets the summoner name cookie
if(isset($_COOKIE["summoner"])) {
    $summoner_name = $_COOKIE["summoner"];
}
/*
else{
    echo '<script language="javascript">';
    echo 'alert("Cookie has expired, Please login again.")';
    echo '</script>';
    header("location:login.php");
}
*/
//Sets tilt
if( isset($_POST["settilt"]) ) {
    $summoner_name = $_COOKIE["summoner"];         
    $tilt = $_POST["settilt"];
    $sql = "UPDATE summoners SET tilt=$tilt WHERE summoner = '$summoner_name' ";
    $link->query($sql);
    header("location:home.php");
    }

//Refreshes the summoner tilt
if( isset($_GET["refresh"]) ) {
    updateTilt($_COOKIE["summoner"]);
}

//Adds friend
if( isset($_POST["friend"]) ) {
    addFriend($_POST["friend"]);
}

//Deletes friend
if( isset($_POST["deleteFriend"]) ) {
    deleteFriend($_POST["deleteFriend"]);
}

//Refreshes friends list
if( isset($_GET["refreshfriends"]) ) {
    $farray = getFriendsArray($summoner_name);
    foreach ($farray as $friend){
        //echo "updating ".$friend;
        updateTilt($friend);
    }
}

//Get tilt score of summoner
function getTilt($summoner_name){
    if (!(in_summoners($summoner_name))){
        add_summoner($summoner_name);
        last_match_time($summoner_name);
        updateTilt($summoner_name);
    }
    $link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    $sql =  "SELECT tilt FROM summoners WHERE summoner='$summoner_name' ";
    $result = $link -> query($sql);
    return $result -> fetch_assoc()["tilt"];

}

//Adds friend to friends list
function addFriend($friend_name){
    $owner = $_COOKIE["summoner"];
    if(valid_summoner($friend_name)){
        $farray = getFriendsArray($owner);
        if (in_array($friend_name, $farray))
        {
            echo 'Friend is already added';
            return false;
        }
        else{
            echo 'Added friend';
            $updatefriends = "";
            foreach ($farray as $friend){
                $updatefriends = $updatefriends."-".$friend;
            }
            $link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            $updatefriends = $updatefriends."-".$friend_name;
            $updatefriends = trim($updatefriends);
            $updatefriends = trim($updatefriends, "-");
            $sql = "UPDATE users SET friends='$updatefriends' WHERE summoner = '$owner' ";
            $link->query($sql);
            return true;
        }
    }
    else{
        echo 'Invalid summoner name';
        return false;
    }
}

//Removes friend from friends list
function deleteFriend($friend_name){
    $owner = $_COOKIE["summoner"];
        $farray = getFriendsArray($owner);
        if (in_array($friend_name, $farray))
        {
            echo "deleted friend ";
            $updatefriends = "";
            foreach ($farray as $friend){
                if($friend != $friend_name){
                    $updatefriends = $updatefriends."-".$friend;
                }
            }
            $link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            $updatefriends = trim($updatefriends);
            $updatefriends = trim($updatefriends, "-");
            $sql = "UPDATE users SET friends='$updatefriends' WHERE summoner = '$owner' ";
            $link->query($sql);
            return true;
        }
        else{
            echo "not in friends list";
            return false;
        }
    return false;
}

//Gets friends list
function getFriendsArray($summoner_name){
    if(valid_summoner($summoner_name)){
        $link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
        $sql =  "SELECT friends FROM users WHERE summoner='$summoner_name' ";
        $result = $link -> query($sql);
        $fstring = $result -> fetch_assoc()["friends"];
        $farray = explode("-", $fstring);
        return $farray;
    }
    return;
}

//Checks if name in summoners
function in_summoners($summoner_name){
    if(valid_summoner($summoner_name)){
        $link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
        $sql =  "SELECT summoner_name FROM summoners WHERE summoner='$summoner_name' ";
        $result = $link -> query($sql);
        if ($result == null){
            return false;
        }
    }
    return true;
}

//Adds summoner into data table
function add_summoner($summoner_name){
    if(!(in_summoners($summoner_name))){
        $link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
        $sql = "INSERT INTO summoners (summoner) 
        VALUES ('$summoner_name')";
        $link->query($sql);
    }
}

//Checks for valid summoner name
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

//Gets last match time for that summoner
function last_match_time($summoner_name){
    $link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    $sql = "SELECT lastgametime FROM summoners WHERE summoner='$summoner_name' ";
    $result = $link->query($sql);
    $lastmatchtime = $result->fetch_assoc()["lastgametime"];
    if ($lastmatchtime == NULL){
        $lastmatchtime = 0;
    }
    if (time()-21600 > $lastmatchtime){
        $newtime = time()-21600;
        $sql = "Update summoners SET lastgametime=$newtime WHERE summoner='$summoner_name' ";
        $lastmatchtime = $newtime;
    }
    return $lastmatchtime;
}

//Updates the tilt score based on match results
function updateTilt($summoner){
    if(isset($_COOKIE["summoner"])) 
    {
        //echo "in update tilt with ". $summoner;
        $link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
        $api = new riotapi('na1');
        $summoner_name = $summoner;
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
                $count += 1;
                $increment += 1;
                if($count > 9){
                    //echo"more than 9 matches";
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
                        //echo" out of new games ";
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
                    //echo "Win";
                    $netimprovement -= 1;
                }
                else{
                    //echo "Loss";
                    $netimprovement += 1;
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
?>