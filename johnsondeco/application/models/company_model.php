<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Company_model extends CI_Model{
	// 修改公司简介
	public function upload_name($data){
		return $this->db->update_batch('company', $data, 'name');
	}

	public function check_name($name){
		return $this->db->where('name', $name)->get('company')->result_array();
	}
}