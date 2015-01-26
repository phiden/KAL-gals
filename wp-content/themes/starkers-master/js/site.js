$(document).ready(function() {

	//hide mobile menu on desktop
	$("#mobile-nav-trigger").addClass('hidden');
	
	function initMobileMenu() {
		
		//display the needed UI
		$("#mobile-nav-trigger").removeClass('hidden');
		$("#mobile-nav").removeClass('hidden');
		
		//trigger mobile menu	
		$("#mobile-nav").mmenu();
	
		//hide the unneeded desktop UI
		$("#main-nav").addClass('hidden');
	}
	
	function resetDesktopMenu() {
		
		//hide mobile UI
		$("#mobile-nav-trigger").addClass('hidden');
		$("#mobile-nav").addClass('hidden');
		
		//display desktop UI
		$("#main-nav").removeClass('hidden');
		
	}
	
	if($(window).width() <= 568) {
		
		initMobileMenu();
	}
	
	
	// This will execute whenever the window is resized
	$(window).resize(function() {
		
		if($(window).width() <= 568) {
			
			initMobileMenu();
			
		} else {
		
			resetDesktopMenu();	
		
		}
	});
      
});