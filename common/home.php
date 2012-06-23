<?php

/*

Home page for the site
Displays a list of all pages, with a featured page preview expanded at the top
If there are no pages saved, then a form to create a new page is shown

*/

// Includes for DB setup and other utilities
include 'lib/config.php';
include 'lib/db.php';
include 'lib/utils.php';

// Connects and selects the DB
connectAndSelectDB();

?>

<!-- Start of content section -->
<section class="content">

<?php

	// Include to pull the sidebar from file
	include 'common/sidebar.php';

?>

	<h1 id="pageTitle">Home</h1>

	<article>
		<p>Welcome to WikiVLE, the Virtual Learning Environment that anyone can edit</p>
	</article>

<?php

	// Query to get the featured page from DB. Pulls title, date (formatted correctly), page content and user name of last editor
	$query = "SELECT Pages.pageTitle, DATE_FORMAT(dateTimeModified, '%h:%i %e %b %Y') AS dateTimeModified, content, username FROM Pages JOIN Edits ON Pages.lastEditId = Edits.id WHERE isFeatured=TRUE";
	$rows = mysql_query($query);

	if (mysql_num_rows($rows) != 0) {

		// Featured page found in the DB

		// Sets featured variable so that later in the script we can tell that a featured page has been found
		$featured = true;

		$line = mysql_fetch_assoc($rows);

		// Assigning variables from the retured DB query
		$pageTitle = $line['pageTitle'];
		$content = $line['content'];
		$modified = $line['dateTimeModified'];
		$username = $line['username'];

		// Trims the page content to 1000 characters, and removes Markdown syntax
		// Provides a plain text preview of the featured page content
		$content = regexTrim("/[*#\[\]_>`]|[~=-]{3}/", "", $content, 1000);

?>
	
	<!-- Start of featured subsection -->
	<article id="featured">

		<h2 class="subsectionheader">Featured page - <a href="./index.php?title=<?php echo $pageTitle; ?>"><?php echo $pageTitle; ?></a></h2>

		<p><?php echo $content; ?></p>

		<div class='meta'>	
			<p>Page modified at <?php echo $modified; ?> by <?php echo $username; ?></p>
		</div>

	</article>
	<!-- End of featured subsection -->

<?php
	
	}
	else {

		// No featured page found in DB

		// Sets featured variable so that later in the script we can tell that no featured page has been found
		$featured = false;
	}

// Query to get all non-featured pages from DB. Pulls title, page content and user name of last editor
$query = "SELECT Pages.pageTitle, content, username FROM Pages JOIN Edits ON Pages.lastEditId = Edits.id WHERE isFeatured != TRUE ORDER BY id DESC";
$rows = mysql_query($query);

if (mysql_num_rows($rows) != 0) {

	// A list of pages has been found

?>

	<!-- Start of list subsection -->
	<article id="list">

<?php

	while ($line = mysql_fetch_assoc($rows)) {

		// For each line in the returned array from the DB, display the page title, and a link to the page

		$pageTitle = $line['pageTitle'];

?>
	<div class="list">

		<a href="./index.php?title=<?php echo $pageTitle; ?>"><h2><?php echo $pageTitle; ?></h2></a>

	</div>

<?php	

	}

?>

	</article>
	<!-- End of list subsection -->

<?php

}
else if (!$featured) {

	// No pages have been found in the DB (featured or otherwise)
	// Display a form to create a new page

?>

<?php

	// If a user is logged in, then use their username, if not set as Guest user

	if (isset ($_COOKIE['username'])) {

		$username = $_COOKIE['username'];

	}
	else {

		$username = "Guest";

	}

?>

	<p>WikiVLE does not have any pages yet. Create one in the form below.</p>

	<!-- Start of new page form subsection -->
	<form action="lib/store.php" method="POST">
		<fieldset>
			<legend>Create a page</legend>

			<p><label>Page Title </label><input type="text" name="pageTitle"></p>	
			<input type="hidden" name="username" value="<?php echo $username; ?>" />
			<?php // Flag to store script - this is a new page ?>
			<input type="hidden" name="isNew" value="y" />

			<p><textarea name="content" placeholder="Write something..." rows="10" cols="100"></textarea></p>
			<p><input type="submit" value="Submit the page" /></p>
		</fieldset>
	</form>
	<!-- End of new page form subsection -->

<?php

}

?>

</section>
<!-- End of content section -->