<?php// this is a work in progress and is not completed yet, it is not recommended to use thisif (ISSET($_POST["Submit"])) { $string = "<?php $IP = "'. $_POST["IP"]. '";$PORT = "'. $_POST["PORT"]. '";$PASSWORD = "'. $_POST["PASSWORD"]. '";$UseDB = "'. $_POST["UseDB"]. '";$HOST = "'. $_POST["HOST"]. '";$DBUSER = "'. $_POST["DBUSER"]. '";$DBPASS = "'. $_POST["DBPASS"]. '";$DBNAME = "'. $_POST["DBNAME"]. '";$DBTABLE = "'. $_POST["DBTABLE"]. '";$payPalURL = "'. $_POST["payPalURL"]. '";$IPN = "'. $_POST["IPN"]. '";$payPalEmail = "'. $_POST["payPalEmail"]. '";$website = "'. $_POST["website"]. '";$currency = "'. $_POST["currency"]. '";$prices = array("'. $_POST["prices"]. '");$ranks = array("'. $_POST["ranks"]. '");$messageRankFail = array("'. $_POST["messageRankFail"]. '");$messageSuccess = array("'. $_POST["messageSuccess"]. '");$messageIPNFail = array("'. $_POST["messageIPNFail"]. '");?>"; $fp = FOPEN("configtest.php", "w");FWRITE($fp, $string);FCLOSE($fp); }; ?><form action="" method="post" name="install" id="install"><h2>Garrysmod Server Configuration</h2>  <p>    <input name="IP" type="text" id="IP" value="">     Server IP</p>  <p>    <input name="PORT" type="text" id="PORT">     Server Port</p>  <p>    <input name="PASSWORD" type="password" id="PASSWORD">	RCON Password</p><h2>MySQL Database Configuration</h2>  <p>    <input name="UseDB" type="text" id="UseDB" value="true">  User MySQL (true/false) </p>  <p>    <input name="HOST" type="text" id="HOST" value="localhost">  Database Host</p>  <p>    <input name="DBUSER" type="text" id="DBUSER">  Database User</p>  <p>    <input name="DBPASS" type="text" id="DBPASS">  Database Password </p>      <input name="DBNAME" type="text" id="DBNAME">  Database Name  </p>      <input name="DBTABLE" type="text" id="DBTABLE">  Database Table </p>   <h2>PayPal Configuration</h2>       <input name="payPalURL" type="text" id="payPalURL" value="https://www.paypal.com/cgi-bin/webscr">  PayPal URL </p>      <input name="IPN" type="text" id="IPN" value="http://YOURDOMAIN/donate/ipn.php">  IPN Script URL </p>     <h2>Donation/Rank Configuration</h2>       <input name="prices" type="text" id="prices" value="">  Prices </p>      <input name="ranks" type="text" id="ranks" value="">  Ranks </p>  <p>    <input type="submit" name="Submit" value="Install">  </p></form>