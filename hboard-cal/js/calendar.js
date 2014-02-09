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


$('#calendar').datepicker({
        inline: true,
        firstDay: 1,
        showOtherMonths: true,
		prevText:   "&lt;",
		prevStatus: "Previous Month",
		nextText:   "<i class=\"icon-angle-right\"></i>",
		nextStatus: "&gt;",
        dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
    });

handleCalendar();

function handleCalendar(){
	var today = $.datepicker.formatDate("yy-mm-dd", $("#calendar").datepicker('getDate'));
	var dates = today.split("-");
	var year  = dates[0];
	var month = dates[1];
	var day   = 0;

	var windw = window.innerHeight;
	var chigh = windw - 45 - 60 - 1;
	var fhigh = chigh / (($("tr").length) - 1);

	$.each($("td"), function(){
		if($(this).hasClass("ui-datepicker-other-month")){
			day = 0;
		} else {
			day++;
			tday = (day < 10) ? "0"+day : day;

			$(this).attr("id", year+"-"+month+"-"+tday).css("height", fhigh+"px");
			if((tday % 2) == 0) $(this).addClass("chessed");
		}
	});

	$("td, a").unbind("click"); // Hack Datepicker to a normal calendar

	var userName = $("#user").attr("name");
	if(userName != ""){
		$.getJSON("api.php?user="+userName, function(json){
			if(json.success){
				var schedulecount = json.dataset.length;
				while(schedulecount--){
					var schedule = json.dataset[schedulecount];
					$("#"+schedule['sdate']).append("<div class=\"schedule\"><strong>"+schedule['title']+"</strong><br />Episode "+schedule['episd']+"</div>");
				}
			} else {
				$("#error").show();
				$("#errorName").text(userName);
			}
		});
	} else {
		$("#error").show();
		$("#errorName").text(userName);
	}
}


window.onresize = function(){
	var windw = window.innerHeight;
	var chigh = windw - 45 - 60 - 1;
	var fhigh = chigh / (($("tr").length) - 1);

	$.each($("td"), function(){
		$(this).css("height", fhigh+"px");
	});
}