<div class="d-flex justify-content-between align-items-center mb-3">
	<h4 class="mb-0">Data Pemesanan Reklame</h4>
</div>

<div class="card">
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table table-hover mb-0">
				<thead class="thead-light">
					<tr>
						<th>Kode</th>
						<th>Customer</th>
						<th>Jenis Reklame</th>
						<th>Ukuran</th>
						<th>Lokasi</th>
						<th>Status</th>
						<th>Tanggal</th>
						<th class="text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($pemesanan)): ?>
						<tr><td colspan="8" class="text-center text-muted py-3">Belum ada data pemesanan.</td></tr>
					<?php else: foreach ($pemesanan as $p): ?>
						<tr>
							<td><?= htmlspecialchars($p['kode_pemesanan']) ?></td>
							<td><?= htmlspecialchars($p['nama_perusahaan']) ?></td>
							<td><?= htmlspecialchars($p['nama_jenis']) ?></td>
							<td><?= htmlspecialchars($p['ukuran']) ?></td>
							<td><?= htmlspecialchars($p['lokasi_pemasangan']) ?></td>
							<td><span class="badge badge-<?= status_badge_class($p['status']) ?>"><?= status_label($p['status']) ?></span></td>
							<td><?= date('d-m-Y', strtotime($p['tanggal_pesan'])) ?></td>
							<td class="text-center">
								<a href="<?= site_url('pemesanan/detail/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary">Detail</a>
								<?php if (current_role() === 'admin'): ?>
									<a href="<?= site_url('pemesanan/hapus/' . $p['id']) ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete" data-message="Hapus pemesanan ini?"><i class="fas fa-trash"></i></a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
