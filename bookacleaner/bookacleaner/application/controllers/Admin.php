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
		if($this->admin_model->session() == 1) { redirect(base_url() . "admin/main"); } else { }
		$this->load->view('Admin/Include/header_login', $data);
		$this->load->view('Admin/Admin_index');
		$this->load->view('Admin/Include/footer_login');
	}
	public function login() {
		if($this->admin_model->session() == 1) { redirect(base_url() . "admin/main"); } else { }

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
			echo $this->admin_model->status(203, 'Error! Please input username and password!');
		} else {
			if($result == 'Success') {
				echo $this->admin_model->status(200, $result);
			} else {
				echo $this->admin_model->status(203, $result);
			}
		}
	}

	public function main() {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }
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
		if($this->admin_model->session() == 1) { redirect(base_url() . "admin/main"); } else { }
		$data = $this->session->all_userdata();
		foreach($data as $row => $rows_value) {
			$this->session->unset_userdata($row);
		}
		
		redirect(base_url() . 'admin/index');
	}

	public function dashboard() {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }
		$data = array(
			'title' => 'Cleaner\'s List | BookACleaner',
			'id' => $this->admin_model->user(),
			'username' => $this->admin_model->user('username')
		);

		$this->load->view('Admin/Include/header', $data);
		$this->load->view('Admin/Admin_cleaners', $data);
		$this->load->view('Admin/Include/footer');
	}

	public function accounts() {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }
		$data = array(
			'title' => 'Accounts\'s List | BookACleaner',
			'id' => $this->admin_model->user(),
			'username' => $this->admin_model->user('username')
		);

		$this->load->view('Admin/Include/header', $data);
		$this->load->view('Admin/Admin_accounts', $data);
		$this->load->view('Admin/Include/footer');
	}

	public function getCleaners($page_number = "") {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }
		if($page_number != "") {
			if($page_number <= 0) {
				$page_number = 1; 
			} else {
				$page_number = $page_number;
			}
			
		} else {
			$page_number = 1;
		}
		$no_of_records_per_page = 10;
        $offset = ($page_number - 1) * $no_of_records_per_page;

		$total_pages_sql = "SELECT COUNT(*) FROM `cleaners`";
        $result = $this->db->query($total_pages_sql)->row_array();

		$total_rows = $result['count(*)'];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

		$array = array();

		$get_data = $this->db->query("SELECT * FROM `cleaners` ORDER BY `id` DESC LIMIT $offset, $no_of_records_per_page");

		foreach($get_data->result() as $row) {
			$search = $this->db->query("SELECT * FROM `cleaners_on_work` WHERE `cleaners_id` = '".$row->id."'")->num_rows();

			$array[] = array(
				'id' =>  $row->id,
				'cleaners_name' =>  $row->cleaners_name,
				'cleaners_contact' =>  $row->cleaners_contact,
				'employee' => $row->employee,
				'available' => $search
			);
		};

		echo json_encode($array);
	}

	public function getAccounts($page_number = "") {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }

		if($page_number != "") {
			if($page_number <= 0) {
				$page_number = 1; 
			} else {
				$page_number = $page_number;
			}
			
		} else {
			$page_number = 1;
		}
		$no_of_records_per_page = 10;
        $offset = ($page_number - 1) * $no_of_records_per_page;

		$total_pages_sql = "SELECT COUNT(*) FROM `accounts`";
        $result = $this->db->query($total_pages_sql)->row_array();

		$total_rows = $result['count(*)'];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

		$array = array();

		$get_data = $this->db->query("SELECT * FROM `accounts` ORDER BY `id` DESC LIMIT $offset, $no_of_records_per_page");

		foreach($get_data->result() as $row) {
			$array[] = array(
				'id' =>  $row->id,
				'full_name' =>  $row->full_name
			);
		};

		echo json_encode($array);
	}
	public function fireCleaner($id) {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }

		$search = $this->db->query("SELECT * FROM `cleaners_on_work` WHERE `cleaners_id` = '".$id."'")->num_rows();

		if($search == 0) {
			$data = array(
				'employee' => 1,
			);
			$this->admin_model->updateCleaners($id, $data);
			echo $this->admin_model->status(200, 'Successfully!');
		} else {
			echo $this->admin_model->status(203, 'Error, this worker is working!');
		}
	}

	public function hireCleaner($id) {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }

		$search = $this->db->query("SELECT * FROM `cleaners_on_work` WHERE `cleaners_id` = '".$id."'")->num_rows();

		if($search == 0) {
			$data = array(
				'employee' => 0,
			);
			$this->admin_model->updateCleaners($id, $data);
			echo $this->admin_model->status(200, 'Successfully!');
		} else {
			echo $this->admin_model->status(203, 'Error, this worker is working!');
		}
	}
	public function insertCleaner() {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }

		$this->form_validation->set_rules('cleaners_name', 'Full Name', 'required');
		$this->form_validation->set_rules('cleaners_contact', 'Contact Number', 'required');
		$result = $this->admin_model->insertCleaner($this->input->post('cleaners_name'), $this->input->post('cleaners_contact'));

		if($this->form_validation->run() == FALSE) {
			echo $this->admin_model->status(203, 'Error! Please input full name and contact number!');
		} else {
			if($result == 'Success') {
				echo $this->admin_model->status(200, $result);
			} else {
				echo $this->admin_model->status(203, $result);
			}
		}
	}
	public function getBookings($page_number = "") {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }
		if($page_number != "") {
			if($page_number <= 0) {
				$page_number = 1; 
			} else {
				$page_number = $page_number;
			}
			
		} else {
			$page_number = 1;
		}
		$no_of_records_per_page = 10;
        $offset = ($page_number - 1) * $no_of_records_per_page;

		$total_pages_sql = "SELECT COUNT(*) FROM `bookings`";
        $result = $this->db->query($total_pages_sql)->row_array();

		$total_rows = $result['count(*)'];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

		$array = array();

		$get_data = $this->db->query("SELECT * FROM `bookings` ORDER BY `id` DESC LIMIT $offset, $no_of_records_per_page");

		foreach($get_data->result() as $row) {
			// who's working?
			$work_cleaner_id = "";
			$work_cleaner_name = "";
			$work_query = $this->db->query("SELECT * FROM `cleaners_on_work` WHERE `bookings_id` = '".$row->id."'")->result_array();
			if($work_query['cleaners_id'] != "") {
				$work_cleaner_id = $work_query['cleaners_id'];
				$work_cleaner_query = $this->db->query("SELECT * FROM `cleaners` WHERE `id` = '".$work_cleaner_id."'")->result_array();
				$work_cleaner_name = $work_cleaner_query['cleaners_name'];
			}

			// service required
			$service_required = array();

			$service_required_query = $this->db->query("SELECT * FROM `bookings_service_required` WHERE `bookings_id` = '".$row->id."'");
			foreach($service_required_query->result() as $row2) {
				$service_required[] = array(
					'choice' => $row2->choice
				);
			}

			$array[] = array(
				'id' =>  $row->id,
				'first_name' =>  $row->first_name,
				'last_name' =>  $row->last_name,
				'email' => $row->email,
				'mobile_number' => $row->mobile_number,
				'preferred_date' => $row->preferred_date,
				'address' => $row->address,
				'cleaning' => $row->cleaning,
				'sqm' => $row->sqm,
				'comments_or_notes' => $row->comments_or_notes,
				'service_required' => $service_required,
				'work_id' => $work_cleaner_id,
				'work_name' => $work_cleaner_name
			);
		};

		echo json_encode($array);
	}
}
?>