<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_model extends CI_Model{
	// 增加新的文章
	public function add_staff($data){
		return $this->db->insert('staff', $data);
	}
	// 获取所有的产品
	public function check_all(){
		return $this->db->order_by('sid','desc')->get('staff')->result_array();
	}
	// 计算文章总数
	public function count_all(){
		return $this->db->count_all_results('staff');
	}

	// 分页
	public function check_page($num, $offset){
		return $this->db->limit($num, $offset)->order_by('sid','desc')->get('staff')->result_array();
	}
	// 通过sid删除文章
	public function del_staff($sid){
		return $this->db->delete('staff', array('sid'=>$sid));
	}
	/*
	*@param string $sid 文章序号
	*/
	public function check_sid($sid){
		return $this->db->where('sid',$sid)->get('staff')->result_array();
	}
	// 更新文章内容
	public function upload_staff($sid,$data){
		// var_dump($data);
		return $this->db->where('sid', $sid)->update('staff', $data);
	}
}