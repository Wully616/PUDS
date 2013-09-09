<?php
require("config.php");
require("steamapiv2.class.php");
require("steamlogin.php");

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
	</head>
	<body onload="sidDonate()">
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
								$steam_login_verify = SteamSignIn::validate();
							if(!empty($steam_login_verify))
							{
								$steam64 = $steam_login_verify;								
								$steam = new SteamAPI($steam_login_verify);								
								$steamID = GetSteamNorm($steam_login_verify); //Get normal steamID		
								$friendlyName = $steam->getFriendlyName();  //Get players ingame name.	
								
							echo "<a href=\"$steam_sign_in_url\"><img src='http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_small.png' /></a>";
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
								$steam_sign_in_url = SteamSignIn::genUrl();
							echo "<a href=\"$steam_sign_in_url\"><img src='http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_small.png' /></a>";
							echo '</td><td>';
							echo '<p> Sign in through Steam to automatically fill in your details.</p>';
							echo "</tr><tr><td>";
							echo "<input type='hidden' name='on0' value='In-Game Name' maxlength='200'>In-Game Name:"; // The player who donated's name, for your reference
							echo "</td><td>";
							echo "<input type='text' id='namedonate'  name='os0' value=''>"; //leave the name as "os0" players name is sent to paypal and used in the ipn script -->
							echo "</td></tr><tr><td>";
							echo "<input type='hidden' name='on1' value='SteamID' maxlength='200'>(STEAM_x:x:xxxxxxxx) SteamID: </td>"; //The Players steamID, a correct ID is needed to apply the rank to the right person-->
							echo "</td><td>";
							echo "<input type='text' id='siddonate'  name='os1' value=''>"; // Leave the name as "os1" this is also sent to paypal and used in the ipn script. -->	
							}
							

							?>
						</td>
					</tr>
				</table>					
				<div style="margin-top:3px;text-align: center;">
					<input type="image" src="paypal-donate.gif" border="0" name="submit" id="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</div>
			</form>
		</div>
	</body>
</html>