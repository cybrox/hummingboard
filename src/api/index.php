<?php

	require_once('../core/config.php');
	require_once('../core/lib/Unirest.php');
	require_once('../core/hummingboard.class.php');
	
	$hummingbuser = (!empty($_GET['user'])) ? $_GET['user'] : "";
	$hummingboard = new Hummingboard($hummingbuser);

	$userStatistics = $hummingboard->generateStatistics();
	
	echo $userStatistics;
	
?>