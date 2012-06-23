<?php

/*

Preview utility

Takes page content as input, and passes it through the Markdown script
The content is then encoded in a JSON object and output

*/

if (isset($_POST['content'])) {

	// Content has been set in the POST request
	
	// Extracts the content from the request
	$content = $_POST['content'];

	// Passes the content through the Markdown script
	include '../markdown/markdown.php';
	$content = Markdown($content);

	// Constructs an array so that it can be returned as output
	$array = array(
		"content" => $content
	);

    // Encode the array in JSON and echo it out
	echo json_encode($array);

}

?>