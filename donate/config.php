<?php $name = '';
	$fee = '';
	$steamid = '';
	$rank = '';
	$_STEAMAPI = '';
	$donationDir = '';
	$IP = '';
	$PORT = '';
	$PASSWORD = '';
	$UseDB = '';
	$HOST = '';
	$DBUSER = '';
	$DBPASS = '';
	$DBNAME = '';
	$DBTABLE = '';
	$payPalURL = '';
	$IPN = '';
	$payPalEmail = '';
	$website = '';
	$currency = '';
	$prices = array('');
	$ranks =  array('');
	$commands = array('');
	$messageRankFail = '	Thank you for your purchase $name Your rank information 
	------------------------- 
	Paid: \$fee 
	SteamID: \$steamid 
	Rank: \$rank 
	-------------------------
	There has been an issue when adding your SteamID to the correct usergroup.
	Please contact the server admin to resolve this issue.';
	$messageSuccess = 'Thank you for your purchase $name Your rank information
	------------------------- 
	Paid: \$fee 
	SteamID: \$steamid
	Rank: \$rank
	-------------------------
	Your rank will be available immediately';
	$messageIPNFail = '	Hello, The donation has failed to be verified by PayPal IPN, however payment may have been taken.
	Please contact the owner of the server to resolve this issue.';
	?>