<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Auth_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Pemesanan_model');
		$this->load->model('Customer_model');
	}

	public function index()
	{
		$user = current_user();
		$data['title'] = 'Dashboard';

		$filter = array();

		// Batasi data sesuai role
		if ($user['role'] === 'customer') {
			$customer = $this->Customer_model->get_by_user_id($user['id']);
			$filter['customer_id'] = $customer ? $customer['id'] : 0;
		}

		$data['total_pemesanan'] = count($this->Pemesanan_model->get_all($filter));
		$data['rekap_status']    = $this->Pemesanan_model->rekap_status();

		// Data terbaru sesuai konteks role
		switch ($user['role']) {
			case 'desainer':
				$filter['status_in'] = array('baru', 'proses_desain', 'revisi_desain_customer', 'revisi_desain_direktur');
				break;
			case 'direktur':
				$filter['status_in'] = array('menunggu_approval_direktur');
				break;
			case 'accounting':
				$filter['status_in'] = array('menunggu_approval_harga', 'harga_disetujui');
				break;
			case 'kepala_produksi':
				$filter['status_in'] = array('spk_dibuat', 'proses_produksi');
				break;
		}

		$data['daftar_pemesanan'] = $this->Pemesanan_model->get_all($filter);

		$this->render('dashboard/index', $data);
	}
}
