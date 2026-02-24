/**
 * D2U Lightbox - Vanilla JS gallery lightbox (BS5 compatible, no jQuery)
 */
(function() {
	var currentGalleryItems = [];
	var currentIndex = 0;

	/**
	 * Create lightbox overlay element if not already present.
	 */
	function ensureLightboxOverlay() {
		if (document.getElementById("d2u-lightbox-overlay")) {
			return;
		}
		var overlay = document.createElement("div");
		overlay.id = "d2u-lightbox-overlay";
		overlay.innerHTML =
			'<div class="d2u-lightbox-close" aria-label="Close">&times;</div>' +
			'<div class="d2u-lightbox-prev" aria-label="Previous">&lsaquo;</div>' +
			'<div class="d2u-lightbox-next" aria-label="Next">&rsaquo;</div>' +
			'<img class="d2u-lightbox-image" src="" alt="">' +
			'<div class="d2u-lightbox-title"></div>';
		document.body.appendChild(overlay);

		// Close button
		overlay.querySelector(".d2u-lightbox-close").addEventListener("click", d2uLightboxClose);

		// Click on backdrop closes lightbox
		overlay.addEventListener("click", function(e) {
			if (e.target === overlay) {
				d2uLightboxClose();
			}
		});

		// Previous / Next navigation
		overlay.querySelector(".d2u-lightbox-prev").addEventListener("click", function(e) {
			e.stopPropagation();
			d2uLightboxNavigate(-1);
		});
		overlay.querySelector(".d2u-lightbox-next").addEventListener("click", function(e) {
			e.stopPropagation();
			d2uLightboxNavigate(1);
		});

		// Keyboard navigation
		document.addEventListener("keydown", function(e) {
			var overlayEl = document.getElementById("d2u-lightbox-overlay");
			if (overlayEl && overlayEl.style.display === "flex") {
				if (e.key === "Escape") {
					d2uLightboxClose();
				} else if (e.key === "ArrowLeft") {
					d2uLightboxNavigate(-1);
				} else if (e.key === "ArrowRight") {
					d2uLightboxNavigate(1);
				}
			}
		});
	}

	/**
	 * Show the current image in the lightbox.
	 */
	function showLightboxImage() {
		var item = currentGalleryItems[currentIndex];
		var overlay = document.getElementById("d2u-lightbox-overlay");
		overlay.querySelector(".d2u-lightbox-image").src = item.href;
		overlay.querySelector(".d2u-lightbox-image").alt = item.getAttribute("data-title") || "";
		overlay.querySelector(".d2u-lightbox-title").textContent = item.getAttribute("data-title") || "";

		// Show/hide navigation arrows for single images
		var prevBtn = overlay.querySelector(".d2u-lightbox-prev");
		var nextBtn = overlay.querySelector(".d2u-lightbox-next");
		if (currentGalleryItems.length <= 1) {
			prevBtn.style.display = "none";
			nextBtn.style.display = "none";
		} else {
			prevBtn.style.display = "";
			nextBtn.style.display = "";
		}
	}

	/**
	 * Open the lightbox for a specific gallery.
	 * @param {string} galleryId Gallery identifier
	 * @param {HTMLElement} element Clicked element
	 */
	window.d2uLightboxOpen = function(galleryId, element) {
		ensureLightboxOverlay();
		currentGalleryItems = Array.from(document.querySelectorAll('[data-d2u-gallery="' + galleryId + '"]'));
		currentIndex = currentGalleryItems.indexOf(element);
		if (currentIndex < 0) {
			currentIndex = 0;
		}
		showLightboxImage();
		document.getElementById("d2u-lightbox-overlay").style.display = "flex";
		document.body.style.overflow = "hidden";
	};

	/**
	 * Close the lightbox.
	 */
	window.d2uLightboxClose = function() {
		document.getElementById("d2u-lightbox-overlay").style.display = "none";
		document.body.style.overflow = "";
	};

	/**
	 * Navigate within the gallery.
	 * @param {number} direction -1 for previous, 1 for next
	 */
	window.d2uLightboxNavigate = function(direction) {
		currentIndex += direction;
		if (currentIndex < 0) {
			currentIndex = currentGalleryItems.length - 1;
		}
		if (currentIndex >= currentGalleryItems.length) {
			currentIndex = 0;
		}
		showLightboxImage();
	};
})();
