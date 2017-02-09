<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Banner_model extends CI_Model{
	// 增加新的文章
	public function add_banner($data){
		return $this->db->insert('banner', $data);
	}

	// 计算文章总数
	public function count_all(){
		return $this->db->count_all_results('banner');
	}

	// 分页
	public function check_page($num, $offset){
		return $this->db->limit($num, $offset)->order_by('bid','desc')->get('banner')->result_array();
	}
	// 通过bid删除文章
	public function del_banner($bid){
		return $this->db->delete('banner', array('bid'=>$bid));
	}
	/*
	*@param string $bid 文章序号
	*/
	public function check_bid($bid){
		return $this->db->where('bid',$bid)->get('banner')->result_array();
	}
	// 更新文章内容
	public function upload_banner($bid,$data){
		// var_dump($data);
		return $this->db->where('bid', $bid)->update('banner', $data);
	}
}