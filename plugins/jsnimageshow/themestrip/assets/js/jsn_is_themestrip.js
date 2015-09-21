/**
 * @version     $Id$
 * @package     JSN.ImageShow
 * @subpackage  JSN.ThemeCarousel
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 * 
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

(function($) {
	$.fn.jsnthemestrip = function(ID, options) {
		
		if (typeof options.slideshow_auto_play != 'undefined')
		{
			if (options.slideshow_auto_play == 'yes')
			{
				options.slideshow_auto_play = true;
			}
			else
			{
				options.slideshow_auto_play = false;
			}
		}
		else
		{
			options.slideshow_auto_play = false;
		}	
		
		if (typeof options.slideshow_delay_time != 'undefined')
		{
			options.slideshow_delay_time = options.slideshow_delay_time;	
		}
		else
		{
			options.slideshow_delay_time = 3000;
		}	
		
		this.elastislide({speed: options.slideshow_sliding_speed, orientation: options.image_orientation, minItems: options.slideshow_min_items, orientation: options.image_orientation, width: options.image_width, height: options.image_height, space: options.image_space, image_border: options.image_border, image_shadow: options.image_shadow, container_border: options.container_border, container_side_fade: options.container_side_fade, autoSlide: options.slideshow_auto_play, delayTime: options.slideshow_delay_time});
		if(options.image_click_action == 'show-original-image') {
			if (typeof jsnThemeCarouseljQuery == "function" || typeof jsnThemeFlowjQuery == "function")
			{
				jQuery(this).find('a').fancybox({
					'titlePosition'	: 'over',
					'titleFormat'	: function(title, currentArray, currentIndex, currentOpts) {
						return '<div class="jsn-themestrip-gallery-info-' + ID + '">' + title + '</div>';
					}
				});
			}	
			else
			{	
				jQuery(this).find('a').fancybox({
					'titlePosition'	: 'over',
					'titleFormat'	: function(title, currentArray, currentIndex, currentOpts) {
						return '<div class="jsn-themestrip-gallery-info-' + ID + '">' + title + '</div>';
					}
				});
			}
		}
	};
})(jsnThemeStripjQuery);
