<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Controller (Auth_Controller)
 *
 * Semua controller yang membutuhkan login harus extend class ini.
 * Gunakan $this->allowed_roles di constructor controller turunan
 * untuk membatasi role yang boleh mengakses.
 */
class Auth_Controller extends CI_Controller
{
	/** @var array Role yang diizinkan mengakses controller ini. Kosong = semua role yang login boleh akses. */
	protected $allowed_roles = array();

	public function __construct()
	{
		parent::__construct();

		// Wajib login
		if (!is_logged_in()) {
			redirect('login');
		}

		// Cek hak akses berdasarkan role jika didefinisikan
		if (!empty($this->allowed_roles) && !in_array(current_role(), $this->allowed_roles)) {
			show_error('Anda tidak memiliki hak akses ke halaman ini.', 403, 'Akses Ditolak');
		}
	}

	/**
	 * Render halaman dengan layout (header, sidebar, footer) standar.
	 */
	protected function render($view, $data = array())
	{
		$data['logged_user'] = current_user();
		$this->load->view('template/header', $data);
		$this->load->view('template/sidebar', $data);
		$this->load->view($view, $data);
		$this->load->view('template/footer', $data);
	}
}
