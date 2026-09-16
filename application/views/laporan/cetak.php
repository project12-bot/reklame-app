<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>Laporan Pemesanan Reklame</title>
	<style>
		body { font-family: Arial, sans-serif; font-size: 13px; color: #222; margin: 30px; }
		h2 { margin-bottom: 0; }
		.sub { color: #666; margin-bottom: 20px; }
		table { width: 100%; border-collapse: collapse; margin-top: 15px; }
		th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
		th { background-color: #f0f0f0; }
		.text-right { text-align: right; }
		.footer-print { margin-top: 30px; font-size: 12px; color: #666; }
		@media print {
			.no-print { display: none; }
		}
	</style>
</head>
<body onload="window.print()">
	<div class="no-print" style="text-align:right; margin-bottom:10px;">
		<button onclick="window.print()">Cetak / Simpan PDF</button>
	</div>

	<h2>Laporan Pemesanan Reklame</h2>
	<div class="sub">
		Periode: <?= $tanggal_dari ? date('d-m-Y', strtotime($tanggal_dari)) : 'Semua' ?>
		s/d <?= $tanggal_sampai ? date('d-m-Y', strtotime($tanggal_sampai)) : 'Semua' ?>
	</div>

	<table>
		<thead>
			<tr>
				<th>No</th>
				<th>Kode Pemesanan</th>
				<th>Customer</th>
				<th>Jenis Reklame</th>
				<th>Lokasi</th>
				<th>Status</th>
				<th>Tanggal Pesan</th>
				<th class="text-right">Harga Disetujui</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($pemesanan)): ?>
				<tr><td colspan="8" style="text-align:center;">Tidak ada data.</td></tr>
			<?php else: $no = 1; $total = 0; foreach ($pemesanan as $p): $total += (float) $p['harga_disetujui']; ?>
				<tr>
					<td><?= $no++ ?></td>
					<td><?= htmlspecialchars($p['kode_pemesanan']) ?></td>
					<td><?= htmlspecialchars($p['nama_perusahaan']) ?></td>
					<td><?= htmlspecialchars($p['nama_jenis']) ?></td>
					<td><?= htmlspecialchars($p['lokasi_pemasangan']) ?></td>
					<td><?= status_label($p['status']) ?></td>
					<td><?= date('d-m-Y', strtotime($p['tanggal_pesan'])) ?></td>
					<td class="text-right"><?= $p['harga_disetujui'] ? number_format($p['harga_disetujui'], 0, ',', '.') : '-' ?></td>
				</tr>
			<?php endforeach; ?>
				<tr>
					<td colspan="7" class="text-right"><strong>Total</strong></td>
					<td class="text-right"><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>

	<div class="footer-print">
		Dicetak oleh: <?= htmlspecialchars($dicetak_oleh) ?> pada <?= $tanggal_cetak ?>
	</div>
</body>
</html>
