<?php

/*

Read API for page content - i.e. the notes
Takes the page title as input from the request
Outputs a JSON object with all the page content and metadata

*/

// Includes for DB setup and extractSanitiseVar function
include '../../lib/config.php';
include '../../lib/db.php';
include '../../lib/utils.php';

if (isset ($_REQUEST['title'])) {

	// Title is set in GET or POST request
	
	// Title is extracted and sanitised
    $pageTitle = extractSanitiseVar('title', '');

    // DB setup - connects and selects
	connectAndSelectDB();

	// Query for the DB. Join of Pages and Edits table to allow access to all data
	// Pulls title, date (formatted correctly), page content, username of last editor, whether the page is locked, and whether it is featured
	$query = "SELECT Edits.id, Pages.pageTitle, content, DATE_FORMAT(dateTimeModified, '%h:%i%p %e %b %Y') AS dateTimeModified, username, isLocked, isFeatured FROM Pages JOIN Edits ON Pages.lastEditId = Edits.id WHERE Pages.pageTitle = '$pageTitle'";
	$rows = mysql_query($query);
	
	if (mysql_num_rows($rows) == 0) {

		// If no matches in the DB are found, a 404 Not Found is returned

		header("HTTP/1.0 404 Not Found");
		header("x-failure-details: No page with this title");

	}

	// Initialises an empty array
	$array = array();

	while ($line = mysql_fetch_assoc($rows)) {
		
		// For each line in the returned DB result

		// Page content is passed through the Markdown script
		include_once '../../markdown/markdown.php';
		$line['content'] = Markdown($line['content']);

		// The line is added to the array
		$array[] = $line;
	
	}

	// Encode the array in JSON and echo it out
	echo json_encode($array);
}
else {
	
	// Title is not set in request - return a 400 Bad Request
	header("HTTP/1.0 400 Bad Request");
	header("x-failure-details: No title set");
}

// DB connection is closed
mysql_close();

?>