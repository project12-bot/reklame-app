<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Desain_model extends CI_Model
{
	public function get_by_pemesanan($pemesanan_id)
	{
		return $this->db->select('desain.*, users.nama_lengkap AS nama_desainer')
			->from('desain')
			->join('users', 'users.id = desain.dibuat_oleh')
			->where('pemesanan_id', $pemesanan_id)
			->order_by('desain.versi', 'ASC')
			->get()->result_array();
	}

	public function get_latest($pemesanan_id)
	{
		return $this->db->where('pemesanan_id', $pemesanan_id)
			->order_by('versi', 'DESC')
			->limit(1)
			->get('desain')->row_array();
	}

	public function get($id)
	{
		return $this->db->get_where('desain', array('id' => $id))->row_array();
	}

	public function insert($data)
	{
		$this->db->insert('desain', $data);
		return $this->db->insert_id();
	}

	public function next_versi($pemesanan_id)
	{
		$latest = $this->get_latest($pemesanan_id);
		return $latest ? ((int) $latest['versi'] + 1) : 1;
	}

	// ---- Revisi Desain ----
	public function insert_revisi($data)
	{
		$this->db->insert('revisi_desain', $data);
		return $this->db->insert_id();
	}

	public function get_revisi_by_pemesanan($pemesanan_id)
	{
		return $this->db->select('revisi_desain.*, users.nama_lengkap')
			->from('revisi_desain')
			->join('users', 'users.id = revisi_desain.user_id')
			->where('revisi_desain.pemesanan_id', $pemesanan_id)
			->order_by('revisi_desain.created_at', 'DESC')
			->get()->result_array();
	}

	// ---- Persetujuan ----
	public function insert_persetujuan($data)
	{
		$this->db->insert('persetujuan', $data);
		return $this->db->insert_id();
	}

	public function get_persetujuan_by_pemesanan($pemesanan_id)
	{
		return $this->db->select('persetujuan.*, users.nama_lengkap')
			->from('persetujuan')
			->join('users', 'users.id = persetujuan.user_id')
			->where('persetujuan.pemesanan_id', $pemesanan_id)
			->order_by('persetujuan.created_at', 'DESC')
			->get()->result_array();
	}
}
