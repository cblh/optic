<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * 显示主页和通知内容
	 */
	public function index()
	{	
		//查询是否要打开登录界面
		// $login_show = $this->input->get_post('login_show');
		// $login_out = $this->input->get_post('login_out');
		// $data['login_show'] = $login_show;
		// $data['login_out'] = $login_out;
		// echo $login_show;
		$this->load->library('session');
		//检测用户是否已经登录
		// var_dump($this->session->userdata['uid']);die();
		if(!($this->session->userdata('uid'))){
			//echo '未登录';/*把引导放这里*/
			$this->load->view('welcome');	
		}
		else{
		//读取cookie session；
		$data['uid'] = $this->session->userdata('uid');
		$data['username'] = $this->session->userdata('username');
		$data['class'] = $this->session->userdata('class');
		$data['password'] = $this->session->userdata('password');
		$data['duty'] = $this->session->userdata('duty');
		$data['allow_admin'] = $this->session->userdata('allow_admin');
		//查询前端用户未通知的数据内容
		$this->load->model('user_model','',TRUE);
		$data['event'] = $this->user_model->check_event($data['username'],$data['class']);
		$data['event_num'] = $this->user_model->check_event_num($data['username'],$data['class']);
		// var_dump($data);die();
		//载入视图，将通知内容传递
		$this->load->view('welcome',$data);		
		}

	}
	//get写入用户记录，记录已查看通知
	public function insert_record()
	{
		$this->load->library('session');
		$data['username'] = $this->session->userdata('username');
		 if($_GET["username"]==$data['username']){
			 $user_record = $this->input->get();
			 $this->load->model('user_model','',true);
			 $this->user_model->insert_record($user_record);
			//$this->load->database();
			//$this->db->update('event', array('notice_num' => '1'), array('ntid' => $_GET['ntid']));
			//echo "debug"; 
			//by Ervine done!
		 }
	}
	//get检查网址健康
	public function IsOk()
	{
		if(@fopen($_GET["url"], "r")){
		echo "1";
		}else{
		echo "0";
		}
	}
	//get检查用户通知
	public function notic()
	{
		$this->load->library('session');
		//读取cookie session；
		$data['uid'] = $this->session->userdata('uid');
		$data['username'] = $this->session->userdata('username');
		$data['class'] = $this->session->userdata('class');
		$this->load->model('user_model','',TRUE);
		$data['event'] = $this->user_model->check_event($data['username'],$data['class']);
		$data['event_num'] = $this->user_model->check_event_num($data['username'],$data['class']);	
				// if(!($this->session->userdata('uid'))){echo "你没有登录";die();}
		if ($data['event_num'])
		{
			echo '{"item":[';
			foreach($data['event'] as $item)
			{
				echo '{"ntid":"';echo $item->ntid;echo '",';
				echo '"content":"';echo $item->content;echo '",';
				echo '"title":"';echo $item->title;echo '"},';
			}
			echo '],"username":"';
			echo $data['username'];//有空把这个弄掉
			echo '"}';
		}
		else
		{die();}
	}
	//注册
	public function register(){
		
	}
	/**
	*登录界面
	*/
	public function login_iframe(){
		$this->load->view('login_iframe');		
	}
	/**
	*登录验证
	*/
	public function login_validation(){
		$this->load->helper(array('form','url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE)
  		{
  			echo '验证失败';
		}
		else
		{
			// echo '验证成功';
			$this->load->model('user_model','',true);
			//检测用户密码是否正确
			$user_login = $this->user_model->check_user($this->input->get_post('username'),$this->input->get_post('password'));
			if(!($user_login)){
				$data['error'] = '用户不存在或密码错误，请认真检查' ;
				$this->load->view('login_iframe',$data);
				// redirect('', 'location');				
			}
			else{
				$user_data = $user_login;
				$this->load->library('session');
				//设置cookie session；
				$this->session->set_userdata(array('uid' => $user_data['uid'],'username' => $user_data['username'],'class' => $user_data['class'],'password' => $user_data['password'],'duty' => $user_data['duty'],'allow_admin' => $user_data['allow_admin']));
				//读取cookie session；
				$data['uid'] = $this->session->userdata('uid');
				$data['username'] = $this->session->userdata('username');
				$data['class'] = $this->session->userdata('class');
				$data['password'] = $this->session->userdata('password');
				$data['duty'] = $this->session->userdata('duty');
				$data['allow_admin'] = $this->session->userdata('allow_admin');
				redirect('', 'location');
			}

		}
	}

	/**
	*用户修改密码
	*/
	public function change_password(){
		$this->load->model('user_model','',true);
		$password = $this->input->post('password_f');
		$data = array(
			'password' => md5($password)
			);
		$this->user_model->change_password($uid);
	}

	/**
	*退出登录
	*/
	public function login_out(){
		$this->load->library('session');
		$this->session->sess_destroy();
		redirect();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */