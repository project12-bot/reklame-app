<?php $role = $logged_user['role']; $uri = uri_string(); ?>
<aside class="app-sidebar">
	<nav class="nav flex-column py-3">

		<a class="nav-link <?= $uri === 'dashboard' ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>">
			<i class="fas fa-tachometer-alt fa-fw mr-2"></i> Dashboard
		</a>

		<?php if ($role === 'admin'): ?>
			<div class="sidebar-heading">Master Data</div>
			<a class="nav-link <?= strpos($uri, 'user') === 0 ? 'active' : '' ?>" href="<?= site_url('user') ?>">
				<i class="fas fa-users-cog fa-fw mr-2"></i> Kelola Pengguna
			</a>
		<?php endif; ?>

		<?php if (in_array($role, ['admin', 'accounting'])): ?>
			<a class="nav-link <?= strpos($uri, 'customer') === 0 ? 'active' : '' ?>" href="<?= site_url('customer') ?>">
				<i class="fas fa-building fa-fw mr-2"></i> Kelola Customer
			</a>
			<a class="nav-link <?= strpos($uri, 'jenisreklame') === 0 ? 'active' : '' ?>" href="<?= site_url('jenisreklame') ?>">
				<i class="fas fa-tags fa-fw mr-2"></i> Jenis Reklame
			</a>
		<?php endif; ?>

		<div class="sidebar-heading">Pemesanan</div>

		<?php if ($role === 'customer'): ?>
			<a class="nav-link <?= $uri === 'pemesanan/tambah' ? 'active' : '' ?>" href="<?= site_url('pemesanan/tambah') ?>">
				<i class="fas fa-plus-circle fa-fw mr-2"></i> Pesan Reklame Baru
			</a>
			<a class="nav-link <?= $uri === 'pemesanan/riwayat' ? 'active' : '' ?>" href="<?= site_url('pemesanan/riwayat') ?>">
				<i class="fas fa-history fa-fw mr-2"></i> Riwayat Pemesanan
			</a>
		<?php else: ?>
			<a class="nav-link <?= $uri === 'pemesanan' ? 'active' : '' ?>" href="<?= site_url('pemesanan') ?>">
				<i class="fas fa-clipboard-list fa-fw mr-2"></i> Data Pemesanan
			</a>
		<?php endif; ?>

		<?php if (in_array($role, ['admin', 'direktur', 'accounting'])): ?>
			<div class="sidebar-heading">Laporan</div>
			<a class="nav-link <?= strpos($uri, 'laporan') === 0 ? 'active' : '' ?>" href="<?= site_url('laporan') ?>">
				<i class="fas fa-chart-bar fa-fw mr-2"></i> Laporan Pemesanan
			</a>
		<?php endif; ?>

	</nav>
</aside>
<main class="app-content">
	<div class="container-fluid">
		<?php if ($this->session->flashdata('success')): ?>
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<?= $this->session->flashdata('success') ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('error')): ?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<?= $this->session->flashdata('error') ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php endif; ?>
