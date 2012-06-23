<?php

/*

Search page

Provides the user with a search functionality
Takes the search query from the GET request (although this is optional - if no query provided, a blank search box is provided)
This is used to query the DB either to find an exact match, or to find some text within a page that matches
A list of matches is then displayed

*/

// Includes for DB setup and extractSanitiseVar function
include 'lib/config.php';
include 'lib/db.php';
include 'lib/utils.php';

// Include to pull the header (including <head> section) from the header file
include 'common/header.php';

// Connects and selects the DB
connectAndSelectDB();

if (isset($_GET['search'])) {

	// A search query is provided in the GET request

	// The query is extracted and sanitised
	$search = extractSanitiseVar('search', '');

	// DB query - searches for exact match to a page title
	$query = "SELECT Pages.pageTitle, content FROM Pages JOIN Edits ON Pages.lastEditId = Edits.id WHERE Pages.pageTitle='$search'";
	$rows = mysql_query($query);

	if (mysql_num_rows($rows) == 1) {

		// Page with exact title match found

		$line = mysql_fetch_assoc($rows);
		$pageTitle = $line['pageTitle'];

		// User is redirected to the page matching the search
		header("Location: ./index.php?title=$pageTitle");

	}
	else {

		// No page with exact title match found
		// User provided with a search page of possible matches

		// DB query - searches for non-exact matches to a page title or within page content
		$query = "SELECT Pages.pageTitle, content FROM Pages JOIN Edits ON Pages.lastEditId = Edits.id WHERE Pages.pageTitle LIKE '%$search%' OR content LIKE '%$search%'";
		$rows = mysql_query($query);

?>

		<!-- Start of content section -->
		<section class="content">

<?php

			// Include to pull sidebar from file
			include 'common/sidebar.php';

?>

			<h1 id="pageTitle">Search results</h1>

			<!-- Start of search form subsection -->
			<article>
				<form action="search.php" method="GET">
					<div class="search">
						<input type="search" name="search" placeholder="Search" />
						<button class="searchButton" title="Search WikiVLE">
							<img src="./img/searchIcon.png" alt="Search">
						</button>
					</div>
				</form>
			</article>
			<!-- End of search form subsection -->

			<!-- Start of search results subsection -->

<?php

		if (mysql_num_rows($rows) != 0) {

			// There are matches in the search results

			while ($line = mysql_fetch_assoc($rows)) {

				// For each line in the returned DB result

				// Assigning variables from the returned DB query
				$pageTitle = $line['pageTitle'];
				$content = $line['content'];

				// Removes Markdown syntax with a regular expression
				$regex = "/[*#\[\]_>`{}]|[~=-]{3}/";
				$content = preg_replace($regex, "", $content);
				// If page content is lognger than 150 characters, it is trimmed to 150 characters
				(strlen($content) > 150) ? $content = substr($content, 0, 150) . "..." : $content;

				// Replaces matched search query with bold tags around it
				$regex = "/$search/i";
				$replacement = "<strong>$0</strong>";
				$content = preg_replace($regex, $replacement, $content, -1, $count);

?>

				<?php // Link to page and sample content ?>
				<a href="./index.php?title=<?php echo $pageTitle; ?>"><?php echo $pageTitle; ?></a>
				<p><?php echo $content; ?></p>

				<!-- End of section result subsection -->

<?php

			}

		}
		else {

			// No matches in the search results
			// Display message to create new page with the search query's title, or to try searching again

?>

			<p>Your search - <strong><?php echo $search; ?></strong> - did not match any pages.</p>
			<p>Click <a href="./index.php?title=<?php echo $search; ?>">here</a> to create a new page.</p>
			<p>Suggestions:
				<ul>
					<li>Make sure all words are spelled correctly.</li>
					<li>Try different search terms</li>
				</ul>
			</p>

<?php

		}
	}
}
else {

	// No search set in GET request - show search box without results

?>

	<!-- Start of content section -->
	<section class="content">

<?php

		include 'common/sidebar.php';

?>

		<h1 id="pageTitle">Search</h1>

		<!-- Start of search form subsection -->
		<article>
				<form action="search.php" method="GET">
					<div class="search">
						<input type="search" name="search" placeholder="Search" />
						<button class="searchButton" title="Search WikiVLE">
							<img src="./img/searchIcon.png" alt="Search">
						</button>
					</div>
				</form>
			</article>
		<!-- End of search form subsection -->

<?php

}

?>

	</section>
	<!-- End of content section -->

<?php

// Include to pull footer from the footer file
include 'common/footer.php';

?>