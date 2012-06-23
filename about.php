<?php

/*

The About page

Briefly explains the concept behind WikiVLE, how it works and about Markdown

*/

// Include to pull the header (including <head> section) from the header file
include 'common/header.php';

?>

<!-- Start of content section -->
<section class="content">

<?php

	// Include to pull sidebar from file
    include 'common/sidebar.php';

?>

	<h1 id="pageTitle">About</h1>

	<article>
		<p>WikiVLE is a Virtual Learning Environment based around the concept of a wiki.</p>

		<p>A wiki is a website where users can collaboratively edit articles to share knowledge. Wikipedia (currently the largest wiki) has a longer article describing wikis <a href="http://en.wikipedia.org/wiki/Wiki">here</a>. The word, <em>wiki</em>, is originally a Hawaiian word meaning "fast" or "quick". Combining this with a virtual learning environment, users can work together to create articles as they learn.</p>
	</article>

	<h2 class="subsectionheader">Using WikiVLE</h2>
	<article>
		<p>New pages on WikiVLE can be created through the search page. Perform a search for the page title that you wish to create, then click the "create new page" button. Enter the initial text for the page, and hit the submit button. <a href="#markdown">Markdown syntax</a> can be used to create nicely formatted text. Now anybody can edit the page to include their information, and to upload relevant files such as lecture slides or images. You can also view the full history of every edit made to an article, and revert back to a previous version. This allows users to control vandalism.</p>

		<p>Tools to control pages are available on each page in the Tools section of the sidebar.</p>
	</article>

	<h2 class="subsectionheader" id="admin">Admin Tools</h2>
	<article>
		<p>Users who are logged in as Admin have special privileges. Using the Admin page, they can change the look and feel of the site using a CSS file, lock notes so that they cannot be edited further, and choose a page to be featured on the Home page.</p>
		</article>

	<h2 class="subsectionheader" id="markdown">Markdown</h2>
	<article>
		<p>Markdown is a text-to-HTML conversion tool, that allows writers to quickly and easily write text that can be nicely formatted for the web. Created by John Gruber and Aaron Swartz, and modified by Michel Fortin, it provides an alternative syntax that is easier to read and write and avoids the extra effort and technical knowledge of HTML. A "cheatsheet" is provided on the Edit page to help users write with Markdown. Full documentation of the syntax is available on Gruber's <a href="http://daringfireball.net/projects/markdown/syntax">website</a>, with additional documentation on Fortin's <a href="http://michelf.com/projects/php-markdown/extra/">website</a>.</p>
	</article>

</section>
<!-- End of content section -->

<?php

	// Include to pull footer from the footer file
    include 'common/footer.php';

?>
