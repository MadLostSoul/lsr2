<?php
if (session_status() === PHP_SESSION_NONE)
	session_start();

$hostname = $_SERVER['HTTP_HOST'];
$path = dirname($_SERVER['PHP_SELF']);

if (isset($_SERVER['HTTPS'])) {
	$protocol = "https://";
} else {
	$protocol = "http://";
}

if ($path == "\\")
	$path = "/";

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
	header("Location: " . $protocol . $hostname . ($path == "/" ? '' : $path) . "/auth/login.php", TRUE, 307);

	echo "<HTML lang=\"de\">\n\r\t<head>\n\r\t\t<link rel='stylesheet' type='text/css' href='../css/liquidsoap.css'/>\n\r\t</head>\n\r<body>\n\r";
	echo "<div class='clearfix' id='page'>\n\r";
	echo "\t<div class='position_content' id='page_position_content'>\n\r";
	echo "\t\t<span class='debug'>DBG[0/0]: Location Header:<br>Location: " . $protocol . $hostname . ($path == "/" ? '' : $path) . "/auth/login.php<br><span class='mysql_err'>Locationswechsel wurde nicht durchgef&uuml;hrt!</span></span>\n\r";
	echo "\t</div>\n\r</div>\n\r";
	echo "\n\r<script type='text/javascript'>window.location.href = '" . $protocol . $hostname . ($path == "/" ? '' : $path) . "/auth/login.php';</script>\n\r";
	echo "</body>\n\r</html>";
	exit ;
}
?>