window.addEventListener('load', function () {
	var elements = Array.prototype.slice.call(document.querySelectorAll('.same-height'));
	if (elements.length === 0) {
		return;
	}

	var maxHeight = 0;
	elements.forEach(function (element) {
		maxHeight = Math.max(maxHeight, element.clientHeight);
	});

	elements.forEach(function (element) {
		element.style.minHeight = maxHeight + 'px';
	});
});