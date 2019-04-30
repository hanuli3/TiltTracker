<?php

// try commenting out the header setting to experiment how the back end refuses the request
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');
require_once "config.php";
// retreive data from the request
$getdata = $_GET['str'];

$link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

// process data
// (this example simply extracts the data and restructures them back)
$request = json_decode($getdata);
$data_array = json_decode($getdata, true);

$data = [];
foreach ($request as $k => $v)
{
   $data[0]['get'.$k] = $v;
}

$name = $data_array['name'];
$phone = $data_array['phone'];
$email = $data_array['email'];
$suggestion = $data_array['suggestion'];
$drink = $data_array['drink_option'];
$reply = $data_array['sendtxt'];

$sql = "INSERT INTO suggestions (name,phone,email,suggestion,type,reply) VALUES ('$name',$phone ,'$email','$suggestion','$drink',$reply)";
$link->query($sql);

// send response (in json format) back the front end
echo json_encode(['content'=>$data]);
?>