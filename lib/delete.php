<?php

/*

Delete utility

Deletes a page from the database (from both the Edits and Pages tables)
Redirects the user to a placeholder page for the page that they deleted
This gives them the ability to recreate the page

*/

// Includes for DB setup
include 'db.php';
include 'config.php';

// Connects and selects the DB
connectAndSelectDB();

if (isset($_POST['pageTitle'])) {
	
	// The page title has been set in the POST request

	// Extracts the page title
	$pageTitle = $_POST['pageTitle'];

	// Query to delete the entry that matches the page title from the Pages table
	$query = "DELETE FROM Pages WHERE pageTitle='$pageTitle'";
	mysql_query($query);

	// Query to delete the entries that match the title from the Edits table
	$query = "DELETE FROM Edits WHERE pageTitle='$pageTitle'";
	mysql_query($query);

	// Redirects the user to the placeholder page
	header("Location: ../index.php?title=$pageTitle");

}

?>