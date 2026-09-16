<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
	protected $table = 'users';

	public function get_by_username($username)
	{
		return $this->db->get_where($this->table, array('username' => $username))->row_array();
	}

	public function get_all($role = null)
	{
		if ($role) {
			$this->db->where('role', $role);
		}
		return $this->db->order_by('nama_lengkap', 'ASC')->get($this->table)->result_array();
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

	public function username_exists($username, $except_id = null)
	{
		$this->db->where('username', $username);
		if ($except_id) {
			$this->db->where('id !=', $except_id);
		}
		return $this->db->get($this->table)->num_rows() > 0;
	}
}
