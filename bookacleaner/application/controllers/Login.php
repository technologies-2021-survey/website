<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Manila");

class Login extends MY_Controller {
	public function __construct() {
		parent::__construct();
		
		$this->load->model('login_model', null, true); // auto-connect model
		
		if($this->session->userdata('id') && $this->session->userdata('selection')) {
			if($this->session->userdata('selection') == "doctor") {
			    redirect('doctor');
			} else if($this->session->userdata('selection') == "receptionist") {
			    redirect('receptionist');
			} else if($this->session->userdata('selection') == "administrator") {
			    redirect('administrator');
			}
		}
	}

	public function index() {

		$data = array(
			'title' => 'Login | WHealth',
			'content' => 'Login'
		);

		$this->load->view('Home/Include/header', $data);
		$this->load->view('Home/Home_login');
		$this->load->view('Home/Include/footer');
	}

	public function validation() {
		$data = array(
			'title' => 'Login | WHealth',
			'content' => 'Login'
		);

		// form valdiation
		$this->form_validation->set_rules('user_name', 'Username', 'required|alpha_numeric|min_length[5]', 
            array(
                "min_length" => "Your username is incorrect."
            )
        );
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]', 
            array(
                "min_length" => "Your password is incorrect."
            )
        );
		$this->form_validation->set_rules('selection', 'Selection', 'required');

		//
		if($this->form_validation->run() == FALSE) {
			$this->load->view('Home/Include/header', $data);
			$this->load->view('Home/Home_login');
			$this->load->view('Home/Include/footer');
		} else {
			$selection = $this->input->post('selection');
			$result = $this->login_model->can_login($this->input->post('user_name'), $this->input->post('password'), $this->input->post('selection'));
			if($result == '') {
				if($selection == "doctor") {
					redirect("doctor");
				} else if($selection == "administrator") {
					redirect("administrator");
				} else if($selection == "receptionist") {
					redirect("receptionist");
				}
			} else {
				redirect('login?error='.$result);
			}
		}
	}
}
?>