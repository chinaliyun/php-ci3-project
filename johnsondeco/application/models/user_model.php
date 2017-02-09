<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_Model{
	public function check_user($user='admin'){
		return $this->db->where('user',$user)->get('user')->result_array();
	}
	// 更新文章内容
	public function upload_user($data,$user='admin'){
		// var_dump($data);
		return $this->db->where('user', $user)->update('user', $data);
	}
}