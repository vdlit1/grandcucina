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
		Site.ProductGallery();
	},
	ProductGallery: function(){
		$productgallery = {
			context: false,
			tabs: false,
			timeout: 5000,		// time before next slide appears (in ms)
			slideSpeed: 1000,	// time it takes to slide in each slide (in ms)
			tabSpeed: 500,		// time it takes to slide in each slide (in ms) when clicking through tabs
			pause:	1,
			fx: 'fade',	// the slide effect to use
				init: function() {
					this.context = jQuery('#product-gallery');
					this.tabs = jQuery('ul.product-gallery-nav li', this.context);
					this.tabs.remove();
					this.prepareSlideshow();
				},
				prepareSlideshow: function(evt) {
					jQuery('.product-gallery-slides > ul', $productgallery.context).cycle({
						timeout: $productgallery.timeout,
						speed: $productgallery.slideSpeed,
						pager: jQuery('ul.product-gallery-nav', $productgallery.context),
						pagerAnchorBuilder: $productgallery.prepareTabs,
						before: $productgallery.activateTab,
						pauseOnPagerHover: true,
						pause: true
					}); 
				},
				prepareTabs: function(i, slide) {
					return $productgallery.tabs.eq(i);
				},
				activateTab: function(currentSlide, nextSlide) {
					var activeTab = jQuery('a[href="#' + nextSlide.id + '"]', $productgallery.context);
					if(activeTab.length) {
						$productgallery.tabs.removeClass('over');
						activeTab.parent().addClass('over');
					}
				}
			};			
		$productgallery.init();
			jQuery("a[rel^='prettyPhoto']").prettyPhoto();
	}
}


jQuery(document).ready(function() {
	Site.init();
});


