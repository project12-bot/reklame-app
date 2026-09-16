document.addEventListener('DOMContentLoaded', function () {
	// Toggle sidebar di layar kecil
	var toggleBtn = document.getElementById('sidebarToggleMobile');
	var sidebar = document.querySelector('.app-sidebar');
	if (toggleBtn && sidebar) {
		toggleBtn.addEventListener('click', function () {
			sidebar.classList.toggle('show');
		});
	}

	// Konfirmasi sebelum menghapus data
	document.querySelectorAll('.btn-confirm-delete').forEach(function (btn) {
		btn.addEventListener('click', function (e) {
			var msg = btn.getAttribute('data-message') || 'Yakin ingin menghapus data ini?';
			if (!confirm(msg)) {
				e.preventDefault();
			}
		});
	});

	// Auto-dismiss alert setelah 5 detik
	document.querySelectorAll('.alert').forEach(function (alertEl) {
		setTimeout(function () {
			if (window.jQuery) {
				jQuery(alertEl).alert('close');
			}
		}, 5000);
	});
});
