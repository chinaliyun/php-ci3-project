<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends My_Controller {
	public function __construct(){
		parent::__construct();
		// $this->output->enable_profiler(true);	//开启debug
	}
	/*
	 *  图片管理
	 */
	public function index(){
		$data['subpage'] = 'admin/img_control.html';
		$data['current_nav'] = 'media';
		$data['path'] = '文章管理 / 文章列表';
		$data['company'] = '上海杰汉申若丝琳装饰有限公司';
		$this->load->model('image_model','image');
		$this->load->library('pagination');
		// 获取image_model里面的所有图片
		$result = $this->image->check_all();
		$count = 0;
		foreach($result as $v){
			// 如果文件存在，则计数+1
			if(file_exists('public/upload/'.$v['filepath'])){
				$count+=1;
			// 如果文件不存在则删除该记录
			}else{
				$this->image->del_id($v['id']);
			}
		}
		$config['base_url'] = site_url('admin/media/index');
		$config['total_rows'] = $count;
		$config['per_page'] = 12;
		$config['first_link']= '第一页';
		$config['next_link']= '下一页';
		$config['prev_link']= '上一页';
		$config['last_link']= '最后页';
		$this->pagination->initialize($config);

		$data['page'] = $this->pagination->create_links();
		
		$data['images'] = $this->image->check_page($config['per_page'],$this->uri->segment(4));


		$this->load->view('admin/tpl.html', $data);
	}

	/*
	*	删除图片
	*/
	public function img_del(){
		$filepath = 'public/upload/'.$this->input->post('thumb');
		if(file_exists($filepath) && unlink($filepath)){
			echo 1;
		}else{
			echo 0;
		}
		
	}
}