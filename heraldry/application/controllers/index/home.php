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
		// 获取最新家徽故事*6
		$this->load->model('story_model','story');
		$data['story'] = $this->story->check_page(6,0);
		// 获取公司产品*12
		$this->load->model('product_model','product');
		$data['product_normal'] = $this->product->check_page_category(0,8,0);
		$data['product_high'] = $this->product->check_page_category(1,6,0);
		// 获取公司团队*6
		$this->load->model('staff_model','staff');
		$data['staff'] = $this->staff->check_page(6,0);
		// var_dump($data);
		$this->load->view('index/index.html', $data);
	}

	public function flow(){
		$this->load->model('flow_model', 'flow');
		$data['flow'] = $this->flow->check_flow();
		// var_dump($data);
		$this->load->view('index/flow.html', $data);
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
	/*
	*	产品详情页
	*/
	public function product(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		// 获取公司产品*12
		$this->load->model('product_model','product');
		$category = $this->input->get('v');
		if($category == 'n'){
			$data['product'] = $this->product->check_category(0);
		}else{
			$data['product'] = $this->product->check_category(1);
		}
		// var_dump($data);
		// var_dump($category);
		$this->load->view('index/product_list.html', $data);
	}

	/*
	*	故事列表页
	*/
	public function story(){
		// 图片地址
		$data['image_path'] = base_url().'public/dist/index/images/';
		// 加载banner模块,获取banner
		$this->load->model('banner_model','banner');
		$data['banner'] = $this->banner->check_page(6,0);
		// 加载故事模块，获取最新家徽故事*6
		$this->load->model('story_model','story');
		// 随机获取推荐的故事
		$data['recommand'] = $this->story->check_page(2,5);
		// 分页
		$this->load->library('pagination');
		$config['base_url'] = site_url('index/sl');
		$config['total_rows'] = $this->story->count_all();
		$config['per_page'] = 8;
		$config['first_link']= false;
		$config['next_link']= '';
		$config['prev_link']= '';
		$config['last_link']= false;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		// 根据segment获取对应story
		$data['story'] = $this->story->check_page($config['per_page'],$this->uri->segment(3));
		$this->load->view('index/story_list.html', $data);
	}

	/*
	*	故事详情页
	*/
	public function story_detail(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		$serial = $this->uri->segment(3);
		$this->load->model('story_model','story');
		$story = $this->story->check_serial($serial);
		if(!$story){
			redirect('index/home/error_serial');
		}
		$data['story'] = $story[0];
		// 获取推荐文章
		$data['recommand'] = $this->story->check_page(3,0);
		// 获取上一篇story
		$data['story_prev'] = $this->story->check_prev($data['story']['sid']);
		$data['story_next'] = $this->story->check_next($data['story']['sid']);
		$this->load->view('index/story_detail.html', $data);
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
		$data['recommand'] = $this->news->check_page(2,5);
		// 分页
		$this->load->library('pagination');
		$config['base_url'] = site_url('index/nl');
		$config['total_rows'] = $this->news->count_all();
		$config['per_page'] = 8;
		$config['first_link']= false;
		$config['next_link']= '';
		$config['prev_link']= '';
		$config['last_link']= false;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		// 根据segment获取对应news
		$data['news'] = $this->news->check_page($config['per_page'],$this->uri->segment(3));
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

	function error_serial(){
		$this->load->view('index/error-page.html');
	}
}