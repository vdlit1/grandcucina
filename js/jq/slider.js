jQuery(document).ready(function($) {    
 $slideshow = {
			context: false,
			tabs: false,
			timeout: 3000,		// time before next slide appears (in ms)
			slideSpeed: 1000,	// time it takes to slide in each slide (in ms)
			tabSpeed: 500,		// time it takes to slide in each slide (in ms) when clicking through tabs
			pause:   1,
			fx: 'fade',		// the slide effect to use
				init: function() {
					this.context = $('#slideshow');
					this.tabs = $('ul.slides-nav li', this.context);
					this.tabs.remove();
					this.prepareSlideshow();
				},
				prepareSlideshow: function(e) {
					$('div.slides > ul', $slideshow.context).cycle({
						fx: $slideshow.fx,
						timeout: $slideshow.timeout,
						speed: $slideshow.slideSpeed,
						fastOnEvent: $slideshow.tabSpeed,
						pager: $('ul.slides-nav', $slideshow.context),
						pagerAnchorBuilder: $slideshow.prepareTabs,
						before: $slideshow.activateTab,
						pauseOnPagerHover: true,
						pause: true
					});
				},
				prepareTabs: function(i, slide) {
					return $slideshow.tabs.eq(i);
				},
				activateTab: function(currentSlide, nextSlide) {
					var activeTab = $('a[href="#' + nextSlide.id + '"]', $slideshow.context);
					if(activeTab.length) {
						$slideshow.tabs.removeClass('on');
						activeTab.parent().addClass('on');
					}
				}
			};
		$slideshow.init();
     });