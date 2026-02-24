window.addEventListener("load", function() {
	var elements = document.querySelectorAll(".same-height");
	var maxHeight = 0;
	elements.forEach(function(el) {
		var height = el.offsetHeight;
		if (height > maxHeight) {
			maxHeight = height;
		}
	});
	elements.forEach(function(el) {
		el.style.minHeight = maxHeight + "px";
	});
});
