<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Project_model extends CI_Model{
	// 增加新的文章
	public function add_project($data){
		return $this->db->insert('project', $data);
	}

	// 计算文章总数
	public function count_all(){
		return $this->db->count_all_results('project');
	}
	public function count_all_category($category){
		return $this->db->where('category', $category)->get('project')->num_rows();
	}
	public function check_all(){
		return $this->db->order_by('time','desc')->get('project')->result_array();
	}
	// 分页
	public function check_page($num, $offset){
		return $this->db->limit($num, $offset)->order_by('time','desc')->get('project')->result_array();
	}
	public function check_page_category($category,$num, $offset){
		return $this->db->where('category', $category)->limit($num, $offset)->order_by('time','desc')->get('project')->result_array();
	}
	/*
	*	根据serial获取故事
	*/
	public function check_serial($serial){
		return $this->db->where('serial',$serial)->get('project')->result_array();
	}
	public function check_category($category){
		return $this->db->where('category', $category)->order_by('time','desc')->get('project')->result_array();
	}
	// 通过sid删除文章
	public function del_project($sid){
		return $this->db->delete('project', array('sid'=>$sid));
	}
	/*
	*@param string $sid 文章序号
	*/
	public function check_sid($sid){
		return $this->db->where('sid',$sid)->get('project')->result_array();
	}
	// 更新文章内容
	public function upload_project($sid,$data){
		// var_dump($data);
		return $this->db->where('sid', $sid)->update('project', $data);
	}
	// 获取上一篇故事
	public function check_prev($sid , $category){
		$result = $this->db->where(array('sid<'=>$sid, 'category'=>$category))->order_by('sid','desc')->limit(1,0)->get('project')->result_array();
		if($result){
			return  $result;
		}else{
			return  null;
		}
	}
	// 获取下一篇故事
	public function check_next($sid , $category){
		$result = $this->db->where(array('sid>'=>$sid, 'category'=>$category))->order_by('sid','asc')->limit(1,0)->get('project')->result_array();
		if($result){
			return  $result;
		}else{
			return  null;
		}
	}
}