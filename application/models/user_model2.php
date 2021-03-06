<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*前端用户管理模型
*/
class User_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	/**
	*前端用户验证查询
	*/
	public function check_user($username,$password){
		$password =md5($password);
		$this->load->database();
		//查询验证用户密码是否正确
		$query_user = $this->db->get_where('user',array('username' => $username, 'password' => $password));
		// var_dump($query_user);die();

		if($query_user->num_rows() == 0){
		show_error('bucunzai') ;
		}else{
		echo '用户存在，密码正确';
		// echo '用户权限'.$query_user->row_array(0)['duty'];
		$row = $query_user->row_array(0);

		$this->load->library('session');
		/**
		*设置cookie session；
		*/
		$this->session->set_userdata(array('uid' => $row['uid'],'username' => $row['username'],'class' => $row['class'],'password' => $row['password']));
		/**
		*读取cookie session；
		*/
		$data['uid'] = $this->session->userdata('uid');
		$data['username'] = $this->session->userdata('username');
		$data['class'] = $this->session->userdata('class');
		$data['passwd'] = $this->session->userdata('passwd');

		$user_data = $row;
		// var_dump($user_data);
		return $user_data;
		 }


// @header("Location:".site_url('admin/index/frame'));	
	}
	/**
	*查询前端用户未通知的数据内容
	*/
	public function check_event($username,$class){
		//查询用户访问过的通知记录
		$this->load->database();
		$query_record = $this->db->get_where('record',array('username' => $username));
		$ntid_rows = $query_record->result_array();
		//查询用户未访问过的通知内容
		if ($query_record->num_rows())
		$last_record = $ntid_rows[$query_record->num_rows()-1]['ntid'];
		else $last_record=0;
		//设置当前北京时区，对时间进行初始化
		@date_default_timezone_set(PRC);
		$query_event = $this->db->get_where('event',array('ntid >' => $last_record,'class' => $class,'deadtime <' => date('Y-m-d H:i:s',time())));
		return $data['event'] = $query_event->result();
		// return $data['num'] = $query_event->num_rows();
		
	}
	/**
	*查询前端用户未通知的数据总数
	*/
	public function check_event_num($username,$class){
		//查询用户访问过的通知记录
		$this->load->database();
		$query_record = $this->db->get_where('record',array('username' => $username));
		$ntid_rows = $query_record->result_array();
		//查询用户未访问过的通知内容
		if ($query_record->num_rows())
		$last_record = $ntid_rows[$query_record->num_rows()-1]['ntid'];
		else $last_record=0;
		//设置当前北京时区，对时间进行初始化
		@date_default_timezone_set(PRC);
		$query_event = $this->db->get_where('event',array('ntid >' => $last_record,'class' => $class,'deadtime <' => date('Y-m-d H:i:s',time())));
		// return $data['event'] = $query_event->result();
		return $data['num'] = $query_event->num_rows();
		
	}
	//写入用户的通知查看记录
	public function insert_record($user_record)
	{
		$this->load->database();
		$this->db->insert('record', $user_record); 	
	}
	/**
	*修改密码
	*/
	public function change_passwd($username,$data){
		$this->load->database();
		$this-db-update('user', $data, array('username' =>$username , ));
	}
}
