<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Image_model extends CI_Model{
	public function add_image($data){
		return $this->db->insert('images', $data);
	}

	public function check_all(){
		return $this->db->get('images')->result_array();
	}

	public function check_serial($serial){
		return $this->db->get_where('images',array('serial'=>$serial));
	}
	public function del_id($id){
		return $this->db->delete('images',array('id'=>$id));
	}
	public function del_serial($serial){
		return $this->db->delete('images',array('serial'=>$serial));
	}
	public function check_page($num,$offset){
		return $this->db->limit($num, $offset)->order_by('time','desc')->get('images')->result_array();
	}
	public function count_all(){
		return $this->db->get('images')->result_array();
		
		// return $count;
	}

}