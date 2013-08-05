jQuery(document).ready(function(){
	jQuery('.archive-year').click(function() {
		var issueYear = parseInt(jQuery(this).val());
		yearPost = {year: issueYear};
		if (!isNaN(issueYear)) {
			
			/*jQuery.post('http://local.metdet.com/archive-api/', yearPost, function(data) {
				jQuery('#issue-div').html(data);
			});*/

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