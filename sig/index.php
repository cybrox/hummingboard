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
	
	require_once('./lib/config.php');
	require_once('./lib/Unirest.php');
	
	
	/* Read input data from URL */
	$requestUrl =  explode("/", $_SERVER["REQUEST_URI"]);
	$userName   = (empty($requestUrl[1])) ? "" : $requestUrl[1];
	$userOpts   = (empty($requestUrl[2])) ? "" : $requestUrl[2];
	if(strstr($userOpts, ";")){
		$userPref = explode(";", $userOpts);
	} else {
		$userPref = array();
	}
	
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
	$timeLable    = array(" year", " month", " day", " hour", " minute");
	$watchTime    = array();
	$watchTime[0] = floor($responseData['life_spent_on_anime'] / 525948);
	$timeLeft     = $responseData['life_spent_on_anime'] % 525948;
	$watchTime[1] = floor($timeLeft / 43829);
	$timeLeft     = $timeLeft % 43829;
	$watchTime[2] = floor($timeLeft / 1440);
	$timeLeft     = $timeLeft % 1440;
	$watchTime[3] = floor($timeLeft / 60);
	$watchTime[4] = floor($timeLeft % 60);
	$timeWatched  = "";
	
	foreach($watchTime as $i => $time){
		if($time == 0) continue;
		if($time != 1) $timeLable[$i] .= "s";
		
		$timeWatched .= $time.$timeLable[$i].", ";
	}
	
	$timeWatched = substr($timeWatched, 0, -2);
	if(empty($timeWatched)) $timeWatched = "Too much to display here.";
	
	
	/* Load images from Hummingbird ... (I wanna have an imagecreatefrom* function -.-') */
	               $usrAvatr = @imagecreatefromjpeg($responseData['avatar']);
	if(!$usrAvatr) $usrAvatr = @imagecreatefrompng($responseData['avatar']);
	if(!$usrAvatr) $usrAvatr = @imagecreatefromgif($responseData['avatar']);
	               $usrCover = @imagecreatefromjpeg($responseData['cover_image']);
	if(!$usrCover) $usrCover = @imagecreatefrompng($responseData['cover_image']);
	if(!$usrCover) $usrCover = @imagecreatefromgif($responseData['cover_image']);
	
	
	/* Read additional options */
	$imgSizeX = (!empty($userPref[0])) ? $userPref[0]: 500; 
	$imgSizeY = (!empty($userPref[1])) ? $userPref[1]: 100;
	$imgBoxAp = (!empty($userPref[2])) ? $userPref[2]: 60;
	$imgColr1 = (!empty($userPref[3])) ? $userPref[3]: 255;
	$imgColr2 = (!empty($userPref[4])) ? $userPref[4]: 210;
	$imgFonS1 = (!empty($userPref[5])) ? $userPref[5]: 15;
	$imgFonS2 = (!empty($userPref[6])) ? $userPref[6]: 13;
	$imgFontF = (!empty($userPref[7])) ? $userPref[7]: "R";
	$imgFontF = strtoupper($imgFontF);
	
	/* Check font parameter 'cause it can break everything */
	$validFonts = array("R", "M", "B");
	if(!in_array($imgFontF, $validFonts)) $imgFontF = "R";
	
	
	/* Calculate image values */
	$avaSizeB = $imgSizeY * 0.8;
	$avaPaddB = $imgSizeY * 0.1;
	$boxPntX1 = $avaSizeB + $avaPaddB;
	$boxPntX2 = $imgSizeX - $avaPaddB;
	$boxPntY1 = $avaPaddB;
	$boxPntY2 = $imgSizeY - $avaPaddB;
	$fntOffsX = $boxPntX1 + $avaPaddB;
	$fntUserY = 3 * $avaPaddB;
	$fntAnmeY = $imgSizeY / 2 + 2 * $avaPaddB;
	$fntTimeY = $fntAnmeY + $imgFonS1;
	
	
	/* Create image and allocate used colors */
	$signatureImg = ImageCreateTrueColor($imgSizeX, $imgSizeY);
	
	$signatureBg1 = imagecolorallocatealpha($signatureImg, 0, 0, 0, $imgBoxAp);
	$signatureTx1 = ImageColorAllocate($signatureImg, $imgColr1, $imgColr1, $imgColr1);
	$signatureTx2 = ImageColorAllocate($signatureImg, $imgColr2, $imgColr2, $imgColr2);
	$signatureFn1 = './lib/font/Ubuntu-'.$imgFontF.'.ttf';
	$signatureSt1 = "My life spent watching anime:";
	
	
	/* Add informations to image */
	imagecopyresampled($signatureImg, $usrCover, 0, 0, 0, 43, $imgSizeX, $imgSizeY, 760, 164);
	imagecopyresampled($signatureImg, $usrAvatr, $avaPaddB, $avaPaddB, 0, 0, $avaSizeB, $avaSizeB, 190, 190);
	imagefilledrectangle($signatureImg, $boxPntX1, $boxPntY1, $boxPntX2, $boxPntY2, $signatureBg1);
	imagefttext($signatureImg, $imgFonS1, 0, $fntOffsX, $fntUserY, $signatureTx1, $signatureFn1, $responseData['name']);
	imagefttext($signatureImg, $imgFonS2, 0, $fntOffsX, $fntAnmeY, $signatureTx2, $signatureFn1, $signatureSt1);
	imagefttext($signatureImg, $imgFonS2, 0, $fntOffsX, $fntTimeY, $signatureTx1, $signatureFn1, $timeWatched);
	
	
	/* Save and output image */
	ImagePNG($signatureImg);
	imagePNG($signatureImg, $userFile.".png");

?>