<?php $edit = isset($user); ?>
<h4 class="mb-3"><?= $edit ? 'Ubah Pengguna' : 'Tambah Pengguna' ?></h4>

<div class="card">
	<div class="card-body">
		<form method="post" action="<?= $edit ? site_url('user/ubah/' . $user['id']) : site_url('user/tambah') ?>">
					<?php if ($this->config->item('csrf_protection')): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif; ?>
			<div class="form-row">
				<div class="form-group col-md-6">
					<label>Username</label>
					<input type="text" name="username" class="form-control" required
						value="<?= $edit ? htmlspecialchars($user['username']) : set_value('username') ?>">
				</div>
				<div class="form-group col-md-6">
					<label>Password <?= $edit ? '<small class="text-muted">(kosongkan jika tidak diubah)</small>' : '' ?></label>
					<input type="password" name="password" class="form-control" <?= $edit ? '' : 'required' ?>>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-6">
					<label>Nama Lengkap</label>
					<input type="text" name="nama_lengkap" class="form-control" required
						value="<?= $edit ? htmlspecialchars($user['nama_lengkap']) : set_value('nama_lengkap') ?>">
				</div>
				<div class="form-group col-md-6">
					<label>Role / Hak Akses</label>
					<select name="role" class="form-control" required>
						<option value="">-- Pilih Role --</option>
						<?php
						$roles = array(
							'admin' => 'Administrator', 'customer' => 'Customer', 'desainer' => 'Desainer',
							'direktur' => 'Direktur', 'accounting' => 'Accounting', 'kepala_produksi' => 'Kepala Produksi',
						);
						foreach ($roles as $val => $label):
							$selected = $edit && $user['role'] === $val ? 'selected' : '';
						?>
							<option value="<?= $val ?>" <?= $selected ?>><?= $label ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-6">
					<label>Email</label>
					<input type="email" name="email" class="form-control"
						value="<?= $edit ? htmlspecialchars($user['email']) : set_value('email') ?>">
				</div>
				<div class="form-group col-md-6">
					<label>No. HP</label>
					<input type="text" name="no_hp" class="form-control"
						value="<?= $edit ? htmlspecialchars($user['no_hp']) : set_value('no_hp') ?>">
				</div>
			</div>
			<?php if ($edit): ?>
				<div class="form-group">
					<label>Status Akun</label>
					<select name="status" class="form-control">
						<option value="aktif" <?= $user['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
						<option value="nonaktif" <?= $user['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
					</select>
				</div>
			<?php endif; ?>

			<button type="submit" class="btn text-white" style="background-color:#2c5f7c;">Simpan</button>
			<a href="<?= site_url('user') ?>" class="btn btn-outline-secondary">Batal</a>
		</form>
	</div>
</div>
