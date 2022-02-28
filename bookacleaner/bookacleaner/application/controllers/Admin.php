<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model', null, true); // auto-connect model
	}

    public function index() {
		$data = array(
			'title' => 'Admin Dashboard | BookACleaner',
		);
		$this->load->view('Admin/Include/header', $data);
		$this->load->view('Admin/Admin_index');
		$this->load->view('Admin/Include/footer');
	}
	public function login() {
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[5]', 
			array(
				"min_length" => "Your username is incorrect."
			)
		);
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]', 
			array(
				"min_length" => "Your password is incorrect."
			)
		);
		$result = $this->admin_model->login($this->input->post('username'), $this->input->post('password'));

		if($this->form_validation->run() == FALSE) {
			echo $this->admin_model->status('203', 'Error! Please input username and password!');
		} else {
			if($result == 'Success') {
				echo $this->admin_model->status('200', $result);
			} else {
				echo $this->admin_model->status('203', $result);
			}
		}
	}

	public function main() {
		// Session
		if($this->session->userdata('id')) {
			redirect('admin/main');
		}
	}
	
}
?>