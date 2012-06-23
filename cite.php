<?php

/*

Displays a information on how to cite the page
Pulls data from the DB, and formats it in various citation styles

*/

// Includes for DB setup
include 'lib/config.php';
include 'lib/db.php';

// Connects and selects the DB
connectAndSelectDB();

// Retrieves the page title from the GET request
$pageTitle = $_GET['title'];

// Query for the DB. The page title, date (formatted correctly for bibiliographic style and APA style), page content and username of the last editor is pulled
$query = "SELECT Pages.pageTitle, DATE_FORMAT(dateTimeModified, '%h:%i%p, %e %b, %Y') AS biblioDate, DATE_FORMAT(dateTimeModified, '%Y, %b %e') AS apaDate, username, id FROM Pages JOIN Edits ON Pages.lastEditId = Edits.id WHERE Pages.pageTitle = '$pageTitle'";
$rows = mysql_query($query);

if (mysql_num_rows($rows) == 0) {
		
	// No page in database with this title
	// Enable users to create a page

	include 'new.php';

}
else {

	$line = mysql_fetch_assoc($rows);

	// Assigning variables from the returned DB query
	$username = $line['username'];
	$biblioDate = $line['biblioDate']; // Date formatted for bibliographic style
	$apaDate = $line['apaDate']; // Date formatted for APA style
	$id = $line['id'];

?>
	
	<!-- Start of content section -->
	<section class='content'>

<?php

	include 'common/sidebar.php';

?>

	<h1 id='pageTitle'>Cite</h1>

	<!-- Start of bibliographic subsection -->
	<h2 class="subsectionheader">Bibliographic details for "<?php echo $pageTitle; ?>"</h2>

	<article>
		
		<ul>
			<li>Page name: <?php echo $pageTitle; ?></li>
			<li>Author: WikiVLE contributors</li>
			<li>Last edit by: <?php echo $username; ?></li>
			<li>Publisher: WikiVLE</li>
			<li>Date and time of last revision: <?php echo $biblioDate; ?></li>
			<li>Date and time retrieved: <?php echo date("g:i j M Y"); ?></li>
			<li>Permanent id: <?php echo $id; ?></li>
		</ul>

		<p>Please remember to check your manual of style, standards guide or instructor's guidelines for the exact syntax to suit your needs.</p>

	</article>
	<!-- End of bibiliographic subsection -->

	<!-- Start of APA subsection -->
	<h2 class="subsectionheader">Citation style for "<?php echo $pageTitle; ?>"</h2>

	<article>
		<div id="cite">
			<h3>APA style</h3>

<?php
	
	// Slightly ugly hack to retrieve full URL for the main page
	$url = $_SERVER['REQUEST_URI'];
	// Removes the action=cite part of the URL, leaving the title section
	$url = preg_replace('/&action=cite/', '', $url);
	$url = "http://" . $_SERVER['SERVER_NAME'] . $url;

	echo "<p>$pageTitle. ($apaDate). In <em>WikiVLE</em>. Retrieved " . date("g:i, M j, Y") . ", from <a href='" . $url . "'>" . $url . "</a></p>";

?>

		</div>
	</article>
	<!-- End of APA subsection -->

</section>
<!-- End of content section -->

<?php

}

?>