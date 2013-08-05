<?php
require "config.php";

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
			<!--<form name="_xclick" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post"><b =""><b =""> --><!--For use with paypals test Sandbox-->
			<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post"><b =""><b ="">
				<input name="cmd" value="_xclick" type="hidden" /> 
				<input name="business" value="YOUR_PAYPAL_EMAIL" type="hidden" /><!--Put in your paypal e-mail in the value -->
				<input name="item_name" value="PUDS - Game Server Donation" type="hidden" /> <!-- Rename the item_name value whatever you want, eg Donation to WullysBuilders Sandbox Server -->
				<input name="no_shipping" value="1" type="hidden" />
				<input name="return" value="http://YOUR.DOMAIN" type="hidden" /> <!--When the donation is complete it will redirect back to the specified URL -->
				<input type="hidden" name="rm" value="2" /> 
				<input type="hidden" name="notify_url"value="http://YOUR.DOMAIN/paypal/ipn.php" /><!--The paypal ipn script you downloaded in this git, replace with your website -->				
				<input name="cn" value="Comments" type="hidden" /> 
				<input name="currency_code" value="GBP" type="hidden" />
				<input name="tax" value="0" type="hidden" /> 
				<input name="lc" value="GB" type="hidden" />
				</b>
				</b>
				<table style="margin: 0pt auto;" width="400"> <!-- Table of choices for donations, preferably ULX group ranks-->
					<tr>
						<td>Rank:</td>
						<td>
						<?php
						echo	'<input type="radio" id="cost1" name="amount" value="'.$cost1.'" checked>'.$rank1.'<br>';
						echo	'<input type="radio" id="cost2" name="amount" value="'.$cost2.'">'.$rank2.'<br>';
						echo	'<input type="radio" id="cost3" name="amount" value="'.$cost3.'">'.$rank3.'<br>';
						echo	'<input type="radio" id="cost4" name="amount" value="'.$cost4.'">'.$rank4.'<br>';
						?>							
						</td>
					</tr>					
					<tr>
						<td>
							<input type="hidden" name="on0" value="In-Game Name" maxlength="200">In-Game Name: <!-- The player who donated's name, for your reference -->
						</td>
						<td>
							<input type="text" id="namedonate"  name="os0" value=""> <!--leave the name as "os0" players name is sent to paypal and used in the ipn script -->
						</td>
					</tr>	
					<tr>
						<td>
							<input type="hidden" name="on1" value="SteamID" maxlength="200">(STEAM_x:x:xxxxxxxx) SteamID: </td> <!-- The Players steamID, a correct ID is needed to apply the rank to the right person-->
						</td>
						<td>
							<input type="text" id="siddonate"  name="os1" value=""><!-- Leave the name as "os1" this is also sent to paypal and used in the ipn script. -->
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