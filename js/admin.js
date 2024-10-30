/* globals jQuery */
/*jshint esversion: 6 */
/*jshint -W053 */

(function() {
  'use strict';
  window.permissions = null;
}());

jQuery(document).ready(function(){
	var fullanalysis = jQuery('#valuation_plugin_setting_fullanalysis').val();
	if(fullanalysis=='yes'){
		jQuery('tr.fullurl').fadeIn();
	}else{
		jQuery('tr.fullurl').fadeOut();
	}
	jQuery('#valuation_plugin_setting_fullanalysis').change(function(){
		var fullanalysis = jQuery(this).val();
		
		if(fullanalysis=='yes'){
			jQuery('tr.fullurl').fadeIn();
		}else{
			jQuery('tr.fullurl').fadeOut();
		}
	});
	
	// copy shortcode
	jQuery('#valuation_form .copyshortcode').click(function(){
		var $temp = jQuery("<input>");
		jQuery("body").append($temp);
		$temp.val(jQuery('#copyshortcode').html()).select();
		document.execCommand("copy");
		$temp.remove();
		
		jQuery('#valuation_form .copied').html('Copied');
	});
});