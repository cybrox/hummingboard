<?php

	/* Get parameters from the requested URL */
	$requestedLink = explode("/", $_SERVER["REQUEST_URI"]);
	$requestedUser = (empty($requestedLink[1])) ? "" : $requestedLink[1];
	$requestedUser = "cybrox"; // DEVTEMP

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	
	<meta name="author" content="cybrox" />
	<meta name="copyright" content="2014 cybrox" />

	<link type="image/x-icon" href="http://hummingboard.me/src/img/favicon.ico" rel="shortcut icon"  />
	<link type="image/x-icon" href="http://hummingboard.me/src/img/favicon.ico" rel="icon" type="image/x-icon" />
	
	<link href="css/style.css" rel="stylesheet" />
	
	<title><?php echo $requestedUser; ?>'s Hummingboard Caledar</title>
</head>
<body>

	<header>
		<h1 id="title"><?php echo $requestedUser; ?>'s Hummingboard Caledar</h1>
	</header>

	<div id="user" name="<?php echo $requestedUser; ?>"></div>
	<div id="calendar"><div>

	<div id="error">
		<h1>Something went terribly wrong!</h1>
		<span><br />
			Have you entered a valid username?<br />
			Have you made sure that there are no typos?<br />
			Are you REALLY! sure that there are no typos?<br />
			<strong>Have you tried turning it off and on again?</strong>
			<br /><br />
			If you think this is a bug, shoot <a href="http://forums.hummingbird.me/users/cybrox/activity">me</a> a PM.
		</span>
	</div>

	<script type="text/javascript" src="js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="js/lib/jqueryui.min.js"></script>
	<script type="text/javascript" src="js/calendar.js"></script>

</body>
</html>