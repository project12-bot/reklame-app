<div class="d-flex justify-content-between align-items-center mb-3">
	<h4 class="mb-0">Jenis Reklame</h4>
	<a href="<?= site_url('jenisreklame/tambah') ?>" class="btn btn-sm text-white" style="background-color:#2c5f7c;">
		<i class="fas fa-plus mr-1"></i> Tambah Jenis
	</a>
</div>

<div class="card">
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table table-hover mb-0">
				<thead class="thead-light">
					<tr>
						<th>#</th>
						<th>Nama Jenis</th>
						<th>Deskripsi</th>
						<th>Satuan</th>
						<th>Harga Dasar</th>
						<th>Status</th>
						<th class="text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($list)): ?>
						<tr><td colspan="7" class="text-center text-muted py-3">Belum ada data.</td></tr>
					<?php else: $no = 1; foreach ($list as $item): ?>
						<tr>
							<td><?= $no++ ?></td>
							<td><?= htmlspecialchars($item['nama_jenis']) ?></td>
							<td><?= htmlspecialchars($item['deskripsi']) ?></td>
							<td><?= htmlspecialchars($item['satuan']) ?></td>
							<td>Rp <?= number_format($item['harga_dasar'], 0, ',', '.') ?></td>
							<td>
								<span class="badge badge-<?= $item['status'] === 'aktif' ? 'success' : 'secondary' ?>">
									<?= ucfirst($item['status']) ?>
								</span>
							</td>
							<td class="text-center">
								<a href="<?= site_url('jenisreklame/ubah/' . $item['id']) ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
								<a href="<?= site_url('jenisreklame/hapus/' . $item['id']) ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete" data-message="Hapus jenis reklame ini?"><i class="fas fa-trash"></i></a>
							</td>
						</tr>
					<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
