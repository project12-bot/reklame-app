<h4 class="mb-3">Selamat datang, <?= htmlspecialchars($logged_user['nama']) ?> <span class="badge badge-secondary"><?= role_label($logged_user['role']) ?></span></h4>

<div class="row mb-4">
	<div class="col-md-4 mb-3">
		<div class="stat-card bg-brand">
			<small>Total Pemesanan</small>
			<h3><?= $total_pemesanan ?></h3>
		</div>
	</div>
	<?php
		$map_rekap = array();
		foreach ($rekap_status as $r) { $map_rekap[$r['status']] = $r['jumlah']; }
	?>
	<div class="col-md-4 mb-3">
		<div class="stat-card" style="background-color:#28a745;">
			<small>Selesai</small>
			<h3><?= isset($map_rekap['selesai']) ? $map_rekap['selesai'] : 0 ?></h3>
		</div>
	</div>
	<div class="col-md-4 mb-3">
		<div class="stat-card" style="background-color:#ffc107;color:#212529;">
			<small>Dalam Proses</small>
			<h3><?= $total_pemesanan - (isset($map_rekap['selesai']) ? $map_rekap['selesai'] : 0) - (isset($map_rekap['dibatalkan']) ? $map_rekap['dibatalkan'] : 0) ?></h3>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<?php if ($logged_user['role'] === 'customer'): ?>
			Pemesanan Terbaru Saya
		<?php elseif ($logged_user['role'] === 'desainer'): ?>
			Pemesanan Menunggu Proses Desain
		<?php elseif ($logged_user['role'] === 'direktur'): ?>
			Menunggu Persetujuan Direktur
		<?php elseif ($logged_user['role'] === 'accounting'): ?>
			Menunggu Proses Harga &amp; SPK
		<?php elseif ($logged_user['role'] === 'kepala_produksi'): ?>
			Pemesanan Siap/Dalam Produksi
		<?php else: ?>
			Pemesanan Terbaru
		<?php endif; ?>
	</div>
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
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($daftar_pemesanan)): ?>
						<tr><td colspan="6" class="text-center text-muted py-3">Belum ada data.</td></tr>
					<?php else: foreach ($daftar_pemesanan as $p): ?>
						<tr>
							<td><?= htmlspecialchars($p['kode_pemesanan']) ?></td>
							<td><?= htmlspecialchars($p['nama_perusahaan']) ?></td>
							<td><?= htmlspecialchars($p['nama_jenis']) ?></td>
							<td><span class="badge badge-<?= status_badge_class($p['status']) ?>"><?= status_label($p['status']) ?></span></td>
							<td><?= date('d-m-Y', strtotime($p['tanggal_pesan'])) ?></td>
							<td><a href="<?= site_url('pemesanan/detail/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary">Detail</a></td>
						</tr>
					<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
