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
		"avatar" => "http://static.hummingbird.me/users/avatars/000/025/515/thumb/cg.fw.png?1384637637",
		"cover_image" => "http://static.hummingbird.me/users/cover_images/000/025/515/thumb/wallpaper-1736374.jpg?1384717343",
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
	$imgFontF = (!empty($userPref[7])) ? $userPref[7]: "M";
	
	
	/* Calculate image values */
	$sigMargn = $imgSizeY * 0.10;		// Margin  for the box and the avatar
	$sigPaddn = $imgSizeY * 0.05;		// Padding for the box
	$avaSizeB = $imgSizeY * 0.80;		// Avatar size, 80% of signature height (square)
	$boxPntX1 = $avaSizeB + $sigMargn;	// X distance of the first  boxpoint
	$boxPntX2 = $imgSizeX - $sigMargn;	// X distance of the second boxpoint
	$boxPntY1 = $sigMargn;				// Y distance of the first  boxpoint
	$boxPntY2 = $imgSizeY - $sigMargn;	// Y distance of the second boxpoint
	$fntOffsX = $boxPntX1 + $sigMargn;	// X offset of the font (distance from avatar)
	$fntUserY = $sigMargn + $sigPaddn + $imgFonS1;
	$fntAnmeY = $imgSizeY - $sigMargn - (2 * $sigPaddn) - $imgFonS2;
	$fntTimeY = $fntAnmeY + $sigPaddn + $imgFonS2;
	
	
	/* Create image and allocate used colors */
	$signatureImg = ImageCreateTrueColor($imgSizeX, $imgSizeY);
	
	$signatureBg1 = imagecolorallocatealpha($signatureImg, 0, 0, 0, $imgBoxAp);
	$signatureTx1 = ImageColorAllocate($signatureImg, $imgColr1, $imgColr1, $imgColr1);
	$signatureTx2 = ImageColorAllocate($signatureImg, $imgColr2, $imgColr2, $imgColr2);
	$signatureFn1 = './lib/font/Font-'.$imgFontF.'.ttf';
	$signatureSt1 = "My life spent watching anime:";
	
	
	/* Add informations to image */
	imagecopyresampled($signatureImg, $usrCover, 0, 0, 0, 43, $imgSizeX, $imgSizeY, 760, 164);
	imagecopyresampled($signatureImg, $usrAvatr, $sigMargn, $sigMargn, 0, 0, $avaSizeB, $avaSizeB, 190, 190);
	imagefilledrectangle($signatureImg, $boxPntX1, $boxPntY1, $boxPntX2, $boxPntY2, $signatureBg1);
	imagefttext($signatureImg, $imgFonS1, 0, $fntOffsX, $fntUserY, $signatureTx1, $signatureFn1, $responseData['name']);
	imagefttext($signatureImg, $imgFonS2, 0, $fntOffsX, $fntAnmeY, $signatureTx2, $signatureFn1, $signatureSt1);
	imagefttext($signatureImg, $imgFonS2, 0, $fntOffsX, $fntTimeY, $signatureTx1, $signatureFn1, $timeWatched);
	
	
	/* Save and output image */
	ImagePNG($signatureImg);

?>