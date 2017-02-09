<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends My_Controller {
	public function __construct(){
		parent::__construct();
		// $this->output->enable_profiler(true);	//开启debug
	}

	/*
	 * 默认页面
	 */
	public function index()
	{
		$data['current_nav'] = 'admin';
		$data['company'] = '上海杰汉申若丝琳装饰有限公司';
		$data['path'] = '首页';
		$data['subpage'] = 'admin/admin.html';
		// 获取未读的留言和申请
		$this->load->model('message_model','message');
		$this->load->model('entry_model','entry');
		$data['message'] = $this->message->check_unread();
		$this->load->view('admin/tpl.html',$data);
	}

	/*
	*	公司信息设置
	*/
	public function company(){
		$data['current_nav'] = 'admin';
		$data['company'] = '上海杰汉申若丝琳装饰有限公司';
		$data['path'] = '首页';
		$data['subpage'] = 'admin/company.html';
		$this->load->model('company_model','company');
		$data['companyleft'] = $this->company->check_name('companyleft')[0];
		$data['companyright'] = $this->company->check_name('companyright')[0];
		// var_dump($data);
		$this->load->view('admin/tpl.html',$data);
	}
	/*
	*公司信息提交
	*/
	public function company_send(){
		$this->load->model('company_model','company');

		$data = array(
			array(
					'name' 	=> 'companyleft',
					'value' => $this->input->post('companyleft'),
				),
			array(
					'name' 	=> 'companyright',
					'value' => $this->input->post('companyright'),
				)
			);
		if($this->company->upload_name($data) > -1 ){
			echo "1";
		}else{
			echo "0";
		}
		
	}

	/*
	 *  留言列表
	 */
	public function message(){
		$data['current_nav'] = 'message';
		$data['path'] = '留言管理 / 留言列表';
		$data['company'] = '上海杰汉申若丝琳装饰有限公司';
		$data['subpage'] = 'admin/message.html';
		// 加载留言模块
		$this->load->model('message_model','message');
		// 获取所有留言，并把所有留言标记为已读
		$result = $this->message->check_all();
		foreach ($result as $v){
			$this->message->update_id($v['id'],array('unread'=>0));
		}
		// 加载设置分页模块
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/home/message');
		$config['total_rows'] = $this->message->count_all();
		$config['per_page'] = 5;
		$config['first_link']= '第一页';
		$config['next_link']= '下一页';
		$config['prev_link']= '上一页';
		$config['last_link']= '最后页';
		$this->pagination->initialize($config);

		$data['page'] = $this->pagination->create_links();
		$data['message'] = $this->message->check_page($config['per_page'], $this->uri->segment(4));
		// var_dump($data);
		$this->load->view('admin/tpl.html',$data);
	}
	/*
	* 删除留言
	*/
	public function message_del(){
		$id = $this->input->post('id');
		$this->load->model('message_model','message');
		if($this->message->del_msg($id) > 0){
			echo 1;
		}else{
			echo 0;
		}
	}

	/*
	 *  申请列表
	 */
	public function entry(){
		$data['current_nav'] = 'entry';
		$data['path'] = '留言管理 / 留言列表';
		$data['company'] = '上海杰汉申若丝琳装饰有限公司';
		$data['subpage'] = 'admin/entry.html';
		// 加载留言模块
		$this->load->model('entry_model','entry');
		// 获取所有留言，并把所有留言标记为已读
		$result = $this->entry->check_all();
		foreach ($result as $v){
			$this->entry->update_id($v['id'],array('unread'=>0));
		}
		// 加载设置分页模块
		$this->load->library('pagination');
		$config['base_url'] = site_url('admin/home/entry');
		$config['total_rows'] = $this->entry->count_all();
		$config['per_page'] = 5;
		$config['first_link']= '第一页';
		$config['next_link']= '下一页';
		$config['prev_link']= '上一页';
		$config['last_link']= '最后页';
		$this->pagination->initialize($config);

		$data['page'] = $this->pagination->create_links();
		$data['entry'] = $this->entry->check_page($config['per_page'], $this->uri->segment(4));
		// var_dump($data);
		$this->load->view('admin/tpl.html',$data);
	}
	/*
	* 删除申请
	*/
	public function entry_del(){
		$id = $this->input->post('id');
		$this->load->model('entry_model','entry');
		if($this->entry->del_entry($id) > 0){
			echo 1;
		}else{
			echo 0;
		}
	}
	/*
	 *  修改密码
	 */
	public function user_edit(){
		$data['current_nav'] = 'userEdit';
		$data['path'] = '留言管理 / 申请列表';
		$data['company'] = '上海杰汉申若丝琳装饰有限公司';
		$data['subpage'] = 'admin/user_edit.html';
		$this->load->view('admin/tpl.html',$data);
	}

	/*
	 *  修改密码提交
	 */
	public function user_send(){
		$oldpass = asp($this->input->post('oldpass'));
		$data['password'] = asp($this->input->post('newpass'));
		$this->load->model('user_model','user');
		$user = $this->user->check_user();
		if($oldpass != $user[0]['password'] ){
			echo 0;die;
		}
		if($this->user->upload_user($data)){
			echo 1;
		}else{
			echo 2;
		}
	}


	/*
	*	富文本编辑器的图片上传
	*/
	public function upload(){
		$serial = $this->input->post('serial');
		$direct = $this->input->post('direct');
		$fileField = $this->input->post('fileField');

		$file = $_FILES[$fileField];
		$tmp_name = $file['tmp_name'];

		$dirpath = 'public/upload/'.$direct;
		$fullpath = $dirpath.'/'.$file['name'];
		$filepath = $direct.'/'.$file['name'];
		$errorpath = 'upload-false.png';
		if ($file["error"] > 0){
		  	echo $errorpath;
		  	die;
	  	}
		if(!file_exists($file['tmp_name'])){
			echo $errorpath;
			die;
		}
		if(!file_exists($dirpath)){
			mkdir($dirpath);
		}
		if(!move_uploaded_file($tmp_name, $fullpath)){
			echo $errorpath;
			die;
		}
		$this->load->model('image_model','image');
		$data['serial'] = $serial;
		$data['filepath'] = $filepath;
		$data['filetype'] = 2;
		$data['time'] = date('YmdHis');
		if(!$this->image->add_image($data)){
			echo $errorpath;
		}
		echo site_url().$fullpath;
		
	}
	/*
	*	正常缩略图/封面的上传管理
	*/
	public function thumb_upload(){
		$this->load->model('image_model','image');
		$serial = $this->input->post('serial');
		$direct = $this->uri->segment(4);
		$fileField = $this->uri->segment(5);
		$this->load->model('image_control','image');

		$file = $_FILES[$fileField];
		$tmp_name = $file['tmp_name'];
		list($filename,$suffix) = explode('.', $file['name']);

		$dirpath = 'public/upload/'.$direct;
		$filepath = $direct.'/'.(round(microtime(true)*1000)*3).".".$suffix;
		$fullpath = 'public/upload/'.$filepath;
		$falseFile = 'upload-false.png';
		if ($file["error"] > 0){
		  	echo $falseFile;
		  	die;
	  	}
		if(!file_exists($file['tmp_name'])){
			echo $falseFile;
			die;
		}
		if(!file_exists($dirpath)){
			mkdir($dirpath);
		}
		if(!move_uploaded_file($tmp_name, $fullpath)){
			echo $falseFile;
		}else{
			echo $filepath;
			$data['serial'] = $serial;
			$data['filepath'] = $filepath;
			$this->image->add_image($data);
		}
	}


	public function flow_edit(){
		$data['current_nav'] = 'flowEdit';
		$data['path'] = '留言管理 / 申请列表';
		$data['company'] = '上海杰汉申若丝琳装饰有限公司';
		$data['subpage'] = 'admin/flow_edit.html';
		$this->load->model('flow_model','flow');
		$data['flow'] = $this->flow->check_flow()[0];
		// var_dump($data);
		$this->load->view('admin/tpl.html', $data);
	}
	public function flow_send(){
		$this->load->model('flow_model','flow');
		$serial = $this->input->post('serial');
		$data['content'] = $this->input->post('content');
		$data['time'] = rtime();
		// var_dump($data);
		// echo $this->flow->add_flow($data);
		if($this->flow->update_serial($serial,$data)){
			echo 1;
		}else{
			echo 0;
		};
	}

}
