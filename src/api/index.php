<?php

	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');
	
	require_once("./config.php");
	require_once("./unilib.php");
	
	$requestUrl  = explode("/api/", $_SERVER['REQUEST_URI']);
	$apiResponse = Unirest::get(APIURL.$requestUrl[1], array("X-Mashape-Authorization" => APIKEY)); 
	
	if($apiResponse->code === 200) echo $apiResponse->raw_body;
	else die("{\"success\": false, \"data\": \"\"}");
	
?>