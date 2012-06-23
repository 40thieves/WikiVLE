<?php

/*

Store utilities

Two utilities to store new edits and pages or upload new files

New edits/pages:
Takes page title, new content and editor's username as input
If page is not locked, then new data is added to Edits table

If the isNew flag is set, then a new page is to be created - data is added to Pages table
Otherwise, the relevant Pages entry is updated with the new lastEditId

Finally, the user is redirected back to the display page

New files:
Takes an uploaded file as input, as well as the page title
Checks if the file is an acceptable file type, and is within the size limits
Then checks if the relevant directory exists (directories within Upload are organised by page title)
The file is then moved from the temporary storage to the correct location

Finally, the user is redirected back to the display page

*/

// Includes for DB setup and the extractSanitiseVar function
include 'config.php';
include 'db.php';
include 'utils.php';

if (isset ($_POST['pageTitle']) && isset($_POST['content'])) {

    // Page title and new content are set in the POST request

    // Title, new content and last editor's username are extracted and sanitised
    $pageTitle = extractSanitiseVar('pageTitle', '');
    $content = extractSanitiseVar('content', '');
    $username = extractSanitiseVar('username', '');
    
    // New date and time created for entry into the DB - formatted for MySQL DATETIME
    $daytime = date("Y-m-d H:i");
    
    // DB setup - connects and selects
    connectAndSelectDB();

    // Inserts the new edit into the Edits table
    $query = "INSERT INTO Edits (content, pageTitle, dateTimeModified, username) VALUES ('$content', '$pageTitle', '$daytime', '$username')";
    mysql_query($query);
    
    // Gets the id from the edit insertion, so that it can be used in the Pages table
    $id = mysql_insert_id();

    if (isset ($_POST['isNew'])) {

        // The isNew flag is set
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
    
    // DB server connection is close
    mysql_close();

    // Redirects the user back to the Display page
    header("Location: ../index.php?title=$pageTitle");
    
}

if (isset($_FILES["file"])) {

    // A file has been uploaded, and is held in temporary storage

    // DB setup - connects and selects
    connectAndSelectDB();

    // The directory that the file will be stored in
    $fileDirectory = $_POST['pageTitle'];

    // Extracts file details from the uploaded file
    $fileName = $_FILES["file"]["name"]; // The file name
    $fileType = $_FILES["file"]["type"]; // The file type - MIME type
    $fileSize = $_FILES["file"]["size"]; // File size in bytes
    $tempFileName = $_FILES["file"]["tmp_name"]; // The name of the file while in temporary storage
    $fileError = $_FILES["file"]["error"]; // Any file errors generated

    // Ugly if statement to check whether the file type is acceptable
    // Accepted file types in order: MS Powerpoint, ODF Presentation, PDF, MS Word, ODF Text, Plain text, GIF, JPEG, PNG
    // Files must be under 3MB in size
    if (( ($fileType == "application/vnd.ms-powerpoint") || ($fileType == "application/vnd.openxmlformats-officedocument.presentationml.presentation") || ($fileType == "application/pdf") || ($fileType == "application/msword") || ($fileType == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") || ($fileType == "text/plain") || ($fileType == "application/vnd.oasis.opendocument.text") || ($fileType == "application/vnd.oasis.opendocument.presentation") || ($fileType == "image/gif") || ($fileType == "image/jpeg") || ($fileType == "image/png") ) && ($fileSize < 3000000)) {

        // Checks whether there were any errors during the upload process
        if ($fileError > 0) {
            
            echo "Error Code: $fileError<br />";

        }
        else {

            // Checks to see whether a directory for the page already exists
            if (!file_exists("../upload/$fileDirectory")) {

                // Directory for the page does not exist

                // A new directory is created within the Upload directory
                mkdir("../upload/$fileDirectory");

            }

            // Check to see whether a file with this name already exists
            if (file_exists("../upload/$fileDirectory/$fileName")) {

                echo "The file: $fileName already exists. ";

            }
            else {

                // The file does not already exist, and a suitable directory has been created

                // The file is moved from temporary storage to the suitable directory
                move_uploaded_file($tempFileName, "../upload/$fileDirectory/$fileName");

            }
        }
    }
    else {

        // The file is too large, or is of an unacceptable file type

        echo "Invalid file";

    }

    // Redirects the user back to the Display page
    header("Location: ../index.php?title=$fileDirectory");


}

?>