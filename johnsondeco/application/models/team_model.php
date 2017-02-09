<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Team_model extends CI_Model{
	// 增加新的文章
	public function add_team($data){
		return $this->db->insert('team', $data);
	}

	// 计算文章总数
	public function count_all(){
		return $this->db->count_all_results('team');
	}

	// 分页
	public function check_page($num, $offset){
		return $this->db->limit($num, $offset)->order_by('time','desc')->get('team')->result_array();
	}
	// 通过nid删除文章
	public function del_team($nid){
		return $this->db->delete('team', array('nid'=>$nid));
	}
	/*
	*@param string $nid 文章序号
	*/
	public function check_nid($nid){
		return $this->db->where('nid',$nid)->get('team')->result_array();
	}
	/*
	*	根据serial获取文章
	*/
	public function check_serial($serial){
		return $this->db->where('serial',$serial)->get('team')->result_array();
	}
	// 更新文章内容
	public function upload_team($nid,$data){
		// var_dump($data);
		return $this->db->where('nid', $nid)->update('team', $data);
	}
	// 获取上一篇故事
	public function check_prev($nid){
		$result = $this->db->where('nid<',$nid)->order_by('nid','desc')->limit(1,0)->get('team')->result_array();
		if($result){
			return  $result;
		}else{
			return  null;
		}
	}
	// 获取下一篇故事
	public function check_next($nid){
		$result = $this->db->where('nid>',$nid)->order_by('nid','asc')->limit(1,0)->get('team')->result_array();
		if($result){
			return  $result;
		}else{
			return  null;
		}
	}
}