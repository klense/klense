$(function() {
	
	$(".fancybox_img").fancybox({
		overlayShow	: true,
		overlayOpacity: 0.9,
		overlayColor : '#000',
		padding : 0,
		centerOnScroll : true,
		showCloseButton : false,
		hideOnContentClick : true,
		titleShow : false,
		type : 'image',
		onStart : hideFlash,
		onClosed : showFlash
	}); // TODO: Preload images; title
	
	$(window).resize(function() {
		$.fancybox.reshow();
	});
	
});