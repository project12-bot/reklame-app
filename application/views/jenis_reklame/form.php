<?php $edit = isset($item); ?>
<h4 class="mb-3"><?= $edit ? 'Ubah Jenis Reklame' : 'Tambah Jenis Reklame' ?></h4>

<div class="card">
	<div class="card-body">
		<form method="post" action="<?= $edit ? site_url('jenisreklame/ubah/' . $item['id']) : site_url('jenisreklame/tambah') ?>">
					<?php if ($this->config->item('csrf_protection')): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif; ?>
			<div class="form-group">
				<label>Nama Jenis</label>
				<input type="text" name="nama_jenis" class="form-control" required
					value="<?= $edit ? htmlspecialchars($item['nama_jenis']) : set_value('nama_jenis') ?>">
			</div>
			<div class="form-group">
				<label>Deskripsi</label>
				<textarea name="deskripsi" class="form-control" rows="2"><?= $edit ? htmlspecialchars($item['deskripsi']) : set_value('deskripsi') ?></textarea>
			</div>
			<div class="form-row">
				<div class="form-group col-md-4">
					<label>Satuan</label>
					<input type="text" name="satuan" class="form-control" placeholder="m2 / pcs / unit"
						value="<?= $edit ? htmlspecialchars($item['satuan']) : set_value('satuan', 'm2') ?>">
				</div>
				<div class="form-group col-md-4">
					<label>Harga Dasar (Rp)</label>
					<input type="number" step="0.01" name="harga_dasar" class="form-control" required
						value="<?= $edit ? $item['harga_dasar'] : set_value('harga_dasar') ?>">
				</div>
				<?php if ($edit): ?>
				<div class="form-group col-md-4">
					<label>Status</label>
					<select name="status" class="form-control">
						<option value="aktif" <?= $item['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
						<option value="nonaktif" <?= $item['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
					</select>
				</div>
				<?php endif; ?>
			</div>

			<button type="submit" class="btn text-white" style="background-color:#2c5f7c;">Simpan</button>
			<a href="<?= site_url('jenisreklame') ?>" class="btn btn-outline-secondary">Batal</a>
		</form>
	</div>
</div>
