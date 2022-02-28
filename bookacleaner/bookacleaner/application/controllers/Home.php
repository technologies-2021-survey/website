<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('home_model', null, true); // auto-connect model
	}

	public function index()
	{
		$data = array(
			'title' => 'Home | BookACleaner',
			'content' => 'Home'
		);
		$this->load->view('Home/Include/header', $data);
		$this->load->view('Home/Home_index');
		$this->load->view('Home/Include/footer');
	}

}
