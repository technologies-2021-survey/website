<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
date_default_timezone_set("Asia/Manila");

class Form extends CI_Controller {
	public function index() {
		if($this->form_validation->run() == FALSE) {
			$this->load->view('myform');
		} else {
			$this->load->view('success');
		}

	}
}