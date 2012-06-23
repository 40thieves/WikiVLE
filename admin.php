<?php

/*

Administration page

Only available to users with Admin rights
Admins can change the stylesheet used for the site, by uploading a new CSS file or switching between existing stylesheets
The can also lock a page so that it cannot be edited, by setting a isLocked flag in the DB
And finally they can set a page to be featured on the Home page, by setting the isFeatured flag in the DB

*/

// Includes for DB setup
include 'lib/config.php';
include 'lib/db.php';

// Include to pull the header (including <head> section) from the header file
include 'common/header.php';

// DB setup - connects and selects
connectAndSelectDB();

?>

<!-- Start of admin section -->
<section class="admin">

<?php

    // Include to pull the sidebar from file
    include 'common/sidebar.php';

?>

    <h1 id="pageTitle">Administration</h1>

<?php

// Check to see whether the user is logged in with admin rights
if (isset($_COOKIE['isAdmin'])) {

    // User has admin rights
?>

    <article>
        <p>Welcome to WikiVLE Administration.</p>
        <p>Here you can change to look and feel of WikiVLE, lock the editing of notes and change the home page.</p>
    </article>

    <!-- Start of Change CSS subsection -->
    <article>
    <h2 class="subsectionheader">Change the look and feel of WikiVLE</h2>

<?php

// Scans the relevant directory within the Upload directory looking for files

$dir = "stylesheets";

// Scans the directory, and reverses the order of output
$files = scandir($dir, 1);

$count = count($files);

?>

    <form action="admin.php" method="POST">
        <p>Please select a stylesheet:</p>

<?php

for ($i = 0; $i <= $count - 3; $i++) {

    // Note that the scandir function returns the current and parent directories as files - cut out from the output (hence $count - 3)

    // For each file found, output a radio button with the filename set as an attribute    
    $filename = $files[$i];
    echo "<p><input type='radio' name='stylesheet' value='$filename' /> $filename</p>";

}

?>

    <p><input type="submit" name="submit" value="Select" /></p>
    </form>

<?php

// Utility to change the stylesheet that is set in the config file

if (isset($_POST['stylesheet'])) {

    // Set path for the config file
    $filename = "lib/config.php";
    // Extract the filename of the stylesheet from the POST request
    $css = $_POST['stylesheet'];

    // Get all the text found in the config file (except the very first character - "<")
    $contents = file_get_contents($filename, NULL, NULL, 1);

    // Regular expression to remove and replace the previous CSS filename from the config file with the new filename
    $regex = "/stylesheets\/.*css/";
    $replacement = "stylesheets/$css";
    $contents = preg_replace($regex, $replacement, $contents);

    // Replaces the first character
    $contents = "<" . $contents;

    // Updated contents of the config file replaced 
    file_put_contents($filename, $contents);

    // User redirected to Admin page - essentially refreshing the page
    header("Location: ./admin.php");

}

?>

    <form action="admin.php" method="POST" enctype="multipart/form-data">
        <p>Upload a new CSS file to change the look and feel of the site</p>
        <p>
            <label>Filename:</label>
            <input type="file" name="file" id="file" /> 
        </p>
        <input type="submit" name="submit" value="Submit" />
    </form>

</article>
<!-- End of CSS change subsection -->

<?php

// Utility to store an uploaded CSS file in the stylesheets directory

if (isset($_FILES["file"])) {

    // Extracts file data from the uploaded file
    $fileName = $_FILES["file"]["name"]; // File name
    $fileType = $_FILES["file"]["type"]; // File type - MIME type
    $fileSize = $_FILES["file"]["size"]; // File size in bytes
    $tempFileName = $_FILES["file"]["tmp_name"]; // Name of file while in temporary storage
    $fileError = $_FILES["file"]["error"];

    // Checks to ensure that the file is a CSS file
    if ($fileType == "text/css") {

        // Checks that there is no error in the file and that it is smaller than 3MB
        if (($fileError > 0) && ($fileSize < 3000000)) {
            
            echo "Error Code: $fileError<br />";

        }
        else {

            // Checks to ensure that a file with the same filename does not already exist
            if (file_exists("stylesheets/$fileName")) {

                echo "The file: $fileName already exists.";

            }
            else {

                // The file is moved from temporary storage to the stylesheets directory
                move_uploaded_file($tempFileName, "stylesheets/$fileName");

                echo "<p>Success!</p>";

            }
        }
    }
    else {

        echo "Invalid file";

    }

    // Redirects user to the Admin page - essentially a page refresh
    header("Location: ./admin.php");

}

?>

<!-- Start of lock notes subsection -->
<article>
    <h2 class="subsectionheader">Lock notes</h2>

    <p>Lock a page's notes so that they cannot be edited</p>

<?php

    // DB query to get all the page titles, with isLocked and isFeatured flags
    $query = "SELECT pageTitle, isLocked, isFeatured FROM Pages";
    $rows = mysql_query($query);

    if (mysql_num_rows($rows) == 0) {
        
    // No pages in the database

    echo "<p>No pages created yet.</p>";

    }
    else {

        // Pages found in the DB 

?>

    <table>
        <tr>
            <th>Page Title</th>
        </tr>

<?php

        while ($line = mysql_fetch_assoc($rows)) {

            // Display a list of pages, with a Lock/Unlock button for each

            $pageTitle = $line['pageTitle'];
            $locked = $line['isLocked'];

?>

            <tr>
                <td><?php echo $pageTitle; ?></td>
                <td>
<?php

                if (!$locked) {

                    // isLocked flag is false - page is not locked
                    // Show a Lock button - action field with value "lock"

?>
                <form action="lib/lock.php" method="POST">
                    <button name="pageTitle" value="<?php echo $pageTitle; ?>">Lock</button>
                    <input type="hidden" name="action" value="lock" />
                </form>

<?php

                }
                else {

                    // isLocked flag is true - page is locked
                    // Show an Unlock button - action field with value unlock

?>
                
                <form action="lib/lock.php" method="POST">
                    <button name="pageTitle" value="<?php echo $pageTitle; ?>">Unlock</button>
                    <input type="hidden" name="action" value="unlock" />
                </form>

<?php

                }

?>

                </td>
            </tr>

<?php

        }
    }

?>

    </table>
</article>
<!-- End of lock notes subsection -->

<!-- Start of featured subsection -->
<article>
    <h2 class="subsectionheader">Change the home page</h2>

    <form action="lib/featured.php" method="POST">
        <p>Select a page to be featured at the top of the home page</p>

<?php

    if (mysql_num_rows($rows) == 0) {
        echo "<p>No pages created yet.</p>";
    }
    else {

        // Resets pointer within the $rows variable to the first item in the results
        // The same as performing the previous DB query again
        mysql_data_seek($rows, 0);

        while ($line = mysql_fetch_assoc($rows)) {

            // Display a list of pages, with a radio button for each

            // Extract the page title and isFeatured variables
            $pageTitle = $line['pageTitle'];
            $featured = $line['isFeatured'];

            if ($featured) {

                // Page is featured

                // Display a checked radio button
?>

            <p><input type='radio' name='featured' value='<?php echo $pageTitle; ?>' checked /> <?php echo $pageTitle; ?></p>

<?php
            }
            else {

                // Page is not featured

                // Display a non-checked radio button

?>

            <p><input type='radio' name='featured' value='<?php echo $pageTitle; ?>' /> <?php echo $pageTitle; ?></p>

<?php

            }

        }

?>
        <?php // An option is provided for selected no page as the featured page ?>
        <p><input type='radio' name='featured' value='none' /> None</p>
        <p><input type="submit" name="submit" value="Select" /></p>
        </form>

<?php
    }

}

else {

    // User is not logged in with admin rights

    // Display a message telling them to log in

?>

<p>Access is restricted. Please use Admin log in details to view this page.</p>

<?php

}

?>

</article>
<!-- End of featured subsection -->

</section>
<!-- End of admin section -->

<?php

// Include to pull footer from the footer file
include 'common/footer.php';

?>        