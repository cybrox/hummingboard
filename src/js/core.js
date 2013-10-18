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
 */
function generateStatsTypes(stats){
	
	totalValue = 0;
	
	$.each(stats, function(key, value){
		totalValue += value;
	});
	
	$.each(stats, function(key, value){
		var barHeight = (value/totalValue)*boxHeight;
		var barOffset = boxHeight - ((value/totalValue)*boxHeight);
		
		$('#graphTypes').append('<div class="bar barO bar5" style="height: '+barHeight+'px; margin-top: '+barOffset+'px"><span>'+key+'</span></div>');
	});
}


/**
 * Generate anime state stats
 */
function generateStatsState(stats){
	
	delete stats.total;
	
	totalValueAnime = 0;
	totalValueEpisd = 0;
	
	$.each(stats, function(key, value){
		totalValueAnime += value["anime"];
		totalValueEpisd += value["episodes"];
	});
	
	console.log(stats);
	console.log(totalValueAnime);
	console.log(totalValueEpisd);
	
	$.each(stats, function(key, value){
		var barHeightAnime = (value["anime"]/totalValueAnime)*boxHeight;
		var barOffsetAnime = boxHeight - ((value["anime"]/totalValueAnime)*boxHeight);
		var barHeightEpisd = (value["episodes"]/totalValueEpisd)*boxHeight;
		var barOffsetEpisd = boxHeight - ((value["episodes"]/totalValueEpisd)*boxHeight);
		
		$('#graphState').append('<div class="bar barO barD1 bar10" style="height: '+barHeightAnime+'px; margin-top: '+barOffsetAnime+'px"><span>'+key+'</span></div>');
		$('#graphState').append('<div class="bar barB barD2 bar10" style="height: '+barHeightEpisd+'px; margin-top: '+barOffsetEpisd+'px"></div>');
	});
}


/**
 * Generate anime rating stats
 */
function generateStatsRates(stats){
	
	totalValue = 0;
	
	$.each(stats, function(key, value){
		totalValue += value;
	});
	
	$.each(stats, function(key, value){
		var barHeight = (value/totalValue)*boxHeight;
		var barOffset = boxHeight - ((value/totalValue)*boxHeight);
		
		$('#graphRates').append('<div class="bar barO bar11" style="height: '+barHeight+'px; margin-top: '+barOffset+'px"><span>'+key+'</span></div>');
	});
	
}