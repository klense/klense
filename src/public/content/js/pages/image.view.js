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
});