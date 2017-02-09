<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Adminclass{
	public function __construct(){
		date_default_timezone_set("Asia/Shanghai");
		if(!isset($_COOKIE['user']) || !isset($_COOKIE['uid'])){
			success('admin/login',"非法访问");
		}
		$this->load->model('user_model','user');
		$userinfo = $this->user->check_user($_COOKIE['user'])[0];
		if($_COOKIE['user'] != $userinfo['user'] || substr($_COOKIE['uid'], 5) != $userinfo['password']){
			success('admin/login',"非法访问");
		}
	}
}