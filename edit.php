<?php

/*

Edit page - provides a text box with the last edit of the page within it
This text can be edited and saved to the DB
A preview of the final version can be displayed
A Markdown cheatsheet for quick reference is also provided

*/

// Includes for DB setup
include 'lib/config.php';
include 'lib/db.php';
include 'lib/utils.php';

// Connects and selects the DB
connectAndSelectDB();

// Retrieves the page title from the GET request
$pageTitle = $_GET['title'];

// Query for the DB. The page title, page content, the isLocked flag and the username of the last editor and is pulled
$query = "SELECT Pages.pageTitle, content, isLocked, username FROM Pages JOIN Edits ON Pages.lastEditId = Edits.id WHERE Pages.pageTitle = '$pageTitle'";
$rows = mysql_query($query);

if (mysql_num_rows($rows) == 0) {
		
	// No page in database with this title
	// Enable users to create a page

	include 'new.php';

}
else {

	$line = mysql_fetch_assoc($rows);

	// Assigning variables from the returned DB query
	$locked = $line['isLocked'];
	$content = $line['content'];
	$lastEditBy = $line['username'];

	// If the user is logged in, then the username variable is set with their username
	if (isset ($_COOKIE['username'])) {

		$username = $_COOKIE['username'];

	}
	else {

		// If the user is not logged in, then the username variable is set as "Guest"

		$username = "Guest";

	}

	?>

	<!-- Start of content section -->
	<section class="content">

<?php

		// Include to pull sidebar from file
		include 'common/sidebar.php';

?>

		<h1 id="pageTitle">Editing <?php echo $pageTitle; ?></h1>

	<?php

	if (!$locked) {

		// The page is not locked - editing is allowed

		// Calls the cheatsheet function - inserts the cheatsheet HTML
		cheatsheet();

	?>

		<?php // Div for inserting the preview content into ?>
		<div id="preview" style="display: none;"></div>

		<form action="lib/store.php" method="POST" name="edit" id="editForm">
			<fieldset>
				<input type="hidden" name="pageTitle" value="<?php echo $pageTitle; ?>" />
				<input type="hidden" name="username" value="<?php echo $username; ?>" />

				<p><textarea name="content" rows="30" cols="100"><?php echo $content; ?></textarea></p>
				<p>
					<input type="submit" name="submit" value="Edit the page" />
					<button id="previewButton" style="display: none;">Show preview</button>
				</p>

				
				<p>Last edit by <?php echo $lastEditBy ?></p>
			</fieldset>
		</form>

	<?php

	}
	else {

		// Note is locked - editing is not allowed

	?>

		<p>This note is locked, and cannot be edited</p>
		<p>Please use the Admin controls to unlock the note</p>

	<?php

	}

	?>

	<?php //Invoking the AJAX for the preview ?>
	<script src='lib/preview.js'></script>

	</section>
	<!-- End of section -->

<?php

}

?>