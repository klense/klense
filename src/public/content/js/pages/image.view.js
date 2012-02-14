$(function() {
	$(".contentSlide.closed").hide();
	$(".headingSlide").click(function() {
		if ($(this).next(".contentSlide").is(":visible")) {
			$(this).removeClass("opened closed").addClass("closed");
			$(this).next(".contentSlide").removeClass("opened closed").addClass("closed");
		} else {
			$(this).removeClass("opened closed").addClass("opened");
			$(this).next(".contentSlide").removeClass("opened closed").addClass("opened");
		}
		$(this).next(".contentSlide").slideToggle(200);
	});
	
	$(".fancybox_img").fancybox({
		'overlayShow'	: true,
		'overlayOpacity': 0.9,
		'overlayColor' : '#000',
		'padding' : 0,
		'centerOnScroll' : true,
		'showCloseButton' : false,
		'hideOnContentClick' : true,
		'titleShow' : false,
		'type' : 'image',
		onStart : hideFlash,
		onClosed : showFlash
	});
	function hideFlash() { // Hide flash to avoid overlapping
		$('embed, object, iframe').css('visibility', 'hidden');
	}
	function showFlash() {
		$('embed, object, iframe').css({ 'visibility' : 'visible' });
	}
	
	$(window).resize(function() {
		$.fancybox.reshow();
	});
	
	$("#image_edit_link").fancybox({
		'scrolling'		: 'no',
		'titleShow'		: false,
		'onClosed'		: function() {
			$("#edit_form .form_error").hide();
		}
	});
});