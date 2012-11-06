jQuery.noConflict();
jQuery(document).ready(function() {
    jQuery(".fancybox").fancybox({
        helpers: {
    	    overlay : {
	            speedIn  : 0,
	            speedOut : 0,
	            opacity  : 0.8,
	            css      : {
		            cursor : 'pointer'
	            },
	            closeClick: true
            }
        }
    });
});
