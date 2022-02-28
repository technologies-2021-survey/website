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
		if($this->admin_model->session() == 1) { redirect("admin/main"); } else { }
		$this->load->view('Admin/Include/header', $data);
		$this->load->view('Admin/Admin_index');
		$this->load->view('Admin/Include/footer');
	}
	public function login() {
		if($this->admin_model->session() == 1) { redirect("admin/main"); } else { }

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
		if($this->admin_model->session() == 0) { redirect("admin/index"); } else { }
		$data = array(
			'title' => 'Admin Dashboard | BookACleaner',
			'id' => $this->admin_model->user(),
			'username' => $this->admin_model->user('username')
		);

		$this->load->view('Admin/Include/header', $data);
		$this->load->view('Admin/Admin_main', $data);
		$this->load->view('Admin/Include/footer');
	}
	public function logout() {
		$data = $this->session->all_userdata();
		foreach($data as $row => $rows_value) {
			$this->session->unset_userdata($row);
		}
		
		redirect('home');
	}
}
?>