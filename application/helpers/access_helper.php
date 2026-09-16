<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Access Helper
 * Kumpulan fungsi bantu untuk autentikasi dan otorisasi (role) pengguna.
 */

if (!function_exists('is_logged_in')) {
	function is_logged_in()
	{
		$ci =& get_instance();
		return (bool) $ci->session->userdata('user_id');
	}
}

if (!function_exists('current_user')) {
	function current_user()
	{
		$ci =& get_instance();
		return array(
			'id'       => $ci->session->userdata('user_id'),
			'username' => $ci->session->userdata('username'),
			'nama'     => $ci->session->userdata('nama_lengkap'),
			'role'     => $ci->session->userdata('role'),
		);
	}
}

if (!function_exists('current_role')) {
	function current_role()
	{
		$ci =& get_instance();
		return $ci->session->userdata('role');
	}
}

if (!function_exists('role_label')) {
	function role_label($role)
	{
		$labels = array(
			'admin'            => 'Administrator',
			'customer'         => 'Customer',
			'desainer'         => 'Desainer',
			'direktur'         => 'Direktur',
			'accounting'       => 'Accounting',
			'kepala_produksi'  => 'Kepala Produksi',
		);
		return isset($labels[$role]) ? $labels[$role] : $role;
	}
}

if (!function_exists('status_label')) {
	function status_label($status)
	{
		$labels = array(
			'baru'                        => 'Baru',
			'proses_desain'               => 'Proses Desain',
			'menunggu_approval_customer'  => 'Menunggu Persetujuan Customer',
			'revisi_desain_customer'      => 'Revisi Desain (Customer)',
			'disetujui_customer'          => 'Disetujui Customer',
			'menunggu_approval_direktur'  => 'Menunggu Persetujuan Direktur',
			'revisi_desain_direktur'      => 'Revisi Desain (Direktur)',
			'disetujui_direktur'          => 'Disetujui Direktur',
			'menunggu_approval_harga'     => 'Menunggu Persetujuan Harga',
			'harga_disetujui'             => 'Harga Disetujui',
			'spk_dibuat'                  => 'SPK Diterbitkan',
			'proses_produksi'             => 'Proses Produksi',
			'selesai_produksi'            => 'Produksi Selesai',
			'selesai'                     => 'Selesai',
			'dibatalkan'                  => 'Dibatalkan',
		);
		return isset($labels[$status]) ? $labels[$status] : $status;
	}
}

if (!function_exists('status_badge_class')) {
	function status_badge_class($status)
	{
		$map = array(
			'baru'                        => 'secondary',
			'proses_desain'               => 'info',
			'menunggu_approval_customer'  => 'warning',
			'revisi_desain_customer'      => 'danger',
			'disetujui_customer'          => 'primary',
			'menunggu_approval_direktur'  => 'warning',
			'revisi_desain_direktur'      => 'danger',
			'disetujui_direktur'          => 'primary',
			'menunggu_approval_harga'     => 'warning',
			'harga_disetujui'             => 'primary',
			'spk_dibuat'                  => 'info',
			'proses_produksi'             => 'info',
			'selesai_produksi'            => 'success',
			'selesai'                     => 'success',
			'dibatalkan'                  => 'dark',
		);
		return isset($map[$status]) ? $map[$status] : 'secondary';
	}
}
