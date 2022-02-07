<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Manila");

class Doctor extends MY_Controller {
	public function __construct() {
		parent::__construct();
		
		$this->load->model('power_model', null, true); // auto-connect model
		if(!$this->session->userdata('id') && !$this->session->userdata('selection') == "doctor") {
			redirect('home');
		}
		
		// every 10 hours, always logout
		$result = $this->power_model->timeout($this->session->userdata('id'), $this->session->userdata('selection'));
		if($result == 1) {
			$this->power_model->modelLogout($this->session->id, 'doctor');
        
			$data = $this->session->all_userdata();
			foreach($data as $row => $rows_value) {
				$this->session->unset_userdata($row);
			}
			
			redirect('home');
		}

		// auto log out if the user doesn't log in.
		$this->power_model->autoTimeout();
	}
	
	// index
	public function index() {
		$DoctorCode = "";

		$data = array(
			'title' => 'Dashboard | Doctor',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor',
			'nextPatients' => $this->power_model->allTransactions() 
		);
		
		
		foreach($this->power_model->getId($this->session->id, 'doctor') as $userInfo) {
    		$DoctorCode = $userInfo->doctor_sms_code;
		}
		
		if(strlen($DoctorCode) == 5 || strlen($DoctorCode) == 6) {
		    redirect('doctor/verification');
		} 
		
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/navigation', $data);
		$this->load->view('Power/index', $data);
		$this->load->view('Power/Include/footer');
	}
	public function logout() {
        $this->power_model->modelLogout($this->session->id, 'doctor');
        
		$data = $this->session->all_userdata();
		foreach($data as $row => $rows_value) {
			$this->session->unset_userdata($row);
		}
		
		redirect('home');
	}
	// change profile picture
	public function change_profile_picture() {
		$data = array(
			'title' => 'Doctor',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor',
			'error' => '',
			'success' => ''
		);

		$config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 2000;
        $config['max_width'] = 1500;
        $config['max_height'] = 1500;
		$this->load->library('upload', $config);


		if (!$this->upload->do_upload('image')) {
            $data['error'] = $this->upload->display_errors();

            $this->load->view('Power/Include/header', $data);
			$this->load->view('Power/change_profile_picture', $data);
			$this->load->view('Power/Include/footer');
        } else {
            //$data['success'] = array('image_metadata' => $this->upload->data());
			$data['success'] = "Successfully!";
			$imgdata = $this->upload->data();
			$imgpath = file_get_contents($imgdata['full_path']);
			
			$arrayImage = array('profile_picture' => $imgpath);
			$this->db->where('doctor_id', $this->session->id);
       		$this->db->update('doctors_tbl', $arrayImage);

			$this->load->view('Power/Include/header', $data);
			$this->load->view('Power/change_profile_picture', $data);
			$this->load->view('Power/Include/footer');
			
        }
	}
	// payment
	public function payment() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 4) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$this->load->model('payment_model', null, true); // auto-connect model
		$data = array (
			'title' => "Payment",
			'items' => $this->payment_model->get_paylist(),
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor'
		);
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/navigation');
		$this->load->view('transaction');
		$this->load->view('Power/Include/footer');
	}
	public function view_payment(){
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 4) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$this->load->model('payment_model', null, true); // auto-connect model
		$id = $this->uri->segment(3);
		$data = array(
			'title' => "Patient Transaction",
			'items' => $this->payment_model->get_payuser('payments_tbl', 'payments_id', $id),
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor'
		);
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/navigation');
		$this->load->view('view_payment');
		$this->load->view('Power/Include/footer');
	}

	public function update_payment(){
		$id = $this->uri->segment(3);
		$data = [
			'payments_service'   =>  $this->input->post('service',true),
			'payments_status'   =>  $this->input->post('status',true),
			'payments_paid' =>  $this->input->post('paid',true)
		];
		$this->payment_model->update_paystatus($data,$id);
		$this->payment();
	}

	public function delete_payment(){
		$id = $this->uri->segment(3);
		$this->payment_model->delete_payuser($id);
		$this->payment();
	}
	// appointment
	public function appointment() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 3) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Appointment',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor'
		);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/appointment.php', $data);
		$this->load->view('Power/Include/footer');
	}
	public function appointment_requests() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 3) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Appointment Requests',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'links' => '',
			'appointment_requests' => '',
			'level' => 'doctor'
		);

		$filter = $_GET['filter'];
		$date = $_GET['date'];
		$doctor_id = $_GET['doctor_id'];
		
		if($filter == "" || $filter == "pending" || $filter == "approved" || $filter == "finished") {
			
		} else {
			$filter = "";
		}

		$config = array();
        $config["base_url"] = base_url() . "doctor/appointment_requests";
        $config["total_rows"] = $this->power_model->get_appointment_requests_count($filter, $date, $this->session->id);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
		$config['reuse_query_string'] = true;

		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
        $data["links"] = $this->pagination->create_links();

        $data['appointment_requests'] = $this->power_model->get_appointment_requests($filter, $date, $this->session->id, $config["per_page"], $page);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/appointment_requests.php', $data);
		$this->load->view('Power/Include/footer', $data);
	}
	public function cancelAppointmentRequest() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 3) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(is_numeric($this->uri->segment(3)) == 1) {
			$datas = array(
				"appointment_status" => "Cancelled",
				"appointment_timestamp" => 0,
				"appointment_timestamp_end" => 0
			);
			$this->power_model->cancelAppointmentRequest($datas, $this->uri->segment(3));
			redirect("doctor/appointment_requests?success");
		} else {
			redirect("doctor/appointment_requests");
		}
	}

	public function doneAppointmentRequest() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 3) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(is_numeric($this->uri->segment(3)) == 1) {
			$datas = array(
				"appointment_timestamp" => "0",
				"appointment_timestamp_end" => "0",
				"appointment_status" => "Finished"
			);
			$this->power_model->doneAppointmentRequest($datas, $this->uri->segment(3));
			redirect("doctor/appointment_requests?success");
		} else {
			redirect("doctor/appointment_requests");
		}
	}

	public function approveAppointmentRequest() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 3) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(is_numeric($this->uri->segment(3)) == 1) {
			$datas = array(
				"appointment_status" => "Approved",
				"appointment_approve_by" => $this->session->id,
				"appointment_approve_selection" => 1
			);
			$this->power_model->approveAppointmentRequest($datas, $this->uri->segment(3));
			redirect("doctor/appointment_requests?success");
		} else {
			redirect("doctor/appointment_requests");
		}
	}
	public function addAppointment() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 3) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/
		// // converter date and time to timestamp
		// $date = '9/22/2021, 17:10:00';
		// $date = str_replace(' ', '/', $date);
		// $fulldate = explode('/', $date);

		// $time = '9/22/2021, 17:10:00';
		// $time = str_replace(' ', ':', $time);
		// $fulltime = explode(':', $time);

		
		// echo '<br/>';

		// echo  var_dump($fulldate) . ' <br/>';
		// echo var_dump($fulltime) . ' <br/>';

		// $dateandtime = $fulldate[0] . '/' . $fulldate[1]. '/' . $fulldate[2] . ' ' .$fulltime[1] .':'.$fulltime[2].':'.$fulltime[3]; 
		// echo strtotime($dateandtime); // 1632345000

		$this->form_validation->set_rules('appointment_description', 'Details', 'required');
		$this->form_validation->set_rules('appointment_datetime', 'Date Time', 'required');

		if($this->form_validation->run() == FALSE) {
			echo 'error';
		} else {
			$date = $this->input->post('appointment_datetime');
			$date = str_replace(' ', '/', $date);
			$date = str_replace(',', '', $date);
		  	$fulldate = explode('/', $date);

			$time = $this->input->post('appointment_datetime');
			$time = str_replace(' ', ':', $time);
			$time = str_replace(',', '', $time);
			$fulltime = explode(':', $time);
			$dateandtime = $fulldate[0] . '/' . $fulldate[1]. '/' . $fulldate[2] . ' ' .$fulltime[1] .':'.$fulltime[2].':'.$fulltime[3]; 
			$timestamp = strtotime($dateandtime);

			$date2 = $date;
			$date2 = str_replace(' ', '/', $date2);
			$date2 = str_replace(',', '', $date2);
		  	$fulldate2 = explode('/', $date2);

			$time2 = $date;
			$time2 = str_replace('/', ':', $time2);
			$time2 = str_replace(',', '', $time2);
			$fulltime2 = explode(':', $time2);

			$dateandtime2 = $fulldate2[0] . '/' . $fulldate2[1]. '/' . $fulldate2[2] . ' ' .$fulltime2[3] .':'.$fulltime2[4].':'.$fulltime2[5]; 
			$timestamp2 = strtotime($dateandtime2 . '+30 minutes');
			$newdateandtime2 = date('m/d/Y H:i:s', $timestamp2);

			$result = $this->power_model->getCheckTimeAppointment($timestamp, $timestamp2);

			if($result == 1) {
				 echo "exist";
			} else {
				$data = array(
					'appointment_timestamp'  => $timestamp,
					'appointment_timestamp_end'  => $timestamp2,
					'appointment_description'  => $this->input->post('appointment_description'),
					'appointment_datetime'  => $dateandtime,
					'appointment_datetime_end'  => $newdateandtime2,
					'appointment_status'=> 0
				);
				$this->power_model->addAppointment($data);
				echo "success"; //if success will return string "success"
			}
		}
	}
	public function getCheckTimeAppointment($start, $end) {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 3) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/
		$result = $this->power_model->getCheckTimeAppointment($start, $end);
		if($result == 1) {
			echo '1';
		} else {
			echo '0';
		}
	}
	public function getAppointment() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 3) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/
		$event_data = $this->power_model->getAppointment($this->session->id);
		foreach($event_data->result_array() as $row) {
			$parent = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$row['appointment_parent_id']."'")->result_array();
			$patient = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['appointment_patient_id']."'")->result_array();
			$data[] = array(
				'id' => $row['appointment_id'],
				'title' => $parent[0]['parent_name'] . "\n" . $patient[0]['patient_name'],
				'start' => $row['appointment_datetime'],
				'end' => $row['appointment_datetime_end'],
				'appointment_parent_id' => $row['appointment_parent_id'],
				'appointment_timestamp' => $row['appointment_timestamp'],
				'appointment_timestamp_end' => $row['appointment_timestamp_end'],
				'appointment_status' => $row['appointment_status']
			);
		}
		echo json_encode($data);
	}

	public function updateAppointment() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 3) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/
		if($this->input->post('id')) {
			$data = array(
				'title'   => $this->input->post('title'),
				'start_event' => $this->input->post('start'),
				'end_event'  => $this->input->post('end')
			);

   			$this->power_model->updateAppointment($data, $this->input->post('id'));
		}
	}
	public function deleteAppointment() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 3) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/
		if($this->input->post('id')) {
			$this->power_model->deleteAppointment($this->input->post('id'));
		}
	}
	

    public function verification() {
        $DoctorCode = "";
		foreach($this->power_model->getId($this->session->userdata('id'), 'doctor') as $userInfo) {
    		$DoctorCode = $userInfo->doctor_sms_code;
		}
		
		if(strlen($DoctorCode) == 6 || strlen($DoctorCode) == 5) {
		} else if($DoctorCode == "@@@@@@@@SUCCESS@@@@@@@@") {
		    redirect('doctor');
		}
		
        $data = array(
			'title' => 'Doctor',
			'data' => $this->power_model->getId($this->session->id, 'doctor'), 
			'error' => '',
			'level' => 'doctor'
		);
		
		$this->form_validation->set_rules('otp', 'One Time Password', 'required');
        
        if($this->form_validation->run() == FALSE) {
			$this->load->view('Power/Include/header', $data);
    		$this->load->view('Power/verification', $data);
    		$this->load->view('Power/Include/footer');
		} else {
		    $result = $this->power_model->submitOTP($this->session->id, 'doctor', $this->input->post('otp'));
		    if($result == "successfully") {
		        redirect('doctor');
		    } else {
		        
		        $data['error'] = 'OTP doesn\'t match.';
		        $this->load->view('Power/Include/header', $data);
        		$this->load->view('Power/verification', $data);
        		$this->load->view('Power/Include/footer');
		    }
		}
    }
	public function addChat($id_to = "", $selection_to = "") {
		$xtstx = "";
		if($this->session->selection == "doctor") { $xtstx = 1; }
		else if($this->session->selection == "administrator") { $xtstx = 2; }
		else if($this->session->selection == "receptionist") { $xtstx = 3; }
		else if($this->session->selection == "parent") { $xtstx = 4; }
		else if($this->session->selection == "patient") { $xtstx = 5; }

		if($this->power_model->checkAccount2($id_to, $selection_to) == 1) {
			// check if this account exist
			// this is exist
		} else {
			redirect($this->session->selection . '/messages?error');
		}
		if($this->session->id == $id_to && $xtstx == $selection_to) {
			redirect($this->session->selection . '/messages?error');
		}

		$resultg = $this->power_model->checkMsgs2($this->session->id, $xtstx, $id_to, $selection_to);
		if($resultg == 1) {
			// exist
			redirect($this->session->selection . '/messages?error');
		} else {
			$generate = mt_rand(00000000, 99999999);
			$encrypts = md5($generate);

			// not exist
			$array = array(
				'chats_id' => $this->session->id,
				'chats_selection' => $xtstx,
				'id_to' => $id_to,
				'selection_to' => $selection_to,
				'code' => $encrypts
			);

			$array2 = array(
				'chats_id' => $id_to,
				'chats_selection' => $selection_to,
				'id_to' => $this->session->id,
				'selection_to' => $xtstx,
				'code' => $encrypts
			);
			$this->power_model->addChat($array, $array2);
			redirect($this->session->selection . '/messages//'.$encrypts.'?success=Successfully added in contact list');
		}
	}

	// chats
	public function displayUsers($txtsssz = ""){
		$xtstx = "";
		if($this->session->selection == "doctor") { $xtstx = 1; }
		else if($this->session->selection == "administrator") { $xtstx = 2; }
		else if($this->session->selection == "receptionist") { $xtstx = 3; }
		else if($this->session->selection == "parent") { $xtstx = 4; }

		$result = $this->power_model->displayUsers($txtsssz, $this->session->id, $xtstx); 
		function sortTime($a, $b) {
			$a = $a['time'];
			$b = $b['time'];
			if ($a == $b)
			  return 0;
			return ($a < $b) ? -1 : 1;
		}

		usort($result, "sortTime");
		echo json_encode(array_reverse($result));
	}
	public function getChats($code = "", $id = "") {
		//1-doctor
		//2-admi
		//3-receptionist
		//4-parent
		
		$selection = "";
		if($this->session->selection == "doctor") { $selection = 1; }
		else if($this->session->selection == "administrator") { $selection = 2; }
		else if($this->session->selection == "receptionist") { $selection = 3; }
		else if($this->session->selection == "parent") { $selection = 4; }

		
		//if($this->doctor_model->checkChatCode($this->session->id, $selection, $code) == 1) { 
		//	redirect('home');
		//}

		$result = $this->power_model->getChats($code, $id);
		echo $result;
	}
	public function getPreviouslyChats($code = "", $id = "") {
		//1-doctor
		//2-admin
		//3-receptionist
		//4-parent

		$selection = "";
		if($this->session->selection == "doctor") { $selection = 1; }
		else if($this->session->selection == "administrator") { $selection = 2; }
		else if($this->session->selection == "receptionist") { $selection = 3; }
		else if($this->session->selection == "parent") { $selection = 4; }

		
		//if($this->doctor_model->checkChatCode($this->session->id, $selection, $code) == 1) { 
		//	redirect('home');
		//}

		$result = $this->power_model->getPreviouslyChats($code, $id);
		echo $result;
	}

	public function messages($code = "") {
		$data = array(
			'title' => 'Messages',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'code' => $code,
			'level' => 'doctor'
		);
		if($code == "") {
			$this->load->view('Power/Include/header', $data);
			$this->load->view('Power/messages_idle', $data);
			$this->load->view('Power/Include/footer');
		} else {
			$selection = "";
			if($this->session->selection == "doctor") { $selection = 1; }
			else if($this->session->selection == "administrator") { $selection = 2; }
			else if($this->session->selection == "receptionist") { $selection = 3; }
			else if($this->session->selection == "parent") { $selection = 4; }

			if($this->power_model->checkChatCode($this->session->userdata('id'), $selection, $code) == 0) {
				$this->load->view('Power/Include/header', $data);
				$this->load->view('Power/messages', $data);
				$this->load->view('Power/Include/footer');
				
			} else {
				redirect('doctor/messages?error=noexists');
				
			}
		}
	}
	public function send() {
		$this->form_validation->set_rules('messages', 'Messages', 'required|min_length[5]');
		$this->form_validation->set_rules('code', 'Code', 'required');

		$selection = "";
		if($this->session->userdata('selection') == "doctor") { $selection = 1; }
		else if($this->session->userdata('selection') == "administrator") { $selection = 2; }
		else if($this->session->userdata('selection') == "receptionist") { $selection = 3; }
		else if($this->session->userdata('selection') == "parent") { $selection = 4; }

		if($this->power_model->checkChatCode($this->session->userdata('id'), $selection, $this->input->post('code')) == 1) {
			echo 'noexists';
		} else {
			if($this->form_validation->run() == FALSE) {
				echo validation_errors();
			} else {
				$checkS = $this->db->query("SELECT * FROM chats_history_tbl WHERE chats_info_code = '".$this->input->post('code')."' ORDER BY chats_history_id DESC")->result_array();
				$querys = array(
					'chats_next_id' => $checkS[0]['chats_next_id'] + 1,
					'chats_info_code' => $this->input->post('code'),
					'chats_account_type' => $selection,
					'chats_id'  => $this->session->id,
					'chats_message' =>$this->input->post("messages"),
					'time' => time()
				);
				$result = $this->power_model->send($querys);
				echo '';
			}
			
		}
	}
	public function getNameandActive($code) {
		echo $this->power_model->getNameandActives($code, $this->session->id);
	}

	// inquiries
	public function inquiries_list() {
		// import database
		$this->load->database();

		// import model
		$this->load->model('power_model');

		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 1) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Inquiries',
			'inquiries' => $this->power_model->getInquiries(), // get all data in inquiries
			'content' => 'Inquiries',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor'
		);
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/inquiries_list');
		$this->load->view('Power/Include/footer');
	}
	public function delete() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 2) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('administrator/index');
		}
		/*
			End of Access Privilege
		*/
		$data = $this->uri->segment(3);
		$this->power_model->deleteInquiries($data);
		redirect("../doctor/inquiries_list");
	}
	public function view() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 1) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('administrator/index');
		}
		/*
			End of Access Privilege
		*/

		$id = $this->uri->segment(3);
		

		$data = array(
			'title' => 'Inquiries View',
			'inquiries_view' => $this->power_model->getInquiriesById($id),
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor'
		);
		
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/inquiries_view', $data);
		$this->load->view('Power/Include/footer');
	}

	// consultation
	public function consultation() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 4) {//consultation
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Consultations',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor'
		);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/consultation.php', $data);
		$this->load->view('Power/Include/footer');
	}
	public function getConsultation() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 4) {//consultation
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/
		$event_data = $this->power_model->getConsultation($this->session->id);
		foreach($event_data->result_array() as $row) {
			$parent = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$row['consultation_parent_id']."'")->result_array();
			$patient = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['consultation_patient_id']."'")->result_array();
			$data[] = array(
				'id' => $row['consultation_id'],
				'title' => $parent[0]['parent_name'] . "\n" . $patient[0]['patient_name'],
				'start' => $row['date_consultation_datetime'],
				'end' => $row['date_consultation_datetime_end'],
				'consultation_parent_id' => $row['consultation_parent_id'],
				'consultation_timestamp' => $row['date_consultation'],
				'consultation_timestamp_end' => $row['date_consultation_end'],
				'consultation_status' => $row['consultation_status']
			);
		}
		echo json_encode($data);
	}

	public function consultation_requests() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 4) {//consultation
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Consultation Requests',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'links' => '',
			'consultations' => '',
			'level' => 'doctor'
		);

		$filter = $_GET['filter'];
		$date = $_GET['date'];
		//$doctor_id = $_GET['doctor_id'];

		if($filter == "" || $filter == "pending" || $filter == "approved" || $filter == "finished") {
			
		} else {
			$filter = "";
		}

		$config = array();
		$config['reuse_query_string'] = true;
        $config["base_url"] = base_url() . "doctor/consultation_requests";
        $config["total_rows"] = $this->power_model->get_consultations_count($filter, $date, $this->session->id);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
		

		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["links"] = $this->pagination->create_links();

        $data['consultations'] = $this->power_model->get_consultations($filter, $date, $this->session->id, $config["per_page"], $page);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/consultation_requests.php', $data);
		$this->load->view('Power/Include/footer', $data);
	}

	public function sendlink() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 4) {//consultation
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$this->form_validation->set_rules('id', 'Consultation ID', 'required');
		$this->form_validation->set_rules('googlelink', 'Google Link', 'required');

		if($this->form_validation->run() == FALSE) {
			redirect("doctor/consultation");
		} else {
			$datas = array(
				"googlelink" => $this->input->post('googlelink')
			);
			$this->power_model->sendlink($datas, $this->input->post('id'));
			redirect("doctor/consultation?success");
		}
	}
	

	public function addPrescription() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 4) {//consultation
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$this->form_validation->set_rules('prescription_id', 'Consultation ID', 'required');
		$this->form_validation->set_rules('prescription', 'Prescription', 'required');

		if($this->form_validation->run() == FALSE) {
			redirect("doctor/consultation");
		} else {
			$datas = array(
				"consultation_prescription" => $this->input->post('prescription', true) 
			);
			$this->power_model->addPrescription($datas, $this->input->post('prescription_id', true));
			redirect("doctor/consultation?success");
		}
	}

	public function cancelConsultation() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 4) {//consultation
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(is_numeric($this->uri->segment(3)) == 1) {
			$datas = array(
				"consultation_status" => "Cancelled",
				"date_consultation" => 0,
				"date_consultation_end" => 0
			);
			$this->power_model->cancelConsultation($datas, $this->uri->segment(3));
			redirect("doctor/consultation?success");
		} else {
			redirect("doctor/consultation");
		}
	}

	public function doneConsultation() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 4) {//consultation
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(is_numeric($this->uri->segment(3)) == 1) {
			$datas = array(
				"date_consultation" => "0",
				"date_consultation_end" => "0",
				"consultation_status" => "Finished"
			);
			$this->power_model->doneConsultation($datas, $this->uri->segment(3));
			redirect("doctor/consultation?success");
		} else {
			redirect("doctor/consultation");
		}
	}

	public function approveConsultation() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 4) {//consultation
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(is_numeric($this->uri->segment(3)) == 1) {
			$datas = array(
				"consultation_status" => "Approved",
				"consultation_approve_by" => $this->session->id,
				"consultation_approve_selection" => 1
			);
			$this->power_model->approveConsultation($datas, $this->uri->segment(3));
			redirect("doctor/consultation?success");
		} else {
			redirect("doctor/consultation");
		}
	}
	
	// transaction
	public function transaction_medical_certificate() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 5) { //transaction
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		

		$config = array();
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config["base_url"] = base_url() . "doctor/transaction_medical_certificate";
        $config["total_rows"] = $this->power_model->get_transaction_medical_certificate_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";

        $this->pagination->initialize($config);

        

		$data = array(
			'title' => 'Transaction (Medical Certificate)',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'links' => '',
			'transactions' => '',
			'level' => 'doctor',
			'pdf_per_page' => $config['per_page'],
			'pdf_page' => $page
		);

        $data["links"] = $this->pagination->create_links();

        $data['transactions'] = $this->power_model->get_transaction_medical_certificate($config["per_page"], $page);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		
		$this->load->view('Power/transaction_medical_certificate.php', $data);
		$this->load->view('Power/Include/footer', $data);
	}
	
	public function transaction_appointment() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 5) { //transaction
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$config = array();
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config["base_url"] = base_url() . "doctor/transaction_appointment";
        $config["total_rows"] = $this->power_model->get_transactionz_appointment_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";

        $this->pagination->initialize($config);

		$data = array(
			'title' => 'Transaction (Appointment)',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'links' => '',
			'transactions' => '',
			'level' => 'doctor',
			'pdf_per_page' => $config['per_page'],
			'pdf_page' => $page
		);
        
        $data["links"] = $this->pagination->create_links();

        $data['transactions'] = $this->power_model->get_transactionz_appointment($config["per_page"], $page);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		
		$this->load->view('Power/transaction.php', $data);
		$this->load->view('Power/Include/footer', $data);
	}

	public function transaction_consultation() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 5) { //transaction
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$config = array();
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config["base_url"] = base_url() . "doctor/transaction_consultation";
        $config["total_rows"] = $this->power_model->get_transactionz_consultation_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";

        $this->pagination->initialize($config);

		$data = array(
			'title' => 'Transaction (Consultation)',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'links' => '',
			'transactions' => '',
			'level' => 'doctor',
			'pdf_per_page' => $config['per_page'],
			'pdf_page' => $page
		);
       

        $data["links"] = $this->pagination->create_links();

        $data['transactions'] = $this->power_model->get_transactionz_consultation($config["per_page"], $page);

		

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		
		$this->load->view('Power/transaction.php', $data);
		$this->load->view('Power/Include/footer', $data);
	}

	public function immunizationrecord() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 6) { //immunization
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Immunization Record',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'links' => '',
			'immunizationrecords' => '',
			'level' => 'doctor'
		);
		
		$patient = "";
		$vaccine = "";
		$date = "";

		if(isset($_GET['patient'])) {
			$patient = htmlspecialchars($_GET['patient']);
		}

		if(isset($_GET['vaccine'])) {
			$vaccine = htmlspecialchars($_GET['vaccine']);
		}

		if(isset($_GET['date'])) {
			$date = htmlspecialchars($_GET['date']);
		}

		$config['reuse_query_string'] = true; 

		$config = array();
        $config["base_url"] = base_url() . "doctor/immunizationrecord";
        $config["total_rows"] = $this->power_model->get_immunizationrecords_count($patient, $vaccine, $date);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
		
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["links"] = $this->pagination->create_links();

        $data['immunizationrecords'] = $this->power_model->get_immunizationrecords($patient, $vaccine, $date, $config["per_page"], $page);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/immunizationRecord.php', $data);
		$this->load->view('Power/Include/footer', $data);
	}

	public function addImmunizationRecord($id = "") {	
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 7) { //addimmunization
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(strlen($id) == 0) {
			redirect("doctor/immunizationrecord");
		}
		if($this->power_model->checkAccount($id, 'patient') == 1) {
			// exist
		} else {
			// not exist
			// error
			redirect("doctor/parents?error");
		}

		$patient = $this->power_model->getUserInfoPatient($id);
		$data = array(
			'title' => 'Add Immunization Record',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'patient_name' => $this->power_model->getUserInfoPatientName($id),
			'parent_name' => $this->power_model->getUserInfoPatientGetParentName($id),
			'parent_id' => $patient[0]['parent_id'],
			'level' => 'doctor'
		);

		$this->form_validation->set_rules('dates', 'Date', 'required');
		$this->form_validation->set_rules('vaccine', 'Vaccine', 'required');
		$this->form_validation->set_rules('route', 'Route', 'required');

		$this->form_validation->set_rules('age', 'Age', 'required');
		$this->form_validation->set_rules('weight', 'Weight', 'required');
		$this->form_validation->set_rules('length', 'Length', 'required');
		$this->form_validation->set_rules('bmi', 'BMI', 'required');

		$this->form_validation->set_rules('head_circumference', 'Head Circumference', 'required');
		$this->form_validation->set_rules('doctors_instruction', 'Doctor\'s Instruction', 'required');
		$this->form_validation->set_rules('comeback_on', 'Comeback On', 'required');
		$this->form_validation->set_rules('comeback_for', 'comeback_for', 'required');
        
		if($this->form_validation->run() == FALSE) {
			$this->load->view('Power/Include/header', $data);
			$this->load->view('Power/addImmunizationRecord', $data);
			$this->load->view('Power/Include/footer');
		} else {
			$times = strtotime($this->input->post('comeback_on') . ' 12:00AM');
			$months = date('m', $times);
			$days = date('d', $times);
			$years = date('Y', $times);

			$hours = date('H', $times);
			$minutes = date('i', $times);
			$seconds = date('s', $times);
			$overallTime = $months . '/'. $days .'/'. $years .' ' .$hours. ':'.$minutes.':00';

			$datas = [
				'patient_id' => $id,
				'parent_id' => $data['parent_id'],
				'date' => $this->input->post("dates"),
				'vaccine_id' => $this->input->post("vaccine"),
				'route' => $this->input->post("route"),
				'age' => $this->input->post('age'),
				'weight' => $this->input->post('weight'),
				'length' => $this->input->post('length'),
				'bmi' => $this->input->post('bmi'),
				'head_circumference' => $this->input->post('head_circumference'),
				'doctors_instruction' => $this->input->post('doctors_instruction'),
				'comeback_on' => $this->input->post('comeback_on'),
				'comeback_for' => $this->input->post('comeback_for'),
				'comeback_on_timestamp' => strtotime($this->input->post('comeback_on') . ' 12:00AM'),
				'comeback_for_timestamp_text' => $overallTime,
				'interview_id' => $this->session->id,
				'timestamp' => time()
			];
			$this->power_model->addImmunizationRecord($datas);
			redirect("doctor/immunizationrecord?success");
		}
	}
	public function editImmunizationRecord($id = "") {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 8) { //editimmunization
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(strlen($id) == 0) {
			redirect("doctor/immunizationrecord");
		}
		$result = $this->power_model->getImmunizationRecord($id);
		$result2 = (array) $result;
		if($this->power_model->checkAccount($result2[0]['patient_id'], 'patient') == 1) {
			// exist
		} else {
			// not exist
			// error
			redirect("doctor/parents?error");
		}
		$patient_name = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$result2[0]['patient_id']."'")->result_array();
		$getParent = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$result2[0]['parent_id']."'")->result_array();

		$data = array(
			'title' => 'Edit Immunization Record',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'patient_name' => $patient_name[0]['patient_name'],
			'parent_name' => $getParent[0]['parent_name'],
			'parent_id' => $result2[0]['parent_id'],
			'vaccine_id' => $result2[0]['vaccine_id'],
			'immunizations' => $result,
			'level' => 'doctor',
			'asd' => $id
		);

		$this->form_validation->set_rules('dates', 'Date', 'required');
		$this->form_validation->set_rules('vaccine', 'Vaccine', 'required');
		$this->form_validation->set_rules('route', 'Route', 'required');

		$this->form_validation->set_rules('age', 'Age', 'required');
		$this->form_validation->set_rules('weight', 'Weight', 'required');
		$this->form_validation->set_rules('length', 'Length', 'required');
		$this->form_validation->set_rules('bmi', 'BMI', 'required');

		$this->form_validation->set_rules('head_circumference', 'Head Circumference', 'required');
		$this->form_validation->set_rules('doctors_instruction', 'Doctor\'s Instruction', 'required');
		$this->form_validation->set_rules('comeback_on', 'Comeback On', 'required');
		$this->form_validation->set_rules('comeback_for', 'comeback_for', 'required');
        
        
		if($this->form_validation->run() == FALSE) {
			$this->load->view('Power/Include/header', $data);
			$this->load->view('Power/editImmunizationRecord', $data);
			$this->load->view('Power/Include/footer');
		} else {
			$times = strtotime($this->input->post('comeback_on') . ' 12:00AM');
			$months = date('m', $times);
			$days = date('d', $times);
			$years = date('Y', $times);

			$hours = date('H', $times);
			$minutes = date('i', $times);
			$seconds = date('s', $times);
			$overallTime = $months . '/'. $days .'/'. $years .' ' .$hours. ':'.$minutes.':00';
			$datas = array(
				'date' => $this->input->post("dates"),
				'vaccine_id' => $this->input->post("vaccine"),
				'route' => $this->input->post("route"),
				'age' => $this->input->post('age'),
				'weight' => $this->input->post('weight'),
				'length' => $this->input->post('length'),
				'bmi' => $this->input->post('bmi')
			);
			$datas2 = array(
				'head_circumference' => $this->input->post('head_circumference'),
				'doctors_instruction' => $this->input->post('doctors_instruction'),
				'comeback_on' => $this->input->post('comeback_on'),
				'comeback_for' => $this->input->post('comeback_for'),
				'comeback_on_timestamp' => strtotime($this->input->post('comeback_on') . ' 12:00AM'),
				'comeback_for_timestamp_text' => $overallTime,
			);
			$this->power_model->editImmunizationRecord($datas, $id);
			$this->power_model->editImmunizationRecord($datas2, $id);
			redirect("doctor/immunizationrecord?success");
		}
	}
	public function deleteImmunizationRecord($id = "") {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 9) { //delete immunization
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(strlen($id) == 0) {
			redirect("doctor/immunizationrecord");
		}
		
		$this->power_model->deleteImmunizationRecord($id);
		redirect("doctor/immunizationrecord?success");
	}
	public function viewImmunizationRecord($patient_id) {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 6) { //immunization
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/
		if(strlen($patient_id) == 0) {
			redirect("doctor/immunizationrecord");
		}

		if($this->power_model->checkAccount($patient_id, 'patient') == 1) {
			// exist
		} else {
			// not exist
			// error
			redirect("doctor/parents?error");
		}

		$data = array(
			'title' => 'View Immunization Record',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'links' => '',
			'viewImmunizationRecords' => '',
			'patient_name' => $this->power_model->getUserInfoPatientName($patient_id),
			'patient_id' => $patient_id,
			'level' => 'doctor'
		);
        $data['viewImmunizationRecords'] = $this->power_model->get_viewImmunizationRecords($patient_id);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/viewImmunizationRecord.php', $data);
		$this->load->view('Power/Include/footer', $data);
	}
	
	// parents
	public function parents() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 10) { //parents
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Parent List',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'links' => '',
			'parents' => '',
			'level' => 'doctor'
		);

		$config = array();
        $config["base_url"] = base_url() . "doctor/parents";
        $config["total_rows"] = $this->power_model->get_parents_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["links"] = $this->pagination->create_links();

        $data['parents'] = $this->power_model->get_parents($config["per_page"], $page);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/parents.php', $data);
		$this->load->view('Power/Include/footer', $data);
	}

	public function addParents() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 11) { //addparents
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Add Parent',
			'data' => $this->power_model->getId($this->session->id,'doctor'),
			'level' => 'doctor'
		);

		$this->form_validation->set_rules('parent_name', 'Parent Name', 'required');
		$this->form_validation->set_rules('parent_emailaddress', 'Parent Email', 'required|valid_email');
		$this->form_validation->set_rules('parent_phonenumber', 'phonenumber', 'required');
		$this->form_validation->set_rules('parent_address', 'address', 'required');
        
		if($this->form_validation->run() == FALSE) {
			$this->load->view('Power/Include/header', $data);
			$this->load->view('Power/addParent', $data);
			$this->load->view('Power/Include/footer');
		} else {
			$datas = array(
				'parent_name' => $this->input->post("parent_name"),
				'parent_emailaddress' => $this->input->post("parent_emailaddress"),
				'parent_phonenumber' => $this->input->post("parent_phonenumber"),
				'parent_address' => $this->input->post("parent_address"),
				'verified' => 1
			);
			$this->power_model->addParents($datas);
			redirect("doctor/parents?success");
		}
	}
	public function editParent($id = "") {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 11) { //addparents
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(strlen($id) == 0) {
			redirect("doctor/parents?error");
		}

		if($this->power_model->checkAccount($id, 'parent') == 1) {
			// exist
		} else {
			// not exist
			// error
			redirect("doctor/parents?error");
		}

		$result = $this->power_model->getUserInfoParent($id);

		$data = array(
			'title' => 'Edit Parent',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'parents' => $result,
			'level' => 'doctor'
		);

		$this->form_validation->set_rules('parent_name', 'Parent Name', 'required');
		$this->form_validation->set_rules('parent_emailaddress', 'Parent Email', 'required|valid_email');
		$this->form_validation->set_rules('parent_phonenumber', 'phonenumber', 'required');
		$this->form_validation->set_rules('parent_address', 'address', 'required');
        
		if($this->form_validation->run() == FALSE) {
			$this->load->view('Power/Include/header', $data);
			$this->load->view('Power/editParent', $data);
			$this->load->view('Power/Include/footer');
		} else {
			$datas = [
				'parent_name' => $this->input->post("parent_name"),
				'parent_emailaddress' => $this->input->post("parent_emailaddress"),
				'parent_phonenumber' => $this->input->post("parent_phonenumber"),
				'parent_address' => $this->input->post("parent_address")
			];
			$this->power_model->editParent($datas, $id);
			redirect("doctor/parents?success");
		}
	}
	public function addChild($id = "") {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 13) { //add child
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/
		if(strlen($id) == 0) {
			redirect("doctor/parents");
		}
		if($this->power_model->checkAccount($id, 'parent') == 1) {
			// exist
		} else {
			// not exist
			// error
			redirect("doctor/parents?error");
		}
		$datau = array(
			'title' => 'Add Child',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'parent_name' => $this->power_model->getUserInfoParentName($id),
			'level' => 'doctor',
			'id' => $id
		);

		$this->form_validation->set_rules('patient_name', 'Patient Name', 'required');
		$this->form_validation->set_rules('patient_birthdate', 'Patient Birthdate', 'required');
		$this->form_validation->set_rules('patient_gender', 'Patient Gender', 'required');
		

		if($this->form_validation->run() == FALSE) {
			$this->load->view('Power/Include/header', $datau);
			$this->load->view('Power/addChild', $datau);
			$this->load->view('Power/Include/footer');
		} else {
			$datas = array(
				'patient_name' => $this->input->post("patient_name"),
				'patient_gender' => ($this->input->post("patient_gender") != "") ? $this->input->post("patient_gender") : "",
				'patient_birthdate' => ($this->input->post("patient_birthdate") != "") ? $this->input->post("patient_birthdate") : "",
				'parent_id' => $id,
				'timestamp' => time()
			);
			$this->power_model->addChild($datas);
			
			redirect("doctor/listChild/".$id."/?success");
		}
	}
	public function listChild($id = "") {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 12) { //list child
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(strlen($id) == 0) {
			redirect("doctor/parents");
		}
		$data = array(
			'title' => 'Child List',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'listChilds' => '',
			'parent_name' =>  $this->power_model->getUserInfoParentName($id),
			'level' => 'doctor'
		);
        $data['listChilds'] = $this->power_model->get_listChilds($id);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/listChild.php', $data);
		$this->load->view('Power/Include/footer', $data);
	}
	public function editChild($id = "") {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 14) { //add child
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(strlen($id) == 0) {
			redirect("doctor/parents");
		}
		$result = $this->power_model->getUserInfoPatient($id);
		$result2 = (array) $result;
		if($this->power_model->checkAccount($result2[0]['patient_id'], 'patient') == 1) {
			// exist
		} else {
			// not exist
			// error
			redirect("doctor/parents?error");
		}
		$parent_name = $this->power_model->getUserInfoPatientGetParentName($result2[0]['parent_id']);

		$data = array(
			'title' => 'Edit Child',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'parent_name' => $parent_name,
			'parent_id' => $result2[0]['parent_id'],
			'childs' => $result,
			'level' => 'doctor'
		);

		$this->form_validation->set_rules('patient_name', 'Patient Name', 'required');
		$this->form_validation->set_rules('patient_gender', 'Patient Gender', 'required');
		$this->form_validation->set_rules('patient_birthdate', 'Patient Birthdate', 'required');
        
		if($this->form_validation->run() == FALSE) {
			$this->load->view('Power/Include/header', $data);
			$this->load->view('Power/editChild', $data);
			$this->load->view('Power/Include/footer');
		} else {
			$datas = [
				'patient_name' => $this->input->post("patient_name"),
				'patient_gender' => $this->input->post("patient_gender"),
				'patient_birthdate' => $this->input->post("patient_birthdate")
			];
			$this->power_model->editChild($datas, $id);
			redirect("doctor/listChild/".$result2[0]['parent_id']."?success");
		}
	}
	
	public function deleteChild($id = "") {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 15) { //delete child
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(strlen($id) == 0) {
			redirect("doctor/parents");
		}
		if($this->power_model->checkAccount($id, 'patient') == 1) {
			// exist
		} else {
			// not exist
			// error
			redirect("doctor/parents?error");
		}
		$this->power_model->deleteChild($id);
		redirect("doctor/parents?success");
	}
	public function patients() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 16) { //patient
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Patient List',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'links' => '',
			'patients' => '',
			'level' => 'doctor'
		);

		$config = array();
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$config["per_page"] = 10;
        $config["base_url"] = base_url() . "doctor/patients";
        $config["total_rows"] = $this->power_model->get_patients_count($config["per_page"], $page);
        
        $config["uri_segment"] = 3;

		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		
        $this->pagination->initialize($config);

        

        $data["links"] = $this->pagination->create_links();

        $data['patients'] = $this->power_model->get_patients($config["per_page"], $page);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/patients.php', $data);
		$this->load->view('Power/Include/footer', $data);
	}

	// patient satisfaction
	public function announcements() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 18) { //announcement
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/
		$data = array(
			'title' => 'Announcement',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'links' => '',
			'announcements' => '',
			'level' => 'doctor'
		);

		$config = array();
        $config["base_url"] = base_url() . "doctor/announcements";
        $config["total_rows"] = $this->power_model->get_announcements_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["links"] = $this->pagination->create_links();

        $data['announcements'] = $this->power_model->get_announcements($config["per_page"], $page);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/announcements.php', $data);
		$this->load->view('Power/Include/footer', $data);
	}

	public function addAnnouncements() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 19) { //addannouncement
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Add Announcement',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor'
		);

		$this->form_validation->set_rules('announcement_tbl_title', 'announcement_tbl_title', 'required');
		$this->form_validation->set_rules('announcement_tbl_content', 'announcement_tbl_content', 'required');
        
		if($this->form_validation->run() == FALSE) {
			$this->load->view('Power/Include/header', $data);
			$this->load->view('Power/addAnnouncements', $data);
			$this->load->view('Power/Include/footer');
		} else {
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = 2000;
			$config['max_width'] = 1500;
			$config['max_height'] = 1500;
			$this->load->library('upload', $config);

			$imgpath = "";
			if($this->upload->do_upload('announcement_tbl_image')) {
				$imgdata = $this->upload->data();
				$imgpath = file_get_contents($imgdata['full_path']);
			}

			$datas = array(
				'announcement_tbl_date' => time(),
				'announcement_tbl_title' => $this->input->post("announcement_tbl_title"),
				'announcement_tbl_content' => $this->input->post("announcement_tbl_content"),
				'announcement_tbl_image' => $imgpath
			);
 			$this->power_model->addAnnouncements($datas);
			redirect("doctor/announcements?success");
		}
	}

	public function deleteAnnouncements($id = "") {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 20) { //deleteannouncement
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(strlen($id) == 0) {
			redirect("doctor/announcements");
		}
		
		$this->power_model->deleteAnnouncements($id);
		redirect("doctor/announcements?success");
	}

	public function editAnnouncements($id = "") {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 20) { //editannouncement
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(strlen($id) == 0) {
			redirect("doctor/announcements");
		}
		$result = $this->power_model->getAnnouncements($id);
		$result2 = (array) $result;
		if($this->power_model->checkAnnouncements($id) == 1) {
			// exist
		} else {
			// not exist
			// error
			redirect("doctor/announcements?error");
		}
		$title = $result2[0]['announcement_tbl_title'];
		$content = $result2[0]['announcement_tbl_content'];

		$data = array(
			'title' => 'Edit Announcement',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'title' => $title,
			'content' => $content,
			'announcements' => $result,
			'level' => 'doctor'
		);

		$this->form_validation->set_rules('announcement_tbl_title', 'announcement_tbl_title', 'required');
		$this->form_validation->set_rules('announcement_tbl_content', 'announcement_tbl_content', 'required');
        
		if($this->form_validation->run() == FALSE) {
			$this->load->view('Power/Include/header', $data);
			$this->load->view('Power/editAnnouncements', $data);
			$this->load->view('Power/Include/footer');
		} else {
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = 2000;
			$config['max_width'] = 1500;
			$config['max_height'] = 1500;
			$this->load->library('upload', $config);

			$imgpath = "";
			if($this->upload->do_upload('announcement_tbl_image')) {
				$imgdata = $this->upload->data();
				$imgpath = file_get_contents($imgdata['full_path']);
			}

			$datas = array(
				'announcement_tbl_date' => time(),
				'announcement_tbl_title' => $this->input->post("announcement_tbl_title"),
				'announcement_tbl_content' => $this->input->post("announcement_tbl_content")
			);
			if($imgpath != "") {
				$datas['announcement_tbl_image'] = $imgpath;
			}
 			$this->power_model->editAnnouncements($datas, $id);
			redirect("doctor/announcements?success");
		}
	}

	// pediatric 
	public function viewPediatricChart($patient_id) {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 24) {//check
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/
		if(strlen($patient_id) == 0) {
			redirect("doctor/viewPediatricChart");
		}

		if($this->power_model->checkAccount($patient_id, 'patient') == 1) {
			// exist
		} else {
			// not exist
			// error
			redirect("doctor/parents?error");
		}

		$data = array(
			'title' => 'View Pediatric Charts',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'links' => '',
			'viewPediatricCharts' => '',
			'patient_name' => $this->power_model->getUserInfoPatientName($patient_id),
			'patient_id' => $patient_id,
			'level' => 'doctor'
		);
        $data['viewPediatricCharts'] = $this->power_model->get_viewPediatricCharts($patient_id);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/viewPediatricChart.php', $data);
		$this->load->view('Power/Include/footer', $data);
	}
	public function deletePediatricChart($id = "") {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 27) {//check
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(strlen($id) == 0) {
			redirect("doctor/parents");
		}
		
		$this->power_model->deletePediatricCharts($id);
		redirect("doctor/parents?success");
	}
	public function addPediatricChart($id = "") {	
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 25) {//check
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(strlen($id) == 0) {
			redirect("doctor/parents");
		}
		if($this->power_model->checkAccount($id, 'patient') == 1) {
			// exist
		} else {
			// not exist
			// error
			redirect("doctor/parents?error");
		}

		$patient = $this->power_model->getUserInfoPatient($id);
		$data = array(
			'title' => 'Add Pediatric Chart',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'patient_name' => $this->power_model->getUserInfoPatientName($id),
			'parent_name' => $this->power_model->getUserInfoPatientGetParentName($id),
			'parent_id' => $patient[0]['parent_id'],
			'level' => 'doctor'
		);

		$this->form_validation->set_rules('chiefComplaint', 'chiefComplaint', 'trim');
		$this->form_validation->set_rules('medicalHistory', 'medicalHistory', 'trim');
		$this->form_validation->set_rules('pastMedicalHistory', 'pastMedicalHistory', 'trim');
		$this->form_validation->set_rules('familyHistory', 'familyHistory', 'trim');
		$this->form_validation->set_rules('birthHistory', 'birthHistory', 'trim');
		$this->form_validation->set_rules('feedingHistory', 'feedingHistory', 'trim');
		$this->form_validation->set_rules('immunization', 'immunization', 'trim');
		$this->form_validation->set_rules('earAndBodyPiercing', 'earAndBodyPiercing', 'trim');
		$this->form_validation->set_rules('circumcision', 'circumcision', 'trim');
		$this->form_validation->set_rules('developmentalHistory', 'developmentalHistory', 'trim');
		$this->form_validation->set_rules('bp', 'bp', 'trim');
		$this->form_validation->set_rules('cr', 'cr', 'trim');
		$this->form_validation->set_rules('rr', 'rr', 'trim');
		$this->form_validation->set_rules('temp', 'temp', 'trim');
		$this->form_validation->set_rules('O2Sat', 'O2Sat', 'trim');
		$this->form_validation->set_rules('weight', 'weight', 'trim');
		$this->form_validation->set_rules('Ht', 'Ht', 'trim');
		$this->form_validation->set_rules('hc', 'hc', 'trim');
		$this->form_validation->set_rules('cc', 'cc', 'trim');
		$this->form_validation->set_rules('ac', 'ac', 'trim');
		$this->form_validation->set_rules('height', 'height', 'trim');
		$this->form_validation->set_rules('skin', 'skin', 'trim');
		$this->form_validation->set_rules('heent', 'heent', 'trim');
		$this->form_validation->set_rules('thorax', 'thorax', 'trim');
		$this->form_validation->set_rules('abdomen', 'abdomen', 'trim');
		$this->form_validation->set_rules('genitourinarySystem', 'genitourinarySystem', 'trim');
		$this->form_validation->set_rules('rectalExamination', 'rectalExamination', 'trim');
		$this->form_validation->set_rules('extremities', 'extremities', 'trim');
		$this->form_validation->set_rules('assessment', 'assessment', 'trim');
		$this->form_validation->set_rules('lmp', 'lmp', 'trim');
		$this->form_validation->set_rules('obstretrics', 'obstretrics', 'trim');
		$this->form_validation->set_rules('investigate', 'Investigate', 'trim');
		$this->form_validation->set_rules('therapy', 'therapy', 'trim');
        
		if($this->form_validation->run() == FALSE) {
			$this->load->view('Power/Include/header', $data);
			$this->load->view('Power/addPediatricChart', $data);
			$this->load->view('Power/Include/footer');
		} else {
			$time = time();
			$getTime = $time;
			$searchPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$id."'")->result_array();
			$data = array(
				'patient_id' => $id,
				'patient_name' => $searchPatient[0]['patient_name'],
				'patient_datetime' => $getTime,
				'date_text' => date("M d, Y",$getTime),
				'chiefComplaint' => $this->input->post('chiefComplaint'),
				'medicalHistory' => $this->input->post('medicalHistory'),
				'pastMedicalHistory' => $this->input->post('pastMedicalHistory')
			);
			$data2 = array(
				'familyHistory' => $this->input->post('familyHistory'),
				'birthHistory' => $this->input->post('birthHistory'),
				'feedingHistory' => $this->input->post('feedingHistory'),
				'immunization' => $this->input->post('immunization'),
				'earAndBodyPiercing' => $this->input->post('earAndBodyPiercing'),
				'circumcision' => $this->input->post('circumcision'),
				'developmentalHistory' => $this->input->post('developmentalHistory')
			);

			$data3 = array(
				'bp' => $this->input->post('bp'),
				'cr' => $this->input->post('cr'),
				'rr' => $this->input->post('rr'),
				'temp' => $this->input->post('temp'),
				'O2Sat' => $this->input->post('O2Sat'),
				'weight' => $this->input->post('weight')
			);
			$data4 = array(
				'Ht' => $this->input->post('Ht'),
				'hc' => $this->input->post('hc'),
				'cc' => $this->input->post('cc'),
				'ac' => $this->input->post('ac'),
				'height' => $this->input->post('height')
			);

			$data5 = array(
				'skin' => $this->input->post('skin'),
				'heent' => $this->input->post('heent'),
				'thorax' => $this->input->post('thorax'),
				'abdomen' => $this->input->post('abdomen'),
				'genitourinarySystem' => $this->input->post('genitourinarySystem'),
				'rectalExamination' => $this->input->post('rectalExamination'),
				'extremities' => $this->input->post('extremities')
			);
			$data6 = array(
				'assessment' => $this->input->post('assessment'),
				'lmp' => $this->input->post('lmp'),
				'obstretrics' => $this->input->post('obstretrics'),
				'Investigate' => $this->input->post('Investigate'),
				'therapy' => $this->input->post('therapy')
			);
			$this->power_model->addPediatricCharts($data);
			$this->power_model->addPediatricChartsBatch2($data2, $id, $getTime);
			$this->power_model->addPediatricChartsBatch2($data3, $id, $getTime);
			$this->power_model->addPediatricChartsBatch2($data4, $id, $getTime);
			$this->power_model->addPediatricChartsBatch2($data5, $id, $getTime);
			$this->power_model->addPediatricChartsBatch2($data6, $id, $getTime);
			redirect("doctor/viewPediatricChart/".$id."?success");
		}
	}
	public function editPediatricChart($id = "") {	
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 26) {//check
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(strlen($id) == 0) {
			redirect("doctor/parents");
		}
		if($this->power_model->checkAccount($id, 'patient') == 1) {
			// exist
		} else {
			// not exist
			// error
			redirect("doctor/parents?error");
		}
		$damz = $this->power_model->getPediatricCharts($id);
		$damzz = (array) $damz;

		$patient = $this->power_model->getUserInfoPatient($damz[0]['patient_id']);
		$data = array(
			'title' => 'Edit Pediatric Chart',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'patient_name' => $this->power_model->getUserInfoPatientName($damz[0]['patient_id']),
			'parent_name' => $this->power_model->getUserInfoPatientGetParentName($damz[0]['patient_id']),
			'parent_id' => $patient[0]['parent_id'],
			'datas' => $this->power_model->getPediatricCharts($id),
			'level' => 'doctor'
		);

		$this->form_validation->set_rules('chiefComplaint', 'chiefComplaint', 'trim');
		$this->form_validation->set_rules('medicalHistory', 'medicalHistory', 'trim');
		$this->form_validation->set_rules('pastMedicalHistory', 'pastMedicalHistory', 'trim');
		$this->form_validation->set_rules('familyHistory', 'familyHistory', 'trim');
		$this->form_validation->set_rules('birthHistory', 'birthHistory', 'trim');
		$this->form_validation->set_rules('feedingHistory', 'feedingHistory', 'trim');
		$this->form_validation->set_rules('immunization', 'immunization', 'trim');
		$this->form_validation->set_rules('earAndBodyPiercing', 'earAndBodyPiercing', 'trim');
		$this->form_validation->set_rules('circumcision', 'circumcision', 'trim');
		$this->form_validation->set_rules('developmentalHistory', 'developmentalHistory', 'trim');
		$this->form_validation->set_rules('bp', 'bp', 'trim');
		$this->form_validation->set_rules('cr', 'cr', 'trim');
		$this->form_validation->set_rules('rr', 'rr', 'trim');
		$this->form_validation->set_rules('temp', 'temp', 'trim');
		$this->form_validation->set_rules('O2Sat', 'O2Sat', 'trim');
		$this->form_validation->set_rules('weight', 'weight', 'trim');
		$this->form_validation->set_rules('Ht', 'Ht', 'trim');
		$this->form_validation->set_rules('hc', 'hc', 'trim');
		$this->form_validation->set_rules('cc', 'cc', 'trim');
		$this->form_validation->set_rules('ac', 'ac', 'trim');
		$this->form_validation->set_rules('height', 'height', 'trim');
		$this->form_validation->set_rules('skin', 'skin', 'trim');
		$this->form_validation->set_rules('heent', 'heent', 'trim');
		$this->form_validation->set_rules('thorax', 'thorax', 'trim');
		$this->form_validation->set_rules('abdomen', 'abdomen', 'trim');
		$this->form_validation->set_rules('rectalExamination', 'rectalExamination', 'trim');
		$this->form_validation->set_rules('extremities', 'extremities', 'trim');
		$this->form_validation->set_rules('assessment', 'assessment', 'trim');
		$this->form_validation->set_rules('lmp', 'lmp', 'trim');
		$this->form_validation->set_rules('obstretrics', 'obstretrics', 'trim');
		$this->form_validation->set_rules('Investigate', 'Investigate', 'trim');
		$this->form_validation->set_rules('therapy', 'therapy', 'trim');
        
		if($this->form_validation->run() == FALSE) {
			$this->load->view('Power/Include/header', $data);
			$this->load->view('Power/editPediatricChart', $data);
			$this->load->view('Power/Include/footer');
		} else {
			$time = time();
			$getTime = $time;
			$data = array(
				'patient_id' => $id,
				'patient_datetime' => $getTime,
				'chiefComplaint' => $this->input->post('chiefComplaint'),
				'medicalHistory' => $this->input->post('medicalHistory'),
				'pastMedicalHistory' => $this->input->post('pastMedicalHistory')
			);
			$data2 = array(
				'familyHistory' => $this->input->post('familyHistory'),
				'birthHistory' => $this->input->post('birthHistory'),
				'feedingHistory' => $this->input->post('feedingHistory'),
				'immunization' => $this->input->post('immunization'),
				'earAndBodyPiercing' => $this->input->post('earAndBodyPiercing'),
				'circumcision' => $this->input->post('circumcision'),
				'developmentalHistory' => $this->input->post('developmentalHistory')
			);

			$data3 = array(
				'bp' => $this->input->post('bp'),
				'cr' => $this->input->post('cr'),
				'rr' => $this->input->post('rr'),
				'temp' => $this->input->post('temp'),
				'O2Sat' => $this->input->post('O2Sat'),
				'weight' => $this->input->post('weight')
			);
			$data4 = array(
				'Ht' => $this->input->post('Ht'),
				'hc' => $this->input->post('hc'),
				'cc' => $this->input->post('cc'),
				'ac' => $this->input->post('ac'),
				'height' => $this->input->post('height')
			);

			$data5 = array(
				'skin' => $this->input->post('skin'),
				'heent' => $this->input->post('heent'),
				'thorax' => $this->input->post('thorax'),
				'abdomen' => $this->input->post('abdomen'),
				'rectalExamination' => $this->input->post('rectalExamination'),
				'extremities' => $this->input->post('extremities')
			);
			$data6 = array(
				'assessment' => $this->input->post('assessment'),
				'lmp' => $this->input->post('lmp'),
				'obstretrics' => $this->input->post('obstretrics'),
				'Investigate' => $this->input->post('Investigate'),
				'therapy' => $this->input->post('therapy')
			);
			$this->power_model->editPediatricCharts($data, $id);
			$this->power_model->editPediatricChartsBatch2($data2, $id);
			$this->power_model->editPediatricChartsBatch2($data3, $id);
			$this->power_model->editPediatricChartsBatch2($data4, $id);
			$this->power_model->editPediatricChartsBatch2($data5, $id);
			$this->power_model->editPediatricChartsBatch2($data6, $id);
			redirect("doctor/viewPediatricChart/".$id."?success");
		}
	}
	public function viewPediatricChartData($id = "") {	
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 26) {//check
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(strlen($id) == 0) {
			redirect("doctor/parents");
		}
		if($this->power_model->checkAccount($id, 'patient') == 1) {
			// exist
		} else {
			// not exist
			// error
			redirect("doctor/parents?error");
		}
		$damz = $this->power_model->getPediatricCharts($id);
		$damzz = (array) $damz;

		$patient = $this->power_model->getUserInfoPatient($damz[0]['patient_id']);
		$data = array(
			'title' => 'View Pediatric Chart Data',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'patient_name' => $this->power_model->getUserInfoPatientName($damz[0]['patient_id']),
			'parent_name' => $this->power_model->getUserInfoPatientGetParentName($damz[0]['patient_id']),
			'parent_id' => $patient[0]['parent_id'],
			'datas' => $this->power_model->getPediatricCharts($id),
			'level' => 'doctor'
		);

		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/viewPediatricChartData', $data);
		$this->load->view('Power/Include/footer');
	}

	// clinic analytics
	public function clinic_analytics() {
		$data = array(
			'title' => 'Clinic Analytics',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor'
		);
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/clinic_analytics', $data);
		$this->load->view('Power/Include/footer', $data);
	}
	
	// health tips
	public function health_tips() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 28) {//check
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Health Tips',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor'
		);
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/health_tips', $data);
		$this->load->view('Power/Include/footer', $data);
	}
	public function getImmunizationRecordPDF() {
		$patient = ($_GET['patient'] != "") ? $_GET['patient'] : "";
		$vaccine = ($_GET['vaccine'] != "") ? $_GET['vaccine'] : "";
		$date = ($_GET['date'] != "") ? $_GET['date'] : "";

		// Get output html
		$html = $this->output->get_output();
			
		// Load pdf library
		$this->load->library('pdf');

		$data = $this->power_model->getImmunizationRecordPDF($patient, $vaccine, $date);
		$html .= '<table style="width: 100%;">';
			$html .= '<tbody>';
				$html .= '<tr>';
					$html .= '<b style="float:left;font-size: 25px;margin-top:10px;margin-left:10px;">WHealth</b>';
					$html .= '<img style="float:left;width:70px;height:70px;" src="data:image/png;base64,' . base64_encode(file_get_contents("https://peekabook.tech/assets/img/whealth.png")).'">';
					$html .= '<div style="clear:both;"></div>';
				$html .= '</tr>';
			$html .= '</tbody>';
		$html .= '</table>';
		$html .= '<div style="clear:both;"></div>';
		$html .= '<table border="1" style="width:100%;border: 1px solid black; border-collapse: collapse;padding:10px;">';
			
			$html .= '<thead>';
				$html .= '<tr>';
					$html .= '<td colspan="6" style="font-size: 12px;border: 1px solid black; border-collapse: collapse;padding:10px;text-align:left;">';
						$html .= 'Immunization Record<br/>';
						$html .= 'Generate Date & Time: '.date("M d, Y H:i:sA", time()).' (GMT +8 hours)';
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= '#';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Parent Name';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Patient Name';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Date';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Vaccine';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Route';
					$html .= '</td>';
				$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
				$count = 0;
				foreach($data as $row) {
					$html .= "<tr>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['immunization_record_id'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['parent_name'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['patient_name'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['date'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['vaccine_name'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['route'];
						$html .= "</td>";
					$html .= "</tr>";
					$count++;
				}
				if($count == 0) {
					$html .= "<tr>";
						$html .= '<td colspan="6" style="font-size: 12px;border: 1px solid black; border-collapse: collapse;padding:10px;text-align:left;text-align:center;">';
							$html .= 'No results found.';
						$html .= '</td>';
					$html .= "</tr>";
				}
			$html .= '</tbody>';
		$html .= '</table>';

		 // Load HTML content
		 $this->pdf->loadHtml($html);
        
		 // (Optional) Setup the paper size and orientation
		 $this->pdf->setPaper('A4', 'portrait');
		 
		 // Render the HTML as PDF
		 $this->pdf->render();
		 
		 // Output the generated PDF (1 = download and 0 = preview)
		 $this->pdf->stream("whealth-report.pdf", array("Attachment"=>1));
	}
	public function getTransactionAppointmentPDF($per_page, $page) {
		if($per_page == "" && $page == "") {
			redirect("doctor/index?error=There\'s something wrong!");
		}
		// Get output html
		$html = $this->output->get_output();
			
		// Load pdf library
		$this->load->library('pdf');

		$data = $this->power_model->get_transactionz_appointment($per_page, $page);
		$html .= '<table style="width: 100%;">';
			$html .= '<tbody>';
				$html .= '<tr>';
					$html .= '<b style="float:left;font-size: 25px;margin-top:10px;margin-left:10px;">WHealth</b>';
					$html .= '<img style="float:left;width:70px;height:70px;" src="data:image/png;base64,' . base64_encode(file_get_contents("https://peekabook.tech/assets/img/whealth.png")).'">';
					$html .= '<div style="clear:both;"></div>';
				$html .= '</tr>';
			$html .= '</tbody>';
		$html .= '</table>';
		$html .= '<div style="clear:both;"></div>';
		$html .= '<table border="1" style="width:100%;border: 1px solid black; border-collapse: collapse;padding:10px;">';
			
			$html .= '<thead>';
				$html .= '<tr>';
					$html .= '<td colspan="7" style="font-size: 12px;border: 1px solid black; border-collapse: collapse;padding:10px;text-align:left;">';
						$html .= 'Transaction (Appointment)<br/>';
						$html .= 'Generate Date & Time: '.date("M d, Y H:i:sA", time()).' (GMT +8 hours)';
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= '#';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Parent Name';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Patient Name';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Date';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Status';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Reference Number';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Money';
					$html .= '</td>';
				$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
				$count = 0;
				foreach($data as $row) {
					$html .= "<tr>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['id'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['parent_name'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['patient_name'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['datetime'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['status'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['reference_number'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">P";
							$html .= $row['money'];
						$html .= "</td>";
					$html .= "</tr>";
					$count++;
				}
				if($count == 0) {
					$html .= "<tr>";
						$html .= '<td colspan="6" style="font-size: 12px;border: 1px solid black; border-collapse: collapse;padding:10px;text-align:left;text-align:center;">';
							$html .= 'No results found.';
						$html .= '</td>';
					$html .= "</tr>";
				}
			$html .= '</tbody>';
		$html .= '</table>';

		 // Load HTML content
		 $this->pdf->loadHtml($html);
        
		 // (Optional) Setup the paper size and orientation
		 $this->pdf->setPaper('A4', 'portrait');
		 
		 // Render the HTML as PDF
		 $this->pdf->render();
		 
		 // Output the generated PDF (1 = download and 0 = preview)
		 $this->pdf->stream("whealth-report.pdf", array("Attachment"=>1));
	}
	public function getTransactionConsultationPDF($per_page, $page) {
		if($per_page == "" && $page == "") {
			redirect("doctor/index?error=There\'s something wrong!");
		}
		// Get output html
		$html = $this->output->get_output();
			
		// Load pdf library
		$this->load->library('pdf');

		$data = $this->power_model->get_transactionz_consultation($per_page, $page);
		$html .= '<table style="width: 100%;">';
			$html .= '<tbody>';
				$html .= '<tr>';
					$html .= '<b style="float:left;font-size: 25px;margin-top:10px;margin-left:10px;">WHealth</b>';
					$html .= '<img style="float:left;width:70px;height:70px;" src="data:image/png;base64,' . base64_encode(file_get_contents("https://peekabook.tech/assets/img/whealth.png")).'">';
					$html .= '<div style="clear:both;"></div>';
				$html .= '</tr>';
			$html .= '</tbody>';
		$html .= '</table>';
		$html .= '<div style="clear:both;"></div>';
		$html .= '<table border="1" style="width:100%;border: 1px solid black; border-collapse: collapse;padding:10px;">';
			
			$html .= '<thead>';
				$html .= '<tr>';
					$html .= '<td colspan="7" style="font-size: 12px;border: 1px solid black; border-collapse: collapse;padding:10px;text-align:left;">';
						$html .= 'Transaction (Consultation)<br/>';
						$html .= 'Generate Date & Time: '.date("M d, Y H:i:sA", time()).' (GMT +8 hours)';
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= '#';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Parent Name';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Patient Name';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Date';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Status';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Reference Number';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Money';
					$html .= '</td>';
				$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
				$count = 0;
				foreach($data as $row) {
					$html .= "<tr>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['id'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['parent_name'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['patient_name'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['datetime'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['status'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['reference_number'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">P";
							$html .= $row['money'];
						$html .= "</td>";
					$html .= "</tr>";
					$count++;
				}
				if($count == 0) {
					$html .= "<tr>";
						$html .= '<td colspan="6" style="font-size: 12px;border: 1px solid black; border-collapse: collapse;padding:10px;text-align:left;text-align:center;">';
							$html .= 'No results found.';
						$html .= '</td>';
					$html .= "</tr>";
				}
			$html .= '</tbody>';
		$html .= '</table>';

		 // Load HTML content
		 $this->pdf->loadHtml($html);
        
		 // (Optional) Setup the paper size and orientation
		 $this->pdf->setPaper('A4', 'portrait');
		 
		 // Render the HTML as PDF
		 $this->pdf->render();
		 
		 // Output the generated PDF (1 = download and 0 = preview)
		 $this->pdf->stream("whealth-report.pdf", array("Attachment"=>1));
	}
	public function getTransactionMedicalCertificatePDF($per_page, $page) {
		if($per_page == "" && $page == "") {
			redirect("doctor/index?error=There\'s something wrong!");
		}
		// Get output html
		$html = $this->output->get_output();
			
		// Load pdf library
		$this->load->library('pdf');

		$data = $this->power_model->get_transaction_medical_certificate($per_page, $page);
		$html .= '<table style="width: 100%;">';
			$html .= '<tbody>';
				$html .= '<tr>';
					$html .= '<b style="float:left;font-size: 25px;margin-top:10px;margin-left:10px;">WHealth</b>';
					$html .= '<img style="float:left;width:70px;height:70px;" src="data:image/png;base64,' . base64_encode(file_get_contents("https://peekabook.tech/assets/img/whealth.png")).'">';
					$html .= '<div style="clear:both;"></div>';
				$html .= '</tr>';
			$html .= '</tbody>';
		$html .= '</table>';
		$html .= '<div style="clear:both;"></div>';
		$html .= '<table border="1" style="width:100%;border: 1px solid black; border-collapse: collapse;padding:10px;">';
			
			$html .= '<thead>';
				$html .= '<tr>';
					$html .= '<td colspan="7" style="font-size: 12px;border: 1px solid black; border-collapse: collapse;padding:10px;text-align:left;">';
						$html .= 'Transaction (Medical Certificate)<br/>';
						$html .= 'Generate Date & Time: '.date("M d, Y H:i:sA", time()).' (GMT +8 hours)';
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= '#';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Parent Name';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Patient Name';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Date';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Status';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Reference Number';
					$html .= '</td>';
					$html .= '<td style="font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;">';
						$html .= 'Money';
					$html .= '</td>';
				$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
				$count = 0;
				foreach($data as $row) {
					$html .= "<tr>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['id'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['parent_name'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['patient_name'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= date("M d, Y",$row['date_of_consultation']);
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['status'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['reference_number'];
						$html .= "</td>";
						$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">P";
							$html .= $row['money'];
						$html .= "</td>";
					$html .= "</tr>";
					$count++;
				}
				if($count == 0) {
					$html .= "<tr>";
						$html .= '<td colspan="6" style="font-size: 12px;border: 1px solid black; border-collapse: collapse;padding:10px;text-align:left;text-align:center;">';
							$html .= 'No results found.';
						$html .= '</td>';
					$html .= "</tr>";
				}
			$html .= '</tbody>';
		$html .= '</table>';

		 // Load HTML content
		 $this->pdf->loadHtml($html);
        
		 // (Optional) Setup the paper size and orientation
		 $this->pdf->setPaper('A4', 'portrait');
		 
		 // Render the HTML as PDF
		 $this->pdf->render();
		 
		 // Output the generated PDF (1 = download and 0 = preview)
		 $this->pdf->stream("whealth-report.pdf", array("Attachment"=>1));
	}
	public function getTabularReport($per_page = "", $page = "", $type = "") {
		if($per_page == "" && $page == "") {
			redirect("doctor/index?error=There\'s something wrong!");
		}
		// Get output html
		$html = $this->output->get_output();
			
		// Load pdf library
		$this->load->library('pdf');

		$num = 0;
		$txt = "";
		if($type == "appointments") {
			$num = 5;
			$txt = "Appointments";
		} else if($type == "number_of_patients") {
			$num = 2;
			$txt = "Number of Patients";
		} else if($type == "patient_satisfactions") {
			$num = 3;
			$txt = "Patient Satisfactions";
		} else if($type == "immunizations") {
			$num = 5;
			$txt = "Immunizations";
		} else {
			$num = 5;
			$txt = "Consultations";
		}

		$data = $this->power_model->get_tabular_reports($type, $per_page, $page);
		$html .= '<table style="width: 100%;">';
			$html .= '<tbody>';
				$html .= '<tr>';
					$html .= '<b style="float:left;font-size: 25px;margin-top:10px;margin-left:10px;">WHealth</b>';
					$html .= '<img style="float:left;width:70px;height:70px;" src="data:image/png;base64,' . base64_encode(file_get_contents("https://peekabook.tech/assets/img/whealth.png")).'">';
					$html .= '<div style="clear:both;"></div>';
				$html .= '</tr>';
			$html .= '</tbody>';
		$html .= '</table>';
		$html .= '<div style="clear:both;"></div>';
		$html .= '<table border="1" style="width:100%;border: 1px solid black; border-collapse: collapse;padding:10px;">';
			
			$html .= '<thead>';
				$html .= '<tr>';
					$html .= '<td colspan="'.$num.'" style="font-size: 12px;border: 1px solid black; border-collapse: collapse;padding:10px;text-align:left;">';
						$html .= 'Tabular Reports / '.$txt;
						$html .= '<br/>Generate Date & Time: '.date("M d, Y H:i:sA", time()).' (GMT +8 hours)';
					$html .= '</td>';
				$html .= '</tr>';
				if($type == "") {
					$html .= '<tr>';
					$html .= '<th>#</th>';
					$html .= '<th>Parent Name</th>';
					$html .= '<th>Patient Name</th>';
					$html .= '<th>Date Start</th>';
					$html .= '<th>Date End</th>';
					$html .= '<th>Status</th>';
					$html .= '</tr>';
				} else if($type == "appointments") {
					$html .= '<tr>';
					$html .= '<th>#</th>';
					$html .= '<th>Parent Name</th>';
					$html .= '<th>Patient Name</th>';
					$html .= '<th>Date Start</th>';
					$html .= '<th>Date End</th>';
					$html .= '<th>Status</th>';
					$html .= '</tr>';
				} else if($type == "number_of_patients") {
					$html .= '<tr>';
					$html .= '<th>#</th>';
					$html .= '<th>Patient Name</th>';
					$html .= '<th>Services</th>';
					$html .= '</tr>';
				} else if($type == "patient_satisfactions") {
					$html .= '<tr>';
					$html .= '<th>#</th>';
					$html .= '<th>Question</th>';
					$html .= '<th>Answer</th>';
					$html .= '<th>Percentage</th>';
					$html .= '</tr>';
				} else if($type == "immunizations") {
					$html .= '<tr>';
					$html .= '<th>#</th>';
					$html .= '<th>Patient Name</th>';
					$html .= '<th>Date</th>';
					$html .= '<th>Vaccine</th>';
					$html .= '<th>Route</th>';
					$html .= '<th>Parent Name</th>';
					$html .= '</tr>';
				}
			$html .= '</thead>';
			$html .= '<tbody>';
				$count = 0;
				if($type == "") {
					$counting1 = 0;
					foreach($data as $row) {
						$counting1++;
						$html .= "<tr>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $counting1;
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['parent_name'];
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['patient_name'];
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['datetime'];
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['datetime_end'];
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['status'];
							$html .= "</td>";
						$html .= "</tr>";
						$count++;
					}
				} else if($type == "appointments") {
					$counting2 = 0;
					foreach($data as $row) {
						$counting2++;
						$html .= "<tr>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $counting2;
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['parent_name'];
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['patient_name'];
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['datetime'];
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['datetime_end'];
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['status'];
							$html .= "</td>";
						$html .= "</tr>";
						$count++;
					}
				} else if($type == "number_of_patients") {
					$counting3 = 0;
					foreach($data as $row) {
						$counting3++;
						$html .= "<tr>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $counting3;
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['patient_name'];
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
							$html .= $row['services'];
							$html .= "</td>";
						$html .= "</tr>";
						$count++;
					}
				} else if($type == "patient_satisfactions") {
					$counting4 = 0;
					foreach($data as $row) {
						$counting4++;
						$html .= "<tr>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $counting4;
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['question'];
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['answer'];
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= ($row['percentage'] != "") ? $row['percentage'] : "-";
							$html .= "</td>";
						$html .= "</tr>";
						$count++;
					}
				} else if($type == "immunizations") {
					$counting5 = 0;
					foreach($data as $row) {
						$counting5++;
						$html .= "<tr>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $counting5;
							$html .= "</td>";
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['patient_name'];
							$html .= "</td>";

							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['date'];
							$html .= "</td>";

							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['vaccine_name'];
							$html .= "</td>";

							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['route'];
							$html .= "</td>";
							
							$html .= "<td style=\"font-size: 10px;border: 1px solid black; border-collapse: collapse;padding:10px;\">";
								$html .= $row['parent_name'];
							$html .= "</td>";
						$html .= "</tr>";
						$count++;
					}
				}
				if($count == 0) {
					$html .= "<tr>";
						$html .= '<td colspan="6" style="font-size: 12px;border: 1px solid black; border-collapse: collapse;padding:10px;text-align:left;text-align:center;">';
							$html .= 'No results found.';
						$html .= '</td>';
					$html .= "</tr>";
				}
			$html .= '</tbody>';
		$html .= '</table>';

		 // Load HTML content
		 $this->pdf->loadHtml($html);
        
		 // (Optional) Setup the paper size and orientation
		 $this->pdf->setPaper('A4', 'portrait');
		 
		 // Render the HTML as PDF
		 $this->pdf->render();
		 
		 // Output the generated PDF (1 = download and 0 = preview)
		 $this->pdf->stream("whealth-report.pdf", array("Attachment"=>1));
	}
	// patient satisfaction
	public function patient_satisfaction() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 22) { //patient satisfaction
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/
		$data = array(
			'title' => 'Patient Satisfaction',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'links' => '',
			'response_rate' => '',
			'level' => 'doctor',
			'myId' => $this->session->id
		);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/patient_satisfaction', $data);
		$this->load->view('Power/Include/footer', $data);
	}

	// pre-defined terms
	public function terms() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 23) {//terms
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Pre-defined Terms',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor'
		);
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/terms', $data);
		$this->load->view('Power/Include/footer', $data);
	}

	// notifications
	public function realtimeNotification() {
		echo json_encode($this->power_model->allTransactions());
	}

	// medical certification
	public function medical_certificate() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 30) {//terms
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Medical Certificate',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor',
			'medical_certificates' => ""
		);
		$filter = $_GET['filter'];
		$date = $_GET['date'];
		$doctor_id = $_GET['doctor_id'];
		
		if($filter == "" || $filter == "pending" || $filter == "approved" || $filter == "finished") {
			
		} else {
			$filter = "";
		}

		$config = array();
        $config["base_url"] = base_url() . "doctor/medical_certificate";
        $config["total_rows"] = $this->power_model->get_medical_certificates_count($filter, $date, $doctor_id);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
		$config['reuse_query_string'] = true;

		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
        $data["links"] = $this->pagination->create_links();

        $data['medical_certificates'] = $this->power_model->get_medical_certificates($filter, $date, $doctor_id, $config["per_page"], $page);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/medical_certificate', $data);
		$this->load->view('Power/Include/footer', $data);
	}

	public function cancelMedicalCertificate() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 30) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(is_numeric($this->uri->segment(3)) == 1) {
			$datas = array(
				"status" => "Cancelled"
			);
			$this->power_model->cancelMedicalCertificate($datas, $this->uri->segment(3));
			redirect("doctor/medical_certificate?success");
		} else {
			redirect("doctor/medical_certificate");
		}
	}

	public function doneMedicalCertificate() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 30) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(is_numeric($this->uri->segment(3)) == 1) {
			$datas = array(
				"status" => "Finished"
			);
			$this->power_model->doneMedicalCertificate($datas, $this->uri->segment(3));
			redirect("doctor/medical_certificate?success");
		} else {
			redirect("doctor/medical_certificate");
		}
	}

	public function approveMedicalCertificate() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 30) {
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		if(is_numeric($this->uri->segment(3)) == 1) {
			$datas = array(
				"status" => "Approved",
				"approve_by" => $this->session->id,
				"approve_selection" => 1
			);
			$this->power_model->approveMedicalCertificate($datas, $this->uri->segment(3));
			redirect("doctor/medical_certificate?success");
		} else {
			redirect("doctor/medical_certificate");
		}
	}

	// laboratory results
	public function laboratory_results() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 31) {//terms
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		$data = array(
			'title' => 'Laboratory Results',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor',
			'laboratory_results' => ""
		);

		$config = array();
        $config["base_url"] = base_url() . "doctor/laboratory_results";
        $config["total_rows"] = $this->power_model->get_laboratory_results_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
		$config['reuse_query_string'] = true;

		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
        $data["links"] = $this->pagination->create_links();

        $data['laboratory_results'] = $this->power_model->get_laboratory_results($config["per_page"], $page);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/laboratory_results', $data);
		$this->load->view('Power/Include/footer', $data);
	}
	public function doctor_schedule() {
		$data = array(
			'title' => 'Doctor Schedule',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor'
		);
		if($this->input->get('delete', TRUE)) {
			$delete = $this->input->get('delete',TRUE);
			$submitQuery = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_id` = '".$delete."' AND `doctor_id` = '".$this->session->id."'");

			if($submitQuery->num_rows() > 0) {
				$this->db->query("DELETE FROM `doctor_schedule` WHERE `doctor_schedule_id` = '".$delete."' AND `doctor_id` = '".$this->session->id."'");
				redirect("doctor/doctor_schedule?success");
			} else {
				redirect("doctor/doctor_schedule?error=There\'s something wrong");
			}
		}
		if($this->input->get('active', TRUE)) {
			$active = $this->input->get('active', TRUE);
			$submitQuery = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_id` = '".$active."' AND `doctor_id` = '".$this->session->id."'");

			if($submitQuery->num_rows() > 0) {
				$this->db->query("UPDATE `doctor_schedule` SET `doctor_status` = 'Active' WHERE `doctor_schedule_id` = '".$active."' AND `doctor_id` = '".$this->session->id."'");
				redirect("doctor/doctor_schedule?success");
			} else {
				redirect("doctor/doctor_schedule?error=There\'s something wrong");
			}
		}
		if($this->input->get('inactive', TRUE)) {
			$inactive = $this->input->get('inactive', TRUE);
			$submitQuery = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_id` = '".$inactive."' AND `doctor_id` = '".$this->session->id."'");

			if($submitQuery->num_rows() > 0) {
				$this->db->query("UPDATE `doctor_schedule` SET `doctor_status` = 'Inactive' WHERE `doctor_schedule_id` = '".$inactive."' AND `doctor_id` = '".$this->session->id."'");
				redirect("doctor/doctor_schedule?success");
			} else {
				redirect("doctor/doctor_schedule?error=There\'s something wrong");
			}
		}
		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/doctor_schedule', $data);
		$this->load->view('Power/Include/footer', $data);
	}

	// tabular reports
	public function tabular_reports() {
		/*
			Start of Access Privilege
		*/
		$permission = 0;
		if($resultAccess = $this->power_model->setAccessPrivilege($this->session->id, 1)) {
			foreach($resultAccess as $data) {
				if($data['category'] == 32) {//terms
					$permission = 1;
				}
			}
		}

		if($permission == 1) {
			redirect('doctor/index');
		}
		/*
			End of Access Privilege
		*/

		

		if($_GET['type'] == "" // consultation
		   || $_GET['type'] == "appointments"  ||
		   $_GET['type'] == "number_of_patients" ||
		   $_GET['type'] == "patient_satisfactions" ||
		   $_GET['type'] == "immunizations") {

		} else {
			redirect("doctor/index");
		}

		$config = array();
        $config["base_url"] = base_url() . "doctor/tabular_reports";
        $config["total_rows"] = $this->power_model->get_tabular_reports_count($_GET['type']);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
		$config['reuse_query_string'] = true;

		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data = array(
			'title' => 'Tabular Reports',
			'data' => $this->power_model->getId($this->session->id, 'doctor'),
			'level' => 'doctor',
			'tabular_reports' => "",
			'page' => $page,
			'per_page' => $config["per_page"],
		);

        $data["links"] = $this->pagination->create_links();

        $data['tabular_reports'] = $this->power_model->get_tabular_reports($_GET['type'], $config["per_page"], $page);

		//Below code will help you to  generate calendar in view
		$this->load->view('Power/Include/header', $data);
		$this->load->view('Power/tabular_reports', $data);
		$this->load->view('Power/Include/footer', $data);
	}
}
?>
