<?php $edit = isset($customer); ?>
<h4 class="mb-3"><?= $edit ? 'Ubah Customer' : 'Tambah Customer' ?></h4>

<div class="card">
	<div class="card-body">
		<form method="post" action="<?= $edit ? site_url('customer/ubah/' . $customer['id']) : site_url('customer/tambah') ?>">
					<?php if ($this->config->item('csrf_protection')): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif; ?>

			<?php if (!$edit): ?>
			<h6 class="text-muted">Akun Login Customer</h6>
			<div class="form-row">
				<div class="form-group col-md-6">
					<label>Username</label>
					<input type="text" name="username" class="form-control" required>
				</div>
				<div class="form-group col-md-6">
					<label>Password</label>
					<input type="password" name="password" class="form-control" required>
				</div>
			</div>
			<hr>
			<?php endif; ?>

			<h6 class="text-muted">Data Perusahaan</h6>
			<div class="form-group">
				<label>Nama Perusahaan</label>
				<input type="text" name="nama_perusahaan" class="form-control" required
					value="<?= $edit ? htmlspecialchars($customer['nama_perusahaan']) : set_value('nama_perusahaan') ?>">
			</div>
			<div class="form-group">
				<label>Alamat</label>
				<textarea name="alamat" class="form-control" rows="2"><?= $edit ? htmlspecialchars($customer['alamat']) : set_value('alamat') ?></textarea>
			</div>
			<div class="form-row">
				<div class="form-group col-md-4">
					<label>Kontak Person</label>
					<input type="text" name="kontak_person" class="form-control"
						value="<?= $edit ? htmlspecialchars($customer['kontak_person']) : set_value('kontak_person') ?>">
				</div>
				<div class="form-group col-md-4">
					<label>No. HP</label>
					<input type="text" name="no_hp" class="form-control"
						value="<?= $edit ? htmlspecialchars($customer['no_hp']) : set_value('no_hp') ?>">
				</div>
				<div class="form-group col-md-4">
					<label>Email</label>
					<input type="email" name="email" class="form-control"
						value="<?= $edit ? htmlspecialchars($customer['email']) : set_value('email') ?>">
				</div>
			</div>

			<button type="submit" class="btn text-white" style="background-color:#2c5f7c;">Simpan</button>
			<a href="<?= site_url('customer') ?>" class="btn btn-outline-secondary">Batal</a>
		</form>
	</div>
</div>
