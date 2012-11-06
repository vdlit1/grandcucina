/*******************************************************************************

	CSS on Sails Framework
	Title: GrandCucina.com
	Author: XHTMLized (http://www.xhtmlized.com/)
	Date: May 2012

*******************************************************************************/

var Site = {

	/**
	 * Init Function
	 */
	init: function() {
		Site.Forms();
	},
		
	Forms: function(){
		//jQuery('input[placeholder], textarea[placeholder]').JSizedFormPlaceholder();
		//jQuery('input[type="checkbox"]').JSizedFormCheckbox({copyClassName: true});
		jQuery('select').JSizedFormSelect({altTextAttribute: 'data-display', maxItems:5, maxItemsUseMaxHeight: false});
		//jQuery('input[type="radio"]').JSizedFormRadio({copyClassName: true});
	}
}

jQuery(document).ready(function() {
	Site.init();
});


