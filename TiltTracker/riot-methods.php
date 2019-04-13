<?php
include('php-riot-api.php');
require_once "config.php";
//testing classes
//using double quotes seems to make all names work (see issue: https://github.com/kevinohashi/php-riot-api/issues/33)
if(!isset($_COOKIE["summoner"])) 
{header("location: logout.php");}
$api = new riotapi('na1');
$summoner_name = $_COOKIE["summoner"];
$summoner_id = $api->getSummonerID($summoner_name);
$account_id = $api->getSummonerAccountID($summoner_name);
$sql = "SELECT lastgametime FROM users WHERE summoner='$summoner_name' ";
$result = $link->query($sql);
$lastmatchtime = $result->fetch_assoc()["lastgametime"];
$max = $lastmatchtime;

$sql = "SELECT tilt FROM users WHERE summoner='$summoner_name' ";
$result = $link->query($sql);
$tilt = $result->fetch_assoc()["tilt"];
//echo "this is the result".$result->fetch_assoc()["lastgametime"];

// $r = $api->getChampion();
// $r = $api->getChampion(true);
// $r = $api->getChampionMastery(23516141);
// $r = $api->getChampionMastery(23516141,1);
// $r = $api->getCurrentGame(23516141);
// $api->setPlatform("na1");
// $r = $api->getStatic("champions", 1, "locale=fr_FR&tags=image&tags=spells");
// $api->setPlatform("euw1");
// $r = $api->getMatch(2898677684);
// $r = $api->getMatch(2898677684,false);
// $r = $api->getTimeline(2898677684);
// $r = $api->getMatchList(27695644);
// $params = array(
	// "queue"=>array(4,8),
	// "beginTime"=>1439294958000
// );
// $r = $api->getMatchList(27695644, $params);
// $r = $api->getRecentMatchList($summoner_id);
// $r = $api->getLeague(24120767);
// $r = $api->getLeaguePosition(24120767);
// $r = $api->getChallenger();
// $r = $api->getMaster();
/*
try {
    $r = $api->getSummonerByName($summoner_name);
    print_r($r);
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
};
echo "<br>";
try {
    $r = $api->getSummoner($summoner_id);
    print_r($r);
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
};
echo "<br>";
*/

    if( isset($_POST["settilt"]) ) {
        $tilt = $_POST["settilt"];
        $sql = "UPDATE users SET tilt=$tilt WHERE summoner = '$summoner_name' ";
        $link->query($sql);
        header("location:home.php");
    }
    if( isset($_POST["updatetilt"]) ) {
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
    $sql = "UPDATE users SET tilt=$change WHERE summoner = '$summoner_name' ";
    $link->query($sql);

    $sql = "UPDATE users SET lastgametime=$max WHERE summoner = '$summoner_name' ";
    $link->query($sql);

    /*
    $match_id = $r['matches']['0']['gameId'];
    //print_r($match_id);
    echo "<br>";
    print_r($api->getMatch($match_id,false)['participantIdentities']);
    echo "<br>";
    $Sum_player = NULL;
    foreach($api->getMatch($match_id,false)['participantIdentities'] as $participant){
        print_r($participant);
        if ($summoner_id == $participant['player']['summonerId']){
            $Sum_player = $participant['participantId'];
        }
    }
    print($Sum_player);
    echo "<br>";
    $Sum_team = NULL;
    foreach($api->getMatch($match_id,false)['participants'] as $participant){
        print_r($participant);
        if ($Sum_player == $participant['participantId']){
            $Sum_team = $participant['teamId'];
        }
    }
    print($Sum_team);
    echo "<br>";
    $Sum_win = NULL;
    foreach($api->getMatch($match_id,false)['teams'] as $team){
        print_r($team);
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
    }
    else{
        echo "<br> You've Failed!" ;
    }
    */

    //print_r($match);
    //print("");
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
};
}
?>