<?php

/*

setLoginCookie function

Sets a cookie so that user is logged in. Optionally sets a isAdmin cookie if the user has admin rights

Takes username as input, and optional isAdmin flag
Sets the cookies, with 1 hour expiration time
Redirects user to the Home page

*/

function setLoginCookie($username, $isAdmin = false) {

	// Sets the username cookie
	// Expires after 1 hour, and is valid on the whole domain
    setcookie("username", "$username", time() + 3600, "/");

    if ($isAdmin) {

    	// User has admin rights

    	// Sets the isAdmin cookie
    	// Expires after 1 hour, and is valid on the whole domain
    	setcookie("isAdmin", "true", time() + 3600, "/");

    }

    // Redirects user to the home page
    header("Location: ../../");

}

?>