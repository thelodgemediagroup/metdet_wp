jQuery(document).ready(function() {

	var data = {
		action: 'display_mdd_results',
		mdd_nonce: mdd_vars.mdd_nonce
	};

	jQuery.post(ajaxurl, data, function(response) {
		jQuery("#mdd-results").html(response);
	});
	return false;
});

jQuery(document).ready(function() {
	
		var data = {
			action: 'mdd_show_cities',
			mdd_nonce: mdd_vars.mdd_nonce
		};

		jQuery.post(ajaxurl, data, function(response) {
			jQuery("#city_select").append(response);
		});
});