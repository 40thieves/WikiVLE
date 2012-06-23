/*

AJAX for refreshing and displaying the history section
Pulls the updated content from the API every 10 seconds, and injects into the table on the History page

*/

/*

constructHTML function

Constructs a HTML fragment with the relevant tags from the data returned by the API

*/
var constructHTML = function (obj) {

	// Creates the table and table headings
	htmlFragment = "<table>";
	htmlFragment += "<tr>";
	htmlFragment += "<th>Time of modification</th>";
	htmlFragment += "<th>Sample content</th>";
	htmlFragment += "<th>Edited by</th>";
	htmlFragment += "</tr>";

	// For each element in the array
	obj.forEach(function(value, index) {
		
		// Create a new row
		htmlFragment += "<tr>";

		// Remove HTML tags (which are returned from the API) from the content
		value.content = value.content.replace(/(<([^>]+)>)/ig,"");
		// Remove any Markdown syntax from the content
		value.content = value.content.replace(/[*#\[\]_`]|[~=-]{3}/g, "");
		// Cut the content down to 110 characters
		value.content = value.content.substring(0, 110);

		// Constructs the HTML to wrap the values 
		htmlFragment += "<td>" + value.dateTimeModified + "</td>";
		htmlFragment += "<td>" + value.content + "</td>";
		htmlFragment += "<td>" + value.username + "</td>";

		// Create a revert button for the row (unless it is the first row - i.e. the lastest edit, and as such cannot be reverted to)
		if (index > 0) {
			htmlFragment += "<td>";
			htmlFragment += "<form action='lib/revert.php' method='POST'>";
			htmlFragment += "<button name='id' value='" + value.id + "'>Revert</button>";
			htmlFragment += "<input type='hidden' name='pageTitle' value='" + value.id + "' />";
			htmlFragment += "</form>";
			htmlFragment += "</td>";
		}

		// Close the row
		htmlFragment += "</tr>";


	});

	// Close the table
	htmlFragment += "</table>";

	return htmlFragment;

}

/*

fetch function

Attempts to fetch the JSON object from the Read API 
If successful the JSON object is decoded and injected it into the relevant section of the page
If unsuccessful, an error message is displayed

*/
var fetch = function () {

	// Initialises variables
	var xhr, target, changeListener, url;

	// Gets target element from the DOM
	target = document.getElementById('historyTarget');
	
	// Creates new XHR object
	xhr = new XMLHttpRequest();

	// changeListener function - Fires every time the XHR object has a readyStateChange event
	changeListener = function () {

		if (xhr.readyState === 4) {

			// readyState is done

			if (xhr.status === 200) {

				// The request was successful - returned with a 200 OK

				// The returned JSON object is parsed
				obj = JSON.parse(xhr.responseText);
				
				// The returned object is passed to the constructHTML function
				html = constructHTML(obj);
				
				// The constructed HTML fragment is injected into the target element
				target.innerHTML = html
			
			}
			else {
			
				// The request was unsuccessful

				target.innerHTML = "<p>Something went wrong.</p>";
			
			}
		}
		else {
			
			// Display a placeholder loading image while the request is being processed
			// This is replaced when the request has returned

			target.innerHTML = "<p><img src='img/working.gif' /></p>";
		
		}
	};

	// Ugly hack to retrieve the page title from the HTTP GET request

	// Gets the full URL after the "?", then slices the "?" off
	// Then split on "&" to get each variable within the GET request into an array
	var urlArray = window.location.search.slice(1).split("&");

	urlArray.forEach(function(element, index) {
	
		// For each variable within the array
		
		// Split on "=" to get the name and value of the variable
		g = element.split("=");
	
		// If the name of the variable is title
		if (g[0] == "title") {
			
			// Set urlPageTitle to the value of the variable
			urlPageTitle = g[1];
	
		}
	});

	// Sets the URL for the XHR request
	url = "api/read/history.php?title=" + urlPageTitle;

	// Open the XHR request - with the GET method, the URL, and the asynchronous flag set
	xhr.open("GET", url, true);
	// Assign the changeListener function to fire whenever the readyState changes
	xhr.onreadystatechange = changeListener;
	// Send the XHR request
	xhr.send();

};

// Create a timeout to call the fetch function every 10 seconds
setInterval(fetch, 10000);