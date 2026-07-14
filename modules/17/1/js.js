(function () {
    var sections = document.querySelectorAll('.googleplaces-section');
    Array.prototype.forEach.call(sections, function (section) {
        var carousel = section.querySelector('.carousel');
        if (!carousel) {
            return;
        }
        var indicators = section.querySelectorAll('.googleplaces-indicators [data-bs-slide-to]');
        if (indicators.length === 0) {
            return;
        }
        carousel.addEventListener('slid.bs.carousel', function (event) {
            Array.prototype.forEach.call(indicators, function (btn, i) {
                var isActive = i === event.to;
                btn.classList.toggle('active', isActive);
                if (isActive) {
                    btn.setAttribute('aria-current', 'true');
                } else {
                    btn.removeAttribute('aria-current');
                }
            });
        });
    });
})();
