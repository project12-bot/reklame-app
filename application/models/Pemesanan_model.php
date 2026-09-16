<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemesanan_model extends CI_Model
{
	protected $table = 'pemesanan';

	/** Daftar list dengan join untuk ditampilkan di tabel */
	protected function base_select()
	{
		return $this->db->select('pemesanan.*, customers.nama_perusahaan, jenis_reklame.nama_jenis')
			->from($this->table)
			->join('customers', 'customers.id = pemesanan.customer_id')
			->join('jenis_reklame', 'jenis_reklame.id = pemesanan.jenis_reklame_id');
	}

	public function get_all($filter = array())
	{
		$this->base_select();

		if (!empty($filter['customer_id'])) {
			$this->db->where('pemesanan.customer_id', $filter['customer_id']);
		}
		if (!empty($filter['status'])) {
			$this->db->where('pemesanan.status', $filter['status']);
		}
		if (!empty($filter['status_in']) && is_array($filter['status_in'])) {
			$this->db->where_in('pemesanan.status', $filter['status_in']);
		}
		if (!empty($filter['tanggal_dari'])) {
			$this->db->where('pemesanan.tanggal_pesan >=', $filter['tanggal_dari']);
		}
		if (!empty($filter['tanggal_sampai'])) {
			$this->db->where('pemesanan.tanggal_pesan <=', $filter['tanggal_sampai']);
		}

		return $this->db->order_by('pemesanan.id', 'DESC')->get()->result_array();
	}

	public function get($id)
	{
		$this->base_select();
		return $this->db->where('pemesanan.id', $id)->get()->row_array();
	}

	public function get_by_kode($kode)
	{
		$this->base_select();
		return $this->db->where('pemesanan.kode_pemesanan', $kode)->get()->row_array();
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

	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}

	public function generate_kode()
	{
		$prefix = 'PSN-' . date('Ym') . '-';
		$this->db->select('kode_pemesanan')
			->from($this->table)
			->like('kode_pemesanan', $prefix, 'after')
			->order_by('id', 'DESC')
			->limit(1);
		$row = $this->db->get()->row_array();
		$next = 1;
		if ($row) {
			$last = (int) substr($row['kode_pemesanan'], -4);
			$next = $last + 1;
		}
		return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
	}

	/** Simpan riwayat perubahan status ke status_log (untuk tracking & laporan) */
	public function log_status($pemesanan_id, $status, $keterangan = '', $user_id = null)
	{
		$this->db->insert('status_log', array(
			'pemesanan_id' => $pemesanan_id,
			'status'       => $status,
			'keterangan'   => $keterangan,
			'created_by'   => $user_id,
		));
	}

	public function get_status_log($pemesanan_id)
	{
		return $this->db->select('status_log.*, users.nama_lengkap')
			->from('status_log')
			->join('users', 'users.id = status_log.created_by', 'left')
			->where('pemesanan_id', $pemesanan_id)
			->order_by('status_log.created_at', 'ASC')
			->get()->result_array();
	}

	/** Ubah status pemesanan sekaligus mencatat log riwayat */
	public function ubah_status($id, $status, $keterangan = '', $user_id = null)
	{
		$this->update($id, array('status' => $status));
		$this->log_status($id, $status, $keterangan, $user_id);
	}

	/** Untuk laporan: rekap jumlah pemesanan per status */
	public function rekap_status()
	{
		return $this->db->select('status, COUNT(*) as jumlah')
			->from($this->table)
			->group_by('status')
			->get()->result_array();
	}

	public function count_by_status($status)
	{
		return $this->db->where('status', $status)->count_all_results($this->table);
	}
}
