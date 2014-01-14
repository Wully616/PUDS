<?php  
require "paypal.class.php";
require "rcon_code.php";
require "config.php";

	
$p = new paypal_class;
$p->paypal_url = $payPalURL; // $payPalURL is defined in config.php


	 $file = './log/puds.log';
	 $current = file_get_contents($file);
//Database stuff
if($UseDB == "true"){	 
		$db=mysqli_connect($HOST,$DBUSER,$DBPASS,$DBNAME);
			file_put_contents($file,PHP_EOL . "[" . date('Y-m-d H:i:s') . "]" ."Connected to database", FILE_APPEND);
			// Check connection
			if (mysqli_connect_errno($db)) {
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
				file_put_contents($file,PHP_EOL . "[" . date('Y-m-d H:i:s') . "]" ."Failed to connect to database", FILE_APPEND);
			} 
		$result = mysqli_query($db,"SHOW TABLES LIKE '".$DBTABLE."'");
		if (!$result) {
			file_put_contents($file,PHP_EOL . "[" . date('Y-m-d H:i:s') . "]" ."Error: ".mysqli_error($db), FILE_APPEND);
			exit();
		}
		$tableExists = mysqli_num_rows($result) > 0;
		
	if($tableExists){
		//connect to the table
		file_put_contents($file,PHP_EOL . "[" . date('Y-m-d H:i:s') . "]" ."Table exists, Connecting to table.", FILE_APPEND);
		mysqli_select_db($db, $DBTABLE);
	} else {
		//Create table
		file_put_contents($file,PHP_EOL . "[" . date('Y-m-d H:i:s') . "]" ."Table does not exist, creating table.", FILE_APPEND);
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
		mysqli_select_db($db, $DBTABLE);
	}	
}
		
if ($p->validate_ipn()) {
		file_put_contents($file,PHP_EOL . "[" . date('Y-m-d H:i:s') . "]" ."IPN Validated.", FILE_APPEND);
		$fee = $p->ipn_data['mc_gross'];
		$email = $p->ipn_data['payer_email']; 
		$name = $p->ipn_data['option_selection1'];
		$steamid = $p->ipn_data['option_selection2'];
		
		if (is_array($prices)){
			foreach($prices as $key => $val){
					$i++;
					if($val == $fee){
						$rank = $ranks[$i - 1];
						$command = $commands[$i - 1] .' '. $steamid.' '.$rank;
					}
			}
		} else {
			file_put_contents($file,PHP_EOL . "[" . date('Y-m-d H:i:s') . "]" ."\$prices is an not array.", FILE_APPEND);
		}
		file_put_contents($file,PHP_EOL . "[" . date('Y-m-d H:i:s') . "]" .$email.' '.$name.' '.$fee .' '.$steamid.' '.$rank, FILE_APPEND);
		
		//Add user donation to database.
		if($UseDB == "true"){
			$sql = 	'INSERT INTO '.$DBTABLE.' (email, steamid, name, rank) VALUES ("'.mysqli_real_escape_string($db, $email).'", "'.$steamid.'", "'.$name.'", "'.$rank.'")';
			mysqli_query($db,$sql);	
			file_put_contents($file,PHP_EOL . "[" . date('Y-m-d H:i:s') . "]" ."Added to database.", FILE_APPEND);		
		}
		
		//Rcon connection to apply rank.
		$srcds_rcon = new srcds_rcon();
		$OUTPUT = $srcds_rcon->rcon_command($IP, $PORT, $PASSWORD, $command);
		file_put_contents($file,PHP_EOL . "[" . date('Y-m-d H:i:s') . "]" .'IP: '.$IP.' Port: '.$PORT.' Password: HIDDEN Command: '.$command, FILE_APPEND);			
		file_put_contents($file,PHP_EOL . "[" . date('Y-m-d H:i:s') . "]" .'Rcon output: ' .$OUTPUT, FILE_APPEND);
		
		//Check reply from server
		if( $OUTPUT == 'Unable to connect!' || $OUTPUT == '' ) { 
			// Email Buyer - Donation complete - Rank failed	
			file_put_contents($file,PHP_EOL . "[" . date('Y-m-d H:i:s') . "]" ."Unable to connect to Rcon, please check your configuration.", FILE_APPEND);			
			$to      = $email;
			$subject = 'PUDS - Donation Complete - Rank failed to set: '.$rank.'';  
			mail($to, $subject, $messageRankFail, $headers);
		} else {
			// Email Buyer		
			$to      = $email;
			$subject = 'PUDS - Donation Complete: '.$rank.'';  
			$headers = 'From:PUDS PayPal-ULX Donation System' . "\r\n";  
			mail($to, $subject, $messageSuccess, $headers);
		}
		
}
else 
{
	// Email Buyer
	$to      = $email;
	$subject = 'PUDS - Donation Failed:';  
	$headers = 'From:PUDS PayPal-ULX Donation System' . "\r\n";  
	mail($to, $subject, $messageIPNFail, $headers);
}
if($UseDB == "true"){
	mysqli_close($db);  
 }
?>  