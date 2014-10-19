<?php 


/*
*author wangjian
*time 2013-03-16
*/
class Index extends MY_Controller {
	private $username ;//登录的用户
	private $admin ;//创始人
	private $group_name ;//群组
	private  $ip  ;//登录的ip地址
	private $table_ ; //表的前缀
    function Index(){
        parent::__construct();
		$this->load->library('admin_common');//加载admin的公用方法
		$this->load->library('session');//加载session
		$this->username = $this->get_login_name(); //得到登录的用户名
		$this->load->model('admin/members/M_members');
		$this->load->model('admin/team/M_duty_perm');
		$this->admin = $this->config->item('web_admin_master');//创始人
		$this->group_name = ($this->admin == $this->username)?'管理员':($this->admin_common->group_name());
		$this->ip = $this->admin_common->get_client_ip();
		$this->table_ = $this->admin_common->table_pre('real_data');
    }                       
    //top
    function top(){
    	
		$data=array(
			'username'=>$this->username,
			'group_name'=>$this->group_name,
			'ip'=>$this->ip ,
		);
        $this->load->view('admin/top',$data);
    }
    //menu
    function menu(){
    	$permition_array =$this->admin_common->return_nav_array();//所有的权限
    	
    	//从cookie获取登录的权限
   		 if(isset($_COOKIE[$this->config->item('cookie_prefix').'permition'])){
				$permition = $_COOKIE[$this->config->item('cookie_prefix').'permition'];
		   		 if($permition){
						$one_res_perm = unserialize($permition) ;
				 }
		}
	 	if($this->admin != $this->username){//如果不是超级管理员的话
    		if($permition_array['left']){
    			foreach($permition_array['left'] as $k1=>$v1){
    			
    				if($v1){
    					foreach($v1 as $child_key=>$child_val){
    						
    						//if(!in_array($child_val['url'])){
    						//	unset($permition_array['left'][$k1][$child_key]) ;
    						//}
    					}
    				}
    			}
    		}
			if($permition_array['left']){
				foreach($permition_array['left'] as $p_k=>$p_v){
					if(empty($p_v)){
						unset($permition_array['left'][$p_k]);
					}
				}
			}
    		
    	}
    	
    	$data['result'] = $permition_array ;
    	
    	
        $this->load->view('admin/menu',$data);
    }
    // function main1(){
    // 	$post_data = $this->input->post(NULL,true) ;
    
    // 	$data=array(
    // 			'version'=>$this->config->item('system_version'),
    // 			'group_name'=>$this->group_name,
				// 'web_admin_master'=>$this->config->item('web_admin_master'),
				// 'web_admin_email'=>$this->config->item('web_admin_email'),
				// 'is_need_passwd'=>($this->config->item('is_need_passwd'))?'是':'<font color="red">否</font>',
				// 'is_write_log_to_database'=>($this->config->item('is_write_log_to_database'))?'是':'<font color="red">否</font>',
				// 'is_need_yzm'=>($this->config->item('is_need_yzm'))?'是':'<font color="red">否</font>',
    // 	);
    // 	 $this->load->model('admin/admin_message/M_messages');
    // 	 if($this->username == $this->admin){
    // 	 	$sql_count="SELECT * FROM `{$this->table_}common_message` ORDER BY message_publish_time DESC ";
    // 	 }else{
    // 	 	$sql_count="SELECT * FROM `{$this->table_}common_message` WHERE `message_to_person` = '{$this->username}' ORDER BY message_publish_time DESC ";
    // 	 }
    	 
    // 	 $total = $this->M_messages->query_count($sql_count);
    // 	 $page = '' ;
    // 	 if(!isset($post_data['page'])){
    // 	 	$page  =1 ;
    // 	 }else{
    // 	 	$page = intval($post_data['page']);
    // 	 }
    	
    // 	 $page_num = 10 ;
    //      $page_string = $this->page_string($total, $page_num, $page);
    // 	 $limit = ($page-1)*$page_num;
    // 	 if($limit<0){$limit=1;}
    // 	 if($this->username == $this->admin){
    // 	 	$sql="SELECT * FROM `{$this->table_}common_message`  ORDER BY message_publish_time DESC  LIMIT ".$limit.",".$page_num;;
    // 	 }else{
    // 	 	$sql="SELECT * FROM `{$this->table_}common_message` WHERE `message_to_person` = '{$this->username}' ORDER BY message_publish_time DESC  LIMIT ".$limit.",".$page_num;;
    // 	 }
    
	
    // 	 $res = $this->M_messages->querylist($sql);
    // 	 $site_url = base_url() ;
    	 
    // 	 if($res){
    // 	 	foreach($res as $k=>$v){
    // 	 		$res[$k]['small_content'] = $this->admin_common->msubstr($v['message_content'],0,10,$this->admin_common->abslength($v['message_content']),'utf-8',true); 
    // 	 		$res[$k]['message_status'] = ($v['message_status']==0)?"<img src='{$site_url}static/image/admin/notice.gif'>".'<font color="red">未读</font>':'<font color="green">已读</font>' ;
    // 	 	}
    // 	 }
    // 	 $data['list'] = $res ;
    // 	 $data['page'] =$page_string ;
    	
    // 	   if(isset($post_data['action'])){
    // 	 	if($post_data['action'] == 'one'){
    // 	 		 echo $this->admin_common->result_to_towf_new($data,1,0,'');
    // 	 		 die();
    // 	 	}else if($post_data['action'] == 'is_read'){
    // 	 		//处理是否已经读了信息
    // 	 		$id = intval($post_data['id']);
    // 	 		if($id > 0){
    // 	 			$sql_is_read = "UPDATE `{$this->table_}common_message` SET `message_status` = '1' WHERE `message_id` = '{$id}' AND `message_to_person` = '{$this->username}'" ;
    	 			
    // 	 			$num = $this->M_messages->update_data($sql_is_read);
    // 	 			if($num>=1){
    // 	 			 	echo $this->admin_common->result_to_towf_new('',1,0,'');
    // 	 			}else{
    // 	 				echo $this->admin_common->result_to_towf_new('',0,0,'');
    // 	 			}
    // 	 			die();
    // 	 		}
    // 	 	}
    // 	 } 
    	
    //      $this->load->view('admin/main',$data);
    // }


    
    //显示我们已经发布的通知


    function main(){
        $post_data = $this->input->post(NULL,true) ;
        $data=array(
                'version'=>$this->config->item('system_version'),
                'group_name'=>$this->group_name,
                'web_admin_master'=>$this->config->item('web_admin_master'),
                'web_admin_email'=>$this->config->item('web_admin_email'),
                'is_need_passwd'=>($this->config->item('is_need_passwd'))?'是':'<font color="red">否</font>',
                'is_write_log_to_database'=>($this->config->item('is_write_log_to_database'))?'是':'<font color="red">否</font>',
                'is_need_yzm'=>($this->config->item('is_need_yzm'))?'是':'<font color="red">否</font>',
        );
         $this->load->model('admin/admin_message/M_messages');
         $this->load->library('table');
            $sql_count="SELECT * FROM `nt_event` ORDER BY publish_time DESC ";
         $total = $this->M_messages->query_count($sql_count);
         $page = '' ;
         if(!isset($post_data['page'])){
            $page  =1 ;
         }else{
            $page = intval($post_data['page']);
         }

         $page_num = 10 ;
         $page_string = $this->page_string($total, $page_num, $page);
         $limit = ($page-1)*$page_num;
         if($limit<0){$limit=1;}
            $sql="SELECT * FROM `nt_event` ORDER BY publish_time DESC  LIMIT ".$limit.",".$page_num;;
         $res = $this->M_messages->querylist($sql);
         // var_dump($res);die();
         // echo $this->table->generate($res);die();
         $site_url = base_url() ;
         
         if($res){
            foreach($res as $k=>$v){
                $res[$k]['small_content'] = $this->admin_common->msubstr($v['content'],0,10,$this->admin_common->abslength($v['content']),'utf-8',true); 
                $res[$k]['message_status'] = '';
            }
         }
         $data['list'] = $res ;
         $data['page'] =$page_string ;
        
           if(isset($post_data['action'])){
            if($post_data['action'] == 'one'){
                 echo $this->admin_common->result_to_towf_new($data,1,0,'');
                 die();
            }else if($post_data['action'] == 'is_delete'){
                //处理是否已经读了信息
                $utid = intval($post_data['utid']);
                if($utid > 0){
                    $sql_is_read = "UPDATE `nt_event` SET `status_delete` = '删除' WHERE `utid` = '{$utid}' " ;
                    
                    $num = $this->M_messages->update_data($sql_is_read);
                    if($num>=1){
                        echo $this->admin_common->result_to_towf_new('',1,0,'');
                    }else{
                        echo $this->admin_common->result_to_towf_new('',0,0,'');
                    }
                    die();
                }
            }
         }         
         $this->load->view('admin/main',$data);
    }
    //更改删除属性
    function status_delete(){
        // $this->load->view('admin/top');die();
        $this->load->model('admin/admin_message/M_messages');
        $utid = $this->input->get_post['utid'];
        echo $utid;
        $sql_is_delete = "UPDATE  `nt_event` SET  `status_delete` =  '删除' WHERE  `ntid` =73; " ;
        $this->M_messages->update_data($sql_is_delete);
        echo '第'.$utid.'条删除成功';

    }
    /*@function page_string
     *@分页返回数据
    *@total 总的数量
    *@page_num 每一页显示的数量
    *@page 当前是第几页数据
    */
    private function page_string($total,$page_num,$page){
    	$page_string = '' ;
    	$this->load->library('pagination');//加载分页类
    	$this->load->library('MY_Pagination');//加载分页类
    	$config['total_rows'] = $total;
    	$config['use_page_numbers'] =true; // 当前页结束样式
    	$config['per_page'] = $page_num; // 每页显示数量，为了能有更好的显示效果，我将该数值设置得较小
    	$config['full_tag_open'] = '<div class="pg">'; // 分页开始样式
    	$config['full_tag_close'] = '</div>'; // 分页结束样式
    	$config['first_link'] = '首页'; // 第一页显示
    	$config['last_link'] = '末页'; // 最后一页显示
    	$config['next_link'] = '下一页 >'; // 下一页显示
    	$config['prev_link'] = '< 上一页'; // 上一页显示
    	$config['cur_tag_open'] = ' <a class="current">'; // 当前页开始样式
    	$config['cur_tag_close'] = '</a>'; // 当前页结束样式
    	$config['uri_segment'] = 6;
    	$config['anchor_class']='class="ajax_page" ';
    	$this->pagination->cur_page = $page ;
    	$this->pagination->initialize($config); // 配置分页
    	$page_string =  $this->pagination->create_links(true,'ajax_data');
    	return $page_string ;
    }    
    //后台框架首页方法
    function frame(){
    	
    	$this->load->view("admin/frame");
    }

    
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */