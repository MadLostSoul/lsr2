<?php
session_start();
session_destroy();

$hostname = $_SERVER['HTTP_HOST'];
$path = dirname($_SERVER['PHP_SELF']);

if (isset($_SERVER['HTTPS'])) {
	$protocol = "https://";
} else {
	$protocol = "http://";
}

if ($path == "\\") $path = "/";

header('Location: ' . $protocol . $hostname . ($path == '/' ? '' : $path) . '/login.php');
?>