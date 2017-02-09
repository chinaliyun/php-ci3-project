<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Banner extends My_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('banner_model', 'banner');
		// $this->output->enable_profiler(true);	//开启debug
	}
	/*
	 * 文章列表
	 */
	public function index(){
		$this->load->library('pagination');
		$data['subpage'] = 'admin/banner.html';
		$data['current_nav'] = 'banner';
		$data['path'] = '文章管理 / 文章列表';
		$data['company'] = '上海家徽纹章管理后台';

		$config['base_url'] = site_url('admin/banner/index');
		$config['total_rows'] = $this->banner->count_all();
		$config['per_page'] = 6;
		$config['first_link']= '第一页';
		$config['next_link']= '下一页';
		$config['prev_link']= '上一页';
		$config['last_link']= '最后页';

		$this->pagination->initialize($config);

		$data['page'] = $this->pagination->create_links();
		
		$data['banner'] = $this->banner->check_page($config['per_page'],$this->uri->segment(4));
		$this->load->view('admin/tpl.html',$data);
	}

	/*
	 * 文章编辑
	 */
	public function edit(){
		$data['subpage'] = 'admin/banner_edit.html';

		$data['current_nav'] = 'bannerEdit';
		$data['path'] = '文章管理 / 发布文章';
		$data['company'] = '上海家徽纹章管理后台';

		if($this->uri->segment(4, 'null') != 'null'){
			$bid = $this->uri->segment(4);
			$data['banner'] = $this->banner->check_bid($bid)[0];
		}
		// echo $bid;
		// var_dump($data['banner']);
		$this->load->view('admin/tpl.html',$data);
	}

	/*
	*	删除文章
	*/
	public function del(){
		$bid = $this->input->post('bid');
		$serial = $this->input->post('serial');
		if($this->banner->del_banner($bid)){
			echo "1";
			// 根据serial删除文件夹内所有图片
			deleteDir('public/upload/'.$serial);
			// 载入图片模型,根据serial删除模型内所有属于该文章的记录
			$this->load->model('image_model','image');
			$this->image->del_serial($serial);
		}else{
			echo "0";
		}
	}
	/*
	 * 文章提交
	 */
	public function send(){
		$bid 	= $this->input->post('bid');
		$data['title']		= $this->input->post('title');
		$data['url']		= $this->input->post('url');
		$data['time'] 		= date('Y-m-d H:i:s');
		$data['serial']		= $this->input->post('serial');
		
		if(isset($_FILES['thumb'])){//如果文件存在
			$file = $_FILES['thumb'];
			list($a,$filetype) = explode('.', $file['name']);

			$filename = rtime().'.'.$filetype;//文件命名(随机)
			$dirpath = 'public/upload/'.$data['serial'];//文件夹路径
			$filepath = $data['serial'].'/'.$filename;//要返回的文件路径
			$fullpath = 'public/upload/'.$filepath;//文件完整路径
			if(!file_exists($dirpath)){
				mkdir($dirpath);
			}
			if(!move_uploaded_file($file['tmp_name'], $fullpath)){
				echo 0;die;
			}
			$data['thumb'] = $filepath;
			$data['thumb_name'] = $file['name'];

			// 提交图片上传记录
			$image['serial'] = $data['serial'];
			$image['filepath'] = $filepath;
			$image['filetype'] = 1;
			$image['time'] = date('YmdHis');
			$this->load->model('image_model','image');
			$this->image->add_image($image);
		}
		if($bid == ''){
			if(!$this->banner->add_banner($data)){
				echo 0;
			};
			echo 1;
		}else{
			//获取旧的thumb再上传新的
			$banner = $this->banner->check_bid($bid)[0];
			$oldthumb = 'public/upload/'.$banner['thumb'];
			if(!$this->banner->upload_banner($bid, $data)){
				echo 0;die;
			}
			if($_FILES['thumb'] && file_exists($oldthumb)){
				unlink($oldthumb);//如果就缩略图存在，删除图片
			}
			echo 1;
		}
	}

	public function test(){
		$dir = 'public/upload/aaa';
		deleteDir($dir);
	}

}