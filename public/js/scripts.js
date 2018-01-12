var hoverOutTimer = null;
 
jQuery(document).ready(function($) { 
	'use strict';
	$(".menulink a").click(function() { 
	  	jQuery("#menu").stop(true, true).slideToggle('fast');
	});
	resize_intro(); 
	jQuery(".hero").stop(true, true).fadeIn('fast');
	jQuery(".form-box").stop(true, true).fadeIn('fast');
	jQuery(".shadow").stop(true, true).fadeIn('fast');
});

jQuery(window).resize(function () { 
	'use strict';
	resize_intro(); 
});

function resize_intro(){
	if(jQuery(window).width() > 720) {  
		page_height =  (((jQuery(window).height()) - jQuery('#header').height())) - 50; 
 		if(jQuery('.form-box').height() < (page_height)) {
	 		if(jQuery('.wrapper-flexi').length < 1) {
				form_margin = (page_height - jQuery('.form-box').height()) / 2; 
				jQuery(".form-box").css('margin-top',form_margin+'px'); 
				hero_margin = ((page_height - jQuery('.hero').height())-50) / 2;
				jQuery(".hero").css('margin-top',hero_margin+'px');   
			}
		}
	} 
}
