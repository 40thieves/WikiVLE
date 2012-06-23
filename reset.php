<?php

/*

Reset utility

Utility to delete the entire database, so that the system can start again

*/

// Includes for DB setup
include 'lib/db.php';
include 'lib/config.php';

// Connects and selects the DB
connectAndSelectDB();

// Query to delete the database entirely
$query = "DROP DATABASE $dbName";
mysql_query($query);

// Closes the DB server connection
mysql_close();

echo "<p>DB Killed.  Let's learn something!</p>";

?>