<?php

/*

Login for Portsmouth University LDAP
NOTE: Will only work when server is connected to University network, due to IP whitelisting

Takes username (either Jupiter number or computer network username) and password as input

Binds anonymously to LDAP server to retrieve Distinguishing Name (DN)
Then uses DN and password to bind again to LDAP server to authenticate
If bind succeeds, password is correct and cookie is set
Finally, user is redirected to the home page

*/

// Includes to get extractSantiseVar and setLoginCookie functions
include '../../lib/utils.php';
include 'loginUtils.php';

// Sets global variable for address of LDAP server
$ldap_host = "ldap.port.ac.uk";

if (isset ($_POST['username'])) {

	// Extracts and sanitises the username and password
	$username = extractSanitiseVar('username', '');
    $password = extractSanitiseVar('password', '');

    // Calls the findDN function
	$dn = findDN($username, $password);
}
else {
	// Error - no login number provided

	echo "No login details provided";
	echo "<p>Click <a href='../../login.php'>here</a> to go back.</p>";
}


function findDN($id, $password) {
	
	// Finds the user's Distinguished Name - the key that uniquely identifies each entry in the directory

	global $ldap_host;

	// Connects to the LDAP server
	$ds = ldap_connect($ldap_host) or die("LDAP connection failed. Please see installation notes on how to configure Apache to work with LDAP.");

	if ($ds) {
		// Connection was successful

		// Performs anonymous bind to LDAP server
		$r = ldap_bind($ds);

		if ($r) {
			// Binding to LDAP server was unsuccessful

			// Determines whether the username provided is the uidNumber (which is numeric - 499908), or the uniqueID (which is alphanumeric - cam01329)
			$filterString = is_numeric($id) ? "uidNumber=$id" : "uniqueID=$id";

			// Performs search for the LDAP number
	    	$searchResult = ldap_search($ds, "ou=LAN,o=PORT", $filterString);

	    	// Gets entries for this search
	    	$info = ldap_get_entries($ds, $searchResult);

	    	// Retrieves the DN and givenname (e.g. Alasdair) for the user
	    	$dn = $info[0]["dn"];
	    	$givenname = $info[0]['givenname'][0];

	    	// Calls the authenticate function
	    	authenticate($dn, $password, $givenname);
	    }
	    else {
	    	// Binding to LDAP server was unsuccessful

	    	echo "Unable to connect to LDAP server";
			echo "<p>Click <a href='../../login.php'>here</a> to go back.</p>";
	    }
	}
	else {
		// Connection to LDAP server was unsuccessful

		echo "Unable to connect to LDAP server";
		echo "<p>Click <a href='../../login.php'>here</a> to go back.</p>";
	}

}

	
function authenticate($username, $password, $givenname) {

	// Authenticates user's password by binding to LDAP server using the password
	// If successful, password is correct

	global $ldap_host;

	// Connects to LDAP server
	$ds = ldap_connect($ldap_host);

	// Performs bind to LDAP server with user's paasword
	// Error messages are supressed if wrong password is entered
	if (@ldap_bind($ds, $username, $password)) {

		// Bind is successful - password is correct

		// Sets cookie with user's givenname
		setLoginCookie($givenname);
	}
	else {

		// Bind is unsuccessful - password is incorrect

		echo "Log in failed. Details incorrect.";
		echo "<p>Click <a href='../../login.php'>here</a> to go back.</p>";
	}
}

?>