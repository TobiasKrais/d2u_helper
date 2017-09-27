$(window).on("load", function() {
	var heights = $(".same-height").map(function() {
		return $(this).innerHeight();
	}).get(),
	maxHeight = Math.max.apply(null, heights);
	$(".same-height").css("min-height", maxHeight);
});