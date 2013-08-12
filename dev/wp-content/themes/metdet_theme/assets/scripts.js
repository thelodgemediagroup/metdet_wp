jQuery(document).ready(function(){

	//var spinnerHtml = '<img src="/wp-content/themes/metdet_theme/assets/images/ajaxspinner_lg.gif" class="ajax-spinner">'

	jQuery('.archive-year').click(function(e) {
		//jQuery('#issue-div').html(spinnerHtml);
		var issueYear = parseInt(jQuery(this).text());
		jQuery('#archive-date').html(issueYear);
		var yearPost = {issue_year: issueYear};
		jQuery('#issue-div').fadeOut('medium', function() {
			if (!isNaN(issueYear)) {
				jQuery.ajax({
					type: "POST",
					url: "/archiveapi/",
					data: yearPost,
					success: function(data) {
						jQuery('#issue-div').html(data);
						jQuery('#issue-div').fadeIn('medium');
					}
				});		
			}
		});
	});
});