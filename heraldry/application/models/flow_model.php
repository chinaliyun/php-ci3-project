<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Flow_model extends CI_Model{
	function check_serial($serial){
		return $this->db->where('serial', $serial)->get('flow')->result_array();
	}
	function add_flow($data){
		return $this->db->insert('flow', $data);
	}
	function update_serial($serial,$data){
		return $this->db->where('serial', $serial)->update('flow', $data);
	}
	function check_flow(){
		return $this->db->limit(1,0)->get('flow')->result_array();
	}
}