<?php
$name = "";
$fee = "";
$steamid = "";
$rank = "";
//////////////////////////////////
//Garrysmod Server configuration//
    $IP = "";		   				//Server IP
    $PORT = "";			   			//Server Port
    $PASSWORD = ""; 				//RCON password
//////////////////////////////////

//////////////////////////////////
///MySQL Database Configuration///
$UseDB = "true";					//Change to false if you do not want to use a MySQL database.
$HOST = "localhost";			   	//If this script is on the same webserver as your database leave as localhost
$DBUSER = "";       				//The user for the MySql database
$DBPASS = "";   					//Password for the MySql user
$DBNAME = "";       				//The name of the database
$DBTABLE = "";      			//The name of the database table to store the donation information
//////////////////////////////////

//////////////////////////////////
/////////PayPal Info//////////////
$payPalURL = "https://www.paypal.com/cgi-bin/webscr";
//$payPalURL = "https://www.sandbox.paypal.com/cgi-bin/webscr"; // For Paypal sandbox
$IPN = "http://YOURDOMAIN/donate/ipn.php"; 						// location of the IPN script
$payPalEmail = "YOUREMAIL"; 									// your paypal email the money should be sent to
$website = "http://YOURDOMAIN.co.uk/"; 							// your website paypal should redirect back to once completed
$currency = "GBP";												// your currency, enter the code as it is on https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_currency_codes
//////////////////////////////////

//////////////////////////////////
///Donation Ranks & prices////////
$prices = array("1","2","3","4"); 								//prices for each rank, do not include currency symbol
$ranks = array("iron","bronze","silver","gold"); 				//ulx group names of each rank corresponding to the same array position in $costs
$commands = array("ulx adduserid","ulx adduserid","ulx adduserid","ulx adduserid"); //commands for each rank, these will support other commands in the future


/////////////////////////////////

/////////////////////////////////
////Email Configuration//////////
/////////////////////////////////
//Failed to apply rank e-mail////
$messageRankFail = ' 
 
Thank you for your purchase '.$name.'
 
Your rank information 
------------------------- 
Paid: '.$fee.' 
SteamID: '.$steamid.' 
Rank: '.$rank.' 
------------------------- 
			 
There has been an issue when adding your SteamID to the correct usergroup.
Please contact the server admin to resolve this issue.';  

//Success Donation and apply rank e-mail//
$messageSuccess = ' 
 
Thank you for your purchase '.$name.'
 
Your rank information 
------------------------- 
Paid: '.$fee.' 
SteamID: '.$steamid.' 
Rank: '.$rank.' 
------------------------- 
			 
Your rank will be available immediatly'; 

//Failed IPN verification & application of rank - note donators money will probably still have been taken
$messageIPNFail = ' 
 
Hello,
 
The donation has failed to be verified by PayPal IPN, however payment may have been taken.
Please contact the owner of the server to resolve this issue.

			 
';  
?>