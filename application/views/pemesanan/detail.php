<?php $role = current_role(); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
	<h4 class="mb-0">Detail Pemesanan <?= htmlspecialchars($pemesanan['kode_pemesanan']) ?></h4>
	<span class="badge badge-<?= status_badge_class($pemesanan['status']) ?> p-2"><?= status_label($pemesanan['status']) ?></span>
</div>

<div class="row">
	<!-- ============ KOLOM KIRI: INFORMASI PESANAN ============ -->
	<div class="col-lg-7">

		<div class="card mb-3">
			<div class="card-header">Informasi Pemesanan</div>
			<div class="card-body">
				<table class="table table-borderless table-sm mb-0">
					<tr><td width="180" class="text-muted">Customer</td><td>: <?= htmlspecialchars($pemesanan['nama_perusahaan']) ?></td></tr>
					<tr><td class="text-muted">Jenis Reklame</td><td>: <?= htmlspecialchars($pemesanan['nama_jenis']) ?></td></tr>
					<tr><td class="text-muted">Ukuran</td><td>: <?= htmlspecialchars($pemesanan['ukuran']) ?></td></tr>
					<tr><td class="text-muted">Lokasi Pemasangan</td><td>: <?= htmlspecialchars($pemesanan['lokasi_pemasangan']) ?></td></tr>
					<tr><td class="text-muted">Tanggal Pesan</td><td>: <?= date('d-m-Y', strtotime($pemesanan['tanggal_pesan'])) ?></td></tr>
					<tr><td class="text-muted">Ide / Kebutuhan Desain</td><td>: <?= nl2br(htmlspecialchars($pemesanan['ide_kebutuhan'])) ?></td></tr>
					<?php if ($pemesanan['catatan_customer']): ?>
					<tr><td class="text-muted">Catatan</td><td>: <?= nl2br(htmlspecialchars($pemesanan['catatan_customer'])) ?></td></tr>
					<?php endif; ?>
					<?php if ($pemesanan['harga_ditawarkan']): ?>
					<tr><td class="text-muted">Harga Ditawarkan</td><td>: Rp <?= number_format($pemesanan['harga_ditawarkan'], 0, ',', '.') ?></td></tr>
					<?php endif; ?>
					<?php if ($pemesanan['harga_disetujui']): ?>
					<tr><td class="text-muted">Harga Disetujui</td><td>: <strong>Rp <?= number_format($pemesanan['harga_disetujui'], 0, ',', '.') ?></strong></td></tr>
					<?php endif; ?>
				</table>
			</div>
		</div>

		<!-- ============ DESAIN ============ -->
		<div class="card mb-3">
			<div class="card-header">Desain Reklame</div>
			<div class="card-body">
				<?php if (empty($desain)): ?>
					<p class="text-muted mb-0">Belum ada desain yang diunggah.</p>
				<?php else: foreach (array_reverse($desain) as $d): ?>
					<div class="border rounded p-3 mb-2">
						<div class="d-flex justify-content-between">
							<strong>Versi <?= $d['versi'] ?></strong>
							<small class="text-muted"><?= date('d-m-Y H:i', strtotime($d['created_at'])) ?> oleh <?= htmlspecialchars($d['nama_desainer']) ?></small>
						</div>
						<?php if ($d['keterangan']): ?><p class="mb-2 mt-1"><?= nl2br(htmlspecialchars($d['keterangan'])) ?></p><?php endif; ?>
						<?php
							$ext = strtolower(pathinfo($d['file_desain'], PATHINFO_EXTENSION));
							$file_url = base_url('uploads/desain/' . $d['file_desain']);
						?>
						<?php if (in_array($ext, ['jpg','jpeg','png'])): ?>
							<a href="<?= $file_url ?>" target="_blank">
								<img src="<?= $file_url ?>" class="img-fluid rounded" style="max-height:280px;" alt="Desain v<?= $d['versi'] ?>">
							</a>
						<?php else: ?>
							<a href="<?= $file_url ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
								<i class="fas fa-file-download mr-1"></i> Unduh File (.<?= $ext ?>)
							</a>
						<?php endif; ?>
					</div>
				<?php endforeach; endif; ?>

				<?php if (in_array($role, ['desainer','admin']) && in_array($pemesanan['status'], ['baru','proses_desain','revisi_desain_customer','revisi_desain_direktur'])): ?>
					<hr>
					<h6>Unggah Desain <?= empty($desain) ? 'Pertama' : 'Revisi' ?></h6>
					<form method="post" action="<?= site_url('pemesanan/upload_desain/' . $pemesanan['id']) ?>" enctype="multipart/form-data">
					<?php if ($this->config->item('csrf_protection')): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif; ?>
						<div class="form-group">
							<input type="file" name="file_desain" class="form-control-file" required>
							<small class="text-muted">Format: jpg, png, pdf, ai, cdr, psd. Maks 10MB.</small>
						</div>
						<div class="form-group">
							<textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan desain (opsional)"></textarea>
						</div>
						<button type="submit" class="btn btn-sm text-white" style="background-color:#2c5f7c;">
							<i class="fas fa-upload mr-1"></i> Kirim ke Customer
						</button>
					</form>
				<?php endif; ?>
			</div>
		</div>

		<!-- ============ RIWAYAT REVISI ============ -->
		<?php if (!empty($revisi)): ?>
		<div class="card mb-3">
			<div class="card-header">Riwayat Permintaan Revisi</div>
			<div class="card-body">
				<?php foreach ($revisi as $r): ?>
					<div class="mb-2 pb-2 border-bottom">
						<span class="badge badge-warning">Revisi dari <?= ucfirst($r['diminta_oleh']) ?></span>
						<small class="text-muted float-right"><?= date('d-m-Y H:i', strtotime($r['created_at'])) ?></small>
						<p class="mb-0 mt-1"><?= nl2br(htmlspecialchars($r['catatan_revisi'])) ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>

		<!-- ============ RIWAYAT STATUS ============ -->
		<div class="card mb-3">
			<div class="card-header">Riwayat Status Pemesanan</div>
			<div class="card-body">
				<ul class="timeline mb-0">
					<?php foreach ($status_log as $log): ?>
						<li>
							<strong><?= status_label($log['status']) ?></strong>
							<small class="text-muted d-block"><?= date('d-m-Y H:i', strtotime($log['created_at'])) ?><?= $log['nama_lengkap'] ? ' oleh ' . htmlspecialchars($log['nama_lengkap']) : '' ?></small>
							<?php if ($log['keterangan']): ?><div class="text-secondary small"><?= htmlspecialchars($log['keterangan']) ?></div><?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>

	<!-- ============ KOLOM KANAN: AKSI SESUAI ROLE ============ -->
	<div class="col-lg-5">

		<!-- CUSTOMER: Respon Desain -->
		<?php if ($role === 'customer' && $pemesanan['status'] === 'menunggu_approval_customer'): ?>
		<div class="card mb-3 border-warning">
			<div class="card-header bg-warning">Persetujuan Desain Diperlukan</div>
			<div class="card-body">
				<p>Silakan periksa desain di atas. Apakah Anda menyetujuinya?</p>
				<form method="post" action="<?= site_url('pemesanan/respon_desain_customer/' . $pemesanan['id']) ?>">
					<?php if ($this->config->item('csrf_protection')): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif; ?>
					<div class="form-group">
						<textarea name="catatan" class="form-control" rows="2" placeholder="Catatan (wajib diisi jika meminta revisi)"></textarea>
					</div>
					<button type="submit" name="keputusan" value="setuju" class="btn btn-success btn-sm"><i class="fas fa-check mr-1"></i> Setujui Desain</button>
					<button type="submit" name="keputusan" value="revisi" class="btn btn-outline-danger btn-sm"><i class="fas fa-redo mr-1"></i> Minta Revisi</button>
				</form>
			</div>
		</div>
		<?php endif; ?>

		<!-- DIREKTUR: Respon Desain -->
		<?php if ($role === 'direktur' && $pemesanan['status'] === 'menunggu_approval_direktur'): ?>
		<div class="card mb-3 border-warning">
			<div class="card-header bg-warning">Persetujuan Direktur Diperlukan</div>
			<div class="card-body">
				<p>Desain telah disetujui customer. Mohon review akhir.</p>
				<form method="post" action="<?= site_url('pemesanan/respon_desain_direktur/' . $pemesanan['id']) ?>">
					<?php if ($this->config->item('csrf_protection')): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif; ?>
					<div class="form-group">
						<textarea name="catatan" class="form-control" rows="2" placeholder="Catatan (wajib diisi jika meminta revisi)"></textarea>
					</div>
					<button type="submit" name="keputusan" value="setuju" class="btn btn-success btn-sm"><i class="fas fa-check mr-1"></i> Setujui Desain</button>
					<button type="submit" name="keputusan" value="revisi" class="btn btn-outline-danger btn-sm"><i class="fas fa-redo mr-1"></i> Minta Revisi</button>
				</form>
			</div>
		</div>
		<?php endif; ?>

		<!-- ACCOUNTING: Tawarkan Harga -->
		<?php if (in_array($role, ['accounting','admin']) && $pemesanan['status'] === 'menunggu_approval_harga' && empty($pemesanan['harga_ditawarkan'])): ?>
		<div class="card mb-3 border-info">
			<div class="card-header bg-info text-white">Input Penawaran Harga</div>
			<div class="card-body">
				<form method="post" action="<?= site_url('pemesanan/tawar_harga/' . $pemesanan['id']) ?>">
					<?php if ($this->config->item('csrf_protection')): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif; ?>
					<div class="form-group">
						<label>Harga (Rp)</label>
						<input type="number" step="0.01" name="harga_ditawarkan" class="form-control" required>
					</div>
					<button type="submit" class="btn btn-sm text-white" style="background-color:#2c5f7c;">Kirim Penawaran ke Customer</button>
				</form>
			</div>
		</div>
		<?php endif; ?>

		<!-- CUSTOMER: Setujui Harga -->
		<?php if ($role === 'customer' && $pemesanan['status'] === 'menunggu_approval_harga' && !empty($pemesanan['harga_ditawarkan'])): ?>
		<div class="card mb-3 border-warning">
			<div class="card-header bg-warning">Persetujuan Harga</div>
			<div class="card-body">
				<p>Harga yang ditawarkan: <strong>Rp <?= number_format($pemesanan['harga_ditawarkan'], 0, ',', '.') ?></strong></p>
				<form method="post" action="<?= site_url('pemesanan/setuju_harga/' . $pemesanan['id']) ?>">
					<?php if ($this->config->item('csrf_protection')): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif; ?>
					<button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check mr-1"></i> Setujui Harga</button>
				</form>
			</div>
		</div>
		<?php endif; ?>

		<!-- ACCOUNTING: Buat SPK -->
		<?php if (in_array($role, ['accounting','admin']) && $pemesanan['status'] === 'harga_disetujui'): ?>
		<div class="card mb-3 border-info">
			<div class="card-header bg-info text-white">Buat Surat Perintah Kerja (SPK)</div>
			<div class="card-body">
				<form method="post" action="<?= site_url('pemesanan/buat_spk/' . $pemesanan['id']) ?>">
					<?php if ($this->config->item('csrf_protection')): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif; ?>
					<div class="form-group">
						<label>Isi / Instruksi SPK</label>
						<textarea name="isi_spk" class="form-control" rows="3" placeholder="Instruksi produksi untuk Kepala Produksi..."></textarea>
					</div>
					<button type="submit" class="btn btn-sm text-white" style="background-color:#2c5f7c;">Terbitkan SPK</button>
				</form>
			</div>
		</div>
		<?php endif; ?>

		<!-- SPK INFO -->
		<?php if ($spk): ?>
		<div class="card mb-3">
			<div class="card-header">Surat Perintah Kerja</div>
			<div class="card-body">
				<p class="mb-1"><strong>No. SPK:</strong> <?= htmlspecialchars($spk['no_spk']) ?></p>
				<p class="mb-1"><strong>Tanggal:</strong> <?= date('d-m-Y', strtotime($spk['tanggal_spk'])) ?></p>
				<?php if ($spk['isi_spk']): ?><p class="mb-0"><?= nl2br(htmlspecialchars($spk['isi_spk'])) ?></p><?php endif; ?>
			</div>
		</div>
		<?php endif; ?>

		<!-- KEPALA PRODUKSI: Update Produksi -->
		<?php if (in_array($role, ['kepala_produksi','admin']) && $produksi && in_array($pemesanan['status'], ['spk_dibuat','proses_produksi'])): ?>
		<div class="card mb-3 border-info">
			<div class="card-header bg-info text-white">Update Status Produksi</div>
			<div class="card-body">
				<p class="mb-1 text-muted">Gambar Teknis Reklame:</p>
				<?php if (!empty($desain)): $last = end($desain); $ext = strtolower(pathinfo($last['file_desain'], PATHINFO_EXTENSION)); ?>
					<a href="<?= base_url('uploads/desain/' . $last['file_desain']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary mb-2">
						<i class="fas fa-drafting-compass mr-1"></i> Lihat Desain Final (v<?= $last['versi'] ?>)
					</a>
				<?php endif; ?>
				<form method="post" action="<?= site_url('pemesanan/update_produksi/' . $pemesanan['id']) ?>">
					<?php if ($this->config->item('csrf_protection')): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif; ?>
					<div class="form-group">
						<label>Status Produksi</label>
						<select name="status_produksi" class="form-control" required>
							<option value="proses" <?= $produksi['status'] === 'proses' ? 'selected' : '' ?>>Sedang Diproses</option>
							<option value="selesai" <?= $produksi['status'] === 'selesai' ? 'selected' : '' ?>>Selesai Produksi</option>
						</select>
					</div>
					<div class="form-group">
						<label>Catatan Produksi</label>
						<textarea name="catatan_produksi" class="form-control" rows="2"><?= htmlspecialchars($produksi['catatan_produksi']) ?></textarea>
					</div>
					<button type="submit" class="btn btn-sm text-white" style="background-color:#2c5f7c;">Simpan</button>
				</form>
				<?php if ($produksi['tanggal_mulai']): ?><small class="text-muted d-block mt-2">Mulai: <?= date('d-m-Y', strtotime($produksi['tanggal_mulai'])) ?></small><?php endif; ?>
				<?php if ($produksi['tanggal_selesai']): ?><small class="text-muted d-block">Selesai: <?= date('d-m-Y', strtotime($produksi['tanggal_selesai'])) ?></small><?php endif; ?>
			</div>
		</div>
		<?php endif; ?>

		<!-- FINALISASI -->
		<?php if (in_array($role, ['admin','accounting','kepala_produksi']) && $pemesanan['status'] === 'selesai_produksi'): ?>
		<div class="card mb-3 border-success">
			<div class="card-header bg-success text-white">Produksi Selesai</div>
			<div class="card-body">
				<form method="post" action="<?= site_url('pemesanan/selesaikan/' . $pemesanan['id']) ?>">
					<?php if ($this->config->item('csrf_protection')): ?>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					<?php endif; ?>
					<button type="submit" class="btn btn-success btn-sm btn-block"><i class="fas fa-flag-checkered mr-1"></i> Tandai Pemesanan Selesai</button>
				</form>
			</div>
		</div>
		<?php endif; ?>

		<!-- RIWAYAT PERSETUJUAN -->
		<?php if (!empty($persetujuan)): ?>
		<div class="card mb-3">
			<div class="card-header">Riwayat Persetujuan</div>
			<div class="card-body">
				<?php foreach ($persetujuan as $per): ?>
					<div class="mb-2 pb-2 border-bottom">
						<span class="badge badge-success">
							<?= str_replace('_', ' ', ucwords($per['jenis'])) ?> - <?= ucfirst($per['status']) ?>
						</span>
						<small class="text-muted d-block"><?= date('d-m-Y H:i', strtotime($per['created_at'])) ?> oleh <?= htmlspecialchars($per['nama_lengkap']) ?></small>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>

	</div>
</div>
