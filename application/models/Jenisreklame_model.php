<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenisreklame_model extends CI_Model
{
	protected $table = 'jenis_reklame';

	public function get_all($only_active = false)
	{
		if ($only_active) {
			$this->db->where('status', 'aktif');
		}
		return $this->db->order_by('nama_jenis', 'ASC')->get($this->table)->result_array();
	}

	public function get($id)
	{
		return $this->db->get_where($this->table, array('id' => $id))->row_array();
	}

	public function insert($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table, $data);
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}
}
