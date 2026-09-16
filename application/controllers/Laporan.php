<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends Auth_Controller
{
	protected $allowed_roles = array('admin', 'direktur', 'accounting');

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Pemesanan_model');
	}

	public function index()
	{
		$filter = array();

		$dari    = $this->input->get('tanggal_dari');
		$sampai  = $this->input->get('tanggal_sampai');
		$status  = $this->input->get('status');

		if ($dari) { $filter['tanggal_dari'] = $dari; }
		if ($sampai) { $filter['tanggal_sampai'] = $sampai; }
		if ($status) { $filter['status'] = $status; }

		$data['title']        = 'Laporan Pemesanan Reklame';
		$data['pemesanan']    = $this->Pemesanan_model->get_all($filter);
		$data['rekap_status'] = $this->Pemesanan_model->rekap_status();
		$data['tanggal_dari'] = $dari;
		$data['tanggal_sampai'] = $sampai;
		$data['status_filter']  = $status;

		// Total nilai transaksi (dari pemesanan yang harga sudah disetujui)
		$total = 0;
		foreach ($data['pemesanan'] as $p) {
			if (!empty($p['harga_disetujui'])) {
				$total += $p['harga_disetujui'];
			}
		}
		$data['total_nilai'] = $total;

		$this->render('laporan/index', $data);
	}

	/** Cetak versi print-friendly (dibuka di tab baru, tanpa sidebar) */
	public function cetak()
	{
		$filter = array();
		$dari    = $this->input->get('tanggal_dari');
		$sampai  = $this->input->get('tanggal_sampai');
		$status  = $this->input->get('status');

		if ($dari) { $filter['tanggal_dari'] = $dari; }
		if ($sampai) { $filter['tanggal_sampai'] = $sampai; }
		if ($status) { $filter['status'] = $status; }

		$data['pemesanan']      = $this->Pemesanan_model->get_all($filter);
		$data['tanggal_dari']   = $dari;
		$data['tanggal_sampai'] = $sampai;
		$data['dicetak_oleh']   = current_user()['nama'];
		$data['tanggal_cetak']  = date('d-m-Y H:i');

		$this->load->view('laporan/cetak', $data);
	}
}
