jQuery(document).ready(function(){
	jQuery('.archive-year').click(function(e) {
		e.preventDefault();
		var issueYear = parseInt(jQuery(this).text());
		var yearPost = {issue_year: issueYear};
		if (!isNaN(issueYear)) {

			jQuery.ajax({
			type: "POST",
			url: "/archiveapi/",
			data: yearPost,
			success: function(data) {
				jQuery('#issue-div').html(data);
			}
			});
	
		}
	});
});