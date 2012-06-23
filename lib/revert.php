<?php

/*

Revert utility

Reverts a page back to a previous version
Takes the id of the post that is to be reverted to as input
Changes the entry in the Pages table, and deletes all the entries in the Edits table up to that point
Then redirects user back to the display page

*/

// Includes for DB setup
include 'db.php';
include 'config.php';

// Connects and selects the DB
connectAndSelectDB();

if (isset ($_POST['id'])) { 

	// An id has been set in the POST request

	// Extracts page title and id from the POST request
	$id = $_POST['id'];
	$pageTitle = $_POST['pageTitle'];


	// DB query to set the lastEditId to the newly reverted id
	$query = "UPDATE Pages SET lastEditId='$id' WHERE pageTitle='$pageTitle'";
    mysql_query($query);

    // DB query to delete all the edits from the previous version to the newly reverted id
	$query = "DELETE FROM Edits WHERE pageTitle = '$pageTitle' AND id > $id";
	$rows = mysql_query($query);

	// Redirects the user back to the display page
	header("Location: ../index.php?title=$pageTitle");

}

?>