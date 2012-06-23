<?php

/*

Utilities for creation and setup of the database

*/


/*

connectAndSelectDB function

Attempts to connect to the DB server, then select the relevant database

*/
function connectAndSelectDB() {
	
	// Gets the DB config
	global $dbName, $dbServer, $dbUser, $dbPassword;

	// Connects to the DB server
	mysql_connect($dbServer, $dbUser, $dbPassword);

	// Selects the relevant DB
	if (! mysql_select_db($dbName)) {
		if (mysql_errno() == 1049) {

			// Selection failed because the DB does not exist

			// Calls the createDB function
			createDB();

		}
		else {

			// Selection failed because of other error
			echo "Could not select DB";
		}
	}
}

/*

createDB function

Creates the database and initialises the tables

*/
function createDB() {

	// Gets the DB config
	global $dbName, $dbServer, $dbUser, $dbPassword;

	// Creates the database
	$query = "CREATE DATABASE $dbName";
	mysql_query($query);

	// Selects the newly created database
	mysql_select_db($dbName);

	// Creates the table Edits
	$query = "CREATE TABLE Edits (	id bigint not null auto_increment,
									pageTitle VARCHAR(50),
									content MEDIUMTEXT,
									dateTimeModified DATETIME,
									username VARCHAR(50),
									PRIMARY KEY (id))";
	mysql_query($query);

	// Creates the table Pages
	$query = "CREATE TABLE Pages (	pageTitle VARCHAR(50) not null,
									lastEditId bigint not null,
									isLocked BOOLEAN DEFAULT 0,
									isFeatured BOOLEAN DEFAULT 0,
									PRIMARY KEY (pageTitle),
									CONSTRAINT FK_PAGE_EDIT FOREIGN KEY (lastEditId) REFERENCES Edits (id))";
	mysql_query($query);

	// Creates the table Users
	$query = "CREATE TABLE Users ( id bigint not null auto_increment,
								   username VARCHAR(50) not null,
								   password VARCHAR(150) not null,
								   isAdmin BOOLEAN DEFAULT 0,
								   PRIMARY KEY (id),
								   UNIQUE (username))";
	mysql_query($query);

	// Initialises an Admin user, by insertion into the Users table
	// Username: admin, Password: admin (which has been salted and hashed for security)
	$query = "INSERT INTO Users (username, password, isAdmin) VALUES ('admin', '69b607e2dc66641cb587adf24d673fda6aedda26f6e66ba4b6fed612a37d48493291e3d51cae3f23a747444a547704ccaf981b2c6bcce7d2aeb200ac9ab4e628', TRUE)";
	mysql_query($query);

}

?>