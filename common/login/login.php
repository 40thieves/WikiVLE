<?php

/*

Login for non-LDAP accounts

Takes username and password as input
Hashes and salts the password, then checks against DB, if match is found the password is correct
Cookie is set on success and user redirected to the Home page

*/

// Includes for DB setup and extractSanitiseVar functions
include '../../lib/db.php';
include '../../lib/config.php';
include '../../lib/utils.php';

// Include for setLoginCookie function
include 'loginUtils.php';

// DB setup - connects and selects
connectAndSelectDB();

if (isset ($_POST['username']) && $_POST['password']) {

    // Username and password are set
    
    // Extracts and sanitises the username and password
    $username = extractSanitiseVar('username', '');
    $password = extractSanitiseVar('password', '');

    // Query for the DB - pulls password and isAdmin flag from DB. Doesn't check password - need to hash and salt
    $query = "SELECT password, isAdmin FROM Users WHERE username = '$username'";
    $rows = mysql_query($query);

    $line = mysql_fetch_assoc($rows);

    // Extracts isAdmin flag
    $isAdmin = $line['isAdmin'];

    /*

    NOTE: Following code adapted from http://elbertf.com/2010/01/store-passwords-safely-with-php-and-mysql/
    The steps attempt to recreate the hashing done when registering an account
    The password that the user enters is hashed in the same way, so that it matches the hashed password stored in the DB

    */

    // The first 64 characters of the password stored in the DB is the salt
    $salt = substr($line['password'], 0, 64);

    // Create an initial hash by appending the salt to the user-entered password
    $hash = $salt . $password;

    // Hash the user-entered password 100000 times
    for ($i = 0; $i < 100000; $i++) {

        $hash = hash('sha256', $hash);
    
    }

    // Create final hash by appending salt
    $hash = $salt . $hash;

    // If the new hash that was created from the user-entered password matches the password stored in the DB, then it is correct
    if ($hash == $line['password']) {

        // Sets cookie with user's givenname
        // If the account has the isAdmin flag, a isAdmin cookie is also set        
        setLoginCookie($username, $isAdmin);

    }
    else {

        // The new hash does not match the one stored in the DB - password is incorrect
    	echo "Wrong username or password combination";
    }
}

?>