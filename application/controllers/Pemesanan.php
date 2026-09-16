<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemesanan extends Auth_Controller
{
	// Semua role boleh masuk, pembatasan detail dilakukan per-aksi
	protected $allowed_roles = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Pemesanan_model');
		$this->load->model('Customer_model');
		$this->load->model('Jenisreklame_model');
		$this->load->model('Desain_model');
		$this->load->model('Spk_model');
		$this->load->model('Produksi_model');
	}

	// ================= LIST =================
	public function index()
	{
		$user   = current_user();
		$filter = array();

		if ($user['role'] === 'customer') {
			$customer = $this->Customer_model->get_by_user_id($user['id']);
			$filter['customer_id'] = $customer ? $customer['id'] : 0;
		} elseif ($user['role'] === 'desainer') {
			$filter['status_in'] = array('baru', 'proses_desain', 'menunggu_approval_customer', 'revisi_desain_customer',
				'menunggu_approval_direktur', 'revisi_desain_direktur');
		} elseif ($user['role'] === 'direktur') {
			$filter['status_in'] = array('menunggu_approval_direktur', 'disetujui_direktur');
		} elseif ($user['role'] === 'accounting') {
			$filter['status_in'] = array('disetujui_direktur', 'menunggu_approval_harga', 'harga_disetujui', 'spk_dibuat');
		} elseif ($user['role'] === 'kepala_produksi') {
			$filter['status_in'] = array('spk_dibuat', 'proses_produksi', 'selesai_produksi');
		}

		if ($this->input->get('status')) {
			$filter['status'] = $this->input->get('status');
			unset($filter['status_in']);
		}

		$data['title']      = 'Data Pemesanan';
		$data['pemesanan']  = $this->Pemesanan_model->get_all($filter);
		$this->render('pemesanan/index', $data);
	}

	/** Riwayat pemesanan khusus customer yang login */
	public function riwayat()
	{
		$user     = current_user();
		$customer = $this->Customer_model->get_by_user_id($user['id']);

		$data['title']     = 'Riwayat Pemesanan Saya';
		$data['pemesanan'] = $this->Pemesanan_model->get_all(array('customer_id' => $customer ? $customer['id'] : 0));
		$this->render('pemesanan/riwayat', $data);
	}

	// ================= TAMBAH (oleh Customer / Admin) =================
	public function tambah()
	{
		$user = current_user();
		if (!in_array($user['role'], array('customer', 'admin'))) {
			show_error('Anda tidak memiliki hak akses ke halaman ini.', 403);
		}

		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('jenis_reklame_id', 'Jenis Reklame', 'required');
			$this->form_validation->set_rules('ide_kebutuhan', 'Ide/Kebutuhan Desain', 'required|trim');
			$this->form_validation->set_rules('ukuran', 'Ukuran', 'required|trim');
			$this->form_validation->set_rules('lokasi_pemasangan', 'Lokasi Pemasangan', 'required|trim');

			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
				redirect('pemesanan/tambah');
			}

			if ($user['role'] === 'customer') {
				$customer = $this->Customer_model->get_by_user_id($user['id']);
				$customer_id = $customer['id'];
			} else {
				$customer_id = $this->input->post('customer_id');
			}

			$id = $this->Pemesanan_model->insert(array(
				'kode_pemesanan'    => $this->Pemesanan_model->generate_kode(),
				'customer_id'       => $customer_id,
				'jenis_reklame_id'  => $this->input->post('jenis_reklame_id'),
				'ide_kebutuhan'     => $this->input->post('ide_kebutuhan', true),
				'ukuran'            => $this->input->post('ukuran', true),
				'lokasi_pemasangan' => $this->input->post('lokasi_pemasangan', true),
				'catatan_customer'  => $this->input->post('catatan_customer', true),
				'status'            => 'baru',
				'tanggal_pesan'     => date('Y-m-d'),
			));

			$this->Pemesanan_model->log_status($id, 'baru', 'Pemesanan dibuat oleh customer.', $user['id']);

			$this->session->set_flashdata('success', 'Pemesanan berhasil dikirim. Tim desainer akan segera memproses.');
			redirect('pemesanan/detail/' . $id);
		}

		$data['title']         = 'Pemesanan Reklame Baru';
		$data['jenis_reklame'] = $this->Jenisreklame_model->get_all(true);
		if ($user['role'] === 'admin') {
			$data['customers'] = $this->Customer_model->get_all();
		}
		$this->render('pemesanan/form', $data);
	}

	// ================= DETAIL =================
	public function detail($id)
	{
		$pemesanan = $this->Pemesanan_model->get($id);
		if (!$pemesanan) {
			show_404();
		}

		$user = current_user();
		if ($user['role'] === 'customer') {
			$customer = $this->Customer_model->get_by_user_id($user['id']);
			if (!$customer || $customer['id'] != $pemesanan['customer_id']) {
				show_error('Anda tidak memiliki hak akses ke pemesanan ini.', 403);
			}
		}

		$data['title']      = 'Detail Pemesanan ' . $pemesanan['kode_pemesanan'];
		$data['pemesanan']  = $pemesanan;
		$data['desain']     = $this->Desain_model->get_by_pemesanan($id);
		$data['revisi']     = $this->Desain_model->get_revisi_by_pemesanan($id);
		$data['persetujuan']= $this->Desain_model->get_persetujuan_by_pemesanan($id);
		$data['spk']        = $this->Spk_model->get_by_pemesanan($id);
		$data['produksi']   = $this->Produksi_model->get_by_pemesanan($id);
		$data['status_log'] = $this->Pemesanan_model->get_status_log($id);

		$this->render('pemesanan/detail', $data);
	}

	// ================= UPLOAD DESAIN (Desainer) =================
	public function upload_desain($id)
	{
		$user = current_user();
		if ($user['role'] !== 'desainer' && $user['role'] !== 'admin') {
			show_error('Hanya desainer yang dapat mengunggah desain.', 403);
		}

		$pemesanan = $this->Pemesanan_model->get($id);
		if (!$pemesanan) {
			show_404();
		}

		if (!isset($_FILES['file_desain']) || $_FILES['file_desain']['error'] !== 0) {
			$this->session->set_flashdata('error', 'File desain wajib diunggah.');
			redirect('pemesanan/detail/' . $id);
		}

		$config['upload_path']   = './uploads/desain/';
		$config['allowed_types'] = 'jpg|jpeg|png|pdf|ai|cdr|psd';
		$config['max_size']      = 10240; // 10 MB
		$config['encrypt_name']  = TRUE;

		if (!is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0755, true);
		}

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('file_desain')) {
			$this->session->set_flashdata('error', $this->upload->display_errors('', ''));
			redirect('pemesanan/detail/' . $id);
		}

		$upload_data = $this->upload->data();
		$versi = $this->Desain_model->next_versi($id);

		$this->Desain_model->insert(array(
			'pemesanan_id' => $id,
			'versi'        => $versi,
			'file_desain'  => $upload_data['file_name'],
			'keterangan'   => $this->input->post('keterangan', true),
			'dibuat_oleh'  => $user['id'],
		));

		$this->Pemesanan_model->ubah_status($id, 'menunggu_approval_customer',
			'Desain versi ' . $versi . ' diunggah, menunggu persetujuan customer.', $user['id']);

		$this->session->set_flashdata('success', 'Desain berhasil diunggah dan dikirim ke customer untuk persetujuan.');
		redirect('pemesanan/detail/' . $id);
	}

	// ================= PERSETUJUAN / REVISI DESAIN CUSTOMER =================
	public function respon_desain_customer($id)
	{
		$user = current_user();
		if ($user['role'] !== 'customer') {
			show_error('Aksi ini hanya untuk customer.', 403);
		}

		$keputusan = $this->input->post('keputusan'); // setuju | revisi
		$catatan   = $this->input->post('catatan', true);
		$desain    = $this->Desain_model->get_latest($id);

		if ($keputusan === 'setuju') {
			$this->Desain_model->insert_persetujuan(array(
				'pemesanan_id' => $id,
				'desain_id'    => $desain ? $desain['id'] : null,
				'jenis'        => 'desain_customer',
				'status'       => 'setuju',
				'catatan'      => $catatan,
				'user_id'      => $user['id'],
			));
			$this->Pemesanan_model->ubah_status($id, 'disetujui_customer',
				'Desain disetujui oleh customer, menunggu review Direktur.', $user['id']);
			// otomatis lanjut ke antrian direktur
			$this->Pemesanan_model->ubah_status($id, 'menunggu_approval_direktur',
				'Diteruskan ke Direktur untuk persetujuan.', $user['id']);
			$this->session->set_flashdata('success', 'Desain disetujui dan diteruskan ke Direktur.');
		} else {
			$this->form_validation->set_rules('catatan', 'Catatan Revisi', 'required|trim');
			if ($this->form_validation->run() == FALSE || !$catatan) {
				$this->session->set_flashdata('error', 'Catatan revisi wajib diisi.');
				redirect('pemesanan/detail/' . $id);
			}
			$this->Desain_model->insert_revisi(array(
				'desain_id'      => $desain ? $desain['id'] : null,
				'pemesanan_id'   => $id,
				'diminta_oleh'   => 'customer',
				'user_id'        => $user['id'],
				'catatan_revisi' => $catatan,
			));
			$this->Pemesanan_model->ubah_status($id, 'revisi_desain_customer',
				'Customer meminta revisi desain.', $user['id']);
			$this->session->set_flashdata('success', 'Permintaan revisi dikirim ke desainer.');
		}

		redirect('pemesanan/detail/' . $id);
	}

	// ================= PERSETUJUAN / REVISI DESAIN DIREKTUR =================
	public function respon_desain_direktur($id)
	{
		$user = current_user();
		if ($user['role'] !== 'direktur') {
			show_error('Aksi ini hanya untuk Direktur.', 403);
		}

		$keputusan = $this->input->post('keputusan'); // setuju | revisi
		$catatan   = $this->input->post('catatan', true);
		$desain    = $this->Desain_model->get_latest($id);

		if ($keputusan === 'setuju') {
			$this->Desain_model->insert_persetujuan(array(
				'pemesanan_id' => $id,
				'desain_id'    => $desain ? $desain['id'] : null,
				'jenis'        => 'desain_direktur',
				'status'       => 'setuju',
				'catatan'      => $catatan,
				'user_id'      => $user['id'],
			));
			$this->Pemesanan_model->ubah_status($id, 'menunggu_approval_harga',
				'Desain disetujui Direktur, menunggu persetujuan harga dari customer.', $user['id']);
			$this->session->set_flashdata('success', 'Desain disetujui. Menunggu persetujuan harga dari customer.');
		} else {
			$this->form_validation->set_rules('catatan', 'Catatan Revisi', 'required|trim');
			if ($this->form_validation->run() == FALSE || !$catatan) {
				$this->session->set_flashdata('error', 'Catatan revisi wajib diisi.');
				redirect('pemesanan/detail/' . $id);
			}
			$this->Desain_model->insert_revisi(array(
				'desain_id'      => $desain ? $desain['id'] : null,
				'pemesanan_id'   => $id,
				'diminta_oleh'   => 'direktur',
				'user_id'        => $user['id'],
				'catatan_revisi' => $catatan,
			));
			$this->Pemesanan_model->ubah_status($id, 'revisi_desain_direktur',
				'Direktur meminta revisi desain.', $user['id']);
			$this->session->set_flashdata('success', 'Permintaan revisi dikirim ke desainer.');
		}

		redirect('pemesanan/detail/' . $id);
	}

	// ================= TAWAR HARGA (Accounting) =================
	public function tawar_harga($id)
	{
		$user = current_user();
		if (!in_array($user['role'], array('accounting', 'admin'))) {
			show_error('Aksi ini hanya untuk Accounting.', 403);
		}

		$this->form_validation->set_rules('harga_ditawarkan', 'Harga', 'required|numeric');
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', 'Harga wajib diisi dengan angka.');
			redirect('pemesanan/detail/' . $id);
		}

		$this->Pemesanan_model->update($id, array(
			'harga_ditawarkan' => (float) $this->input->post('harga_ditawarkan'),
		));
		$this->Pemesanan_model->log_status($id, 'menunggu_approval_harga', 'Penawaran harga dikirim ke customer.', $user['id']);

		$this->session->set_flashdata('success', 'Penawaran harga terkirim ke customer.');
		redirect('pemesanan/detail/' . $id);
	}

	// ================= PERSETUJUAN HARGA (Customer) =================
	public function setuju_harga($id)
	{
		$user = current_user();
		if ($user['role'] !== 'customer') {
			show_error('Aksi ini hanya untuk customer.', 403);
		}

		$pemesanan = $this->Pemesanan_model->get($id);

		$this->Desain_model->insert_persetujuan(array(
			'pemesanan_id' => $id,
			'jenis'        => 'harga_customer',
			'status'       => 'setuju',
			'catatan'      => 'Harga disetujui: Rp ' . number_format($pemesanan['harga_ditawarkan'], 0, ',', '.'),
			'user_id'      => $user['id'],
		));

		$this->Pemesanan_model->update($id, array('harga_disetujui' => $pemesanan['harga_ditawarkan']));
		$this->Pemesanan_model->ubah_status($id, 'harga_disetujui',
			'Harga disetujui oleh customer.', $user['id']);

		$this->session->set_flashdata('success', 'Harga disetujui. Menunggu penerbitan SPK oleh Accounting.');
		redirect('pemesanan/detail/' . $id);
	}

	// ================= BUAT SPK (Accounting) =================
	public function buat_spk($id)
	{
		$user = current_user();
		if (!in_array($user['role'], array('accounting', 'admin'))) {
			show_error('Aksi ini hanya untuk Accounting.', 403);
		}

		$pemesanan = $this->Pemesanan_model->get($id);

		$spk_id = $this->Spk_model->insert(array(
			'pemesanan_id' => $id,
			'no_spk'       => $this->Spk_model->generate_no_spk(),
			'isi_spk'      => $this->input->post('isi_spk', true),
			'dibuat_oleh'  => $user['id'],
			'tanggal_spk'  => date('Y-m-d'),
		));

		// Buat entri produksi awal, diteruskan ke Kepala Produksi
		$this->Produksi_model->insert(array(
			'pemesanan_id' => $id,
			'spk_id'       => $spk_id,
			'status'       => 'belum_mulai',
		));

		$this->Pemesanan_model->ubah_status($id, 'spk_dibuat',
			'SPK diterbitkan dan diteruskan ke Kepala Produksi.', $user['id']);

		$this->session->set_flashdata('success', 'SPK berhasil dibuat dan diteruskan ke Kepala Produksi.');
		redirect('pemesanan/detail/' . $id);
	}

	// ================= UPDATE PRODUKSI (Kepala Produksi) =================
	public function update_produksi($id)
	{
		$user = current_user();
		if (!in_array($user['role'], array('kepala_produksi', 'admin'))) {
			show_error('Aksi ini hanya untuk Kepala Produksi.', 403);
		}

		$produksi = $this->Produksi_model->get_by_pemesanan($id);
		if (!$produksi) {
			show_404();
		}

		$status_produksi = $this->input->post('status_produksi'); // proses | selesai
		$catatan         = $this->input->post('catatan_produksi', true);

		$update = array(
			'status'           => $status_produksi,
			'catatan_produksi' => $catatan,
			'updated_by'       => $user['id'],
		);

		if ($status_produksi === 'proses' && empty($produksi['tanggal_mulai'])) {
			$update['tanggal_mulai'] = date('Y-m-d');
		}
		if ($status_produksi === 'selesai') {
			$update['tanggal_selesai'] = date('Y-m-d');
		}

		$this->Produksi_model->update($produksi['id'], $update);

		$status_pemesanan = ($status_produksi === 'selesai') ? 'selesai_produksi' : 'proses_produksi';
		$this->Pemesanan_model->ubah_status($id, $status_pemesanan, 'Status produksi diperbarui: ' . $status_produksi, $user['id']);

		$this->session->set_flashdata('success', 'Status produksi berhasil diperbarui.');
		redirect('pemesanan/detail/' . $id);
	}

	// ================= SELESAIKAN PEMESANAN =================
	public function selesaikan($id)
	{
		$user = current_user();
		if (!in_array($user['role'], array('admin', 'accounting', 'kepala_produksi'))) {
			show_error('Anda tidak memiliki hak akses.', 403);
		}

		$this->Pemesanan_model->ubah_status($id, 'selesai', 'Pemesanan dinyatakan selesai.', $user['id']);
		$this->session->set_flashdata('success', 'Pemesanan telah diselesaikan.');
		redirect('pemesanan/detail/' . $id);
	}

	public function hapus($id)
	{
		if (current_role() !== 'admin') {
			show_error('Anda tidak memiliki hak akses.', 403);
		}
		$this->Pemesanan_model->delete($id);
		$this->session->set_flashdata('success', 'Pemesanan berhasil dihapus.');
		redirect('pemesanan');
	}
}
