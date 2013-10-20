<?php
		
	require_once('./src/core/config.php');
	
	$requestUrl = explode("/", $_SERVER["REQUEST_URI"]);
		
	$nameUrlP = 1;
	$userName = (empty($requestUrl[$nameUrlP])) ? "" : $requestUrl[$nameUrlP];

?>
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
	
	<title>Home ~ Hummingboard</title>
</head>
<body>

	<div id="headContainer">
		<div id="headline">
			<div class="headpart">
				<!-- <a id="back" href="#" onClick="embedLandingPage('')"></a> -->
				<a href="#" id="userimage">
					<img id="useravatar" src="" alt=""/>
				</a>
				<a href="#" id="userfont"></a>
			</div>
			<div class="headpart">
				<div id="detailsContainer">
					<span class="descr">Anime </span><span class="anime" id="anmc"></span>
					<span class="divid"> | </span>
					<span class="episd" id="epsc"></span><span class="descr"> Episodes</span><br />
					<span id="anitime"></span>
				</div>
			</div>
			<div class="break"></div>
		</div>
	</div>

	<div id="statsContainer">
	
		<div id="landingPage">
			<div id="landingInfo">
				<h1 id="landingTitle">Hummingboard</h1>
				<h4 id="landingSubline">Generating simple Hummingboard.me stats.</h4>
				<div id="landingForm">
					<input id="landingInput" type="text" name="landingInput" placeholder="Enter a hummingbird name to generate stats."/>
					<button id="landingSubmit" onClick="submitLandingPage();">Generate Stats</button>
				</div>
				<span id="copyright">written with &#9825; by <a href="http://cybrox.eu">cybrox</a></span>
			</div>
		</div>
		
		<div id="statsPage">
			<div id="statsUpper">
				<section id="statsState">
					<div class="title"><h2>Anime Allocation</h2></div>
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
			
			<!-- <div id="statsMiddle"></div> -->

			<div id="statsLower">
				<section id="statsLists"></section>
			</div>
		</div>
	</div>

	<div id="loader">
		<div></div><div></div><div></div>
		<div></div><div></div><div></div>
		<div></div><div></div><div></div>
	</div>
	
	<script type="text/javascript" src="src/js/jquery.min.js"></script>
	<script type="text/javascript" src="src/js/graph.js"></script>
	<script type="text/javascript" src="src/js/core.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
		
			/* Enter listener for landing form */
			$('#landingInput').keyup(function(key){
				if(key.keyCode == 13){submitLandingPage();}
			});
		
			<?php
			
				echo (!empty($userName)) ? 'loadUserStats("'.strtolower($userName).'");': 'embedLandingPage("");';
			?>
		});
	</script>
	
</body>
</html>
