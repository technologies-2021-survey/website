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
				'full_name' =>  $row->full_name,
				'account_level' => $row->account_level
			);
		};

		echo json_encode($array);
	}

	public function getCustomers($page_number = "") {
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

		$total_pages_sql = "SELECT COUNT(*) FROM `customer_accounts`";
        $result = $this->db->query($total_pages_sql)->row_array();

		$total_rows = $result['count(*)'];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

		$array = array();

		$get_data = $this->db->query("SELECT * FROM `customer_accounts` ORDER BY `id` DESC LIMIT $offset, $no_of_records_per_page");

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

		$count = 1;
		$pagesn = ($page_number==2) ? 1 : $page_number;
		foreach($get_data->result() as $row) {
			$array[] = array(
				'queue' => ($page_number == 1) ? $count : (10 * $pagesn) + $count,
				'id' =>  $row->id,
				'table_id' =>  $row->table_id,
				'time' => $row->time,
				'status' => 'Waiting'
			);
			$count++;
		};

		echo json_encode($array);
	}

	public function doneServe($id, $table_id) {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }

		$searchIfAvailable = $this->db->query("SELECT * FROM `tables_dine_in` WHERE `table_id` = '".$table_id."' AND `status` = 'Eating'")->num_rows();

		if($searchIfAvailable == 0) {
			$search = $this->db->query("SELECT * FROM `tables_dine_in` WHERE `id` = '".$id."' AND `status` = 'Waiting'")->num_rows();

			if($search == 1) {
				
				$search2 = $this->db->query("SELECT * FROM `tables_dine_in` WHERE `id` = '".$id."' AND `status` = 'Waiting'");
				foreach($search2->result() as $data2) {
					$get_table_id = $data2->table_id;
					$this->db->query("UPDATE `tables` SET `status` = 'Eating' WHERE `id` = '".$get_table_id."'");
				}
				
				$dataz = array(
					'status' => 'Eating',
				);
				$this->admin_model->updateDineIn($id, $dataz);
				echo $this->admin_model->status(200, 'Successfully!');
			} else {
				echo $this->admin_model->status(203, 'Error, no data found.');
			}
		} else {
			echo $this->admin_model->status(203, 'Error, there\'s someone in the table.');
		}
	}

	public function doneEating($id) {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }

		$search = $this->db->query("SELECT * FROM `tables` WHERE `id` = '".$id."' AND `status` = 'Eating'")->num_rows();

		if($search == 1) {
			$data = array(
				'status' => 'Available',
			);
			$this->admin_model->updateTable($id, $data);

			$data2 = array(
				'status' => 'Done',
			);
			$this->admin_model->updateDineIn2($id, $data2);
			echo $this->admin_model->status(200, 'Successfully!');
		} else {
			echo $this->admin_model->status(203, 'Error, no data found.');
		}
	}
	public function getTableDineInOrder($id) {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }
		$search = $this->db->query("SELECT * FROM `tables_dine_in` WHERE `id` = '".$id."'")->num_rows();

		if($search == 1) {
			$sql = $this->db->query("SELECT * FROM `tables_orders` WHERE `tables_dine_in_id` = '".$id."'");
			$array = array();
			$overall_total = 0;
			foreach($sql->result() as $data) {
				$food_name = "";
				$food_price = "";

				$sql2 = $this->db->query("SELECT * FROM `menu` WHERE `id` = '".$data->menu_id."'");
				foreach($sql2->result() as $data2) {
					$food_name = $data2->food_name;
					$food_price = $data2->food_price;
				}

				$array[] = array(
					'food_name' => $food_name,
					'food_price' => $food_price,
					'quantity' => $data->quantity,
					'row_total' => $data->quantity * $food_price
				);
				$overall_total += ($data->quantity * $food_price);
			}

			echo json_encode(
				array(
					$array,
					array("overall_total" => $overall_total) 
				)
			);
		} else {
			echo $this->admin_model->status(203, 'Error, no data found.');
		}

	}

	public function menu() {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }
		$data = array(
			'title' => 'Menu | Cafe Lidia',
			'id' => $this->admin_model->user(),
			'username' => $this->admin_model->user('username')
		);

		$this->load->view('Admin/Include/header', $data);
		$this->load->view('Admin/Admin_menu', $data);
		$this->load->view('Admin/Include/footer');
	}

	public function getMenu($page_number = "") {
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

		$total_pages_sql = "SELECT COUNT(*) FROM `menu`";
        $result = $this->db->query($total_pages_sql)->row_array();

		$total_rows = $result['count(*)'];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

		$array = array();

		$get_data = $this->db->query("SELECT * FROM `menu` ORDER BY `id` DESC LIMIT $offset, $no_of_records_per_page");

		foreach($get_data->result() as $row) {
			$array[] = array(
				'id' =>  $row->id,
				'food_name' =>  $row->food_name,
				'food_price' =>  $row->food_price
			);
		};

		echo json_encode($array);
	}

	public function deleteMenu($id) {
		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }

		$search = $this->db->query("SELECT * FROM `menu` WHERE `id` = '".$id."'")->num_rows();

		if($search == 1) {
			$this->admin_model->deleteMenu($id);
			echo $this->admin_model->status(200, 'Successfully!');
		} else {
			echo $this->admin_model->status(203, 'Error, no data found.');
		}
	}
	public function addMenu() {

		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }

		$this->form_validation->set_rules('food_name', 'Name', 'required');
		$this->form_validation->set_rules('food_price', 'Price', 'required');

		$data = array(
			'food_name' => $this->input->post('food_name'),
			'food_price' => $this->input->post('food_price')
		);

		$result = $this->admin_model->addMenu($data);

		if($this->form_validation->run() == FALSE) {
			echo $this->admin_model->status(203, 'Error! Please input name and price!');
		} else {
			if($result == 'Success') {
				echo $this->admin_model->status(200, $result);
			} else {
				echo $this->admin_model->status(203, $result);
			}
		}
	}

	public function getListOrders($page_number = "") {
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

		$total_pages_sql = "SELECT COUNT(*) FROM `tables_dine_in` WHERE `status` = 'Done' OR `status` = 'Eating'";
        $result = $this->db->query($total_pages_sql)->row_array();

		$total_rows = $result['count(*)'];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

		$array = array();

		$get_data = $this->db->query("SELECT * FROM `tables_dine_in` WHERE `status` = 'Done' OR `status` = 'Eating' ORDER BY `id` DESC LIMIT $offset, $no_of_records_per_page");

		foreach($get_data->result() as $row) {
			$array[] = array(
				'id' =>  $row->id,
				'table_id' =>  $row->table_id,
				'time' => $row->time,
				'status' => $row->status
			);
		};

		echo json_encode($array);
	}

	public function addAccount() {

		if($this->admin_model->session() == 0) { redirect(base_url() . "admin/index"); } else { }

		$this->form_validation->set_rules('full_name', 'Full Name', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('account_level', 'Account Level', 'required');

		if($this->admin_model->checkUsername($this->input->post('username'), 'accounts') == false) {
			echo $this->admin_model->status(203, 'Error! username is already exist!');
			return false;
		}

		if($this->input->post('account_level') == 0) { }
		else if($this->input->post('account_level') == 1) { }
		else if($this->input->post('account_level') == 2) { }
		else if($this->input->post('account_level') == 3) { }
		else if($this->input->post('account_level') == 4) { }
		else {
			echo $this->admin_model->status(203, 'Error! please put properly the account level');
			return false;
		}


		$data = array(
			'full_name' => $this->input->post('full_name'),
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password')),
			'account_level' => $this->input->post('account_level')
		);
		
		$result = $this->admin_model->addAccount($data);

		if($this->form_validation->run() == FALSE) {
			echo $this->admin_model->status(203, 'Error! Please input the data');
		} else {
			if($result == 'Success') {
				echo $this->admin_model->status(200, $result);
			} else {
				echo $this->admin_model->status(203, $result);
			}
		}
	}
	public function addSampleDineIn() {
		$getUniqId = uniqid();
		$finalUniqId = $getUniqId;

		$data = array(
			'unique_id' => $finalUniqId,
			'table_id' => rand(1, 4),
			'time' => time(),
			'status' => 'Waiting'
		);

		$this->db->insert('tables_dine_in', $data);

		$getId = "";
		$search = $this->db->query("SELECT * FROM `tables_dine_in` WHERE `unique_id` = '".$finalUniqId."'");
		foreach($search->result() as $dataz) {
			$getId = $dataz->id;
		}

		$data2 = array(
			'tables_dine_in_id' => $getId,
			'menu_id' => 1,
			'quantity' => rand(1,10)
		);
		$this->db->insert('tables_orders', $data2);

		echo $this->admin_model->status(200, 'Success');
	}
}
?>