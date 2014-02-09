<?php

/**
 * Hummingboard
 * Simple but elegant Hummingbird stats page
 *
 * Written and copyrighted 2013+
 * by Sven Marc 'CybroX' Gehring
 *
 * Licensed under CC BY-SA 3.0
 * For additional informations, please read the
 * LICENSE.md file or the license deed at the
 * Creative Commons website.
 */
 
	
	/**
	 * Hummingbird class
	 */
	class Hummingboard {
		
		/**
		 * Send a request to mashape hummingbird API
		 *
		 * This function allows to send an API request
		 * to the mashape hummingbird API.
		 *
		 * @param "target" {string} - URL string
		 * @param "postar" {array} - Additional post parameters
		 */
		public function requestData($target, $postar = NULL){
		
			$apiResponse = ($postar !== NULL) ? 
				Unirest::post(APIURL.$target, array("X-Mashape-Authorization" => APIKEY), $postar):
				Unirest::get(APIURL.$target, array("X-Mashape-Authorization" => APIKEY)); 
			
			if($apiResponse->code !== 200) return false;
			
			$responseBody = $apiResponse->raw_body;
			$responseData = json_decode($responseBody, true);
			
			return $responseData;
		}
		
		
		/**
		 * Get the user data
		 *
		 * This function will request the user informations
		 * from the hummingbird API in order to display the
		 * user's avatar and nickname on the site.
		 * Thanks to TheBetterRed for telling me.
		 */
		public function readUserData($user = ""){
		
			if(empty($user)) $user = $this->user;
			
			$userData = $this->requestData("users/".$user);
			
			if(empty($userData["name"])) return false;
			
			$effectiveUserData = array(
				"username" => $userData["name"],
				"useravat" => $userData["avatar"],
				"usertime" => $userData["life_spent_on_anime"]
			);
			
			return $effectiveUserData;
			
		}
		
	
		
		/**
		 * Output an error
		 *
		 * This function will output an error for the
		 * requesting ajax script
		 */
		public function handleError($errorMessage){
		
			die("{\"state\": \"3\", \"error\": \"".$errorMessage."\", \"data\": \"\"}");
		
		}
	}
	
?>