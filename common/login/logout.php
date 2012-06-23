<?php

/*

Logout utility

Deletes cookies so that the user is logged out
Redirects user to the Home page

*/

if (isset ($_COOKIE['username'])) {

	// The username cookie is set

	// Deletes cookies by setting them to expire in 1 second
	setcookie("username", "", 1, "/");
	setcookie("isAdmin", "", 1, "/");

}

// Redirects the user to the Home page
header("Location: ../../")

?>