<?php

/*

Featured utility

Sets the isFeatured flag in the DB - this page will then be expanded on the Home page
Then redirects them back to the Admin page

*/

// Includes for DB setup
include 'db.php';
include 'config.php';

// Connects and selects the DB
connectAndSelectDB();

if (isset($_POST['featured'])) {

	// The page title of the page has been set in the POST request

	// Extracts the page title of the page to be featured
    $featured = $_POST['featured'];

    // Query that sets all pages that are currently featured to false (i.e. not featured)
    $query = "UPDATE Pages SET isFeatured=FALSE WHERE isFeatured=TRUE";
    $rows = mysql_query($query);

    // If the user has not selected "None" as the featured page (i.e. a page is to be featured)
    if ($featured != "none") {
    	
    	// Query that sets the isFeatured flag to true for the page that is to be featured
    	$query = "UPDATE Pages SET isFeatured=TRUE WHERE pageTitle='$featured'";
    	$rows = mysql_query($query);
    
    }

    // Redirects the user back to the Admin page
	header("Location: ../admin.php");

}

?>