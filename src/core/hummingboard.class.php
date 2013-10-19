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
	 * Request Unirest library
	 */
	require_once('./src/lib/Unirest.php');
	
	
	/**
	 * Hummingbird class
	 */
	class Hummingboard {
	
		public  $user;
		private $pass;
		
		/**
		 * The class constructor
		 */
		public function __construct($user, $pass = ""){
			
			$this->user = $user;
			$this->pass = $pass;
			
		}
		
		
	
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
			
			$effectiveUserData = array(
				"username" => $userData["name"],
				"useravat" => $userData["avatar"],
				"userdata" => $userData["life_spent_on_anime"]
			);
			
			return $effectiveUserData;
			
		}
		
		
		/**
		 * Send multiple requests to get all anime
		 *
		 * The hummingbird API doesn't support the
		 * "all" parameter* so this function will get
		 * informations from all anime list tabs and
		 * marge them together to one big array.
		 * *(I guess, haven't found anything.)
		 *
		 * @param "user" {string} - User name to get anime from
		 */
		public function readAllAnime($user = ""){
			
			if(empty($user)) $user = $this->user;
			
			$userAnimeActive = $this->requestData("users/".$user."/library?status=currently-watching");
			$userAnimePlannd = $this->requestData("users/".$user."/library?status=plan-to-watch");
			$userAnimeComplt = $this->requestData("users/".$user."/library?status=completed");
			$userAnimeOnhold = $this->requestData("users/".$user."/library?status=on-hold");
			$userAnimeDroppd = $this->requestData("users/".$user."/library?status=dropped");
		
			$userAnime = array_merge(
				$userAnimeActive,
				$userAnimePlannd,
				$userAnimeComplt,
				$userAnimeOnhold,
				$userAnimeDroppd
			);
		
			return $userAnime;
		
		}
		
		
		/**
		 * Create Hummingboard information string
		 *
		 * This function will create a string which the
		 * hummingboard will save to cache a user's data
		 */
		public function generateStatistics($user = ""){
		
			if(empty($user)) $user = $this->user;
		
			if($this->checkCache($user)){
			
				$userStatistics = $this->getCache($user);
				
			} else {
			
				$userAnime = $this->readAllAnime($user);
			
				$animeWatchd = array();
				$animeTypeof = array("TV" => 0, "Movie" => 0, "Special" => 0, "OVA" => 0, "ONA" => 0);
				
				$animeAmount = array(
					"currently-watching" => array("anime" => 0, "episodes" => 0),
					"plan-to-watch" =>      array("anime" => 0, "episodes" => 0),
					"completed" =>          array("anime" => 0, "episodes" => 0),
					"on-hold" =>            array("anime" => 0, "episodes" => 0),
					"dropped" =>            array("anime" => 0, "episodes" => 0),
					"total" =>              array("anime" => 0, "episodes" => 0)
				);
				
				$animeRating = array(
					"-" =>   array("anime" => 0, "episodes" => 0), "0.0" => array("anime" => 0, "episodes" => 0),
					"0.5" => array("anime" => 0, "episodes" => 0), "1.0" => array("anime" => 0, "episodes" => 0),
					"1.5" => array("anime" => 0, "episodes" => 0), "2.0" => array("anime" => 0, "episodes" => 0),
					"2.5" => array("anime" => 0, "episodes" => 0), "3.0" => array("anime" => 0, "episodes" => 0),
					"3.5" => array("anime" => 0, "episodes" => 0), "4.0" => array("anime" => 0, "episodes" => 0),
					"4.5" => array("anime" => 0, "episodes" => 0), "5.0" => array("anime" => 0, "episodes" => 0)
				);
				
				
				foreach($userAnime as $a){
				
					$animeAmount["total"]["anime"]++;
					$animeAmount["total"]["episodes"] += $a["episodes_watched"];
					$animeAmount[$a["status"]]["anime"]++;
					$animeAmount[$a["status"]]["episodes"] += $a["episodes_watched"];
					
					
					$animeTypeof[$a["anime"]["show_type"]]++;
					
					if($a["rating"]["value"] == ""){
						$animeRating["-"]["anime"]++;
						$animeRating["-"]["episodes"] += $a["episodes_watched"];
					} else {
						$animeRating[$a["rating"]["value"]]["anime"]++;
						$animeRating[$a["rating"]["value"]]["episodes"] += $a["episodes_watched"];
					}
					
					array_push($animeWatchd, array($a["last_watched"], $a["anime"]["title"], $a["anime"]["cover_image"]));
					
				}
				
				// Filter unprogressed data
				unset($animeTypeof["music"]);
				unset($animeTypeof[""]);
				
				$userData = $this->readUserData($user);
				
				$userStatistics = array(
					"_userdata" => $userData,
					"animetype" => $animeTypeof,
					"animeamnt" => $animeAmount,
					"animertng" => $animeRating,
					"animelist" => $animeWatchd
				);
				
				$this->cacheData($userStatistics);
			}
			
			return $userStatistics;
		}
		
		
		/**
		 * Create cache file string
		 */
		private function cacheString(){
			return "./src/cache/".strtolower($this->user).".json";
		}
		
		
		/**
		 * Create or update a cache file
		 *
		 * This function allows to create a new cache file
		 * or edit an existing file to cache an user's data.
		 */
		protected function cacheData($cacheData){
		
			$cacheFile = $this->cacheString();
			$cacheJson = json_encode($cacheData);
			
			file_put_contents ($cacheFile, $cacheJson);
		}
		
		
		/**
		 * Check the site cache for an existing list
		 *
		 * Check the cache directory for an existing file
		 * with the respective user's name to load data
		 * from cache instead of using the hb mashape API
		 */
		protected function checkCache(){
			
			$cacheTime = 86400;
			$cacheFile = $this->cacheString();
			
			if(!file_exists($cacheFile)) return false;
			
			if(filemtime($cacheFile) < (time() - $cacheTime)) return false;
			
			return true;
			
		}
		
		
		/**
		 * Load data from cached user file
		 *
		 * Load the hummingboard data from a cached user
		 * file if the checkCache function hasn't found an
		 * version younger than the defined $cacheTime
		 */
		protected function getCache(){
		
			$cacheFile = $this->cacheString();
			$cacheData = file_get_contents($cacheFile);
			$cacheJson = json_decode($cacheData, true);
			
			return $cacheJson;
		
		}
		
		
		/**
		 * Generate anime time string
		 *
		 * This function will generate the -spent on anime-
		 * string out of the given numbers of seconds.
		 */
		public function generateAnimeTime($totalTime){
			
			$years   = floor($totalTime / 525948.766);
			$left    = $totalTime % 525948.766;
			$months  = floor($left / 43829.766);
			$left    = $left % 43829.0639;
			$days    = floor($left / 1440);
			$left    = $left % 1440;
			$hours   = floor($left / 60);
			$minutes = $left % 60;
			
			return $years." Years, ".$months." Months, ".$days." Days, ".$hours." Hours, ".$minutes." Minutes";
			
		}
	}
	
?>