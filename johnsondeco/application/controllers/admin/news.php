<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class News extends My_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('news_model', 'news');
		// $this->output->enable_profiler(true);	//开启debug
	}
	/*
	 * 文章列表
	 */
	public function index(){
		$data['subpage'] = 'admin/news.html';
		$data['current_nav'] = 'news';
		$data['path'] = '文章管理 / 文章列表';
		$data['company'] = '上海杰汉申若丝琳装饰有限公司';

		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/news/index');
		$config['total_rows'] = $this->news->count_all();
		$config['per_page'] = 15;
		$config['first_link']= '第一页';
		$config['next_link']= '下一页';
		$config['prev_link']= '上一页';
		$config['last_link']= '最后页';

		$this->pagination->initialize($config);

		$data['page'] = $this->pagination->create_links();
		
		$data['news'] = $this->news->check_page($config['per_page'],$this->uri->segment(4));
		$this->load->view('admin/tpl.html',$data);
	}

	/*
	 * 文章编辑
	 */
	public function edit(){
		$data['subpage'] = 'admin/news_edit.html';

		$data['current_nav'] = 'newsEdit';
		$data['path'] = '文章管理 / 发布文章';
		$data['company'] = '上海杰汉申若丝琳装饰有限公司';

		if($this->uri->segment(4, 'null') != 'null'){
			$nid = $this->uri->segment(4);
			$data['news'] = $this->news->check_nid($nid)[0];
		}
		$this->load->view('admin/tpl.html',$data);
	}

	/*
	*	删除文章
	*/
	public function del(){
		$nid = $this->input->post('nid');
		$serial = $this->input->post('serial');
		if($this->news->del_news($nid)){
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
		$nid 	= $this->input->post('nid');
		$data['title'] 		= trim($this->input->post('title'));
		$data['english'] 		= trim($this->input->post('english'));
		$data['info'] 		= trim($this->input->post('info'));
		$data['content'] 	= trim($this->input->post('content'));
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
		if($nid == ''){
			if(!$this->news->add_news($data)){
				echo 0;
			};
			echo 1;
		}else{
			//获取旧的thumb再上传新的,便于删除旧图片
			$news = $this->news->check_nid($nid)[0];
			$oldthumb = 'public/upload/'.$news['thumb'];
			if(!$this->news->upload_news($nid, $data)){
				echo 0;die;
			}
			if(isset($_FILES['thumb']) && file_exists($oldthumb)){
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