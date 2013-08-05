<?php  
require "paypal.class.php";
require "rcon_code.php";
require "config.php";

	
$p = new paypal_class;
$p->paypal_url = $payPalURL; // $payPalURL is defined in config.php

$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
//if (empty($_GET['action'])) $_GET['action'] = 'process';  

	 $file = 'log.txt';
	 $current = file_get_contents($file);
	 
	$db=mysqli_connect($HOST,$DBUSER,$DBPASS,$DBTABLE);
		$current .="Connected to database\n";
		file_put_contents($file, $current);
		// Check connection
		if (mysqli_connect_errno($db)) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
			$current .="Failed to connect to database\n";
			file_put_contents($file, $current);
		} 
	$result = mysqli_query($db,"SHOW TABLES LIKE ".$DBTABLE."");
	$tableExists = mysqli_num_rows($result) > 0;
	
if($tableExists){
 //do nothing
} else {
	//Create table
	$sql = "CREATE TABLE ".$DBTABLE." 
	(
	PID INT NOT NULL AUTO_INCREMENT, 
	PRIMARY KEY(PID),
	email VARCHAR(250),
	steamid VARCHAR(250),
	name VARCHAR(250),
	rank VARCHAR(250)
	)";
	mysqli_query($db,$sql);	
}	
		
if ($p->validate_ipn()) {

		$fee = $p->ipn_data['mc_gross'];
		$email = $p->ipn_data['payer_email']; 
		$name = $p->ipn_data['option_selection1'];
		$steamid = $p->ipn_data['option_selection2'];

		switch ($fee) {
			case $cost1:
				$rank = $rank1;
				break;
			case $cost2:
				$rank = $rank2;
				break;
			case $cost3:
				$rank = $rank3;
				break;
			case $cost4:
				$rank = $rank4;
				break;			
		}
		$current .=$email.' '.$name.' '.$fee .' '.$steamid.' '.$rank.'\n';
		file_put_contents($file, $current);
		
		//Add user donation to database.
		$sql = 	'INSERT INTO '.$DBTABLE.' (email, steamid, name, rank) VALUES ("'.mysqli_real_escape_string($db, $email).'", "'.$steamid.'", "'.$name.'", "'.$rank.'")';
		mysqli_query($db,$sql);		
		
		
		$srcds_rcon = new srcds_rcon();
		$COMMAND .=' '.$steamid.' '.$rank.'';
		$OUTPUT = $srcds_rcon->rcon_command($IP, $PORT, $PASSWORD, $COMMAND);
		$current .=$OUTPUT;
		file_put_contents($file, $current);  
		if( $OUTPUT == '' ) { //Check reply from server
			// Email Buyer - Donation complete - Rank failed			
			$to      = $email;
			$subject = 'PUDS - Donation Complete - Rank failed to set: '.$rank.'';  
			$message = ' 
			 
			Thank you for your purchase '.$name.'
			 
			Your rank information 
			------------------------- 
			Paid: '.$fee.' 
			SteamID: '.$steamid.' 
			Rank: '.$rank.' builder
			------------------------- 
						 
			There has been an issue when adding your SteamID to the correct usergroup.
			Please contact the server admin to resolve this issue.';  
			$headers = 'From:PUDS PayPal-ULX Donation System' . "\r\n";  
			  
			mail($to, $subject, $message, $headers);
		} else {
			// Email Buyer
			
			$to      = $email;
			$subject = 'PUDS - Donation Complete: '.$rank.'';  
			$message = ' 
			 
			Thank you for your purchase '.$name.'
			 
			Your rank information 
			------------------------- 
			Paid: '.$fee.' 
			SteamID: '.$steamid.' 
			Rank: '.$rank.' builder
			------------------------- 
						 
			Your rank will be available immediatly';  
			$headers = 'From:PUDS PayPal-ULX Donation System' . "\r\n";  
			  
			mail($to, $subject, $message, $headers);
		}
		
}
else 
{
// Email Buyer
		
		$to      = $email;
		$subject = 'PUDS - Donation Failed:';  
		$message = ' 
		 
		Hello,
		 
		The donation has failed however payment may have been taken.
		Please contact the owner of the server to resolve this issue.

					 
		';  
		$headers = 'From:PUDS PayPal-ULX Donation System' . "\r\n";  
		  
		mail($to, $subject, $message, $headers);
}
  mysqli_close($db);  
?>  