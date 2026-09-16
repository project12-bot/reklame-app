<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model
{
	protected $table = 'customers';

	public function get_all()
	{
		return $this->db->select('customers.*, users.username, users.status AS status_akun')
			->from($this->table)
			->join('users', 'users.id = customers.user_id')
			->order_by('customers.nama_perusahaan', 'ASC')
			->get()->result_array();
	}

	public function get($id)
	{
		return $this->db->select('customers.*, users.username, users.status AS status_akun')
			->from($this->table)
			->join('users', 'users.id = customers.user_id')
			->where('customers.id', $id)
			->get()->row_array();
	}

	public function get_by_user_id($user_id)
	{
		return $this->db->get_where($this->table, array('user_id' => $user_id))->row_array();
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
