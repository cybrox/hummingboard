<?php

	/**
	 * Hummingboard Calender
	 * 'cause I want to know how long I need to wait for my animu
	 *
	 * Written and copyrighted 2014+
	 * by Sven Marc 'CybroX' Gehring
	 *
	 * Licensed under CC BY-SA 3.0
	 * For additional informations, please read the
	 * LICENSE.md file or the license deed at the
	 * Creative Commons website.
	 */

	

	/* Get user's library from Hummingbird (inofficial) */
	$userLibrary   = @file_get_contents("http://hummingbird.me/library_entries?user_id=".$_GET['user']."&status=Currently+Watching");
	$userDataset   = @json_decode($userLibrary, true);
	$totalAnime    = $userDataset['anime'];
	$userAnime     = array();
	$userSchedule  = array();


	/* Handle user's library */
	if(!empty($userDataset)){
		foreach($userDataset['anime'] as $index => $ani){
			if($ani['finished_airing'] == null){
				if($ani['started_airing_date_known'] == "1"){
					array_push($userAnime, array(
						"title" => $ani['canonical_title'],
						"sdate" => $ani['started_airing'],
						"rtime" => $ani['episode_count']
					));
				}
			}
		}
		$userExists = true;


		/* Create schedule list */
		foreach ($userAnime as $ani) {
			for($i = 0; $i < $ani['rtime']; $i++){
				$date = new DateTime($ani['sdate']);
				$date->modify('+'.$i.' week');
				array_push($userSchedule, array(
					"title" => $ani['title'],
					"sdate" => $date->format('Y-m-d'),
					"episd" => ($i + 1)
				));
			}
		}
	} else {
		$userExists = false;
	}

	$response = array(
		"success" => $userExists,
		"dataset" => $userSchedule
	);

	echo json_encode($response);

?>