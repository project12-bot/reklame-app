<h4 class="mb-3">Laporan Pemesanan Reklame</h4>

<div class="card mb-3">
	<div class="card-body">
		<form method="get" action="<?= site_url('laporan') ?>" class="form-row align-items-end">
			<div class="form-group col-md-3">
				<label>Tanggal Dari</label>
				<input type="date" name="tanggal_dari" class="form-control" value="<?= htmlspecialchars($tanggal_dari) ?>">
			</div>
			<div class="form-group col-md-3">
				<label>Tanggal Sampai</label>
				<input type="date" name="tanggal_sampai" class="form-control" value="<?= htmlspecialchars($tanggal_sampai) ?>">
			</div>
			<div class="form-group col-md-3">
				<label>Status</label>
				<select name="status" class="form-control">
					<option value="">Semua Status</option>
					<?php
					$statuses = array('baru','proses_desain','menunggu_approval_customer','revisi_desain_customer',
						'disetujui_customer','menunggu_approval_direktur','revisi_desain_direktur','disetujui_direktur',
						'menunggu_approval_harga','harga_disetujui','spk_dibuat','proses_produksi','selesai_produksi',
						'selesai','dibatalkan');
					foreach ($statuses as $s): ?>
						<option value="<?= $s ?>" <?= $status_filter === $s ? 'selected' : '' ?>><?= status_label($s) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group col-md-3">
				<button type="submit" class="btn text-white mr-1" style="background-color:#2c5f7c;"><i class="fas fa-filter mr-1"></i>Filter</button>
				<a href="<?= site_url('laporan/cetak?tanggal_dari=' . $tanggal_dari . '&tanggal_sampai=' . $tanggal_sampai . '&status=' . $status_filter) ?>" target="_blank" class="btn btn-outline-secondary">
					<i class="fas fa-print mr-1"></i>Cetak
				</a>
			</div>
		</form>
	</div>
</div>

<div class="row mb-3">
	<div class="col-md-4">
		<div class="stat-card bg-brand">
			<small>Total Pemesanan (sesuai filter)</small>
			<h3><?= count($pemesanan) ?></h3>
		</div>
	</div>
	<div class="col-md-4">
		<div class="stat-card" style="background-color:#28a745;">
			<small>Total Nilai Transaksi Disetujui</small>
			<h3>Rp <?= number_format($total_nilai, 0, ',', '.') ?></h3>
		</div>
	</div>
	<div class="col-md-4">
		<div class="stat-card" style="background-color:#17a2b8;">
			<small>Jumlah Status Berbeda</small>
			<h3><?= count($rekap_status) ?></h3>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">Rekap Per Status</div>
	<div class="card-body">
		<?php foreach ($rekap_status as $r): ?>
			<span class="badge badge-<?= status_badge_class($r['status']) ?> p-2 mr-2 mb-2 d-inline-block">
				<?= status_label($r['status']) ?>: <?= $r['jumlah'] ?>
			</span>
		<?php endforeach; ?>
	</div>
</div>

<div class="card mt-3">
	<div class="card-header">Daftar Pemesanan</div>
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table table-hover mb-0">
				<thead class="thead-light">
					<tr>
						<th>Kode</th>
						<th>Customer</th>
						<th>Jenis Reklame</th>
						<th>Status</th>
						<th>Tanggal</th>
						<th>Harga Disetujui</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($pemesanan)): ?>
						<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data untuk filter ini.</td></tr>
					<?php else: foreach ($pemesanan as $p): ?>
						<tr>
							<td><?= htmlspecialchars($p['kode_pemesanan']) ?></td>
							<td><?= htmlspecialchars($p['nama_perusahaan']) ?></td>
							<td><?= htmlspecialchars($p['nama_jenis']) ?></td>
							<td><span class="badge badge-<?= status_badge_class($p['status']) ?>"><?= status_label($p['status']) ?></span></td>
							<td><?= date('d-m-Y', strtotime($p['tanggal_pesan'])) ?></td>
							<td><?= $p['harga_disetujui'] ? 'Rp ' . number_format($p['harga_disetujui'], 0, ',', '.') : '-' ?></td>
						</tr>
					<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
