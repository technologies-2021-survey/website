<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('home_model', null, true); // auto-connect model
	}

	public function index() {
		$data = array(
			'title' => 'Home | BookACleaner',
			'content' => 'Home'
		);
		$this->load->view('Home/Include/header', $data);
		$this->load->view('Home/Home_index');
		$this->load->view('Home/Include/footer');
	}
	public function book() {
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');
		$this->form_validation->set_rules('email', 'E-mail Address', 'required');
		$this->form_validation->set_rules('mobile_number', 'Mobile Number', 'required');
		$this->form_validation->set_rules('preferred_date', 'Preferred Date', 'required');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('cleaning', 'I need cleaning for', 'required');
		$this->form_validation->set_rules('sqm', 'How big is the space that needs cleaning? (sqm).', 'required');
		$this->form_validation->set_rules('service_required[]', 'Deep Cleaning', 'required');
		$this->form_validation->set_rules('service_required[]', 'Disinfection Service', 'required');
		$this->form_validation->set_rules('service_required[]', 'Move In & Move Out Cleaning', 'required');
		$this->form_validation->set_rules('service_required[]', 'Upholstery Cleaning', 'required');
		$this->form_validation->set_rules('service_required[]', 'Steam Cleaning', 'required');
		$this->form_validation->set_rules('service_required[]', 'Aircon Cleaning', 'required');
		$this->form_validation->set_rules('comments_or_notes', 'Comments/Notes', 'required');

		if($this->form_validation->run() == FALSE) {
			echo $this->home_model->status(203, 'Error! Please input the fields!');
		} else {
			$check = 0;
			for($i = 0; $i < 6; $i++) {
				if($this->input->post('service_required['.$i.']') != "") {
					$check++;
				}
			}
			if($check == 0) {
				echo $this->home_model->status(203, 'Error! Please select service required!');
			} else {
				if($this->input->post('cleaning') == "Please select") {
					echo $this->home_model->status(203, 'Error! Please select one!');
				} else {
					$uniq_id = md5(uniqid(rand(), true));
					$uniq_id2 = $uniq_id;
					
					if($this->home_model->check_uniq_id($uniq_id2) == 1) {
						$uniq_id = md5(uniqid(rand(), true));
						$uniq_id2 = $uniq_id;
					}

					$insertData = array(
						'unique_id' => $uniq_id2,
						'first_name' => $this->input->post('first_name'),
						'last_name' => $this->input->post('last_name'),
						'email' => $this->input->post('email'),
						'mobile_number' => $this->input->post('mobile_number'),
						'preferred_date' => $this->input->post('preferred_date'),
						'address' => $this->input->post('address'),
						'cleaning' => $this->input->post('cleaning'),
						'sqm' => $this->input->post('sqm'),
						'first_name' => $this->input->post('first_name'),
						'comments_or_notes' => $this->input->post('comments_or_notes')
					);

					$this->home_model->insertBook($insertData);

					$getBookId = $this->home_model->getBookId($uniq_id2);

					for($x = 0; $x < 6; $x++) {
						if($this->input->post('service_required['.$x.']') != "") {
							$insertData2 = array(
								'bookings_id' => $getBookId,
								'choice' => $x
							);
							
							$this->home_model->insertServiceRequired($insertData2);
						}
					}

					//echo $this->home_model->status(203, 'Service Required: ' . $check);
					echo $this->home_model->status(200, 'Successfully booked!');
				}
			}
		}
	}
}
