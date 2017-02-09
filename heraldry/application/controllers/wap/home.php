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
	public function wap_menu(){
		$data['path'] = '导航';
		// 获取首页公司简介
		$this->load->model('company_model','company');
		$data['companyleft'] = $this->company->check_name('companyleft')[0]['value'];
		$data['companyright'] = $this->company->check_name('companyright')[0]['value'];
		$this->load->view('wap/wap_menu.html', $data);
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
	public function about(){
		// 图片地址
		$data['image_path'] = base_url().'public/dist/index/images/';
		$data['path'] = '关于我们';
		// 获取首页公司简介
		$this->load->model('company_model','company');
		$data['companyleft'] = $this->company->check_name('companyleft')[0]['value'];
		$data['companyright'] = $this->company->check_name('companyright')[0]['value'];
		$this->load->view('wap/about.html', $data);
	}

	public function product(){
		// 图片地址
		$data['image_path'] = base_url().'public/dist/index/images/';
		$data['path'] = '定制产品';
		//加载动态模块
		$this->load->model('product_model','product');
		//分页
		$per_page = 4;
		$count = $this->product->count_all();
		$curindex = $this->uri->segment(3,0);
		$data['page_prev'] = $curindex == 0 ? 0 :$curindex - $per_page;
		$data['page_next'] = $curindex+$per_page >= $count ? $curindex : $curindex + $per_page;
		$data['product'] = $this->product->check_page($per_page,$curindex);
		$data['count'] = $count;
		$this->load->view('wap/product_list.html', $data);
	}
	/*
	*	产品列表页
	*/
	public function product_normal(){
		// 图片地址
		$data['image_path'] = base_url().'public/dist/index/images/';
		$data['path'] = '基础定制产品';
		// 加载故事模块，获取最新家徽故事*6
		$this->load->model('product_model','product');
		$data['product'] = $this->product->check_category(0);
		// var_dump($data);
		$this->load->view('wap/product_list.html', $data);
	}
	public function product_high(){
		// 图片地址
		$data['image_path'] = base_url().'public/dist/index/images/';
		$data['path'] = '高级定制产品';
		// 加载故事模块，获取最新家徽故事*6
		$this->load->model('product_model','product');
		$data['product'] = $this->product->check_category(1);
		// var_dump($data);
		$this->load->view('wap/product_list.html', $data);
	}
	public function product_detail(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		$data['path'] = '产品定制';
		$serial = $this->uri->segment(3);
		$this->load->model('product_model','product');
		$data['product'] = $this->product->check_serial($serial)[0];
		// 获取推荐文章
		$data['recommand'] = $this->product->check_page(3,0);
		// 获取上一篇product
		// $data['product_prev'] = $this->product->check_prev($data['product']['sid']);
		// $data['product_next'] = $this->product->check_next($data['product']['sid']);
		// var_dump($data);
		$this->load->view('wap/product_detail.html', $data);
	}
 	public function staff(){
 		// 图片地址
 		$data['image_path'] = base_url().'public/dist/index/images/';
 		$data['path'] = '追梦团队';
 		// 加载故事模块，获取最新家徽故事*6
 		$this->load->model('staff_model','staff');
 		$data['staff'] = $this->staff->check_page(6,0);
 		// var_dump($data);
 		$this->load->view('wap/staff_list.html', $data);
 	}
	/*
	*	故事列表页
	*/
	public function story(){
		header("Content-Type:text/html; charset=utf-8");
		// 图片地址
		$data['image_path'] = base_url().'public/dist/index/images/';
		$data['path'] = '家徽故事';

		//加载动态模块
		$this->load->model('story_model','story');
		//分页
		$per_page = 4;
		$count = $this->story->count_all();
		$curindex = $this->uri->segment(3,0);
		$data['page_prev'] = $curindex == 0 ? 0 :$curindex - $per_page;
		$data['page_next'] = $curindex+$per_page >= $count ? $curindex : $curindex + $per_page;
		$data['story'] = $this->story->check_page($per_page,$curindex);
		$this->load->view('wap/story_list.html', $data);
	}

	/*
	*	故事详情页
	*/
	public function story_detail(){
		$data['image_path'] = base_url().'public/dist/index/images/';
		$data['path'] = '家徽故事';
		$serial = $this->uri->segment(3);
		$this->load->model('story_model','story');
		$story = $this->story->check_serial($serial);
		if(!$story){
			redirect('wap/home/error_serial');
		}
		$data['story'] = $story[0];
		// 获取推荐文章
		$data['recommand'] = $this->story->check_page(3,0);
		// 获取上一篇story
		$data['story_prev'] = $this->story->check_prev($data['story']['sid']);
		$data['story_next'] = $this->story->check_next($data['story']['sid']);
		$this->load->view('wap/story_detail.html', $data);
	}

	public function load_story(){
		// 设置返回的类型
		header('Content-type: application/json');
		// $num = $this->input->post('num');
		$offset = $this->input->post('offset');
		// 加载news模块并获取相应数量内容
		$this->load->model('story_model','story');
		$data = $this->story->check_page(4,$offset);
		

		echo json_encode($data);
	}

	/*
	*	动态列表页
	*/
	public function news(){
		// 测试数据
		$data['image_path'] = base_url().'public/dist/index/images/';
		$data['path'] = '纹章院动态';
		//加载动态模块
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
		$data['path'] = '纹章院动态';
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

	public function load_news(){
		// 设置返回的类型
		header('Content-type: application/json');
		// $num = $this->input->post('num');
		$offset = $this->input->post('offset');
		// 加载news模块并获取相应数量内容
		$this->load->model('news_model','news');
		$data = $this->news->check_page(4,$offset);
		

		echo json_encode($data);
	}

	public function flow(){
		$data['path'] = '申请流程';
		$this->load->model('flow_model', 'flow');
		$data['flow'] = $this->flow->check_flow();
		// var_dump($data);
		$this->load->view('wap/flow.html', $data);
	}

	function error_serial(){
		$data['path'] = '出错啦';
		$this->load->view('wap/error-page.html', $data);
	}
}