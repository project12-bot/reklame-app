<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= isset($title) ? $title . ' - ' : '' ?>SI Reklame</title>
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/fontawesome.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark app-navbar fixed-top">
	<a class="navbar-brand" href="<?= site_url('dashboard') ?>">
		<i class="fas fa-ad mr-2"></i>SI Reklame
	</a>
	<button class="navbar-toggler" type="button" id="sidebarToggleMobile">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="ml-auto d-flex align-items-center">
		<div class="dropdown">
			<a class="nav-link dropdown-toggle text-light" href="#" role="button" data-toggle="dropdown">
				<i class="fas fa-user-circle mr-1"></i>
				<?= htmlspecialchars($logged_user['nama']) ?>
				<span class="badge badge-light ml-1"><?= role_label($logged_user['role']) ?></span>
			</a>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item" href="<?= site_url('logout') ?>"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
			</div>
		</div>
	</div>
</nav>

<div class="app-wrapper">
