<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Entry_model extends CI_Model{
	// 新增申请
	public function add_entry($data){
		return $this->db->insert('entry', $data);
	}	
	// 统计所有数目
	public function count_all(){
		return $this->db->count_all('entry');
	}
	// 分页
	public function check_page($num, $offset){
		return  $this->db->limit($num, $offset)->order_by('time','desc')->get('entry')->result_array();
	}
	// 删除申请
	public function del_entry($id){
		return $this->db->delete('entry',array('id'=>$id));
	}
	// 返回所有的数据
	public function check_all(){
		return $this->db->get('entry')->result_array();
	}
	// 更新未读状态
	public function update_id($id,$data){
		return $this->db->where('id',$id)->update('entry',$data);
	}
	// 获取未读的条数
	public function check_unread(){
		return $this->db->where(array('unread'=>1))->from('entry')->count_all_results();
	}

}