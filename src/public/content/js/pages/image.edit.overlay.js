$(function() {
	$("#edit_form .form_error").hide();
	
	$("#edit_form form").bind("submit", function() {
		
		$.fancybox.showActivity();
		
		$.ajax({
			type	: "POST",
			cache	: false,
			url		: "image/edit-overlay/" + image_id,
			data	: $(this).serializeArray(),
			success: function(data) {
				$.fancybox.hideActivity();
				if(data == "1") {
					location.reload();
				} else {
					$("#edit_form .form_error").show();
					$("#edit_form .form_error").html(data);
					$.fancybox.resize();
				}
			}
		});
		
		return false;
	});
});