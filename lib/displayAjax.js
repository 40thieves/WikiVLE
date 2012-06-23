/*

AJAX for refreshing and displaying the main content section
Pulls the updated content from the API every 10 seconds, and injects into the Notes section of the display page

*/

/*

constructHTML function

Constructs a HTML fragment with the relevant tags from the data returned by the API

*/
var constructHTML = function (content) {

	// Constructs the main content section within paragraph tags
	htmlFragment = "<p>" + content.content + "</p>";
	// Constructs the meta section within a div
	htmlFragment += "<div class='meta'>";
	htmlFragment += "<p>Page modified at " + content.dateTimeModified + " by " + content.username + "</p>";
	htmlFragment += "</div>";

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
	target = document.getElementById('notes');

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
				html = constructHTML(obj[0]);

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
	url = "api/read/?title=" + urlPageTitle;

	// Open the XHR request - with the GET method, the URL, and the asynchronous flag set
	xhr.open("GET", url, true);
	// Assign the changeListener function to fire whenever the readyState changes
	xhr.onreadystatechange = changeListener;
	// Send the XHR request
	xhr.send();

};

// Create a timeout to call the fetch function every 10 seconds
setInterval(fetch, 10000);