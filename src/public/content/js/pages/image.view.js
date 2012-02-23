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

	
	$(window).resize(function() {
		$.fancybox.reshow();
	});
	
	$("#image_edit_link").fancybox({
		'scrolling'		: 'no',
		'titleShow'		: false,
		'onClosed'		: function() {
			$("#edit_form .form_error").hide();
		},
		onStart : hideFlash,
		onClosed : showFlash
	});
	
	$("#btnAddComment").click(function(){
		if($("#txtAddComment").val() != "") {
			addComment($("#txtAddComment").val(), {
				'target':null,
				'preloader':null,
				'onUpdate': function(response, root) { 
					if(response != "_error") {
						$("#txtAddComment").val("");
						$(response).hide().insertAfter("#comment_placeholder").show(1000);
					}
				}
			});
		}
	});
	
	$(".imageviews_sparkline").sparkline('html', {
											type: 'line',
											barColor: 'red',
											lineColor: '#F29E3D',
											fillColor: '#FFDFBA',
											width: '100%',
											height: '20px',
											minSpotColor: false,
											maxSpotColor: false,
											spotColor: false,
											chartRangeMin: 0
	});
});