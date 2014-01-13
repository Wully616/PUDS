<?php
ob_start();
require("config.php");
require ("openid.php");


function GetSteamNorm($Steam64){
	$authserver = bcsub( $Steam64, '76561197960265728' ) & 1;
	//Get the third number of the steamid
	$authid = ( bcsub( $Steam64, '76561197960265728' ) - $authserver ) / 2;
	//Concatenate the STEAM_ prefix and the first number, which is always 0, as well as colons with the other two numbers
	$steamid = "STEAM_0:$authserver:$authid";
	return $steamid;
}


?>
<script language="javascript" type="text/javascript">
</script>
<!DOCTYPE html>
<html>
	<head>
		<title>PUDS - PayPal to ULX Donation System</title>
		<script type="text/javascript" src="js/js.js"></script>
		<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/dot-luv/jquery-ui-1.10.3.custom.min.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body onload="sidDonate()">
	<h1> THIS IS A TEST DONATION PAGE </h1>
		<div id="menu-bar">
			<form action="?login" method="post">
						<input type="image" src="http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_small.png">
			</form>
		</div>
		<div class="donationform"><b =""><b =""> </b></b>
			<form name="_xclick" action="<?php echo $payPalURL; ?>" method="post"><b =""><b =""> 
				<input name="cmd" value="_xclick" type="hidden" /> 
				<input name="business" value="<?php echo $payPalEmail; ?>" type="hidden" /><!--Put in your paypal e-mail in the value -->
				<input name="item_name" value="PUDS - Game Server Donation" type="hidden" /> <!-- Rename the item_name value whatever you want, eg Donation to WullysBuilders Sandbox Server -->
				<input name="no_shipping" value="1" type="hidden" />
				<input name="return" value="<?php echo $website; ?>" type="hidden" /> <!--When the donation is complete it will redirect back to the specified URL -->
				<input type="hidden" name="rm" value="2" /> 
				<input type="hidden" name="notify_url"value="<?php echo $IPN; ?>" /><!--The paypal ipn script you downloaded in this git, replace with your website -->				
				<input name="cn" value="Comments" type="hidden" /> 
				<input name="currency_code" value="<?php echo $currency ?>" type="hidden" />
				<input name="tax" value="0" type="hidden" /> 
				<input name="lc" value="GB" type="hidden" />
				</b>
				</b>
				<table style="margin: 0pt auto;" width="400"> <!-- Table of choices for donations, preferably ULX group ranks-->
					<tr>
						<td>Rank:</td>
						<td>
						<?php
							foreach($prices as $cost){
								$i++;
								if($i == 1){
									echo '<input type="radio" id="cost'.$i.'" name="amount" value="'.$cost.'" checked>'.$ranks[$i - 1].' ('.$cost.$currency.')<br>';
								} else {
									echo '<input type="radio" id="cost'.$i.'" name="amount" value="'.$cost.'">'.$ranks[$i - 1].' ('.$cost.$currency.')<br>';
								}
							}
						?>							
						</td>
					</tr>					
					<tr>					
						<td>
						<?php

							

					echo '</form>';
					try 
					{
						$openid = new LightOpenID($donationDir);
						if(!$openid->mode) 
						{
							echo "</td><td>";
							echo '<p> Sign in through Steam to automatically fill in your details.</p>';
							echo "</tr><tr><td>";
							echo "<input type='hidden' name='on0' value='In-Game Name' maxlength='200'>In-Game Name:"; // The player who donated's name, for your reference
							echo "</td><td>";
							echo "<input class='textboxinput' type='text' id='namedonate'  name='os0' value='$friendlyName' readonly>"; //leave the name as "os0" players name is sent to paypal and used in the ipn script -->
							echo "</td></tr><tr><td>";
							echo "<input type='hidden' name='on1' value='SteamID' maxlength='200'>(STEAM_x:x:xxxxxxxx) SteamID: </td>"; //The Players steamID, a correct ID is needed to apply the rank to the right person-->
							echo "</td><td>";
							echo "<input class='textboxinput' type='text' id='siddonate'  name='os1' value='$steamID' readonly>"; // Leave the name as "os1" this is also sent to paypal and used in the ipn script. -->	
							if(isset($_GET['login'])) 
							{
								$openid->identity = 'http://steamcommunity.com/openid/?l=english';    // This is forcing english because it has a weird habit of selecting a random language otherwise
								header('Location: ' . $openid->authUrl());
							}
						} 
						elseif($openid->mode == 'cancel') 
						{
							echo 'User has canceled authentication!';
						} 
						else 
						{
							if($openid->validate()) 
							{
									$id = $openid->identity;
									// identity is something like: http://steamcommunity.com/openid/id/76561197960435530
									// we only care about the unique account ID at the end of the URL.
									$ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
									preg_match($ptn, $id, $matches);
									echo "User is logged in (steamID: $matches[1])\n";

									$url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$_STEAMAPI&steamids=$matches[1]";
									$json_object= file_get_contents($url);
									$json_decoded = json_decode($json_object);

									foreach ($json_decoded->response->players as $player)
									{
										/*echo "
										<br/>Player ID: $player->steamid
										<br/>Player Name: $player->personaname
										<br/>Profile URL: $player->profileurl
										<br/>SmallAvatar: <img src='$player->avatar'/> 
										<br/>MediumAvatar: <img src='$player->avatarmedium'/> 
										<br/>LargeAvatar: <img src='$player->avatarfull'/> 
										";*/
										$steam64 = $player->steamid;																					
										$steamID = GetSteamNorm($steam64); //Get normal steamID		
										#$steam = new SteamAPI($steam64);			
										#$friendlyName = $steam->getFriendlyName();  //Get players ingame name
										$friendlyName = $player->personaname;  //Get players ingame name
									}
										

							echo "</td><td>";
							echo "<p> Successfully grabbed your details!</p>";
							echo "</tr><tr><td>";
							echo "<input type='hidden' name='on0' value='In-Game Name' maxlength='200'>In-Game Name:"; // The player who donated's name, for your reference
							echo "</td><td>";
							echo "<input type='text' id='namedonate'  name='os0' value='$friendlyName' readonly>"; //leave the name as "os0" players name is sent to paypal and used in the ipn script -->
							echo "</td></tr><tr><td>";
							echo "<input type='hidden' name='on1' value='SteamID' maxlength='200'>(STEAM_x:x:xxxxxxxx) SteamID: </td>"; //The Players steamID, a correct ID is needed to apply the rank to the right person-->
							echo "</td><td>";
							echo "<input type='text' id='siddonate'  name='os1' value='$steamID' readonly>"; // Leave the name as "os1" this is also sent to paypal and used in the ipn script. -->			

							} 
							else 
							{								
									echo "User is not logged in.\n";
							}
						}
					} 
					catch(ErrorException $e) 
					{
						echo $e->getMessage();
					}
												

							?>
						</td>
					</tr>
				</table>					
				<div style="margin-top:3px;text-align: center;">
					<input type="image" src="paypal-donate.gif" border="0" name="submit" id="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</div>			
		</div>
	</body>
</html>