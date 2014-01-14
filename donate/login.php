<?php
ob_start();
session_start();
/////////////////////////////////////////
////////PUDS Config Installer////////////
//////Configuration//////////////////////
//Steam API Key//////////////////////////
$_STEAMAPI = "";
/////////////////////////////////////////
require("openid.php");
require("admin.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<link rel="stylesheet" type="text/css" href="style.css">
    <title>PUDS Donation System</title>
</head>
<body>
	<div id="header">
		<h1>PayPay-ULX Donation System Config Installer</h1>
	</div>
<?php
function curDomain() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
		$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"];
	}
	return $pageURL;
}
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
function GetSteamNorm($Steam64){
	
	$authserver = bcsub( $Steam64, '76561197960265728' ) & 1;
	//Get the third number of the steamid	$authid = ( bcsub( $Steam64, '76561197960265728' ) - $authserver ) / 2;
	//Concatenate the STEAM_ prefix and the first number, which is always 0, as well as colons with the other two numbers	$steamid = "STEAM_0:$authserver:$authid";
	return $steamid;
}

if(empty($_STEAMAPI)) {
	echo "<h1>You have not configured PUDS installer correctly! Please edit login.php and put your Steam API Key in the top of the file and try again!</h1>";
} else {
	if (!isset($_SESSION['id'])){
		echo "
				<p>Please login to configure Wullys PayPal-ULX Donation System</p>
				<form action='?login' method='post'>
				<input type='image' src='http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_small.png'>
			</form>
			<h2>Instructions for use:</h2>
			<p>Sign in through steam. If you are the first user it will make you admin.</p>
			<p>You will be told your not admin, Log out then Log in again to be authenticated as admin.</p>";
	
		try {
			$openid = new LightOpenID(curDirectory());
			if(!$openid->mode){
				if(isset($_GET['login'])) {
					$openid->identity = 'http://steamcommunity.com/openid/?l=english';
					// This is forcing english because it has a weird habit of selecting a random language otherwise
					header('Location: ' . $openid->authUrl());
				}
			} elseif($openid->mode == 'cancel') {
				echo 'User has canceled authentication!';
			} else {
				if($openid->validate()) {
					$id = $openid->identity;
					// identity is something like: http://steamcommunity.com/openid/id/76561197960435530
					// we only care about the unique account ID at the end of the URL.
					$ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
					preg_match($ptn, $id, $matches);
					echo "User is logged in (steamID: $matches[1])\n";
					$url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$_STEAMAPI&steamids=$matches[1]";
					$json_object= file_get_contents($url);
					$json_decoded = json_decode($json_object);
					foreach ($json_decoded->response->players as $player){
						/*echo "						
						<br/>Player ID: $player->steamid
						<br/>Player Name: $player->personaname
						<br/>Profile URL: $player->profileurl
						<br/>SmallAvatar: <img src='$player->avatar'/>
						<br/>MediumAvatar: <img src='$player->avatarmedium'/>
						<br/>LargeAvatar: <img src='$player->avatarfull'/>";
						echo "<br/>SmallAvatar: <img src='$player->avatar'/>";
						*/						
						$steam64 = $player->steamid;
						$steamID = GetSteamNorm($steam64);

						//Get players ingame name
						$_SESSION['id'] = $steam64;
						$_SESSION['sid'] = $steamID;
						$_SESSION['name'] = $player->personaname;
						$_SESSION['mAvatar'] = $player->avatarmedium;
						$_SESSION['steamAPI'] = $_STEAMAPI;
						if(empty($admin)){			
							$str = "<?php \$admin = '$steam64';?>";
							$fp = FOPEN("admin.php", "w");
							FWRITE($fp, $str);
							FCLOSE($fp);			
							echo "<p> Successfully made $friendlyName an admin!</p>";
							header("Location: logout.php");							
						}							
						if($steam64 == $admin){
							$_SESSION['admin'] = True;
						} else {
							$_SESSION['admin'] = False;
						}
					}
					header("Location: install.php");
				}		
			} 
		} catch(ErrorException $e) {
			echo $e->getMessage();
		}
	} else {
		
	}
}
?>
	</body>
</html>