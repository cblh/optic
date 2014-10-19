<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<html>
<head>
<title>左边的菜单形式</title>
<link rel="stylesheet" href="<?php echo base_url();?>/static/css/admin/base.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url();?>/static/css/admin/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript'>var curopenItem = '1';</script>
<script language="javascript" type="text/javascript" src="<?php echo base_url();?>/static/js/admin/menu.js"></script>
<base target="main" />
</head>
<body target="main">
<table width='99%' height="100%" border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td style="padding-left:3px;padding-top:8px" valign="top">
    	<!-- Item  Strat -->
    	<?php 
    
    		if($result['left']){
    			$index = 1 ;
    			foreach($result['left'] as $k=>$v){    		
    	?>
      <dl class="bitem">
        <dt onclick='showHide("items<?php echo $index ;?>_1")'><b><?php echo $k ;?></b></dt>
        <dd style="display:block" class="sitem" id="items<?php echo $index ;?>_1">
          <ul class="sitemu">	
          			  <?php 
          			  	if($v){
          			  		foreach($v as $child_key=>$child_val){   
          			  		         			  		
       			  ?>
                      <li><a href="<?php echo site_url($child_val['url']) ;?>" target="main"><?php echo $child_key ;?></a> </li>
                    <?php 
    					}
    						}
                    ?>
                    </ul>
        </dd>
      </dl>
      
      <?php 
			$index++ ;
      	}
      	
      	}
      ?>
      </td>
       
  <!-- Item 1 End -->

    
  </tr>
</table>
</body>
</html>