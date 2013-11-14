<?php

/**
 * Hummingboard Signatures
 * Simple but elegant Hummingbird signatures
 *
 * Written and copyrighted 2013+
 * by Sven Marc 'CybroX' Gehring
 *
 * Licensed under CC BY-SA 3.0
 * For additional informations, please read the
 * LICENSE.md file or the license deed at the
 * Creative Commons website.
 */
	
	header("Content-type: image/png");
	
	define("APIURL", "https://hummingbirdv1.p.mashape.com/");
	define("APIKEY", "71IpOFQi3khmCFUOdGhvzlrV221YF0yy");
	
	require_once('./lib/Unirest.php');
	
	
	/* Read input data from URL */
	$requestUrl =  explode("/", $_SERVER["REQUEST_URI"]);
	$userName   = (empty($requestUrl[1])) ? "" : $requestUrl[1];
	$userOpts   = (empty($requestUrl[2])) ? "" : $requestUrl[2];
	$userPref   =  explode(";", $userOpts);
	
	
	/* Allow adding fake suffixes for image recognisation */
	if(strstr($userName, ".")){
		$userPart = explode(".", $userName);
		$userName = $userPart[0];
	}
	
	
	/* Check if this user already has an active signature */
	$userFile = "./img/".strtolower($userName);
	$userImag = $userFile.".png";
	
	if(file_exists($userImag)){
		if((time() - filemtime($userImag)) < 84400){
			header("Content-Length:".filesize($userImag));
			readfile($userImag);
			exit();
		}
	}
	
	
	/* Load user details from Hummingbird API */	
	$apiResponse = Unirest::get(APIURL."users/".$userName, array("X-Mashape-Authorization" => APIKEY)); 
			
	if($apiResponse->code !== 200) return false;
			
	$responseBody = $apiResponse->raw_body;
	$responseData = json_decode($responseBody, true);
	
	
	/* Calculate watchtime */
	$timeYears   = floor($responseData['life_spent_on_anime'] / 525948.766);
	$timeLeft    = $responseData['life_spent_on_anime'] % 525948.766;
	$timeMonths  = floor($timeLeft / 43829.766);
	$timeLeft    = $timeLeft % 43829.0639;
	$timeDays    = floor($timeLeft / 1440);
	$timeLeft    = $timeLeft % 1440;
	$timeHours   = floor($timeLeft / 60);
	$timeMinutes = floor($timeLeft % 60);
	$timeWatchd1 = $timeYears." Years, ".$timeMonths." Months, ";
	$timeWatchd2 = $timeDays." Days, ".$timeHours." Hours, ".$timeMinutes." Minutes";
	
	if($timeYears == 0 && $timeMonths == 0) $timeWatchd1 = "";
	
	
	/* Set image settings */
	$imgSizeX = (!empty($userPref[0])) ? $userPref[0]: 500; 
	$imgSizeY = (!empty($userPref[1])) ? $userPref[1]: 100; 
	
	$usrAvatr = @imagecreatefromjpeg($responseData['avatar']);
	if(!$usrAvatr){
		$usrAvatr = @imagecreatefrompng($responseData['avatar']);
		if(!$usrAvatr){
			$usrAvatr = @imagecreatefromgif($responseData['avatar']);
		}
	}
	
	
	/* Create image */
	$signatureImg = @ImageCreateTrueColor($imgSizeX, $imgSizeY) or die ("Internal Error");
	$signatureBg1 = ImageColorAllocate($signatureImg, 0, 0, 0);
	$signatureBg2 = ImageColorAllocate($signatureImg, 70, 70, 70);
	$signatureTx1 = ImageColorAllocate($signatureImg, 50, 50, 50);
	$signatureTx2 = ImageColorAllocate($signatureImg, 75, 75, 75);
	
	
	/* Add informations to image */
	imagecolortransparent($signatureImg, $signatureBg1);
	imagefilledrectangle($signatureImg, 8, 8, 92, 92, $signatureBg2);
	@imagecopyresampled($signatureImg, $usrAvatr, 10, 10, 0, 0, 80, 80, 190, 190);
	ImageString($signatureImg, 5, 100, 10, $responseData['name'], $signatureTx1);
	ImageString($signatureImg, 3, 100, 50, $timeWatchd1, $signatureTx1);
	ImageString($signatureImg, 3, 100, 60, $timeWatchd2, $signatureTx1);
	ImageString($signatureImg, 4, 100, 72, "spent on watching anime.", $signatureTx2);
	
	
	/* Save and output image */
	ImagePNG($signatureImg);
	imagePNG($signatureImg, $userFile.".png");

?>