<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller{
	public function __construct(){
		parent::__construct();
		date_default_timezone_set("Asia/Shanghai");
		// $this->output->enable_profiler(true);
	}

	/*
	*	入口页面--选择两个公司的内容
	*/
	public function loading(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		$this->load->view('index/loading.html', $data);
	}

	/*
	*	首页
	*/
	public function index(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		// 获取banner
		$this->load->model('banner_model','banner');
		$data['banner'] = $this->banner->check_page(6,0);
		// 获取首页公司简介
		$this->load->model('company_model','company');
		$data['companyleft'] = $this->company->check_name('companyleft')[0]['value'];
		$data['companyright'] = $this->company->check_name('companyright')[0]['value'];
		// 获取最新动态*6
		$this->load->model('news_model','news');
		$data['news'] = $this->news->check_page(6,0);
		// 获取最新项目信息
		$this->load->model('project_model','project');
		$data['project_outside'] = $this->project->check_page_category(0,6,0);
		$data['project_inside'] = $this->project->check_page_category(1,6,0);
		$data['project_residence'] = $this->project->check_page_category(2,6,0);
		// 获取公司团队*6
		$this->load->model('team_model','team');
		$data['team'] = $this->team->check_page(6,0);
		// var_dump($data);
		$this->load->view('index/index.html', $data);
	}

	/*
	*	动态列表页
	*/
	public function news(){
		// 测试数据
		$data['image_path'] = base_url().'public/dist/index/images/';
		// 获取banner
		$this->load->model('banner_model','banner');
		$data['banner'] = $this->banner->check_page(6,0);
		// 获取最新动态*64
		$this->load->model('news_model','news');
		// 随机获取推荐的动态
		$data['recommand'] = $this->news->check_page(2,0);
		// 分页
		$this->load->library('pagination');
		$config['base_url'] = site_url('index/nl');
		$config['total_rows'] = $this->news->count_all();
		$config['per_page'] = 4;
		$config['first_link']= false;
		$config['next_link']= '';
		$config['prev_link']= '';
		$config['last_link']= false;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		// 根据segment获取对应news
		$data['news'] = $this->news->check_page($config['per_page'],$this->uri->segment(3));
		// var_dump($data);
		$this->load->view('index/news_list.html', $data);
	}

	/*
	*	动态详情页
	*/
	public function news_detail(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		// 获取对应的文章
		$serial = $this->uri->segment(3);
		$this->load->model('news_model','news');
		$news = $this->news->check_serial($serial);
		if(!$news){
			redirect('index/home/error_serial');
		}
		$data['news'] = $news[0];
		// 获取推荐文章
		$data['recommand'] = $this->news->check_page(3,0);
		// 获取上一篇story
		$data['news_prev'] = $this->news->check_prev($data['news']['nid']);
		$data['news_next'] = $this->news->check_next($data['news']['nid']);
		// var_dump($data);
		$this->load->view('index/news_detail.html', $data);
	}


	/*
	*	项目列表页
	*/
	public function project(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		// 获取banner
		$this->load->model('banner_model','banner');
		$data['banner'] = $this->banner->check_page(6,0);
		// 获取公司产品*12
		$this->load->model('project_model','project');
		$category = $this->uri->segment(4);
		if($category == 'po'){
			$category_num = 0;
		}elseif($category == 'pi'){
			$category_num = 1;
		}else{
			$category_num = 2;
		}
		// 随机获取推荐的故事
		$data['recommand'] = $this->project->check_page(2,5);
		// 分页
		$this->load->library('pagination');
		$config['base_url'] = site_url('index/home/project/').$category;
		$config['total_rows'] = $this->project->count_all_category($category_num);
		$config['per_page'] = 4;
		$config['first_link']= false;
		$config['next_link']= '';
		$config['prev_link']= '';
		$config['last_link']= false;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		// 根据segment获取对应project
		// 获取对应的项目列表

		$data['project'] = $this->project->check_page_category($category_num,$config['per_page'],$this->uri->segment(5));
		// var_dump($data);
		$this->load->view('index/project_list.html', $data);
	}

	/*
	*	项目详情页
	*/
	public function project_detail(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		// 获取公司产品*12
		$this->load->model('project_model','project');
		$serial = $this->uri->segment(4);
		$data['project'] = $this->project->check_serial($serial);
		if(!$data['project']){
			redirect('index/home/error_serial');
		}
		// 获取上一篇和下一篇
		$data['project_prev'] = $this->project->check_prev($data['project'][0]['sid'], $data['project'][0]['category']);
		$data['project_next'] = $this->project->check_next($data['project'][0]['sid'], $data['project'][0]['category']);
		// 获取推荐文章
		$data['recommand'] = $this->project->check_page_category($data['project'][0]['category'],3,0);
		// var_dump($data);

		$this->load->view('index/project_detail.html', $data);
	}

	public function team(){
		// 测试数据
		$data['image_path'] = base_url().'public/dist/index/images/';
		// 获取banner
		$this->load->model('banner_model','banner');
		$data['banner'] = $this->banner->check_page(6,0);
		// 获取最新动态*64
		$this->load->model('team_model','team');
		// 随机获取推荐的动态
		$data['recommand'] = $this->team->check_page(2,0);
		// 分页
		$this->load->library('pagination');
		$config['base_url'] = site_url('index/nl');
		$config['total_rows'] = $this->team->count_all();
		$config['per_page'] = 4;
		$config['first_link']= false;
		$config['next_link']= '';
		$config['prev_link']= '';
		$config['last_link']= false;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		// 根据segment获取对应team
		$data['team'] = $this->team->check_page($config['per_page'],$this->uri->segment(3));
		// var_dump($data);
		$this->load->view('index/team_list.html', $data);
	}
	public function team_detail(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		// 获取对应的文章
		$serial = $this->uri->segment(3);
		$this->load->model('team_model','team');
		$team = $this->team->check_serial($serial);
		if(!$team){
			redirect('index/home/error_serial');
		}
		$data['team'] = $team[0];
		// 获取推荐文章
		$data['recommand'] = $this->team->check_page(3,0);
		// 获取上一篇story
		$data['team_prev'] = $this->team->check_prev($data['team']['nid']);
		$data['team_next'] = $this->team->check_next($data['team']['nid']);
		// var_dump($data);
		$this->load->view('index/team_detail.html', $data);
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

	public function error_serial(){
		$this->load->view('index/error-page.html');
	}
}