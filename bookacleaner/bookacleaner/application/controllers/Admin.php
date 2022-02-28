<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct() {
		parent::__construct();
		//$this->load->model('home_model', null, true); // auto-connect model
	}
    public function index() {
		$data = array(
			'title' => 'Admin Dashboard | BookACleaner',
		);
		$this->load->view('Admin/Include/header', $data);
		$this->load->view('Admin/Admin_index');
		$this->load->view('Admin/Include/footer');
	}
	public function login($username = "", $password = "") {
		$result = $this->admin_model->login($username, $password);
		if($result == 'Success') {
			$this->admin_model->status('200', $result);
		} else {
			$this->admin_model->status('203', $result);
		}
	}
	
}
?>