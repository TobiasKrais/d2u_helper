/**
 * Vanilla JS autocomplete for the search-it search field.
 * Replaces the legacy jQuery $.fn.suggest plugin.
 *
 * Usage:
 *   D2UHelperSearchSuggest.init(inputElement, {
 *     source: 'index.php?rex-api-call=search_it_autocomplete',
 *     onSelect: function () { this.form && this.form.submit(); }
 *   });
 */
(function () {
	'use strict';

	function escapeRegExp(str) {
		return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
	}

	function escapeHtml(str) {
		return String(str)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#39;');
	}

	function Suggest(input, settings) {
		this.input = input;
		this.settings = Object.assign({
			source: '',
			delay: 100,
			resultsClass: 'ac_results',
			selectClass: 'ac_over',
			matchClass: 'ac_match',
			minchars: 2,
			delimiter: '\n',
			onSelect: null,
			maxCacheSize: 65536
		}, settings || {});

		this.input.setAttribute('autocomplete', 'off');
		this.list = document.createElement('ul');
		this.list.className = this.settings.resultsClass;
		this.list.style.display = 'none';
		this.list.style.position = 'absolute';
		document.body.appendChild(this.list);

		this.timer = null;
		this.lastLength = 0;
		this.cache = [];
		this.cacheSize = 0;

		this.bind();
		this.position();
	}

	Suggest.prototype.position = function () {
		var rect = this.input.getBoundingClientRect();
		this.list.style.top = (rect.top + window.pageYOffset + this.input.offsetHeight) + 'px';
		this.list.style.left = (rect.left + window.pageXOffset) + 'px';
	};

	Suggest.prototype.bind = function () {
		var self = this;
		window.addEventListener('load', function () { self.position(); });
		window.addEventListener('resize', function () { self.position(); });
		this.input.addEventListener('blur', function () {
			setTimeout(function () { self.list.style.display = 'none'; }, 200);
		});
		this.input.addEventListener('keydown', function (e) { self.onKey(e); });
		this.input.addEventListener('input', function () { self.onInput(); });
	};

	Suggest.prototype.isVisible = function () {
		return this.list.style.display !== 'none' && this.list.children.length > 0;
	};

	Suggest.prototype.getSelected = function () {
		if (!this.isVisible()) {
			return null;
		}
		return this.list.querySelector('li.' + this.settings.selectClass);
	};

	Suggest.prototype.onKey = function (e) {
		var key = e.keyCode;
		var navigationKey = key === 27 || key === 38 || key === 40;
		var enterKey = key === 13 || key === 9;

		if ((navigationKey && this.isVisible()) || (enterKey && this.getSelected())) {
			e.preventDefault();
			e.stopPropagation();
			switch (key) {
				case 38: this.prev(); break;
				case 40: this.next(); break;
				case 9:
				case 13: this.select(); break;
				case 27: this.list.style.display = 'none'; break;
			}
		}
	};

	Suggest.prototype.onInput = function () {
		var self = this;
		if (this.input.value.length === this.lastLength) {
			return;
		}
		if (this.timer) {
			clearTimeout(this.timer);
		}
		this.timer = setTimeout(function () { self.fetch(); }, this.settings.delay);
		this.lastLength = this.input.value.length;
	};

	Suggest.prototype.fetch = function () {
		var query = this.input.value.trim();
		if (query.length < this.settings.minchars) {
			this.list.style.display = 'none';
			return;
		}
		var cached = this.cacheGet(query);
		if (cached) {
			this.render(cached.items);
			return;
		}
		var self = this;
		var url = this.settings.source
			+ (this.settings.source.indexOf('?') === -1 ? '?' : '&')
			+ 'q=' + encodeURIComponent(query);
		fetch(url, { credentials: 'same-origin' })
			.then(function (r) { return r.text(); })
			.then(function (text) {
				self.list.style.display = 'none';
				var items = self.parse(text, query);
				self.render(items);
				self.cachePut(query, items, text.length);
			})
			.catch(function () { /* ignore network errors */ });
	};

	Suggest.prototype.cacheGet = function (query) {
		for (var i = 0; i < this.cache.length; i++) {
			if (this.cache[i].q === query) {
				var hit = this.cache.splice(i, 1)[0];
				this.cache.unshift(hit);
				return hit;
			}
		}
		return null;
	};

	Suggest.prototype.cachePut = function (query, items, size) {
		while (this.cache.length && this.cacheSize + size > this.settings.maxCacheSize) {
			var removed = this.cache.pop();
			this.cacheSize -= removed.size;
		}
		this.cache.push({ q: query, size: size, items: items });
		this.cacheSize += size;
	};

	Suggest.prototype.parse = function (text, query) {
		var lines = text.split(this.settings.delimiter);
		var items = [];
		var pattern = new RegExp(escapeRegExp(query), 'ig');
		var matchClass = this.settings.matchClass;
		for (var i = 0; i < lines.length; i++) {
			var line = lines[i].trim();
			if (!line) {
				continue;
			}
			var safe = escapeHtml(line).replace(pattern, function (match) {
				return '<span class="' + matchClass + '">' + match + '</span>';
			});
			items.push(safe);
		}
		return items;
	};

	Suggest.prototype.render = function (items) {
		if (!items || !items.length) {
			this.list.style.display = 'none';
			return;
		}
		this.list.innerHTML = '';
		var self = this;
		for (var i = 0; i < items.length; i++) {
			var li = document.createElement('li');
			li.innerHTML = items[i];
			this.list.appendChild(li);
		}
		this.list.style.display = '';
		this.list.querySelectorAll('li').forEach(function (li) {
			li.addEventListener('mouseover', function () {
				self.list.querySelectorAll('li').forEach(function (other) {
					other.classList.remove(self.settings.selectClass);
				});
				li.classList.add(self.settings.selectClass);
			});
			li.addEventListener('mousedown', function (e) {
				e.preventDefault();
				e.stopPropagation();
				self.select();
			});
		});
	};

	Suggest.prototype.select = function () {
		var current = this.getSelected();
		if (!current) {
			return;
		}
		this.input.value = current.textContent;
		this.list.style.display = 'none';
		if (typeof this.settings.onSelect === 'function') {
			this.settings.onSelect.call(this.input);
		}
	};

	Suggest.prototype.next = function () {
		var current = this.getSelected();
		if (current && current.nextElementSibling) {
			current.classList.remove(this.settings.selectClass);
			current.nextElementSibling.classList.add(this.settings.selectClass);
		} else if (!current) {
			var first = this.list.firstElementChild;
			if (first) {
				first.classList.add(this.settings.selectClass);
			}
		}
	};

	Suggest.prototype.prev = function () {
		var current = this.getSelected();
		if (current && current.previousElementSibling) {
			current.classList.remove(this.settings.selectClass);
			current.previousElementSibling.classList.add(this.settings.selectClass);
		} else if (!current) {
			var last = this.list.lastElementChild;
			if (last) {
				last.classList.add(this.settings.selectClass);
			}
		}
	};

	window.D2UHelperSearchSuggest = {
		init: function (input, settings) {
			if (!input) {
				return null;
			}
			return new Suggest(input, settings);
		}
	};
})();
