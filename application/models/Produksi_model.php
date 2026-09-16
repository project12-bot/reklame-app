<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produksi_model extends CI_Model
{
	protected $table = 'produksi';

	public function get_by_pemesanan($pemesanan_id)
	{
		return $this->db->get_where($this->table, array('pemesanan_id' => $pemesanan_id))->row_array();
	}

	public function get_all_active()
	{
		return $this->db->select('produksi.*, pemesanan.kode_pemesanan, pemesanan.lokasi_pemasangan,
				customers.nama_perusahaan, jenis_reklame.nama_jenis')
			->from($this->table)
			->join('pemesanan', 'pemesanan.id = produksi.pemesanan_id')
			->join('customers', 'customers.id = pemesanan.customer_id')
			->join('jenis_reklame', 'jenis_reklame.id = pemesanan.jenis_reklame_id')
			->where_in('produksi.status', array('belum_mulai', 'proses'))
			->order_by('produksi.id', 'DESC')
			->get()->result_array();
	}

	public function insert($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', $id);
		return $this->db->update($this->table, $data);
	}
}
