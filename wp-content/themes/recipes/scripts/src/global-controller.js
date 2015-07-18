/* 
	If you're using Grunt, you should consider using the modular design pattern.
	Grunt is configured to concatenate all of the .js files saved in scripts/src
	to global.js.  This is entirely up to you and based on your comfort with 
	javascript.  This file is a boilerplate, duplicate as needed.

	The idea is to use abstraction to separate concerns.  a navController maybe.
	another called sliderController.  navController contains all of the functionality needed
	to power your site's navigation.  sliderController controls all of the functionality related to your
	slideshows.
	
	Read More about the modular pattern: http://addyosmani.com/largescalejavascript/#modpattern

	NOTE: 	in most websites, simple jQuery is sufficient (this pattern is advanced JavaScript).
			I used it on ALPCO (a very large site) and thought you'd find it useful.

	WARNING:  Saving this file while Grunt is watching will overwrite your global.js
	Either start a new project using this system, or delete the scripts/src folder.

	Love, 
		Sean
*/

globalController = (function($) {
	var ret = {}, win, doc;

	function onDocumentReady() {
		win = $(window);
		doc = $(document);

		$(BreakpointController).on("crossBreakpoint", onCrossBreakpoint);
	}

	function onCrossBreakpoint() {

	}

	ret = {
		// functions you want to be reached by other controllers,
		// there's a good example in the breakpoint controller if you need it
	};

	$(onDocumentReady);

	return ret;
})(jQuery);