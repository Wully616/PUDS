<?php
session_start();
//Gets the current Directory
function curDirectory() {
	$current_dir_url = 'http';
	if ($_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
		$current_dir_url .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$current_dir_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	} else {
		$current_dir_url .= $_SERVER["SERVER_NAME"];
	}
	$current_dir_url .= dirname($_SERVER['PHP_SELF']);
	return $current_dir_url;
}
$curdirectory = curDirectory();
$_SESSION = array();
session_destroy();
header("Location: $curdirectory/install.php");
?>