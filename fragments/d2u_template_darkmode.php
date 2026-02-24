<?php
/**
 * Dark mode support fragment for Bootstrap 5 templates.
 *
 * @var string $this->position 'head' or 'body'
 *   - 'head': Outputs the flash-prevention script (reads localStorage/prefers-color-scheme and
 *             sets data-bs-theme before page renders). Must be placed in <head> after CSS.
 *   - 'body': Outputs the toggle functionality script (click handler for #darkModeToggle,
 *             localStorage persistence, system preference change listener).
 *             Must be placed before </body> after Bootstrap JS.
 */

$position = $this->position ?? '';

if ('head' === $position) {
?>
<script>
	// Apply dark mode preference before page renders to prevent flash
	(function() {
		var stored = localStorage.getItem('d2u_theme');
		if (stored) {
			document.documentElement.setAttribute('data-bs-theme', stored);
		} else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
			document.documentElement.setAttribute('data-bs-theme', 'dark');
		}
	})();
</script>
<?php
} elseif ('body' === $position) {
?>
<script>
	// Dark mode toggle functionality
	(function() {
		var toggle = document.getElementById('darkModeToggle');
		if (!toggle) return;

		toggle.addEventListener('click', function() {
			var current = document.documentElement.getAttribute('data-bs-theme');
			var next = current === 'dark' ? 'light' : 'dark';
			document.documentElement.setAttribute('data-bs-theme', next);
			localStorage.setItem('d2u_theme', next);
		});

		// Listen for system preference changes when no manual override
		window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
			if (!localStorage.getItem('d2u_theme')) {
				document.documentElement.setAttribute('data-bs-theme', e.matches ? 'dark' : 'light');
			}
		});
	})();
</script>
<?php
}
