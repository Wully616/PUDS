<?php
//////////////////////////////////
//Garrysmod Server configuration//
    $IP = "";		   				//Server IP
    $PORT = "";			   			//Server Port
    $PASSWORD = ""; 				//RCON password
    $COMMAND = "ulx adduserid";    	//RCON command - LEAVE AS IS
//////////////////////////////////

//////////////////////////////////
///MySQL Database Configuration///
$HOST = "localhost";			   	//If this script is on the same webserver as your database leave as localhost
$DBUSER = "";       				//The user for the MySql database
$DBPASS = "";          				//Password for the MySql user
$DBTABLE = "";      				//The name of the database table to store the donation information
//////////////////////////////////

//////////////////////////////////
/////////PayPal Info//////////////
$payPalURL = "https://www.paypal.com/cgi-bin/webscr";
//$payPalURL = "https://www.sandbox.paypal.com/cgi-bin/webscr"; //For Paypal sandbox
//////////////////////////////////

//////////////////////////////////
///Donation Ranks & prices////////
$cost1 = "1";						//The cost of group 1 donation DO NOT INCLUDE CURRENCY SYMBOL
$rank1 = "group1";					//The name of your ULX group the user will be added to for donating $cost1
$cost2 = "2";
$rank2 = "group2";
$cost3 = "3";
$rank3 = "group3";
$cost4 = "4";
$rank4 = "group4";
/////////////////////////////////


?>