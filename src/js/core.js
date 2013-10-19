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

 
var boxHeight = 0;

 
/**
 * Generate statistics
 *
 * This is the main function of the
 * generation process, it will call
 * all needed subfunctions.
 */
function generateStats(dataArray){
	
	var exampleContainer = $('#graphState');
	boxHeight = exampleContainer.height();

	generateStatsTypes(dataArray[0]);
	generateStatsState(dataArray[1]);
	generateStatsRates(dataArray[2]);
}


/**
 * Generate anime types stats
 *
 * Generate bars for the anime types
 * TV, Movie, Special, OVA, ONA
 */
function generateStatsTypes(stats){
	
	/* Calculate total possible points */
	totalValue = 0;
	$.each(stats, function(key, value){totalValue += value;});
	
	/* Progress data and append bars to DOM */
	$.each(stats, function(key, value){
		var barHeight = (value/totalValue)*boxHeight;
		var barOffset = boxHeight - ((value/totalValue)*boxHeight);
		
		var displayValue = (value > 0) ? value : "";
		
		$('#graphTypes').append('<div class="bar barO bar5" style="height: '+barHeight+'px; margin-top: '+barOffset+'px"><span class="desc">'+key+'</span><span class="val">'+displayValue+'</span></div>');
	});
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
	
	/* read total anime/episodes and delete help value */
	totalValueAnime = stats.total["anime"];
	totalValueEpisd = stats.total["episodes"];
	delete stats.total;
	
	/* Progress data and append bars to DOM */
	$.each(stats, function(key, value){
		var barHeightAnime = (value["anime"]/totalValueAnime)*boxHeight;
		var barOffsetAnime = boxHeight - ((value["anime"]/totalValueAnime)*boxHeight);
		var barHeightEpisd = (value["episodes"]/totalValueEpisd)*boxHeight;
		var barOffsetEpisd = boxHeight - ((value["episodes"]/totalValueEpisd)*boxHeight);
		
		var displayValueAnime = (value["anime"] > 0) ? value["anime"] : "";
		var displayValueEpisd = (value["episodes"] > 0) ? value["episodes"] : "";
		
		/* Rename listpoints */
		switch(key){
			case "currently-watching": state = "watching"; break;
			case "plan-to-watch":      state = "planned";  break;
			case "on-hold":            state = "onhold";   break;
			default:                   state = key;        break;
		}
		
		$('#graphState').append('<div class="bar barO barD1 bar10" style="height: '+barHeightAnime+'px; margin-top: '+barOffsetAnime+'px"><span class="desc dbdesc">'+state+'</span><span class="val">'+displayValueAnime+'</span></div>');
		$('#graphState').append('<div class="bar barB barD2 bar10" style="height: '+barHeightEpisd+'px; margin-top: '+barOffsetEpisd+'px"><span class="val">'+displayValueEpisd+'</span></div>');
	});
}


/**
 * Generate anime rating stats
 *
 * Generates the stats for the anime ratings
 * from 0.0 - 5.0 plus "-" (unrated)
 */
function generateStatsRates(stats){
	
	/* Calculate total possible points */
	totalValue = 0;
	$.each(stats, function(key, value){totalValue += value;});
	
	/* Progress data and append bars to DOM */
	$.each(stats, function(key, value){
		var barHeight = (value/totalValue)*boxHeight;
		var barOffset = boxHeight - ((value/totalValue)*boxHeight);
		
		var displayValue = (value > 0) ? value : "";
		
		$('#graphRates').append('<div class="bar barO bar12" style="height: '+barHeight+'px; margin-top: '+barOffset+'px"><span class="desc">'+key+'</span><span class="val">'+displayValue+'</span></div>');
	});
	
}