<?php

/*

Sidebar common to all pages within the site
Contains links to Home, About and Search pages
Also has a dropdown toolbar only shown when on a actual page (i.e. not Home or Admin) with links to edit the page, display history, upload files and cite the page

*/

if (isset($_COOKIE['isAdmin'])) {

	// If the user is logged in with Admin rights, the isAdmin variable is set for use later in the script
	$isAdmin = true;
}
else {

	$isAdmin = false;

}

?>

<!-- Start of sidebar section -->
<nav class="sidebar">
	<div id="navigation">
		<ul>
			<?php // Links to Home, About and Search pages ?>
			<li><a href="./">Home</a></li>
			<li><a href="about.php">About</a></li>
			<li><a href="search.php">Search</a></li>

<?php

			if ($isAdmin) {

				// If the user is logged in with Admin rights, a link to the Admin page is shown

?>

			<li><a href="admin.php">Admin</a></li>

<?php

			}

?>

		</ul>
	</div>

<?php

if (isset($_GET['title'])) {

	// If the title is set in the GET request, then the page is a notes page (i.e. not Home, Admin, Search, etc)

	$pageTitle = $_GET['title'];

?>

	<div id="tools">
		<h4 class="open">Tools</h4>
	</div>
	<div class="sidebarMenu">
		<ul>
			<?php // Links to edit, history, upload and cite ?>
			<li><a href="index.php?title=<?php echo $pageTitle; ?>&amp;action=edit">Edit this page</a></li>
			<li><a href="index.php?title=<?php echo $pageTitle; ?>&amp;action=history">History</a></li>
			<li><a href="index.php?title=<?php echo $pageTitle; ?>&amp;action=upload">Upload a file</a></li>
			<li><a href="index.php?title=<?php echo $pageTitle; ?>&amp;action=cite">Cite this page</a></li>

<?php

			if ($isAdmin) {

				// If the user has Admin rights, a delete link is shown

?>

			<li><a href="index.php?title=<?php echo $pageTitle; ?>&amp;action=delete">Delete this page</a></li>

<?php

			}

?>

		</ul>
	</div>

<?php

}

?>

</nav>
<!-- End of sidebar section -->