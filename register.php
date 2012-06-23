<?php

/*

Login page

Provides forms to allow the user to create a new account with the built-in account system

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
	
	<h1 id="pageTitle">Register an account</h1>

	<form action="common/login/register.php" method="POST" name="register">
		<fieldset>
			<p>Username: <input type="text" name="username" placeholder="Enter your username" /></p>
			<p>Password: <input type="password" name="password" placeholder="Enter your password" /></p>
			<p><input type="submit" value="Register this account" /></p>
		</fieldset>
	</form>

</section>
<!-- End of content section -->

<?php

include 'common/footer.php';

?>