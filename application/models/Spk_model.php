<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spk_model extends CI_Model
{
	protected $table = 'spk';

	public function get_by_pemesanan($pemesanan_id)
	{
		return $this->db->get_where($this->table, array('pemesanan_id' => $pemesanan_id))->row_array();
	}

	public function get($id)
	{
		return $this->db->select('spk.*, pemesanan.kode_pemesanan, pemesanan.lokasi_pemasangan, pemesanan.ukuran,
				customers.nama_perusahaan, jenis_reklame.nama_jenis')
			->from($this->table)
			->join('pemesanan', 'pemesanan.id = spk.pemesanan_id')
			->join('customers', 'customers.id = pemesanan.customer_id')
			->join('jenis_reklame', 'jenis_reklame.id = pemesanan.jenis_reklame_id')
			->where('spk.id', $id)
			->get()->row_array();
	}

	public function insert($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function generate_no_spk()
	{
		$prefix = 'SPK-' . date('Ym') . '-';
		$this->db->select('no_spk')
			->from($this->table)
			->like('no_spk', $prefix, 'after')
			->order_by('id', 'DESC')
			->limit(1);
		$row = $this->db->get()->row_array();
		$next = 1;
		if ($row) {
			$last = (int) substr($row['no_spk'], -4);
			$next = $last + 1;
		}
		return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
	}
}
