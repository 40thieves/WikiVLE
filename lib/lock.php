<?php

/*

Lock utility

Locks a page in the database
Takes the page title as input, then sets the isLocked flag in the DB
Redirects the user back to the Admin page

*/

// Includes for DB setup
include 'db.php';
include 'config.php';

// Connects and selects the DB
connectAndSelectDB();

if (isset ($_POST['pageTitle'])) {

	// The page title has been set in the POST request

	// Extracts page title and action from the POST request
	$pageTitle = $_POST['pageTitle'];
	$action = $_POST['action'];

	if ($action == "lock") {

		// The page is to be locked

		// DB query to set the isLocked flag to true on the entry that matches the title
		$query = "UPDATE Pages SET isLocked=TRUE WHERE pageTitle='$pageTitle'";
		$rows = mysql_query($query);

	}
	else {

		// DB query to set the isLocked flag to false on the entry that matches the title
		$query = "UPDATE Pages SET isLocked=FALSE WHERE pageTitle='$pageTitle'";
		$rows = mysql_query($query);

	}

	// Redirects the user back to the Admin page
	header("Location: ../admin.php");

}

?>