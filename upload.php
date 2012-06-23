<?php

/*

Upload page

Allows user to upload files related to the page
And display a list of previously uploaded files

*/

// Includes for DB setup
include 'lib/config.php';
include 'lib/db.php';

// Connects and selects the DB
connectAndSelectDB();

// Retrieves the page title from the GET request
$pageTitle = $_GET['title'];

// Query for the DB. Only used to check that a page within the database exists - therefore no specific data is pulled
$query = "SELECT * FROM Pages JOIN Edits ON Pages.lastEditId = Edits.id WHERE Pages.pageTitle = '$pageTitle'";
$rows = mysql_query($query);

if (mysql_num_rows($rows) == 0) {
        
    // No page in database with this title
    // Enable users to create a page

    include 'new.php';

}
else {


$line = mysql_fetch_assoc($rows);

?>

<!-- Start of content section -->
<section class="content">

<?php

    // Include to pull sidebar from file
    include 'common/sidebar.php';

?>
    
    <h1 id="pageTitle">Upload</h1>

    <!-- Start of file form subsection -->
    <h2 class="subsectionheader">Upload files</h2>
    <article>
        <form action="lib/store.php" method="POST" enctype="multipart/form-data">
            <p>
                <label>Filename:</label>
                <input type="file" name="file" id="file" /> 
            </p>
            <input type="hidden" name="pageTitle" value="<?php echo $pageTitle; ?>">
            <input type="submit" name="submit" value="Submit" />
        </form>

        <p><small>WikiVLE accepts: Powerpoint, Word, ODF Text, ODF Presentation, PDF, Plain text, GIF, JPEG and PNG file formats</small></p>

    </article>
    <!-- End of file form subsection -->

    <!-- Start of file display subsection -->
    <h2 class="sectionsubheader">Uploaded files</h2>

    <article id="uploadTarget">

<?php

        // Scans the relevant directory within the Upload directory for files
        
        $dir = "upload/$pageTitle";

        if (file_exists($dir)) {

            // Scans the directory, and reverses the order of output
            $files = scandir($dir, 1);
            $count = count($files);

            // For each file found, display it's name and a link to download it
            // Note that the scandir function returns the current and parent directories as files - cut out from the output (hence $count - 3)
            for ($i = 0; $i <= $count - 3; $i++) {
                
                $filename = $files[$i];

?>

        <p><a href='upload/<?php echo $pageTitle . "/" . $filename; ?>'><?php echo $filename; ?></a></p>

<?php

            }
        }
        else {

            // Directory doesn't exist, therefore there are no associated files with this page - display a "No files found" message

?>

        <p>No files to display</p>

<?php

        }

?>

    </article>
    <!-- End of file display subsection -->

    <script src='lib/uploadAjax.js'></script>

</section>
<!-- End of content section -->

<?php

}

?>