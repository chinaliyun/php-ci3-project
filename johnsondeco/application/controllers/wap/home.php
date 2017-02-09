<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller{
	public function __construct(){
		parent::__construct();
		date_default_timezone_set("Asia/Shanghai");
	}

	/*
	*	入口页面--选择两个公司的内容
	*/
	public function loading(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		$this->load->view('wap/loading.html', $data);
	}

	/*
	*	首页
	*/
	public function index(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		$data['path'] = '首页';
		// 获取banner
		$this->load->model('banner_model','banner');
		$data['banner'] = $this->banner->check_page(6,0);
		// 获取首页公司简介
		$this->load->model('company_model','company');
		$data['companyleft'] = $this->company->check_name('companyleft')[0]['value'];
		$data['companyright'] = $this->company->check_name('companyright')[0]['value'];
		// var_dump($data);
		$this->load->view('wap/index.html', $data);
	}

	public function entry(){
		$data['path'] = '家徽申请';
		$this->load->view('wap/entry.html', $data);
	}

	/*
	*	申请提交
	*/
	public function entry_send(){
		$data['name'] = trim($this->input->post('name'));
		$data['sex'] = trim($this->input->post('sex'));
		$data['birth'] = trim($this->input->post('birth'));
		$data['wechat'] = trim($this->input->post('wechat'));
		$data['mobile'] = trim($this->input->post('mobile'));
		$data['email'] = trim($this->input->post('email'));
		$data['time'] = date('Y-m-d H:i:s');
		$this->load->model('entry_model','entry');
		if($this->entry->add_entry($data) > 0){
			echo 1;
		}else{
			echo 0;
		}
	}
	/*
	*	留言提交
	*/
	public function message_send(){
		$data['name'] = trim($this->input->post('name'));
		$data['mobile'] = trim($this->input->post('mobile'));
		$data['info'] = trim($this->input->post('info'));
		$data['time'] = date('Y-m-d H:i:s');
		$this->load->model('message_model','entry');
		if($this->entry->add_message($data) > 0){
			echo 1;
		}else{
			echo 0;
		}
	}
	// 基础导航
	public function base_menu(){
		$data['path'] = '导航';
		// 获取首页公司简介
		$this->load->model('company_model','company');
		$data['companyleft'] = $this->company->check_name('companyleft')[0]['value'];
		$data['companyright'] = $this->company->check_name('companyright')[0]['value'];
		$this->load->view('wap/base_menu.html', $data);
	}
	public function project_menu(){
		$data['path'] = '导航';
		// 获取首页公司简介
		$this->load->model('company_model','company');
		$data['companyleft'] = $this->company->check_name('companyleft')[0]['value'];
		$data['companyright'] = $this->company->check_name('companyright')[0]['value'];
		$this->load->view('wap/project_menu.html', $data);
	}
	public function team(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		$data['path'] = '团队';
		// 获取最新动态*64
		$this->load->model('team_model','team');
		//分页
		$per_page = 4;
		$count = $this->team->count_all();
		$curindex = $this->uri->segment(3,0);
		$data['page_prev'] = $curindex == 0 ? 0 :$curindex - $per_page;
		$data['page_next'] = $curindex+$per_page >= $count ? $curindex : $curindex + $per_page;
		$data['team'] = $this->team->check_page($per_page,$curindex);
		$this->load->view('wap/team_list.html', $data);
	}	
	/*
	*	员工列表
	*/

 	public function staff(){
 		// 图片地址
 		$data['image_path'] = base_url().'public/dist/index/images/';
 		$data['path'] = '追梦团队';
 		// 加载员工模块，获取最新家徽故事*6
 		$this->load->model('staff_model','staff');
 		$data['staff'] = $this->staff->check_page(4,0);
 		// var_dump($data);
 		$this->load->view('wap/staff_list.html', $data);
 	}
	/*
	*	项目列表页
	*/
	public function project(){
		header("Content-Type:text/html; charset=utf-8");
		// 图片地址
		$data['image_path'] = base_url().'public/dist/index/images/';

		$category = $this->uri->segment(2);
		if($category == 'po'){
			$category_num = 0;
			$data['path'] = '外资项目';
		}elseif($category == 'pi'){
			$category_num = 1;
			$data['path'] = '内资项目';
		}else{
			$category_num = 2;
			$data['path'] = '住宅项目';
		}
		// 加载项目模块，获取最新家徽项目*6
		$this->load->model('project_model','project');
		// 分页
		$per_page = 4;
		$count = $this->project->count_all_category($category_num);
		// echo "<br/><br/><br/><br/><br/><br/>".$count;
		$curindex = $this->uri->segment(3,0);
		$data['page_prev'] = $curindex == 0 ? 0 :$curindex - $per_page;
		$data['page_next'] = $curindex+$per_page >= $count ? $curindex : $curindex + $per_page;
		$data['project'] = $this->project->check_page_category($category_num,4,$curindex);
		$this->load->view('wap/project_list.html', $data);
	}




	/*
	*	动态列表页
	*/
	public function news(){
		// 测试数据
		$data['image_path'] = base_url().'public/dist/index/images/';
		$data['path'] = '杰汉申动态';
		// 获取最新动态*64
		$this->load->model('news_model','news');
		//分页
		$per_page = 4;
		$count = $this->news->count_all();
		$curindex = $this->uri->segment(3,0);
		$data['page_prev'] = $curindex == 0 ? 0 :$curindex - $per_page;
		$data['page_next'] = $curindex+$per_page >= $count ? $curindex : $curindex + $per_page;
		$data['news'] = $this->news->check_page($per_page,$curindex);
		$this->load->view('wap/news_list.html', $data);
	}

	/*
	*	动态详情页
	*/
	public function news_detail(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		$data['path'] = '杰汉申动态';
		// 获取对应的文章
		$serial = $this->uri->segment(3);
		$this->load->model('news_model','news');
		$news = $this->news->check_serial($serial);
		if(!$news){
			redirect('wap/home/error_serial');
		}
		$data['news'] = $news[0];
		// 获取上一篇story
		$data['news_prev'] = $this->news->check_prev($data['news']['nid']);
		$data['news_next'] = $this->news->check_next($data['news']['nid']);
		// var_dump($data);
		$this->load->view('wap/news_detail.html', $data);
	}


	
	public function error_serial(){
		$data['path'] = '出错啦';
		$this->load->view('wap/error-page.html', $data);
	}

}