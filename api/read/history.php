<?php

/*

Read API for history of a page
Takes the page title as input from the request - can also specify an id, or a range of ids
If a single id is set then only that edit is returned
If "from" is set, then all edits from that edit to the current are returned
If "to" is also set, then all edits from the "from" id to the "to" edit are returned
Output is given in a JSON encoded array

*/

// Includes for DB setup and extractSanitiseVar function
include '../../lib/config.php';
include '../../lib/db.php';
include '../../lib/utils.php';

if (isset ($_REQUEST['title'])) {

	// Title is set in GET or POST request

	// Page title is extracted and sanitised
    $pageTitle = extractSanitiseVar('title', '');

    // Connects and selects the DB
	connectAndSelectDB();

	// Determines whether an id (or id range has been given)
	if (isset($_REQUEST['id'])) {

		// Only a id has been set - only that particular edit is to be returned

		// Extracts and sanitises the id variable
		$id = extractSanitiseVar('id', '');
		// Sets the relevant DB query fragment
		$id = "AND id = $id";

	}
	else if (isset($_REQUEST['from'])) {

		// Only "from" has been set - all edits from that edit to the current edit are to be returned

		// Extracts and sanitises the from variable
		$from = extractSanitiseVar('from', '');
		// Sets the relevant DB query fragment
		$id = "AND id >= $from";

		if (isset($_REQUEST['to'])) {

			// Both "from" and "to" have been set - the range of edit between those ids are to be returned 

			// Extracts and sanitises the tom variable
			$to = extractSanitiseVar('to', '');
			// Sets the relevant DB query fragment
			$id = "AND id BETWEEN $from AND $to";

		}

	}
	else {

		// No id has been set, so the DB query fragment is empty

		$id = "";	

	}

	// Query for the DB. Pulls data from Edits table where the title matches
	// Id variable holds query fragment if id is set in request
	$query = "SELECT * FROM Edits WHERE pageTitle = '$pageTitle' $id ORDER BY id DESC";
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