<h4 class="mb-3">Pemesanan Reklame Baru</h4>

<div class="card">
	<div class="card-body">
		<form method="post" action="<?= site_url('pemesanan/tambah') ?>">
					<?php if ($this->config->item('csrf_protection')): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif; ?>

			<?php if (isset($customers)): ?>
				<div class="form-group">
					<label>Customer</label>
					<select name="customer_id" class="form-control" required>
						<option value="">-- Pilih Customer --</option>
						<?php foreach ($customers as $c): ?>
							<option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nama_perusahaan']) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>

			<div class="form-group">
				<label>Jenis Reklame</label>
				<select name="jenis_reklame_id" class="form-control" required>
					<option value="">-- Pilih Jenis Reklame --</option>
					<?php foreach ($jenis_reklame as $j): ?>
						<option value="<?= $j['id'] ?>"><?= htmlspecialchars($j['nama_jenis']) ?> (mulai Rp <?= number_format($j['harga_dasar'], 0, ',', '.') ?>/<?= htmlspecialchars($j['satuan']) ?>)</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="form-group">
				<label>Ide / Kebutuhan Desain</label>
				<textarea name="ide_kebutuhan" class="form-control" rows="4" required
					placeholder="Jelaskan konsep, warna, teks, atau referensi desain yang Anda inginkan..."><?= set_value('ide_kebutuhan') ?></textarea>
			</div>

			<div class="form-row">
				<div class="form-group col-md-6">
					<label>Ukuran</label>
					<input type="text" name="ukuran" class="form-control" placeholder="contoh: 4m x 6m" required>
				</div>
				<div class="form-group col-md-6">
					<label>Lokasi Pemasangan</label>
					<input type="text" name="lokasi_pemasangan" class="form-control" placeholder="contoh: Jl. Sudirman, Jakarta" required>
				</div>
			</div>

			<div class="form-group">
				<label>Catatan Tambahan (opsional)</label>
				<textarea name="catatan_customer" class="form-control" rows="2"></textarea>
			</div>

			<button type="submit" class="btn text-white" style="background-color:#2c5f7c;">
				<i class="fas fa-paper-plane mr-1"></i> Kirim Pemesanan
			</button>
			<a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary">Batal</a>
		</form>
	</div>
</div>
