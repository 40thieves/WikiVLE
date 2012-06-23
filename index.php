<?php

/*

Main file that organises the architecture of the site.
Page content (not including header, sidebar and footer) pulled from relevant file, based on title and action variables set in the GET request.

*/

// Include to pull the header (including <head> section) from the header file
include 'common/header.php';


// Checks whether the page title is set in the GET request, and if so attempts to retrieve action from the GET request
if (isset ($_GET['title'])) {

	$pageTitle = $_GET['title'];

	// If action is not set, then it is initialised to an empty string
	if (!isset($_GET['action'])) {
		$action = "";
	}
	else {
		$action = $_GET['action'];
	}

 	if ($action == "") {
		
		// There is no action to be performed
		// Therefore the page content is to be presented

		include 'display.php';

	}

	else if ($action == "history") {
		
		// The history action is requested
		// Display the history page for the current page (i.e. one that matches $pageTitle)

		include 'history.php';

	}

	else if ($action == "edit") {

			// The edit action is requested
			// Display the edit page for the current page (i.e. one that matches $pageTitle)

		include 'edit.php';

	}
	else if ($action == "upload") {

		// The upload action is requested
		// Display the upload page for the current page (i.e. one that matches $pageTitle)

		include 'upload.php';

	}
	else if ($action == "delete") {

		// The delete action is requested
		// Display the delete page for the current page (i.e. one that matches $pageTitle)

		include 'delete.php';

	}
	else if ($action == "cite") {

		// The cite action is requested
		// Display the cite page for the page (i.e. one that matches $pageTitle)

		include 'cite.php';

	}
	else {

		// Some other (unknown) action is requested
		// Display error page - "No such action"

		include 'error.php';
	}
	
}
else {

 	// Home page is being called
 	// Present home page content

	include 'common/home.php';

}
	// Include to pull footer from the footer file
	include 'common/footer.php';

?>