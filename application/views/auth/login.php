<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - SI Reklame</title>
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/fontawesome.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
<div class="login-wrapper">
	<div class="card login-card shadow">
		<div class="card-header">
			<i class="fas fa-ad fa-2x mb-2"></i>
			<h5 class="mb-0">SI Reklame</h5>
			<small>Sistem Informasi Pemesanan &amp; Produksi Reklame</small>
		</div>
		<div class="card-body p-4">

			<?php if ($this->session->flashdata('error')): ?>
				<div class="alert alert-danger py-2"><?= $this->session->flashdata('error') ?></div>
			<?php endif; ?>

			<form action="<?= site_url('auth/proses_login') ?>" method="post">
					<?php if ($this->config->item('csrf_protection')): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif; ?>
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required autofocus>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
				</div>
				<button type="submit" class="btn btn-block text-white" style="background-color:#2c5f7c;">
					<i class="fas fa-sign-in-alt mr-1"></i> Masuk
				</button>
			</form>
		</div>
	</div>
</div>
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
