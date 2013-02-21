jQuery(document).ready(function() {
	
	var data = {
		action: 'current_issue_display_results',
		current_issue_nonce: current_issue_vars.current_issue_nonce
	};

	jQuery.post(ajaxurl, data, function(response) {
		jQuery("#current-issue-results").html(response);
	});
	return false;

});