<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message_model extends CI_Model{
	// 新增留言
	public function add_message($data){
		return $this->db->insert('message', $data);
	}	
	// 计算所有结果数目
	public function count_all(){
		return $this->db->count_all('message');
	}
	// 分页
	public function check_page($num, $offset){
		return  $this->db->limit($num, $offset)->order_by('time','desc')->get('message')->result_array();
	}
	// 删除留言
	public function del_msg($id){
		return $this->db->delete('message',array('id'=>$id));
	}
	// 返回所有的数据
	public function check_all(){
		return $this->db->get('message')->result_array();
	}
	// 更新未读状态
	public function update_id($id,$data){
		return $this->db->where('id',$id)->update('message',$data);
	}
	// 获取未读的条数
	public function check_unread(){
		return $this->db->where(array('unread'=>1))->from('message')->count_all_results();
	}

}