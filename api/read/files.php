<?php

/*

Read API for files.
Takes the page title as input from the request
Outputs a JSON object with all the files related to the page

*/

// Includes for extractSanitiseVar function
include '../../lib/utils.php';

if (isset ($_REQUEST['title'])) {
	
	// Title is set in GET or POST request
	
	// Page title is extracted and sanitised
	$pageTitle = extractSanitiseVar('title', '');

	$dir = "../../upload/$pageTitle";

	// Scanning the relevant directory within the Upload directory for files
	if (file_exists($dir)) {

		// Scans the directory, and reverses the order of output
		$files = scandir($dir, 1);
		$count = count($files);

		// For each file found, modify filename so that it gives the correct pathname
		// Note that the scandir function returns the current and parent directories as files - cut out from the output (hence $count - 3)
		for ($i = 0; $i <= $count - 3; $i++) {
		    
		    $files[$i] = "upload/" . $files[$i];

		}

		// Cuts off the current dir and parent dir references (. and ..)
		array_splice($files, $count - 2);

	}
	else {
		
		// Directory doesn't exist, therefore there are no associated files with this page - output an empty array
		$files = array();
	}

	// Encode the array in JSON and echo it out
	echo json_encode($files);
	
}
else {

	// Title is not set in request - return a 400 Bad Request

	header("HTTP/1.0 400 Bad Request");
	header("x-failure-details: No title set");
}

// DB connection is closed
mysql_close();

?>