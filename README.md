# WikiVLE #

WikiVLE is a Virtual Learning Environment that I built for my Web Client & Server coursework in Winter/Spring 2012. To install 

## Installation ##

Prerequisites to installation:
XAMPP 1.7.7 installed on a suitable computer - running Apache 2.2, PHP 5.3 and MySQL 5.5

The files for creating and setting up the Virtual Learning Environment are entirely contained within the folder named “499908”. To install the files, simply copy the folder into the “htdocs” folder found within XAMPP. This will enable the system to run under localhost on the computer.

Unfortunately, however, XAMPP 1.7.7 is not configured to work with LDAP initially. To configure XAMPP follow the instructions below:
* Copy the file named "libsasl.dll" from the directory "xampp/php" into the "xampp/apache/bin" directory
* Open the "xampp/php/php.ini" file in a text editor, such as Notepad++ or TextMate
* Find (try using Ctrl-F) and uncomment the line: "extention=php_ldap.dll"
* To uncomment a line, remove the semi-colon - “;” - from the beginning of the line
* Save the file
* Restart XAMPP

An Admin log in account is created on installation. To log in with Admin rights (required for actions such as locking or deleting a page), log in using the username “admin”, and password “admin”.

WikiVLE has been tested on the following browsers:
Google Chrome 18, Firefox 11, Apple Safari 5.1 and Internet Explorer 9 with no errors
Internet Explorer 8 and 7, with only Javascript errors (AJAX updating and other functions don’t work)

WikiVLE should now be properly installed and set up on your server. ((I may add my User Manual to the wiki sometime))

## Stuff that might be broken ##

This has only been tested on a local machine, running XAMPP - I'm not entirely sure how well it'll work on a live server. 

NOTE: The LDAP functionality will **only** work when connected to the University of Portsmouth's network.

## Improvements ##

I'll be honest straight out - I don't think it's very good! But that's why I'm open-sourcing it, so you guys can help improve it.

There's several places that I think need improving:

 * It needs some serious abstraction - for example, there's SQL statements in the PHP that needs to be abstracted away
 ** I've been working with FuelPHP lately, so I might try a MVC approach
 * Some proper optimisation - sometimes there's 3 SQL requests on one page!
 * The home page is pretty basic - I was aiming for something like Wikipedia's front page
 * Asynchronous password lookup
 * Facebook/Twitter login
 * Expand the user account system, so that user's data is recorded
 * Hook into the Twitter API for some search-y goodness
