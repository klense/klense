$(function() {
	$("#edit_form form").bind("submit", function() {
		
		/*
		if ($("#login_name").val().length < 1 || $("#login_pass").val().length < 1) {
			$("#login_error").show();
			$.fancybox.resize();
			return false;
		}
		*/
		
		$.fancybox.showActivity();
		
		$.ajax({
			type	: "POST",
			cache	: false,
			url		: "image/edit-overlay/7",
			data	: $(this).serializeArray(),
			success: function(data) {
				if(data == "1") location.reload();
				
			}
		});
		
		return false;
	});
});