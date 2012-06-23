<?php

/*

Error page
Displays an error message if an unknown action is requested in the GET request

*/

?>

<!-- Start of content section -->
<section class='content'>

<?php

	// Include to pull sidebar from file
	include 'common/sidebar.php';

?>

	<h1 id='pageTitle'>No such action</h1>
	<article>
		<p>WikiVLE doesn't recognise the action specified by the URL.</p>
		<p>Return to the <a href='./'>Home Page</a></p>
	</article>
</section>

<!-- End of content section -->