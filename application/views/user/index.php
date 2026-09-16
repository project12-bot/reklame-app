<div class="d-flex justify-content-between align-items-center mb-3">
	<h4 class="mb-0">Kelola Pengguna</h4>
	<a href="<?= site_url('user/tambah') ?>" class="btn btn-sm text-white" style="background-color:#2c5f7c;">
		<i class="fas fa-plus mr-1"></i> Tambah Pengguna
	</a>
</div>

<div class="card">
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table table-hover mb-0">
				<thead class="thead-light">
					<tr>
						<th>#</th>
						<th>Username</th>
						<th>Nama Lengkap</th>
						<th>Role</th>
						<th>Email</th>
						<th>Status</th>
						<th class="text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($users)): ?>
						<tr><td colspan="7" class="text-center text-muted py-3">Belum ada data pengguna.</td></tr>
					<?php else: $no = 1; foreach ($users as $u): ?>
						<tr>
							<td><?= $no++ ?></td>
							<td><?= htmlspecialchars($u['username']) ?></td>
							<td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
							<td><span class="badge badge-info"><?= role_label($u['role']) ?></span></td>
							<td><?= htmlspecialchars($u['email']) ?></td>
							<td>
								<span class="badge badge-<?= $u['status'] === 'aktif' ? 'success' : 'secondary' ?>">
									<?= ucfirst($u['status']) ?>
								</span>
							</td>
							<td class="text-center">
								<a href="<?= site_url('user/ubah/' . $u['id']) ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
								<a href="<?= site_url('user/hapus/' . $u['id']) ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete" data-message="Hapus pengguna ini?"><i class="fas fa-trash"></i></a>
							</td>
						</tr>
					<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
