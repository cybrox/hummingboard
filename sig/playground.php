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
	
	
	/* Fake input data */
	$userName     = "cybrox";
	$userOpts     = (empty($_GET['pref'])) ? "" : $_GET['pref'];
	$userPref     = explode(";", $userOpts);
	$responseData = array(
		"name" => "cybrox",
		"avatar" => "http://static.hummingbird.me/users/avatars/000/025/515/thumb/dca7dc42b6db16763a9587fb5ab37532_(1).jpeg?1384196341",
		"cover_image" => "http://static.hummingbird.me/users/cover_images/000/025/515/thumb/wallpaper-2838683.jpg?1381410200",
		"life_spent_on_anime" => "19594"
	);
	
	
	/* Calculate watchtime */
	$timeYears   = floor($responseData['life_spent_on_anime'] / 525948.766);
	$timeLeft    = $responseData['life_spent_on_anime'] % 525948.766;
	$timeMonths  = floor($timeLeft / 43829.766);
	$timeLeft    = $timeLeft % 43829.0639;
	$timeDays    = floor($timeLeft / 1440);
	$timeLeft    = $timeLeft % 1440;
	$timeHours   = floor($timeLeft / 60);
	$timeMinutes = floor($timeLeft % 60);
	$timeWatchd1 = $timeYears." year, ".$timeMonths." months, ";
	$timeWatchd2 = $timeDays." days, ".$timeHours." hours, ".$timeMinutes." minutes";
	
	$timeWatched = ($timeYears == 0 && $timeMonths == 0) ? $timeWatchd2 : $timeWatchd1.$timeWatchd2;
	
	
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
	
	$signatureBg0 = imagecolorallocatealpha($signatureImg, 0, 0, 0, $imgBoxAp);
	$signatureBg1 = ImageColorAllocate($signatureImg, 0, 0, 0);
	$signatureTx1 = ImageColorAllocate($signatureImg, $imgColr1, $imgColr1, $imgColr1);
	$signatureTx2 = ImageColorAllocate($signatureImg, $imgColr2, $imgColr2, $imgColr2);
	$signatureFn1 = './lib/font/Ubuntu-'.$imgFontF.'.ttf';
	$signatureSt1 = "My life spent watching anime:";
	
	
	/* Add informations to image */
	imagecolortransparent($signatureImg, $signatureBg1);
	imagecopyresampled($signatureImg, $usrCover, 0, 0, 0, 43, $imgSizeX, $imgSizeY, 760, 164);
	imagecopyresampled($signatureImg, $usrAvatr, $avaPaddB, $avaPaddB, 0, 0, $avaSizeB, $avaSizeB, 190, 190);
	imagefilledrectangle($signatureImg, $boxPntX1, $boxPntY1, $boxPntX2, $boxPntY2, $signatureBg0);
	imagefttext($signatureImg, $imgFonS1, 0, $fntOffsX, $fntUserY, $signatureTx1, $signatureFn1, $responseData['name']);
	imagefttext($signatureImg, $imgFonS2, 0, $fntOffsX, $fntAnmeY, $signatureTx2, $signatureFn1, $signatureSt1);
	imagefttext($signatureImg, $imgFonS2, 0, $fntOffsX, $fntTimeY, $signatureTx1, $signatureFn1, $timeWatched);
	
	
	/* Save and output image */
	ImagePNG($signatureImg);

?>