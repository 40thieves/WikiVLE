<?php

/*

Register a non-LDAP account

Takes username and password as input
Hashes and salts the password for security, then stores in the DB
Cookie is set on success and user redirected to the Home page

*/

// Includes for DB setup and extractSanitiseVar functions
include '../../lib/db.php';
include '../../lib/config.php';
include '../../lib/utils.php';

// Include for setLoginCookie function
include 'loginUtils.php';

// Connects and selects the database
connectAndSelectDB();

if (isset ($_POST['username']) && ($_POST['password'])) {

    // Username and password are set

    // Extracts and sanitises the username and password
	$username = extractSanitiseVar('username', '');
    $password = extractSanitiseVar('password', '');

    /*

    NOTE: Following code adapted from http://elbertf.com/2010/01/store-passwords-safely-with-php-and-mysql/
    A random salt is generated and appended to the given password to generate a hash
    This is then hashed 100000 times for extra security
    The salt is then appended to the hash, so that the salt can be retrieved later (i.e. on log in)

    */

    // Create a 256 bit (64 characters) long random salt
	// Add 'something random' and the username to the salt as well for added security
	$salt = hash('sha256', uniqid(mt_rand(), true) . 'something random' . strtolower($username));
	 
	// Prefix the password with the salt
	$hash = $salt . $password;
	 
	// Hash the salted password 100000 times
	for ($i = 0; $i < 100000; $i++) {
	    
	    $hash = hash('sha256', $hash);

	}
	 
	// Prefix the hash with the salt so we can get it back later
	$hash = $salt . $hash;

	// Insert the username and hashed password into the DB
	$query = "INSERT INTO Users (username, password) VALUES ('$username', '$hash')";

	// If the insertion was successful, then set the log in cookie
	if (mysql_query($query)) {
	
		// Calls the setLoginCookie function, which sets a cookie for the username
		setLoginCookie($username);

	}
	else {

		// Insertion was unsuccessful

		if (mysql_errno() == 1062) {

			// Insertion failed because the username is already being used

			echo "Oh no! The username '$username' is already taken!";

		}

	}

}

?>