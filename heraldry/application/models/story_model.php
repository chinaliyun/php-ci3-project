<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Story_model extends CI_Model{
	// 增加新的文章
	public function add_story($data){
		return $this->db->insert('story', $data);
	}

	// 计算文章总数
	public function count_all(){
		return $this->db->count_all_results('story');
	}
	public function check_all(){
		return $this->db->order_by('time','desc')->get('story')->result_array();
	}
	// 分页
	public function check_page($num, $offset){
		return $this->db->limit($num, $offset)->order_by('time','desc')->get('story')->result_array();
	}
	/*
	*	根据serial获取故事
	*/
	public function check_serial($serial){
		return $this->db->where('serial',$serial)->get('story')->result_array();
	}
	// 通过sid删除文章
	public function del_story($sid){
		return $this->db->delete('story', array('sid'=>$sid));
	}
	/*
	*@param string $sid 文章序号
	*/
	public function check_sid($sid){
		return $this->db->where('sid',$sid)->get('story')->result_array();
	}
	// 更新文章内容
	public function upload_story($sid,$data){
		// var_dump($data);
		return $this->db->where('sid', $sid)->update('story', $data);
	}
	// 获取上一篇故事
	public function check_prev($sid){
		$result = $this->db->where('sid<',$sid)->order_by('sid','desc')->limit(1,0)->get('story')->result_array();
		if($result){
			return  $result;
		}else{
			return  null;
		}
	}
	// 获取下一篇故事
	public function check_next($sid){
		$result = $this->db->where('sid>',$sid)->order_by('sid','asc')->limit(1,0)->get('story')->result_array();
		if($result){
			return  $result;
		}else{
			return  null;
		}
	}
}