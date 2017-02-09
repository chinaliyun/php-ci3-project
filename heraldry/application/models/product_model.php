<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product_model extends CI_Model{
	// 增加新的文章
	public function add_product($data){
		return $this->db->insert('product', $data);
	}
	// 获取所有的产品
	public function check_all(){
		return $this->db->order_by('pid','desc')->get('product')->result_array();
	}
	// 计算文章总数
	public function count_all(){
		return $this->db->count_all_results('product');
	}

	// 分页
	public function check_page($num, $offset){
		return $this->db->limit($num, $offset)->order_by('pid','asc')->get('product')->result_array();
	}
	public function check_category($category){
		return $this->db->where('category', $category)->order_by('time','desc')->get('product')->result_array();
	}
	public function check_serial($serial){
		return $this->db->where('serial',$serial)->get('product')->result_array();
	}
	// 分类
	public function check_page_category($category, $num, $offset){
		return $this->db->where('category', $category)->limit($num, $offset)->order_by('pid','asc')->get('product')->result_array();
	}
	// 通过pid删除文章
	public function del_product($pid){
		return $this->db->delete('product', array('pid'=>$pid));
	}
	/*
	*@param string $pid 文章序号
	*/
	public function check_pid($pid){
		return $this->db->where('pid',$pid)->get('product')->result_array();
	}
	// 更新文章内容
	public function upload_product($pid,$data){
		// var_dump($data);
		return $this->db->where('pid', $pid)->update('product', $data);
	}
}