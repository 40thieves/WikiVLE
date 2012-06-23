<?php

/*

New page

Provides user with form to create a new page

*/

// Retrieves the page title from the GET request
$pageTitle = $_GET['title'];

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

	// Includes for DB setup
	include 'common/sidebar.php';

?>

	<h1 id='pageTitle'><?php echo $pageTitle; ?></h1>
	
	<article>

		<p><strong>WikiVLE does not have a page with this title</strong>. Try a <a href="./index.php?title=<?php echo $pageTitle; ?>">search</a> for <?php echo $pageTitle; ?> in WikiVLE to check for alternative titles or spellings.</p>
		<p>Other reasons this message may be displayed:
			<ul>
				<li>If the page was recently created, it may not yet be visible due to a delay in updating the database - wait a few minutes and try again</li>
			</ul>
		</p>

	</article>

	<button id="formButton">Create a page</button>

	<form action="lib/store.php" method="POST">
		<fieldset>
			<legend>Create a page</legend>

			<p><input type="hidden" name="pageTitle" value="<?php echo $pageTitle; ?>"></p>	
			<input type="hidden" name="username" value="<?php echo $username; ?>" />
			<input type="hidden" name="isNew" value="y" />

			<p><textarea name="content" placeholder="Write something..." rows="10" cols="100"></textarea></p>
			<p><input type="submit" value="Submit the page" /></p>
		</fieldset>
	</form>
</section>
<!-- End of content section -->