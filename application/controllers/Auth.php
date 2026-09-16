<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
	}

	/** Halaman login */
	public function index()
	{
		if (is_logged_in()) {
			redirect('dashboard');
		}

		$data['title'] = 'Login';
		$this->load->view('auth/login', $data);
	}

	/** Proses verifikasi username & password */
	public function proses_login()
	{
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('login');
		}

		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$user = $this->User_model->get_by_username($username);

		if (!$user) {
			$this->session->set_flashdata('error', 'Username tidak ditemukan.');
			redirect('login');
		}

		if ($user['status'] !== 'aktif') {
			$this->session->set_flashdata('error', 'Akun anda tidak aktif. Hubungi administrator.');
			redirect('login');
		}

		if (!password_verify($password, $user['password'])) {
			$this->session->set_flashdata('error', 'Password salah.');
			redirect('login');
		}

		// Set session
		$session_data = array(
			'user_id'      => $user['id'],
			'username'     => $user['username'],
			'nama_lengkap' => $user['nama_lengkap'],
			'role'         => $user['role'],
		);
		$this->session->set_userdata($session_data);

		redirect('dashboard');
	}

	/** Logout */
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}
