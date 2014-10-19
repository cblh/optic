<?php 
	class Captcha extends MY_Controller{
		
		function aa(){
			
			$this->load->helper('captcha');
			$vals = array(
			    'word' => "wodssas".rand(1,2),
			    'img_path' => './data/captcha/',
			    'img_url' => base_url()."data/captcha/",
			    'font_path' => '',
			    'img_width' => '100',
			    'img_height' => 30,
			    'expiration' => 60
    );

			$cap = create_captcha($vals);
			echo '<pre>';
			print_r($cap);
			echo $cap['image'];
		}
	}
?>