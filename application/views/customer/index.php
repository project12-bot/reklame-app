<div class="d-flex justify-content-between align-items-center mb-3">
	<h4 class="mb-0">Kelola Customer</h4>
	<a href="<?= site_url('customer/tambah') ?>" class="btn btn-sm text-white" style="background-color:#2c5f7c;">
		<i class="fas fa-plus mr-1"></i> Tambah Customer
	</a>
</div>

<div class="card">
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table table-hover mb-0">
				<thead class="thead-light">
					<tr>
						<th>#</th>
						<th>Nama Perusahaan</th>
						<th>Kontak Person</th>
						<th>No. HP</th>
						<th>Email</th>
						<th>Username</th>
						<th class="text-center">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($customers)): ?>
						<tr><td colspan="7" class="text-center text-muted py-3">Belum ada data customer.</td></tr>
					<?php else: $no = 1; foreach ($customers as $c): ?>
						<tr>
							<td><?= $no++ ?></td>
							<td><?= htmlspecialchars($c['nama_perusahaan']) ?></td>
							<td><?= htmlspecialchars($c['kontak_person']) ?></td>
							<td><?= htmlspecialchars($c['no_hp']) ?></td>
							<td><?= htmlspecialchars($c['email']) ?></td>
							<td><?= htmlspecialchars($c['username']) ?></td>
							<td class="text-center">
								<a href="<?= site_url('customer/ubah/' . $c['id']) ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
								<a href="<?= site_url('customer/hapus/' . $c['id']) ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete" data-message="Hapus customer ini beserta akunnya?"><i class="fas fa-trash"></i></a>
							</td>
						</tr>
					<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
