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

(function($) {

	var graphOptions = new Array;
	
	$.fn.graph = function(inputOptions){
	
	
		/**
		 * Initialize new graph
		 */
		initializeGraph = function(targetElement){

			graphOptions[targetElement.id] = $.extend({}, $.fn.graph.defaults, inputOptions);
			
			$(targetElement).css({
				'width': graphOptions[targetElement.id].width,
				'height': graphOptions[targetElement.id].height,
				'position':'relative',
				'text-align':'center'
			});
			
			createGraph(targetElement);

		};
	
	
		/**
		 * Calculate arraySum of elements in an array
		 */
		arraySum = function(inputArray, isDouble){
			if(!isDouble){
			
				arrayTotal = 0;
				for(i in inputArray){
					arrayTotal += parseFloat(inputArray[i][0]);
				}
				summedArray = arrayTotal.toFixed(2);
				
			} else {
			
				arrayTotal = [0,0];
				for(i in inputArray){
					arrayTotal[0] += parseFloat(inputArray[i][0][0]);
					arrayTotal[1] += parseFloat(inputArray[i][0][1]);
				}
				summedArray = arrayTotal;
			
			}
			
			return summedArray;
		}
			
			
			
		calculateBarWidth = function(barAmount){
			
			var calculatedBarWidth = 100 / barAmount;
			var effectiveBarWidth  = calculatedBarWidth * 0.8;
			var effectiveBarOffset = calculatedBarWidth * 0.1;
			
			return [effectiveBarWidth, effectiveBarOffset];
			
		}
		
		calculateBarHeight = function(barValue, maxValue){
			return (barValue / maxValue) * 100;
		}
		
		createGraph = function(targetElement){
			
			targetOptions = graphOptions[targetElement.id];
			targetDataset = targetOptions.data;
			
			/* Check needed input */
			if(targetDataset == undefined){
				$(targetElement).html('<span>No input data for graph available</span>');
				return false;
			}
			
			maxBarValue = arraySum(targetDataset, targetOptions.isdouble);
					
			barDimensions = calculateBarWidth(targetDataset.length);
			barWidth  = barDimensions[0];
			barOffset = barDimensions[1];
			barOffDis = 0;
			
			for(dataKey in targetDataset){
			
				dataValue = targetDataset[dataKey][0];
				dataLable = targetDataset[dataKey][1];
				dataClass = targetDataset[dataKey][2];
				dataNames = targetElement.id;
				dataUnqId = dataNames + "_" + dataKey;
				dataHtml  = '';
				
				if(!targetOptions.isdouble){
				
					dataSizeY   = calculateBarHeight(dataValue, maxBarValue);
					
					dataHtml += '<div class="graphBar graphBar'+dataNames+'" id="graphBar'+dataUnqId+'" style="position:absolute; bottom:0; left:'+barOffDis+'%; height:100%; width:'+barWidth+'%">';
					dataHtml += '<div class="graphElm graphElm'+dataNames+' '+dataClass+'" id="graphElm'+dataUnqId+'" style="position:absolute;bottom:14px;height:0%;width:100%;">';
					dataHtml += '<div class="graphVal graphVal'+dataNames+'" id="graphBar'+dataUnqId+'" style="position:absolute; top:-14px;width:100%;text-align:center;">'+dataValue+'</div></div>';
					dataHtml += '<div class="graphDes graphDes'+dataNames+'" id="graphDes'+dataUnqId+'" style="position:absolute; bottom:0;width:100%;text-align:center;">'+dataLable+'</div>';
					dataHtml += '</div>';
				
					barOffDis += (barWidth + (2 * barOffset));
					
					$('#'+dataNames).append(dataHtml);
					
					$('#graphElm'+dataUnqId).animate({'height': dataSizeY+'%'}, 1000);
				
				} else {
				
					dataSizeY = [calculateBarHeight(dataValue[0], maxBarValue[0]), calculateBarHeight(dataValue[1], maxBarValue[1])];
					
					dataHtml += '<div class="graphBar graphBar'+dataNames+'" id="graphBar'+dataUnqId+'" style="position:absolute; bottom:0; left:'+barOffDis+'%; height:100%; width:'+barWidth+'%">';
					dataHtml += '<div class="graphElm graphElm'+dataNames+' '+dataClass[0]+'" id="graphElm'+dataUnqId+'_1" style="position:absolute;bottom:14px;height:0%;width:50%;">';
					dataHtml += '<div class="graphVal graphVal'+dataNames+'" id="graphBar'+dataUnqId+'" style="position:absolute; top:-14px;width:100%;text-align:center;">'+dataValue[0]+'</div></div>';
					dataHtml += '<div class="graphElm graphElm'+dataNames+' '+dataClass[1]+'" id="graphElm'+dataUnqId+'_2" style="position:absolute;bottom:14px;left:50%;height:0%;width:50%;">';
					dataHtml += '<div class="graphVal graphVal'+dataNames+'" id="graphBar'+dataUnqId+'" style="position:absolute; top:-14px;width:100%;text-align:center;">'+dataValue[1]+'</div></div>';
					dataHtml += '<div class="graphDes graphDes'+dataNames+'" id="graphDes'+dataUnqId+'" style="position:absolute; bottom:0;width:100%;text-align:center;">'+dataLable+'</div>';
					dataHtml += '</div>';
				
					barOffDis += (barWidth + (2 * barOffset));
					
					$('#'+dataNames).append(dataHtml);
					
					$('#graphElm'+dataUnqId+'_1').animate({'height': dataSizeY[0]+'%'}, 1000);
					$('#graphElm'+dataUnqId+'_2').animate({'height': dataSizeY[1]+'%'}, 1000);
				
				}
			}
		}
		

		/**
		 * Initialize graph creating 
		 */
		this.each (function(){
			initializeGraph(this);
		});
	};

	
	/**
	 * Defualt variables for this function
	 */
	$.fn.graph.defaults = {
		isdouble: false
	};
	
})(jQuery);