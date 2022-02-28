<?php

class MY_Controller extends CI_Controller { 
	public function home_page($page, $arrayData = []) {
		$this->load->view('Home/Include/header.php', $arrayData);
		$this->load->view('Home/'.$page);
		$this->load->view('Home/Include/footer.php');
	}
}