<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model', null, true); // auto-connect model
	}

    public function index() {
		$data = array(
			'title' => 'Admin Dashboard | Cafe Lidia',
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
			'title' => 'Admin Dashboard | Cafe Lidia',
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
			'title' => 'Dashboard | Cafe Lidia',
			'id' => $this->admin_model->user(),
			'username' => $this->admin_model->user('username')
		);

		$this->load->view('Admin/Include/header', $data);
		$this->load->view('Admin/Admin_dashboard', $data);
		$this->load->view('Admin/Include/footer');
	}

	public function accounts() {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }
		$data = array(
			'title' => 'Accounts\'s List | Cafe Lidia',
			'id' => $this->admin_model->user(),
			'username' => $this->admin_model->user('username')
		);

		$this->load->view('Admin/Include/header', $data);
		$this->load->view('Admin/Admin_accounts', $data);
		$this->load->view('Admin/Include/footer');
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

	public function table() {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }
		$data = array(
			'title' => 'Table | Cafe Lidia',
			'id' => $this->admin_model->user(),
			'username' => $this->admin_model->user('username')
		);

		$this->load->view('Admin/Include/header', $data);
		$this->load->view('Admin/Admin_table', $data);
		$this->load->view('Admin/Include/footer');
	}
	
	public function getTables($page_number = "") {
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

		$total_pages_sql = "SELECT COUNT(*) FROM `tables`";
        $result = $this->db->query($total_pages_sql)->row_array();

		$total_rows = $result['count(*)'];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

		$array = array();

		$get_data = $this->db->query("SELECT * FROM `tables` ORDER BY `id` DESC LIMIT $offset, $no_of_records_per_page");

		foreach($get_data->result() as $row) {
			$array[] = array(
				'id' =>  $row->id,
				'table_name' =>  $row->table_name,
				'status' => $row->status
			);
		};

		echo json_encode($array);
	}

	public function getDineIn($page_number = "") {
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

		$total_pages_sql = "SELECT COUNT(*) FROM `tables_dine_in` WHERE `status` = 'Waiting'";
        $result = $this->db->query($total_pages_sql)->row_array();

		$total_rows = $result['count(*)'];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

		$array = array();

		$get_data = $this->db->query("SELECT * FROM `tables_dine_in` WHERE `status` = 'Waiting' ORDER BY `id` DESC LIMIT $offset, $no_of_records_per_page");

		foreach($get_data->result() as $row) {
			$array[] = array(
				'id' =>  $row->id,
				'table_id' =>  $row->table_id,
				'time' => $row->time,
				'status' => 'Waiting'
			);
		};

		echo json_encode($array);
	}
}
?>