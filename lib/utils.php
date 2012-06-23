<?php

/*

General library of PHP functions

*/

/*

regexTrim function

Uses a regular expression to remove/replace characters from a string, and trim the string to a given length
Takes a regular expression, the replacement text (use an empty string to remove characters), the original string, and the desired length of the string as input
Returns the string with the matched characters replaced and trimmed to the correct length

*/
function regexTrim($regex, $replacement, $content, $length) {

	// Replaces the characters that match the regular expression in the content string with the replacement
	$content = preg_replace($regex, $replacement, $content);

	// Trims the content string to the length specified
	$content = substr($content, 0, $length);
	
	// Returns the transformed string
	return $content;

}

/*

extractSanitiseVar function

Pulls the named variables from the superglobal variables, and passes them through sanitation to ensure that no SQL injection attacks can take place. 
Takes the name of the variable, and it's default value if not set as input
Output is the extracted and sanitised variable

*/
function extractSanitiseVar($var, $defaultValue) {

	if (isset($_REQUEST[$var])) {

		// The named variable is set
		
		// It is passed through mysql_real_escape_string() which sanitises strings for use with MySQL
		$return = mysql_real_escape_string($_REQUEST[$var]);

		// Returns the extracted and sanitised variable
		return $return;
	
	}
	// The named variable is not set

	// The default value is returned
	return $defaultValue;

}

/*

cheatsheet function

Returns the HTML for the Markdown Cheatsheet, shown on the Edit page

*/
function cheatsheet() {

?>

	<p id="cheatsheetButton" style="display: none;">Markdown cheatsheet</p>
	<!-- Start of cheatsheet subsection -->
	<div id="cheatsheet">

		<h1>Markdown Cheatsheet</h1>

		<h2>Emphasis</h2>
		<pre><code>*italic*   **bold**
_italic_   __bold__
</code></pre>

		<h2>Links</h2>		
		<code>
			An [example](http://url.com/ "Title")
		</code>

		<h2>Headers</h2>
		<pre><code>Header 1
========
Header 2
--------
</code></pre>

		<pre><code># Header 1 #
## Header 2 ##
###### Header 6
</code></pre>

		<h2>Lists</h2>
		<pre><code>1.  Foo
2.  Bar
</code></pre>

	<pre><code>*   An unordered list item.

    With multiple paragraphs.

*   Bar
</code></pre>

	<h2>Code blocks</h2>
	<pre><code>~~~
if ($allOurBase) ?
echo "Are belong to you";
~~~
</code></pre>

	<h2>Full documentation</h2>
	<p>Available <a href="http://daringfireball.net/projects/markdown/syntax">here</a> and <a href="http://michelf.com/projects/php-markdown/extra/">here</a></p>

	</div>
	<!-- End of cheatsheet subsection -->

<?php

}

?>