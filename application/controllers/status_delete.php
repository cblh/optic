<?php 


/*
*author wangjian
*time 2013-03-16
*/
class Status_delete extends CI_Controller {
        //更改删除属性
    function index(){
        $ntid = $this->input->get_post('ntid') ;
        // echo $ntid;
        $this->load->database();
        $sql_is_delete = "UPDATE `nt_event` SET `status_delete` = '已删除' WHERE `ntid` = '{$ntid}' " ;
        $this->db->query($sql_is_delete);
        echo '第'.$ntid.'条删除成功';

    }

}
 