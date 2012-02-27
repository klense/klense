$(function() {
	/*
	$(".tooltip-show[title]").tooltip({
		effect: "fade"
	});
	*/
});

Modernizr.load({
	test: Modernizr.input.placeholder,
	nope: 'content/js/placeholder/jquery.placeholder.min.js',
	complete: function () {
		if(!Modernizr.input.placeholder) $('input, textarea').placeholder();
	}
});

function hideFlash() { // Hide flash to avoid overlapping
	$('embed, object, iframe').css('visibility', 'hidden');
}
function showFlash() {
	$('embed, object, iframe').css({ 'visibility' : 'visible' });
}