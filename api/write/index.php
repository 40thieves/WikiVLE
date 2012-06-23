<?php

/*

Write API for page content
Write either an new edit for a page, or create a new page

Takes title, new content and username of editor as input
If page is not locked, then new data is added to Edits table

If the isNew flag is set, then a new page is to be created - data is added to Pages table
Otherwise, the relevant Pages entry is updated with the new lastEditId

Output is given in a JSON encoded array of the newly written content

*/

// Includes for DB setup and extractSanitiseVar function
include '../../lib/config.php';
include '../../lib/db.php';
include '../../lib/utils.php';   

if (isset ($_REQUEST['title'])) {
    
    // Title is set in GET or POST request

    // DB setup - connects and selects
    connectAndSelectDB();

    // Title, new content and last editor's username are extracted and sanitised
	$pageTitle = extractSanitiseVar('title', '');
    $content = extractSanitiseVar('content', '');
    $username = extractSanitiseVar('username', '');

    // New date and time created for entry into the DB - formatted for MySQL DATETIME
    $daytime = date("Y-m-d H:i");

    // Determines whether the page is locked, by querying the DB for the page and pulling the isLocked flag
    $query = "SELECT * FROM Pages WHERE pageTitle='$pageTitle'";
    $rows = mysql_query($query);
    $line = mysql_fetch_assoc($rows);

    // Pulls the isLocked flag from the DB result
    $isLocked = $line['isLocked'];

    if (!$isLocked) {

        // Page is not locked - can edit it
        
        // Inserts the new edit into the Edits table
        $query = "INSERT INTO Edits (content, pageTitle, dateTimeModified, username) VALUES ('$content', '$pageTitle', '$daytime', '$username')";
        mysql_query($query);

        // Gets the id from the edit insertion, so that it can be used in the Pages table
        $id = mysql_insert_id();

        if (isset ($_REQUEST['isNew'])) {

            // Storing a new page - a new entry needs to be inserted into the Pages table
            
            // Inserts the new page into Page table
            $query = "INSERT INTO Pages (pageTitle, lastEditId) VALUES ('$pageTitle', '$id')";
            mysql_query($query);

        }
        else {

            // Editing an existing page - lastEditId needs to be updated in the Pages table

            $query = "UPDATE Pages SET lastEditId='$id' WHERE pageTitle='$pageTitle'";
            mysql_query($query);

        }

        // Passes the newly stored content through the Markdown script, so that it can be returned
        include_once '../../markdown/markdown.php';
        $content = Markdown($content);

        // Constructs an array so that it can be returned as output
        $array = array(
            "id" => $id,
            "pageTitle" => $pageTitle,
            "content" => $content,
            "dateTimeModified" => $daytime,
            "username" => $username
        );

        // Encode the array in JSON and echo it out
        echo json_encode($array);

    }
    else {

        // Page is locked, so editing is not allowed - return a 400 Bad Request
        header("HTTP/1.0 400 Bad Request");
        header("x-failure-details: Page is locked");

    }

}
else {

    // Title is not set in request - return a 400 Bad Request
    header("HTTP/1.0 400 Bad Request");
    header("x-failure-details: No title set");

}

// DB connection is closed
mysql_close();

?>