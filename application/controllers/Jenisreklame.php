<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenisreklame extends Auth_Controller
{
	protected $allowed_roles = array('admin', 'accounting');

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Jenisreklame_model');
	}

	public function index()
	{
		$data['title'] = 'Jenis Reklame';
		$data['list']  = $this->Jenisreklame_model->get_all();
		$this->render('jenis_reklame/index', $data);
	}

	public function tambah()
	{
		if ($this->input->method() === 'post') {
			$this->_validate();

			$this->Jenisreklame_model->insert(array(
				'nama_jenis'  => $this->input->post('nama_jenis', true),
				'deskripsi'   => $this->input->post('deskripsi', true),
				'satuan'      => $this->input->post('satuan', true),
				'harga_dasar' => (float) $this->input->post('harga_dasar'),
				'status'      => 'aktif',
			));

			$this->session->set_flashdata('success', 'Jenis reklame berhasil ditambahkan.');
			redirect('jenisreklame');
		}

		$data['title'] = 'Tambah Jenis Reklame';
		$this->render('jenis_reklame/form', $data);
	}

	public function ubah($id)
	{
		$item = $this->Jenisreklame_model->get($id);
		if (!$item) {
			show_404();
		}

		if ($this->input->method() === 'post') {
			$this->_validate();

			$this->Jenisreklame_model->update($id, array(
				'nama_jenis'  => $this->input->post('nama_jenis', true),
				'deskripsi'   => $this->input->post('deskripsi', true),
				'satuan'      => $this->input->post('satuan', true),
				'harga_dasar' => (float) $this->input->post('harga_dasar'),
				'status'      => $this->input->post('status', true),
			));

			$this->session->set_flashdata('success', 'Jenis reklame berhasil diperbarui.');
			redirect('jenisreklame');
		}

		$data['title'] = 'Ubah Jenis Reklame';
		$data['item']  = $item;
		$this->render('jenis_reklame/form', $data);
	}

	public function hapus($id)
	{
		$this->Jenisreklame_model->delete($id);
		$this->session->set_flashdata('success', 'Jenis reklame berhasil dihapus.');
		redirect('jenisreklame');
	}

	private function _validate()
	{
		$this->form_validation->set_rules('nama_jenis', 'Nama Jenis', 'required|trim');
		$this->form_validation->set_rules('harga_dasar', 'Harga Dasar', 'required|numeric');

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect($this->uri->uri_string());
		}
	}
}
