/*
	The Breakpoint Controller is useful for triggering events once at a set viewport width.
	Rather than trigging events on window resize which can be unweildy, the Breakpoint 
	Controller checks the window during resize and only fires the event at the specified 
	viewport width.  The size variables listed at the top of the function should match your
	specified breakpoints.

	Use Case:
	You have a hamburger nav on SMALL viewports that uses slideToggle to show and hide 
	the navigation but displays normally on LARGE viewports.
	It's possible that the user might be viewing the page on a desktop browser with
	the window sized down if the user closes the nav and resizes the browser to a
	width that SHOULD display the nav normally.  SlideToggle's inline display:none 
	overrides the stylesheets media query that should be display:block (or something 
	similar).

	In our javascript a simple function would take care of this.
	$(BreakpointController).on('crossBreakpoint', onCrossBreakpoint);

	Then we have a function that controls everything that should happen onCrossBreakpoint

	function onCrossBreakpoint() {
		if ($window.width() >= BreakpointController.LARGE) {
			$('.navigtaion').attr('style', "");  
		}
	}


*/
BreakpointController = (function($) {
	var ret = {},
		SMALL = 600,
		MEDIUM = 760,
		LARGE = 1000,
		X_LARGE = 1280,
		XX_LARGE = 1400,
		win,
		currentBreakpoint,
		breakpoints = [
			{ label: "small", width: SMALL },
			{ label: "medium", width: MEDIUM },
			{ label: "large", width: LARGE },
			{ label: "x-large", width: X_LARGE },
			{ label: "xx-large", width: XX_LARGE }
		];

	function onDocumentReady(){
		win = $(window);
		win.on('resize', checkWindow);
		checkWindow();
	}

	function checkWindow(){
		var w = win.width(),
			ret;

		for(var i=0; i<breakpoints.length; i++)
		{
			breakpoint = breakpoints[i];
			if(w >= breakpoint.width)
			{
				ret = breakpoint.width;
			}
			else
			{
				break;
			}
		}

		setBreakpoint(ret);
	}

	function setBreakpoint(breakpoint){
		if(breakpoint !== currentBreakpoint)
		{
			currentBreakpoint = breakpoint;
			$(BreakpointController).trigger('crossBreakpoint');
		}
		else
		{
			currentBreakpoint = breakpoint;
		}
	}

	ret = {
		getBreakpoint: function(){
			return currentBreakpoint;
		},
		SMALL: SMALL,
		MEDIUM: MEDIUM,
		LARGE: LARGE,
		X_LARGE: X_LARGE,
		XX_LARGE: XX_LARGE
	};

	$(onDocumentReady);

	return ret;
})(jQuery);