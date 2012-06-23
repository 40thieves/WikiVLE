<?php
/*

Display page

Displays page content for each page
Content pulled from DB, and displayed. Related files also found and displayed

*/

// Includes for DB setup
include 'lib/config.php';
include 'lib/db.php';

// Connects and selects the DB
connectAndSelectDB();

// Retrieves the page title from the GET request
$pageTitle = $_GET['title'];

// Query for the DB. The page title, date (formatted correctly), page content and username of the last editor is pulled
$query = "SELECT Pages.pageTitle, DATE_FORMAT(dateTimeModified, '%h:%i%p %e %b %Y') AS dateTimeModified, content, username FROM Pages JOIN Edits ON Pages.lastEditId = Edits.id WHERE Pages.pageTitle = '$pageTitle'";
$rows = mysql_query($query);

if (mysql_num_rows($rows) == 0) {
		
	// No page in database with this title
	// Enable users to create a page

	include 'new.php';

}
else {

	$line = mysql_fetch_assoc($rows);

	// Assigning variables from the returned DB query
	$content = $line['content'];

	// Passes the page content through the Markdown script, converting it to HTML
	include 'markdown/markdown.php';
	$content = Markdown($content);

	$username = $line['username'];
	$modified = $line['dateTimeModified'];

?>
	<!-- Start of content section -->
	<section class="content">

<?php

	// Include to pull sidebar from file
	include 'common/sidebar.php';

?>

	<h1 id="pageTitle"><?php echo $pageTitle ?></h1>

	<!-- Start of files subection -->
	<h2 class="subsectionheader">
		<span class="editlink">
			[<a href="index.php?title=<?php echo $pageTitle; ?>&amp;action=upload">Upload</a>]
		</span>
		<span>Files</span>
	</h2>

	<article id="uploadTarget">

	<?php

	// Scanning the relevant directory within the Upload directory for files
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
	<!-- End of files subsection -->

	<!-- Start of notes subsection -->
	<h2 class="subsectionheader">
		<span class="editlink">
			[<a href="index.php?title=<?php echo $pageTitle; ?>&amp;action=edit">Edit</a>]
		</span>
		<span>Notes</span>
	</h2>

	<article id="notes">
		<?php // Echoing out the content and metadata ?>
		<p><?php echo $content; ?></p>

		<div class='meta'>	
			<p>Page modified at <?php echo $modified; ?> by <?php echo $username; ?></p>
		</div>
	</article>
	<!-- End of notes subsection -->

	<?php //Invoking the AJAX for the notes and files subsections  ?>
	<script src='lib/displayAjax.js'></script>
	<script src='lib/uploadAjax.js'></script>

</section>
<!-- End of content section -->

<?php
	
}

?>