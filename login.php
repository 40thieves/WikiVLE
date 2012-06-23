<?php

/*

Login page

Provides forms to allow the user to login with LDAP or the built-in account system

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

	<h1 id="pageTitle">Log in</h1>

	<!-- Start of ldap subsection -->
	<h2 class="subsectionheader">Log in with LDAP</h2>
	<article>
		
		<p>Enter your University computer number to log into this account.</p>

		<form action="common/login/ldapLogin.php" method="POST" name="ldap">
			<fieldset>
				<p>University computer number: <input type="text" name="username" placeholder="e.g. cam01329" /></p>
				<p>Password: <input type="password" name="password" placeholder="Enter your password" /></p>
				<p><input type="submit" value="Login with LDAP" /></p>
			</fieldset>
		</form>

	</article>
	<!-- End of ldap subsection -->

	<!-- Start of built-in subsection -->
	<h2 class="subsectionheader">Login in to your account</h2>
	<article>
		
		<p>Enter your username and password to log into your account.</p>

		<form action="common/login/login.php" method="POST" name="login">
			<fieldset>
				<p>Username: <input type="text" name="username" placeholder="Enter your username" /></p>
				<p>Password: <input type="password" name="password" placeholder="Enter your password" /></p>
				<p><input type="submit" value="Log in" /></p>
			</fieldset>
		</form>
	
	</article>
	<!-- End of built-in subsection -->

</section>
<!-- End of content section -->

<?php

// Include to pull footer from the footer file
include 'common/footer.php';

?>