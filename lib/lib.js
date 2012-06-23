/*

General libary of Javascript functions

*/

/*

toggle function

Fires when a user clicks on the "Create new page" button

Toggles the display style attribute of the Create New Page form - thereby showing or hiding the related form element

*/
function toggle() {

	// Gets the next element to the event's target element - i.e. the form element
	nextElem = this.nextElementSibling;

	// If the form element is hidden (using the style="display: none;" attribute)
	if (nextElem.style.display == "none") {
		
		// Shows the element - by removing the style from the attribute
		nextElem.setAttribute("style", "");
	
	}
	// If the form element is showing
	else {
	
		// Hides the element - by setting the style="display: none;" attribute
		nextElem.setAttribute("style", "display: none;");

	}
}

/*

validate function

Fires when a user submits a log in form

Checks whether a user has entered text into a input field
If not, and the submit button is clicked, then the field turns red

*/
function validate(evt) {
	
	// Initialises the variables to gets the username element (i.e. the DOM element) and value (i.e. any input text), and the password element and value
	var username = this.elements['username'].value,
		usernameElem = this.elements['username'],
		password = this.elements['password'].value,
		passwordElem = this.elements['password'];

	if (username == "" && password == "") {

		// Username and password are both blank
		
		// Prevents the form from being submitted
		event.preventDefault();
		
		// Sets the class attribute so that the colour of the border will change to red
		usernameElem.setAttribute('class', 'invalid');
		passwordElem.setAttribute('class', 'invalid');

	}
	else if (username == "") {

		// Username is blank

		// Prevents the form from being submitted
		event.preventDefault();
		
		// Sets the class atribute so that the colour of the border will change to red
		usernameElem.setAttribute('class', 'invalid');

	}
	else if (password == "") {

		// Password is blank

		// Prevents the form from being submitted
		event.preventDefault();

		// Sets the class attribute so that colour of the border will change to red
		passwordElem.setAttribute('class', 'invalid');

	}		

}

/*

searchFocus function

Fires when a user presses any key

Checks if the key combination is Ctrl-Shift-F
If so, then the search box (in the header) is brought into focus

*/
var searchFocus = function(e) {

	// Checks if the key pressed is Ctrl-Shift-F
	if (e.keyCode == 6) {

		// Gets search box from the DOM
		var search = document.forms['headerSearch'].elements['search'];

		// Puts search box in focus
		search.focus();

	}

}

/*

initialise function

Fires when the window has finished loading

Initialises the page as soon as it has finished loading
Attempts to get elements on the page
If they exist (they are not found on every page), eventListeners are added

*/
var initialise = function() {

	// Initialises the variable
	var form, login, ldap, register, searchShortcut, previewButton;
	
	// Adds eventListeners if the corresponding elements exist

	// Gets the "Create new page" button from the DOM
	form = document.getElementById("formButton");
	if (form) {

		// The button exists

		// The "Create new page" form is hidden - shown by default because the page is progressively enhanced
		form.nextElementSibling.setAttribute('style', 'display: none;');

		// Adds click eventListener to the button that will fire the toggle function
		form.addEventListener("click", toggle, false);
	}

	// Gets the LDAP login form from the DOM
	ldap = document.forms['ldap'];
	if (ldap) {

		// The form exists

		// Attaches submit eventListener to the form that will fire the validate function
		ldap.addEventListener('submit', validate, false);
	}

	// Gets the regular (i.e. non-LDAP) login form from the DOM
	login = document.forms['login'];
	if (login) {

		// The form exists

		// Attaches submit eventListener to the form that will fire the validate function
		login.addEventListener('submit', validate, false);
	}

	// Gets the register form from the DOM
	register = document.forms['register'];
	if (register) {

		// The form exists

		// Attaches submit eventListener to the form that will fire the validate function
		register.addEventListener('submit', validate, false);
	}

	// Attaches a keypress eventListener to the window that will fire the searchFocus function
	searchShortcut = window.addEventListener("keypress", searchFocus, false);

	// Gets the preview button from the DOM
	previewButton = document.getElementById('previewButton');
	if (previewButton) {

		// The button exists

		// Removes the style attribute (which is set to "display: none;", thereby showing it
		// Button is hidden by default - the page is progressively enhanced
		previewButton.removeAttribute('style');

		// Attaches a click eventListener to the button that will fire the fetchPreview function (found in preview.js)
		previewButton.addEventListener('click', fetchPreview, false);
	}


	/*

	jQuery click events to toggle cheatsheet and sidebar menus

	*/

	// Markdown Cheatsheet fade in/out
	(function() {

		// Gets the Cheatsheet div from the DOM, using jQuery's selectors
		// The div is then hidden - it is shown by default due to progressive enhancement
		cheatsheet = $('#cheatsheet').hide();
		button = $('#cheatsheetButton').show();

		// Attaches click event listener to the Cheatsheet button
		button.on('click', function() {
			
			// Button has been clicked

			// jQuery increases/decreases the element's opacity over a period of 200 miliseconds
			// Produces a fade in/fade out effect
			cheatsheet.fadeToggle(200);

		});
	})(); // Anonymous self-invoking function - will invoke itself upon load

	// Sidebar slide up/down
	(function() {

		// Gets the Tools div from the DOM, using jQuery's selectors
		$('#tools').on('click', function() {
		
			// Caches the h4 element within the div - the "Tools" menu title
			menuTitle = $(this).children('h4');

			if (menuTitle.attr('class') == "open") {

				// The menu title is open
		
				// The open class is removed - thereby changing the small arrow to closed
				menuTitle.removeClass("open");
		
			}
			else {
		
				// The menu title is closed

				// The open class is added - thereby changing the small arrow to open
				menuTitle.addClass("open");
		
			}
		
			// Gets the dropdown part of the menu from the DOM, using jQuery's selectors
			// Then jQuery increases/decreases the height of the element over a period of 200 miliseconds
			// Producing a slide up/down effect
			$('.sidebarMenu').slideToggle(200);
		
		});
	})(); // Anonymous self-invoking function - will invoke itself upon load

}

// Attaches a load eventListener to the window that will fire the initialise function
// Will initialise the page when it has finished loading
window.addEventListener('load', initialise, false);