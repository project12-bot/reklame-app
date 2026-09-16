<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends Auth_Controller
{
	protected $allowed_roles = array('admin', 'accounting');

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Customer_model');
		$this->load->model('User_model');
	}

	public function index()
	{
		$data['title']     = 'Kelola Customer';
		$data['customers'] = $this->Customer_model->get_all();
		$this->render('customer/index', $data);
	}

	public function tambah()
	{
		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('username', 'Username', 'required|trim');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
			$this->form_validation->set_rules('nama_perusahaan', 'Nama Perusahaan', 'required|trim');

			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
				redirect('customer/tambah');
			}

			if ($this->User_model->username_exists($this->input->post('username'))) {
				$this->session->set_flashdata('error', 'Username sudah digunakan.');
				redirect('customer/tambah');
			}

			// Buat akun user role customer
			$user_id = $this->User_model->insert(array(
				'username'     => $this->input->post('username', true),
				'password'     => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
				'nama_lengkap' => $this->input->post('nama_perusahaan', true),
				'email'        => $this->input->post('email', true),
				'no_hp'        => $this->input->post('no_hp', true),
				'role'         => 'customer',
				'status'       => 'aktif',
			));

			// Buat data detail customer
			$this->Customer_model->insert(array(
				'user_id'         => $user_id,
				'nama_perusahaan' => $this->input->post('nama_perusahaan', true),
				'alamat'          => $this->input->post('alamat', true),
				'no_hp'           => $this->input->post('no_hp', true),
				'email'           => $this->input->post('email', true),
				'kontak_person'   => $this->input->post('kontak_person', true),
			));

			$this->session->set_flashdata('success', 'Customer baru berhasil ditambahkan.');
			redirect('customer');
		}

		$data['title'] = 'Tambah Customer';
		$this->render('customer/form', $data);
	}

	public function ubah($id)
	{
		$customer = $this->Customer_model->get($id);
		if (!$customer) {
			show_404();
		}

		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('nama_perusahaan', 'Nama Perusahaan', 'required|trim');
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
				redirect('customer/ubah/' . $id);
			}

			$this->Customer_model->update($id, array(
				'nama_perusahaan' => $this->input->post('nama_perusahaan', true),
				'alamat'          => $this->input->post('alamat', true),
				'no_hp'           => $this->input->post('no_hp', true),
				'email'           => $this->input->post('email', true),
				'kontak_person'   => $this->input->post('kontak_person', true),
			));

			$this->session->set_flashdata('success', 'Data customer berhasil diperbarui.');
			redirect('customer');
		}

		$data['title']    = 'Ubah Customer';
		$data['customer'] = $customer;
		$this->render('customer/form', $data);
	}

	public function hapus($id)
	{
		$customer = $this->Customer_model->get($id);
		if ($customer) {
			$this->User_model->delete($customer['user_id']); // cascade menghapus data customer juga
		}
		$this->session->set_flashdata('success', 'Customer berhasil dihapus.');
		redirect('customer');
	}
}
