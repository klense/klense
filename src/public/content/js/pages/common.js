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