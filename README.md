PUDS
====

PayPal-ULX Donation System

This is a collection of php based scripts which creates 
a Paypal donation system for use with the Garrysmod admin mod ULX.

Features:

Automatic Promotion to the correct ULX group upon successful payment.
Provides a framework for custom donation systems without the use of LUA addons.
E-mails buyers of successful payment & promotion or if the payment or promotion failed.
Saves donator information to a MySQL database for future processing (eg creating a list of donators)

Requirements:

MySQL Database
Garrysmod Server
ULX/ULIB SVNS
Paypal account
Web Server

Installation:

Copy all the files to a web accessible folder on your webserver. 

CHMOD the folder+files 755.

Configure Paypal with your IPN, log into Paypal > profile > my selling preferences > Instant payment notifications.

Set PayPal to point to the IPN script, eg http://www.YOURDOMAIN.co.uk/donate/ipn.php
Create a MySQL database on your server and a user with full permissions.


Edit config.php and input the Information for your MySQL Database, Garrysmod Server Rcon details,

Donation Groups & Prices, you do not need to enter the currency symbol for the price and the Groups must be valid ULX groups on your server.


A sample donation form has been included: index.php
Lines are commented showing what should be edited in index.php

Author: William Robb
E-mail: William_robb9@hotmail.com
Website: www.wullysgamers.co.uk

Credits:
PHP Paypal IPN Integration Class - Micah Carrick
PHP Rcon Script - William Ruckman
