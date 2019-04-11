function onSignIn(googleUser) {
	var id_token = googleUser.getAuthResponse().id_token;
	var xhr = new XMLHttpRequest();
	xhr.open('Post, "");
	xhr.setRequestHeader();
	xhr.send()
}

<?php

if($_SERVER['REQUEST_METHOD']) ==='POST'){
	$MY_CLIENT_ID = 
	$MY_CLIENT_SECRET = 

	$client = new Google_Client();
	$client->setClientId($MY_CLIENT_ID);
	$client->setClientSecret($MY_CLIENT_SECRET);
	$client->addScope(email);
	$client->setAccessType("offline")
	$client->setApplicationName('')
	$client->setApprovalPrompt("force")

	$oauth = new Google_Service_Oauth($client);
	$id_token = $_POST['idtoken'];
	$payload = $client -> verifyIdToken(id_token);

	if($payload) {
		$connection = new mysqli($host, $username, $password, $dbname);
		if(connection->connect_error){
			die("Connection failed: ". $connection->connect_error);
		}

		$id = $payload['sub'];
		$name = $payload['given_name'];

		session_start();
		$_SESSION['id'] = $id;


	}
}

function userExists($id){
	require("config.php");
	$connection = new mysqli($host, $username, $password, $dbname)
}
