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
 * Generate statistics
 *
 * This is the main function of the
 * generation process, it will call
 * all needed subfunctions.
 */
function loadUserStats(username){

	$('#landingPage').hide();
	$('#loader').show();
	
	$.ajax({
		type: 'GET',
		dataType: 'json',
		url: './src/api/index.php?user='+username,
		success: function(jsonData){
		
			if(jsonData.state != 4){
				embedLandingPage("User '"+username+"' not found, please try again with a different name.");
				return false;
			}
			
			window.history.pushState({}, username+" ~ Hummingboard", "/"+username);
			dataArray = jsonData.data;
			
			$('#loader').hide();
			$('#statsPage').fadeIn('fast');
			updateInterface(dataArray);
			
			generateStatsTypes(dataArray.animetypes);
			generateStatsState(dataArray.animeallocation);
			generateStatsRates(dataArray.animeratings);
		},
		error: function(xhr, status, error) {
			console.log(xhr);
			console.log(status);
			console.log(error);
			console.log("Something went wrong, please hit me up if this problem won't go away in the next few minutes.");
		}
	});
}


/**
 * Display landing page
 *
 * This function will display the landing
 * page if the user call the site without
 * any defined user name.
 */
function embedLandingPage(invalidinput){

	if(inputValue !== "") $('#landingWhoops').text(invalidinput);
	
	$('#loader').hide();
	$('#statsPage').hide();
	$('#landingPage').fadeIn('fast');
}


/**
 * Submit landing page
 */
function submitLandingPage(){
	loadUserStats($('#landingInput').val());
}


/**
 * Update interface
 */
function updateInterface(dataArray){

	var hummingLink = "http://hummingbird.me/users/"+dataArray.name+"/";

	$('#useravatar').attr("src", dataArray.avatar);
	$('#userimage').attr("href", hummingLink);
	$('#userfont').attr("href", hummingLink);
	$('#userfont').text(dataArray.hbname);
	$('#anmc').text(dataArray.animeallocation.total.anime);
	$('#epsc').text(dataArray.animeallocation.total.episodes);
	$('#anitime').text(generateAnimeTime(dataArray.animetime));
	
	$('#headContainer').css("margin-top", "0px");
	$('#graphState').css({"width": "80%","height": "80%"});
	$('#graphRates').css({"width": "80%","height": "80%"});
	$('#graphTypes').css({"width": "80%","height": "80%"});
}


/**
 * Generate anime time string
 *
 * This function will generate a string
 * that contains the user's watched anime
 * time.
 */
function generateAnimeTime(totalTime){

	years   = Math.floor(totalTime / 525948.766);
	left    = totalTime % 525948.766;
	months  = Math.floor(left / 43829.766);
	left    = left % 43829.0639;
	days    = Math.floor(left / 1440);
	left    = left % 1440;
	hours   = Math.floor(left / 60);
	minutes = Math.floor(left % 60);
	
	return years+" Years, "+months+" Months, "+days+" Days, "+hours+" Hours, "+minutes+" Minutes";
	
}


/**
 * Generate anime types stats
 *
 * Generate bars for the anime types
 * TV, Movie, Special, OVA, ONA
 */
function generateStatsTypes(stats){
	
	/* Generate stats array */
	var dataArray = [];
	$.each(stats, function(key, value){
		dataArray.push([value, key, 'orangeBar']);
	});
	
	appendGraph("graphTypes", dataArray);
}


/**
 * Generate anime state stats
 *
 * Generate the stats for watching, planned
 * and so on, this involves two bars per
 * element, one for the anime and one for the
 * episodes.
 */
function generateStatsState(stats){
	var dataArray = [];
	
	delete stats.total;
	
	$.each(stats, function(key, value){
		
		/* Rename listpoints */
		switch(key){
			case "currently-watching": keyname = "watching"; break;
			case "plan-to-watch":      keyname = "planned";  break;
			case "on-hold":            keyname = "onhold";   break;
			default:                   keyname = key;        break;
		}
	
		dataArray.push([[value["anime"], value["episodes"]], keyname, ['orangeBar', 'blueBar']]);
	});
	
	$('#graphState').html("");
	$('#graphState').graph({
		data: dataArray,
		isdouble: true
	});
}


/**
 * Generate anime rating stats
 *
 * Generates the stats for the anime ratings
 * from 0.0 - 5.0 plus "-" (unrated)
 */
function generateStatsRates(stats){
	var dataArray = [];
	
	$.each(stats, function(key, value){
		dataArray.push([[value["anime"], value["episodes"]], key, ['orangeBar', 'blueBar']]);
	});
	
	$('#graphRates').html("");
	$('#graphRates').graph({
		data: dataArray,
		isdouble: true
	});
}


/**
 * Append graph to target
 */
function appendGraph(target, dataArray){
	$('#'+target).html("");
	
	var exampleContainer = $('#'+target);
	graphSizeX = exampleContainer.width();
	graphSizeY = exampleContainer.height();
	
	$('#'+target).graph({
		data: dataArray,
		isdouble: false
	});
}


/**
 * Resite the stats container
 *
 * This function will resize the stats container
 * every time the window is resized.
 */
function resizeStatsContainer(){
	$('statsContainer').css("height", ($('body').height() - 100)+"px");
}
