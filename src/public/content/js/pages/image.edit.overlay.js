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
				} else if(data == "2") {
					// vai a pagina profilo
					window.location.replace("browse");
				} else {
					// Reset delle proprietà per la funzione delete
					$("#edit_form form input[name=delete]").val("0");
					$("#edit_form form").removeAttr("novalidate");
					
					$("#edit_form .form_error").show();
					$("#edit_form .form_error").html(data);
					$.fancybox.resize();
				}
			}
		});
		
		return false;
	});
	
	$("#btnImageDelete").click(function() {
		var conf = window.confirm("Eliminare definitivamente questa immagine da klense?");
		if(conf) {
			$("#edit_form form input[name=delete]").val("1");
			$("#edit_form form").attr("novalidate", "");
			$("#edit_form form").submit();
		}
		return false;
	});
});