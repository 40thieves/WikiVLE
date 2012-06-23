<?php

/*

History page

Displays a table of previous versions of the page
Content pulled from the DB, and displayed in chronological order
Revert buttons are provided for each edit, so that the page can go back to a previous version

*/

// Includes for DB setup and regexTrim function
include 'lib/config.php';
include 'lib/db.php';
include 'lib/utils.php';

// Connects and selects the DB
connectAndSelectDB();

// Retrieves the page title from the GET request
$pageTitle = $_GET['title'];

// Query for the DB. The page title, date (formatted correctly), page content and username of the last editor for each edit is pulled
$query = "SELECT id, pageTitle, DATE_FORMAT(dateTimeModified, '%h:%i%p %e %b %Y') AS dateTimeModified, content, username FROM Edits WHERE pageTitle = '$pageTitle' ORDER BY id DESC";
$rows = mysql_query($query);

if (mysql_num_rows($rows) == 0) {
		
	// No page in database with this title
	// Enable users to create a page

	include 'new.php';

}
else {

?>
	<!-- Start of content section -->
	<section class="content">

<?php

		// Include to pull sidebar from file
		include 'common/sidebar.php';

?>


		<h1 id="pageTitle">History of <?php echo $pageTitle; ?></h1>

		<!-- Start of table subsection -->
		<article id="historyTarget">
			<table>
				<tr>
					<th>Time of modification</th>
					<th>Sample content</th>
					<th>Edited by</th>
				</tr>

<?php

			$i = 0;
			while ($line = mysql_fetch_assoc($rows)) {

				// Assigning variables from the returned DB query
				$modified = $line['dateTimeModified'];
				$content = $line['content'];
				$id = $line['id'];
				$pageTitle = $line['pageTitle'];
				$username = $line['username'];

				// Trims the page content to 110 characters, and removes Markdown syntax
				// Provides a plain text preview of the featured page content
				$content = regexTrim("/[*#\[\]_>`]|[~=-]{3}/", "", $content, 110);

?>

				<tr>
					<td><?php echo $modified; ?></td>
					<td><?php echo $content; ?></td>
					<td><?php echo $username; ?></td>
					<td>

<?php
					if ($i > 0) {

						// If the edit is the last in the list (i.e. is the last edit), then no revert button is provided - you can't revert to the current version

?>
						<form action="lib/revert.php" method="POST">
							<button name="id" value="<?php echo $id; ?>">Revert</button>
							<input type="hidden" name="pageTitle" value="<?php echo $pageTitle; ?>" />
						</form>

<?php

					}

?>

					</td>
				</tr>

<?php

			$i++;

			}

?>

			</table>
		</article>
		<!-- End of table subsection -->

		<?php //Invoking the AJAX for the table update  ?>
		<script src='lib/historyAjax.js'></script>

	</section>
	<!-- End of content section -->

<?php

}

?>
