<div class="d-flex justify-content-between align-items-center mb-3">
	<h4 class="mb-0">Riwayat Pemesanan Saya</h4>
	<a href="<?= site_url('pemesanan/tambah') ?>" class="btn btn-sm text-white" style="background-color:#2c5f7c;">
		<i class="fas fa-plus mr-1"></i> Pesan Baru
	</a>
</div>

<div class="card">
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table table-hover mb-0">
				<thead class="thead-light">
					<tr>
						<th>Kode</th>
						<th>Jenis Reklame</th>
						<th>Lokasi</th>
						<th>Status</th>
						<th>Tanggal Pesan</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($pemesanan)): ?>
						<tr><td colspan="6" class="text-center text-muted py-3">Anda belum memiliki pemesanan.</td></tr>
					<?php else: foreach ($pemesanan as $p): ?>
						<tr>
							<td><?= htmlspecialchars($p['kode_pemesanan']) ?></td>
							<td><?= htmlspecialchars($p['nama_jenis']) ?></td>
							<td><?= htmlspecialchars($p['lokasi_pemasangan']) ?></td>
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
