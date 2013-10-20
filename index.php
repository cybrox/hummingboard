<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	
	<meta name="author" content="CybroX" />
	<meta name="copyright" content="2013" CybroX" />

	<link type="image/x-icon" href="<?php echo DIRURL; ?>src/img/favicon.ico" rel="shortcut icon"  />
	<link type="image/x-icon" href="<?php echo DIRURL; ?>http://cybrox.eu/src/img/favicon.ico" rel="icon" type="image/x-icon" />
	
	<link href="<?php echo DIRURL; ?>src/css/style.css" rel="stylesheet" />
	
	<title><?php echo $userStatistics["_userdata"]["username"]; ?> ~ Hummingboard</title>
</head>
<body>

	<div id="headContainer">
		<div id="headline">
			<div id="username">
				<img id="useravatar" src="<?php echo $userStatistics["_userdata"]["useravat"]; ?>" alt=""/>
				<span id="userfont"><?php echo $userStatistics["_userdata"]["username"]; ?></span>
			</div>
			<div id="detailsContainer">
				<span class="descr">Anime </span><span class="anime"><?php echo $userStatistics["animeamnt"]["total"]["anime"]; ?></span>
				<span class="divid"> | </span>
				<span class="episd"><?php echo $userStatistics["animeamnt"]["total"]["episodes"]; ?></span><span class="descr"> Episodes</span><br />
				<span id="anitime"><?php echo $hummingboard->generateAnimeTime($userStatistics["_userdata"]["userdata"]); ?></span>
			</div>
		</div>
	</div>

	<div id="statsContainer">
		<div id="statsUpper">
			<section id="statsState">
				<div class="title"><h2>Anime allocation</h2></div>
				<div class="graph" id="graphState"></div>
			</section>
			<section id="statsRates">
				<div class="title"><h2>Anime Ratings</h2></div>
				<div class="graph" id="graphRates"></div>
			</section>
			<section id="statsTypes">
				<div class="title"><h2>Anime Types</h2></div>
				<div class="graph" id="graphTypes"></div>
			</section>
			<div class="break"></div>
		</div>
		<div id="statsLower">
			<section id="statsLists"></section>
		</div>
	</div>

	<script type="text/javascript" src="src/js/jquery.min.js"></script>
	<script type="text/javascript" src="src/js/graph.js"></script>
	<script type="text/javascript" src="src/js/core.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			resizeStatsContainer();
			generateStats(<?php echo json_encode($userStatistics); ?>);
		});
		
		/* Listener to prevent resizing bug (temp) */
		$(window).resize(function(){
			resizeStatsContainer();
			generateStats(<?php echo json_encode($userStatistics); ?>);
		});
	</script>
	
</body>
</html>