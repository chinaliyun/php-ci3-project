<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	/*
	*	登录页面
	*/
	public function index(){
		// $data['subpage'] = 'admin/login.html';
		$this->load->view('admin/login.html');
	}

	public function send(){
		$user = $this->input->post('user');
		$password = asp($this->input->post('password'));
		$this->load->model('user_model','user');
		$admin = $this->user->check_user($user)[0];
		if($user != $admin['user'] || $password != $admin['password']){
			echo 0;die;
		}
		echo 'uid='.as2p($password);
	}
}