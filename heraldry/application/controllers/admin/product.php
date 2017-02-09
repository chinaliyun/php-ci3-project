<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product extends My_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('product_model', 'product');
		// $this->output->enable_profiler(true);	//开启debug
	}

	/*
	 * 产品列表
	 */
	public function index(){
		$this->load->library('pagination');
		$data['subpage'] = 'admin/product.html';
		$data['current_nav'] = 'product';
		$data['path'] = '产品管理 / 产品列表';
		$data['company'] = '上海家徽纹章管理后台';

		/*$config['base_url'] = site_url('admin/product/index');
		$config['total_rows'] = $this->product->count_all();
		$config['per_page'] = 5;
		$config['first_link']= '第一页';
		$config['next_link']= '下一页';
		$config['prev_link']= '上一页';
		$config['last_link']= '最后页';

		$this->pagination->initialize($config);

		$data['page'] = $this->pagination->create_links();
		
		$data['product'] = $this->product->check_page($config['per_page'],$this->uri->segment(4));*/
		$data['product'] = $this->product->check_all();
		$this->load->view('admin/tpl.html',$data);
	}

	/*
	 * 产品编辑
	 */
	public function edit(){
		$data['subpage'] = 'admin/product_edit.html';

		$data['current_nav'] = 'productEdit';
		$data['path'] = '产品管理 / 发布产品';
		$data['company'] = '上海家徽纹章管理后台';

		if($this->uri->segment(4, 'null') != 'null'){
			$pid = $this->uri->segment(4);
			$data['product'] = $this->product->check_pid($pid)[0];
		}
		$this->load->view('admin/tpl.html',$data);
	}

	/*
	*	删除产品
	*/
	public function del(){
		$pid = $this->input->post('pid');
		$serial = $this->input->post('serial');
		
		if($this->product->del_product($pid)){
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
	 * 产品提交
	 */
	public function send(){
		$pid 	= $this->input->post('pid');
		$data['serial']		= trim($this->input->post('serial'));
		$data['title'] 		= trim($this->input->post('title'));
		$data['english'] 	= trim($this->input->post('english'));
		$data['category'] 	= $this->input->post('category');
		$data['content'] 	= trim($this->input->post('content'));
		if($pid == ''){
			$data['time'] 		= date('Y-m-d H:i:s');
		}
		// var_dump($GLOBALS);
		if(isset($_FILES['thumb'])){//如果文件存在
			$thumb = $_FILES['thumb'];
			list($a,$thumbtype) = explode('.', $thumb['name']);

			$thumbname = rtime().'.'.$thumbtype;//文件命名(随机)
			$dirpath = 'public/upload/'.$data['serial'];//文件夹路径
			$thumbpath = $data['serial'].'/'.$thumbname;//要返回的文件路径
			$fullpath = 'public/upload/'.$thumbpath;//文件完整路径
			if(!file_exists($dirpath)){
				mkdir($dirpath);
			}
			if(!move_uploaded_file($thumb['tmp_name'], $fullpath)){
				echo 0;die;
			}
			$data['thumb'] = $thumbpath;
			$data['thumb_name'] = $thumb['name'];

			// 提交图片上传记录
			$image['serial'] = $data['serial'];
			$image['filepath'] = $thumbpath;
			$image['filetype'] = 1;
			$image['time'] = date('YmdHis');
			$this->load->model('image_model','image');
			$this->image->add_image($image);
		}
		if(isset($_FILES['banner'])){//如果文件存在
			$banner = $_FILES['banner'];
			list($a,$bannertype) = explode('.', $banner['name']);

			$bannername = rtime().'1.'.$bannertype;//文件命名(随机)
			$dirpath = 'public/upload/'.$data['serial'];//文件夹路径
			$bannerpath = $data['serial'].'/'.$bannername;//要返回的文件路径
			$fullpath = 'public/upload/'.$bannerpath;//文件完整路径
			if(!file_exists($dirpath)){
				mkdir($dirpath);
			}
			if(!move_uploaded_file($banner['tmp_name'], $fullpath)){
				echo 0;die;
			}
			$data['banner'] = $bannerpath;
			$data['banner_name'] = $banner['name'];

			// 提交图片上传记录
			$image['serial'] = $data['serial'];
			$image['filepath'] = $bannerpath;
			$image['filetype'] = 1;
			$image['time'] = date('YmdHis');
			$this->load->model('image_model','image');
			$this->image->add_image($image);
		}
		if($pid == ''){
			if(!$this->product->add_product($data)){
				echo 0;
			};
			echo 1;
		}else{
			//获取旧的thumb再上传新的
			$product = $this->product->check_pid($pid)[0];
			$oldthumb = 'public/upload/'.$product['thumb'];
			$oldbanner = 'public/upload/'.$product['banner'];
			if(!$this->product->upload_product($pid, $data)){
				echo 0;die;
			}
			if($_FILES['thumb'] && file_exists($oldthumb)){
				unlink($oldthumb);//如果就缩略图存在，删除图片
			}
			if($_FILES['banner'] && file_exists($oldbanner)){
				unlink($oldbanner);//如果就缩略图存在，删除图片
			}
			echo 1;
		}
	}

}