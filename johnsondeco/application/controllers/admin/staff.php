<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Staff extends My_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('staff_model', 'staff');
		// $this->output->enable_profiler(true);	//开启debug
	}

	/*
	 * 产品列表
	 */
	public function index(){
		$this->load->library('pagination');
		$data['subpage'] = 'admin/staff.html';
		$data['current_nav'] = 'staff';
		$data['path'] = '员工管理 / 员工列表';
		$data['company'] = '上海杰汉申若丝琳装饰有限公司';

		/*$config['base_url'] = site_url('admin/staff/index');
		$config['total_rows'] = $this->staff->count_all();
		$config['per_page'] = 5;
		$config['first_link']= '第一页';
		$config['next_link']= '下一页';
		$config['prev_link']= '上一页';
		$config['last_link']= '最后页';

		$this->pagination->initialize($config);

		$data['page'] = $this->pagination->create_links();
		
		$data['staff'] = $this->staff->check_page($config['per_page'],$this->uri->segment(4));*/
		$data['staff'] = $this->staff->check_all();
		$this->load->view('admin/tpl.html',$data);
	}

	/*
	 * 产品编辑
	 */
	public function edit(){
		$data['subpage'] = 'admin/staff_edit.html';

		$data['current_nav'] = 'staffEdit';
		$data['path'] = '产品管理 / 发布产品';
		$data['company'] = '上海杰汉申若丝琳装饰有限公司';

		if($this->uri->segment(4, 'null') != 'null'){
			$sid = $this->uri->segment(4);
			$data['staff'] = $this->staff->check_sid($sid)[0];
		}
		$this->load->view('admin/tpl.html',$data);
	}

	/*
	*	删除产品
	*/
	public function del(){
		$sid = $this->input->post('sid');
		$serial = $this->input->post('serial');
		
		if($this->staff->del_staff($sid)){
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
		$sid 	= $this->input->post('sid');
		$data['name'] 		= trim($this->input->post('name'));
		$data['info'] 		= trim($this->input->post('info'));
		$data['position'] 	= trim($this->input->post('position'));
		if($sid == ''){
			$data['time'] 		= date('Y-m-d H:i:s');
		}
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
		if($sid == ''){
			if(!$this->staff->add_staff($data)){
				echo 0;
			};
			echo 1;
		}else{
			//获取旧的thumb再上传新的
			$staff = $this->staff->check_sid($sid)[0];
			$oldthumb = 'public/upload/'.$staff['thumb'];
			if(!$this->staff->upload_staff($sid, $data)){
				echo 0;die;
			}
			if($_FILES['thumb'] && file_exists($oldthumb)){
				unlink($oldthumb);//如果就缩略图存在，删除图片
			}
			echo 1;
		}
	}


}