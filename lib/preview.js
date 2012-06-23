/*

AJAX for displaying a preview of the changes made on an Edit page
Sends the changed data to the preview.php script, which returns a JSON encoded object with the content to be previewed
This preview content is then injected into the preview div

*/

/*

constructHTML function

Constructs a HTML fragment with the relevant tags from the data returned by the API

*/
var constructHTML = function (content) {

	htmlFragment =	"<div id='previewHeader'>";
	htmlFragment += "<h2 class='subsectionheader'>Preview</h2>";
	htmlFragment +=	"<p>Remember that this is only a preview - your changes have not been saved! <a href='#editForm'> → Continue editing</a></p>";
	htmlFragment += "</div>";
	htmlFragment += content;

	return htmlFragment;

}

/*

fetchPreview function

Sends data to the preview.php script, and fetches returned the JSON object 
If successful the JSON object is decoded and injected it into the relevant section of the page
If unsuccessful, an error message is displayed

*/
var fetchPreview = function (evt) {

	// Prevents the Edit form from being submitted
	evt.preventDefault();

	// Initialises variables
	var xhr, target, changeListener, url, data;

	// Gets target element from the DOM
	target = document.getElementById('preview');
	// Removes the style attribute ("display: none") from the target element - thereby showing it
	target.removeAttribute('style');

	// Gets the content data from the form
	data = document.forms['edit'].elements['content'].value;
	// Appends "&content=" so it is formatted for the XHR request
	data = "&content=" + data;

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
				html = constructHTML(obj['content']);

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

	// Sets the URL for the XHR request
	url = 'lib/preview.php';

	// Open the XHR request - with the POST method, the URL, and the asynchronous flag set
	xhr.open('POST', url, true);
	// Assign the changeListener function to fire whenever the readyState changes
	xhr.onreadystatechange = changeListener;
	// Sets the request header to show that the request comes from a form
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	// Send the XHR request, with the data from the form
	xhr.send(data);

};