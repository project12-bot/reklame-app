<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Auth_Controller
{
	protected $allowed_roles = array('admin');

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
	}

	public function index()
	{
		$data['title'] = 'Kelola Pengguna';
		$data['users'] = $this->User_model->get_all();
		$this->render('user/index', $data);
	}

	public function tambah()
	{
		if ($this->input->method() === 'post') {
			$this->_validate();

			if ($this->User_model->username_exists($this->input->post('username'))) {
				$this->session->set_flashdata('error', 'Username sudah digunakan.');
				redirect('user/tambah');
			}

			$this->User_model->insert(array(
				'username'     => $this->input->post('username', true),
				'password'     => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
				'nama_lengkap' => $this->input->post('nama_lengkap', true),
				'email'        => $this->input->post('email', true),
				'no_hp'        => $this->input->post('no_hp', true),
				'role'         => $this->input->post('role', true),
				'status'       => 'aktif',
			));

			$this->session->set_flashdata('success', 'Pengguna baru berhasil ditambahkan.');
			redirect('user');
		}

		$data['title'] = 'Tambah Pengguna';
		$this->render('user/form', $data);
	}

	public function ubah($id)
	{
		$user = $this->User_model->get($id);
		if (!$user) {
			show_404();
		}

		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('username', 'Username', 'required|trim');
			$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
			$this->form_validation->set_rules('role', 'Role', 'required');

			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
				redirect('user/ubah/' . $id);
			}

			if ($this->User_model->username_exists($this->input->post('username'), $id)) {
				$this->session->set_flashdata('error', 'Username sudah digunakan.');
				redirect('user/ubah/' . $id);
			}

			$update = array(
				'username'     => $this->input->post('username', true),
				'nama_lengkap' => $this->input->post('nama_lengkap', true),
				'email'        => $this->input->post('email', true),
				'no_hp'        => $this->input->post('no_hp', true),
				'role'         => $this->input->post('role', true),
				'status'       => $this->input->post('status', true),
				'updated_at'   => date('Y-m-d H:i:s'),
			);

			if ($this->input->post('password')) {
				$update['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
			}

			$this->User_model->update($id, $update);
			$this->session->set_flashdata('success', 'Data pengguna berhasil diperbarui.');
			redirect('user');
		}

		$data['title'] = 'Ubah Pengguna';
		$data['user']  = $user;
		$this->render('user/form', $data);
	}

	public function hapus($id)
	{
		if ((int) $id === (int) current_user()['id']) {
			$this->session->set_flashdata('error', 'Tidak dapat menghapus akun sendiri.');
			redirect('user');
		}
		$this->User_model->delete($id);
		$this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
		redirect('user');
	}

	private function _validate()
	{
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
		$this->form_validation->set_rules('role', 'Role', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('user/tambah');
		}
	}
}
