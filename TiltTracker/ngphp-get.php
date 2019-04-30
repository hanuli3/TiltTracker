<?php
require_once "config.php";
// try commenting out the header setting to experiment how the back end refuses the request
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');

// retreive data from the request
$getdata = $_GET['str'];

$link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

// process data
// (this example simply extracts the data and restructures them back)
$request = json_decode($getdata);
$data_array = json_decode($getdata, true)

$data = [];
foreach ($request as $k => $v)
{
   $data[0]['get'.$k] = $v;
}

$sql = "INSERT INTO summoners (name,phone,email,suggestion,type,reply) VALUES ($data_array["name"],$data_array["phone"],$data_array["email"],$data_array["suggestion"],$data_array["drink],$data_array["sendText"])";
$link->query($sql);

// send response (in json format) back the front end
echo json_encode(['content'=>$data]);
?>