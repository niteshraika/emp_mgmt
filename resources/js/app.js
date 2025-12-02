import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
	const toggle = document.getElementById('sidebarToggle');
	const overlay = document.getElementById('mobileOverlay');

	function closeSidebar() {
		document.body.classList.remove('sidebar-open');
		if (overlay) overlay.setAttribute('aria-hidden', 'true');
	}

	function openSidebar() {
		document.body.classList.add('sidebar-open');
		if (overlay) overlay.setAttribute('aria-hidden', 'false');
	}

	if (toggle) {
		toggle.addEventListener('click', function (e) {
			e.preventDefault();
			if (document.body.classList.contains('sidebar-open')) {
				closeSidebar();
			} else {
				openSidebar();
			}
		});
	}

	if (overlay) {
		overlay.addEventListener('click', function () {
			closeSidebar();
		});
	}

	// Theme toggle: persist theme in localStorage and apply 'dark' class on body
	const themeToggle = document.getElementById('themeToggle');

	function applyTheme(theme) {
		if (theme === 'dark') {
			document.body.classList.add('dark');
			if (themeToggle) themeToggle.setAttribute('aria-pressed', 'true');
		} else {
			document.body.classList.remove('dark');
			if (themeToggle) themeToggle.setAttribute('aria-pressed', 'false');
		}
	}

	// initialize theme from localStorage or prefers-color-scheme
	const storedTheme = localStorage.getItem('theme');
	if (storedTheme) {
		applyTheme(storedTheme);
	} else {
		const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
		applyTheme(prefersDark ? 'dark' : 'light');
	}

	if (themeToggle) {
		themeToggle.addEventListener('click', function (e) {
			e.preventDefault();
			const isDark = document.body.classList.contains('dark');
			const next = isDark ? 'light' : 'dark';
			localStorage.setItem('theme', next);
			applyTheme(next);
		});
	}

	// Auto-dismiss and manual close for flash alerts
	const alerts = document.querySelectorAll('.alert');
	alerts.forEach((alert) => {
		// add close button if not present
		if (!alert.querySelector('.alert-close')) {
			const btn = document.createElement('button');
			btn.type = 'button';
			btn.className = 'alert-close';
			btn.setAttribute('aria-label', 'Close');
			btn.innerHTML = '&times;';
			btn.style.marginLeft = '0.5rem';
			btn.style.background = 'transparent';
			btn.style.border = 'none';
			btn.style.cursor = 'pointer';
			btn.style.fontSize = '1.25rem';
			btn.addEventListener('click', () => {
				alert.remove();
			});
			alert.appendChild(btn);
		}

		// auto-dismiss after 5 seconds
		setTimeout(() => {
			if (alert.parentNode) alert.remove();
		}, 5000);
	});
});
