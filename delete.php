<?php

/*

Displays delete page confirmation
Checks to see whether the page is locked
If so, then a message is diplayed
Otherwise a button is provided to delete the page from the DB

*/

// Includes for DB setup
include 'lib/config.php';
include 'lib/db.php';

// Connects and selects the DB
connectAndSelectDB();

// Retrieves the page title from the GET request
$pageTitle = $_GET['title'];

// Query for the DB. Only the isLocked flag is pulled, as it's all that is required
$query = "SELECT isLocked FROM Pages JOIN Edits ON Pages.lastEditId = Edits.id WHERE Pages.pageTitle = '$pageTitle'";
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

?>

	<!-- Start of content section -->
	<section class="content">

<?php

		// Include to pull sidebar from file
		include 'common/sidebar.php';

?>

		<h1 id="pageTitle">Delete <?php echo $pageTitle; ?>?</h1>

<?php

	if (isset ($_COOKIE['isAdmin'])) {
	
		if (!$locked) {

			// Page is not locked

?>

		<article>

			<p>Are you sure that you want to delete the page "<?php echo $pageTitle; ?>"?</p>
			<form action="lib/delete.php" method="POST">
				<button name="pageTitle" value="<?php echo $pageTitle; ?>">Confirm</button>
			</form>

		</article>

<?php

		}
		else {
			// Page is locked

?>

		<article>
			<p>This note is locked, and cannot be edited</p>
			<p>Please use the Admin controls to unlock the note</p>
		</article>

<?php

		}
	}
	else {
		// Not an admin log in

?>

		<article>

			<p>Access is restricted. Please use Admin log in details to view this page.</p>

		</article>

<?php

	}
	
}

?>

</section>
<!-- End of content section -->