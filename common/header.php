<?php

/*

Header common to all pages within the site
Contains the <head> section, with DOCTYPE, favicon, title, Javascript files and stylesheets
Also contains the header with logo, log in and search

*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link href="./img/favicon.png" rel="icon" />

<?php

	// Title set, based on title and action variables set in the GET request. 
	$title = "";
	
	if (isset($_GET['title'])) {

		$title = $_GET['title'];

		if (isset($_GET['action'])) {

			$action = $_GET['action'];

			if ($action == "edit") {
				$title = "Editing $title";
			}
			else if ($action == "history") {
				$title = "Revision history of $title";
			}
			else if ($action == "upload") {
				$title = "Upload files for $title";
			}
			else if ($action == "delete") {
				$title = "Delete $title?";
			}
			else if ($action == "cite") {
				$title = "Citing $title";
			}

		}

		$title .= " - ";

	}

?>

	<title><?php echo $title; ?>WikiVLE</title>

	<?php // Invoking the Javascript files required ?>
	<script src="lib/lib.js"></script>
	<?php // Invokes jQuery from Google's CDN ?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<?php // HTML5 shiv to improve compatibility for IE 8, 7 and 6 ?>
	<!--[if lt IE 9]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<?php

		// Include to retrieve stylesheet variable from config file (stylesheet can be changed by Admin user)
		include "lib/config.php";
		global $stylesheet;
	?>

	<link href="<?php echo $stylesheet; ?>" rel="stylesheet" type="text/css" />
	
	
</head>

<body>
	<!-- Start of header section -->
	<header>
		<?php // Logo pulled from img directory ?>
		<a href="./"><img src="./img/logo.png" id="logo" alt="Logo" /></a>

		<div id="right">
			<!-- Start of login subsection -->
			<div id="login">
				<ul>

<?php

					if (isset ($_COOKIE['username'])) {
						// If cookie is set, user is logged in - show username and logout link

?>

					<li>
						<span>Hello <?php echo $_COOKIE['username']; ?>!</span>
					</li>
					<li>
						<a href="common/login/logout.php">Logout</a>
					</li>

<?php
					}
					else {
						// Cookie is not set - show log in and register links

?>

					<li>
						<a href="login.php">Log in</a>
					</li>
					<li>
						<a href="register.php">Register</a>
					</li>

<?php

					}

?>

				</ul>
			</div>
			<!-- End of login subsection -->

			<!-- Start of search subsection -->
			<form action="search.php" method="GET" name="headerSearch">
				<div class="search">
					<input type="search" name="search" placeholder="Search" title="Search WikiVLE (Ctrl-Shift-F)" />
					<button class="searchButton" title="Search WikiVLE">
						<img src="./img/searchIcon.png" alt="Search">
					</button>
				</div>
			</form>
			<!-- End of search subsection -->
		</div>
	</header>
	<!-- End of header section -->