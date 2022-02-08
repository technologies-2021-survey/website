<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Manila");

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct() {
		parent::__construct();
		$this->load->model('home_model', null, true); // auto-connect model
		date_default_timezone_set('Asia/Manila');

	}

	public function index()
	{
		$data = array(
			'title' => 'Home',
			'content' => 'Home'
		);
		$this->load->view('Home/Include/header', $data);
		$this->load->view('Home/Home_index');
		$this->load->view('Home/Include/footer');
	}

	public function resend() {
		$this->load->model('login_model', null, true); // auto-connect model


		$otp = rand(000000,999999);
		$copyOtp = $otp;
		if($this->session->selection == "doctor"){

			$llsdl = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id`= '".$this->session->id."'")->result_array();
			$this->home_model->itexmo($llsdl[0]['doctor_phonenumber'], $copyOtp . ' is your OTP. Please enter this to confirm your login. (Resend Code)');
			$this->db->query("UPDATE doctors_tbl SET doctor_sms_code = '".$copyOtp."' WHERE doctor_id= '".$this->session->id."'");
		} else if($this->session->selection == "administrator"){

			$llsdl = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_id`= '".$this->session->id."'")->result_array();
			$this->home_model->itexmo($llsdl[0]['admin_phonenumber'], $copyOtp . ' is your OTP. Please enter this to confirm your login. (Resend Code)');
			$this->db->query("UPDATE admins_tbl SET admin_sms_code = '".$copyOtp."' WHERE admin_id= '".$this->session->id."'");
		} else if($this->session->selection == "receptionist"){

			$llsdl = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_id`= '".$this->session->id."'")->result_array();
			$this->home_model->itexmo($llsdl[0]['receptionist_phonenumber'], $copyOtp . ' is your OTP. Please enter this to confirm your login. (Resend Code)');
			$this->db->query("UPDATE receptionists_tbl SET receptionist_sms_code = '".$copyOtp."' WHERE receptionist_id= '".$this->session->id."'");

		}
		
		redirect($this->session->selection."/verification?success");
	}

	public function insert() {
	    $this->form_validation->set_rules('fullname','Name','required');
	    $this->form_validation->set_rules('email','Email','required|valid_email');
	    $this->form_validation->set_rules('phoneNumber','Phone Number','required');
	    $this->form_validation->set_rules('title','Title','required');
	    $this->form_validation->set_rules('message','message','required');
	    
	    if($this->form_validation->run()==FALSE) {
	        echo validation_errors();
	    } else {
	    
    		$insert = [
    			'Inquiries_Name' => $this->input->post('fullname'),
    			'Inquiries_EmailAddress' => $this->input->post('email'),
    			'Inquiries_PhoneNumber' => $this->input->post('phoneNumber'),
    			'Inquiries_title' => $this->input->post('title'),
    			'Inquiries_body' => $this->input->post('message'),
    			'Inquiries_DateTime' => time()
    		];
    		$this->home_model->insertInquiries($insert);
	    }
		//redirect("../home?success=yay");
	}
	
	public function forgotpassword() {
		if($this->session->userdata('id') && $this->session->userdata('selection') == "doctor") {
			redirect('doctor');
		} else if($this->session->userdata('id') && $this->session->userdata('selection') == "administrator") {
			redirect('administrator');
		} else if($this->session->userdata('id') && $this->session->userdata('selection') == "receptionist") {
			redirect('receptionist');
		}
		
		$data = array(
			'title' => 'Forgot Password',
			'error' => '',
			'success' => ''
		);

		// form valdiation
		$this->form_validation->set_rules('email', 'email', 'required');
		$this->form_validation->set_rules('selection', 'selection', 'required');

		if($this->form_validation->run() == FALSE) {
			$this->load->view('Home/Include/header', $data);
			$this->load->view('Home/forgotpassword');
			$this->load->view('Home/Include/footer');
		} else {
			$result = $this->home_model->forgotpassword($this->input->post('email'), $this->input->post('selection'));
			if($result == 'Successfully') {

				$gettingCode = $this->home_model->getCode($this->input->post('email'), $this->input->post('selection'));
				$gettingUsername = $this->home_model->getEmailToUsername($this->input->post('email'), $this->input->post('selection'));
				// email
				$config['useragent'] = 'CodeIgniter';
				$config['protocol']    = 'smtp';
				$config['smtp_host']    = 'ssl://smtp.hostinger.com';
				$config['smtp_port']    = '465';
				$config['smtp_timeout'] = '7';
				$config['smtp_user']    = 'harvs@peekabook.tech';
				$config['smtp_pass']    = 'Akashi24';
				
				//$config['smtp_host']    = 'ssl://smtp.gmail.com';
				//$config['smtp_port']    = '465';
				//$config['smtp_timeout'] = '7';
				//$config['smtp_user']    = 'blackspidermanxx66@gmail.com';
				//$config['smtp_pass']    = 'sirjeansmolpp';
				$config['charset']    = 'utf-8';
				$config['newline']    = "\r\n";
				$config['mailtype'] = 'text'; // or html
				$config['validation'] = TRUE; // bool whether to validate email or not      
				$this->email->initialize($config);
				$this->email->from('harvs@peekabook.tech', 'Forgot your password!');
				$this->email->to($this->input->post('email'));
				$this->email->subject('Please verify your account!');
				$this->email->message('Hi '.$this->input->post('email').'! '.base_url().'home/forgot/'.md5($gettingCode).'/'.$gettingUsername.'/'.$this->input->post('selection'));
				$this->email->send();
				//echo $this->email->print_debugger();

				$data['success'] = 'Successfully!';
				$this->load->view('Home/Include/header', $data);
				$this->load->view('Home/forgotpassword', $data);
				$this->load->view('Home/Include/footer');
			} else {
				// error
				$data['error'] = 'Error! Email doesn\'t exist';
				$this->load->view('Home/Include/header', $data);
				$this->load->view('Home/forgotpassword', $data);
				$this->load->view('Home/Include/footer');
			}
		}
	}
	public function tests($x,$y,$z) {
		echo $x . '<br/>';
		echo $y . '<br/>';
		echo $z . '<br/>';
	}
	public function forgot($md5, $username, $selection) {
		$data = array(
			'title' => 'Forgot Password',
			'error' => '',
			'success' => ''
		);
        if($selection != "doctor" && $selection != "administrator" &&$selection != "receptionist") {
            redirect("login?error=404");   
        }
        
		$gettingCode = $this->home_model->getCodeFromUsername($username, $selection);
        
        
		if(md5($gettingCode) == $md5) {
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]', 
	            array(
	                "min_length" => "Your password is incorrect."
	            )
	        );
			$this->form_validation->set_rules('repassword', 'Re-type Password', 'required|min_length[5]', 
	            array(
	                "min_length" => "Your password is incorrect."
	            )
	        );
	        if($this->form_validation->run() == FALSE) {
				$this->load->view('Home/Include/header', $data);
				$this->load->view('Home/forgot', $data);
				$this->load->view('Home/Include/footer');
			} else {
				if($this->input->post('password') == $this->input->post('repassword')) {
				    if($selection == "doctor") {
    					$this->home_model->removeCode($username, $selection);
    					$this->home_model->updatePassword($username, md5($this->input->post('password')), $selection);
    					$data['success'] = 'Successfully!';
    					$this->load->view('Home/Include/header', $data);
    					$this->load->view('Home/forgot', $data);
    					$this->load->view('Home/Include/footer');
				    } else if($selection == "receptionist") {
    					$this->home_model->removeCode($username, $selection);
    					$this->home_model->updatePassword($username, md5($this->input->post('password')), $selection);
    					$data['success'] = 'Successfully!';
    					$this->load->view('Home/Include/header', $data);
    					$this->load->view('Home/forgot', $data);
    					$this->load->view('Home/Include/footer');
				    }  else if($selection == "administrator") {
    					$this->home_model->removeCode($username, $selection);
    					$this->home_model->updatePassword($username, md5($this->input->post('password')), $selection);
    					$data['success'] = 'Successfully!';
    					$this->load->view('Home/Include/header', $data);
    					$this->load->view('Home/forgot', $data);
    					$this->load->view('Home/Include/footer');
				    }
				} else {
					$data['error'] = 'Error! Password and Re-type Password doesn\'t match.';
					$this->load->view('Home/Include/header', $data);
					$this->load->view('Home/forgot', $data);
					$this->load->view('Home/Include/footer');
				}
	        }

		} else {
			redirect("login?error=404");
			//echo md5($gettingCode);
			//echo '<br/>';
			//echo $md5;
		}
	}

	public function autoNotification() {
		date_default_timezone_set('Asia/Manila');
		$query = $this->db->query("SELECT * FROM `doctors_tbl`");
		foreach($query->result_array() as $data) {
			$timestamp = time();
			$month = date('m', $timestamp);
			$day = date('d', $timestamp);
			$year = date('Y', $timestamp);

			$hour = date('H', $timestamp);
			$minute = date('i', $timestamp);
			$second = date('s', $timestamp);

			$final = mktime($hour, $minute, $second, $month, $day, $year);
			if(($final <= $data['login_timeout'])) {
				$this->db->where("doctor_id", $data['doctor_id']);
				$this->db->set("doctor_sms_code", "@@@@@@@@EXIT@@@@@@@@");
				$this->db->set("active", "0");
				$this->db->update("doctors_tbl");
			}

			echo $final;
			echo '<br/>';
		}

		$query2 = $this->db->query("SELECT * FROM `admins_tbl`");
		foreach($query2->result_array() as $data2) {
			if(($final <= $data2['login_timeout'])) {
				$this->db->where("admin_id", $data2['admin_id']);
				$this->db->set("admin_sms_code", "@@@@@@@@EXIT@@@@@@@@");
				$this->db->set("active", "0");
				$this->db->update("admins_tbl");
			}
		}

		$query3 = $this->db->query("SELECT * FROM `receptionists_tbl`");
		foreach($query3->result_array() as $data3) {
			if(($final <= $data3['login_timeout'])) {
				$this->db->where("receptionist_id", $data3['receptionist_id']);
				$this->db->set("receptionist_sms_code", "@@@@@@@@EXIT@@@@@@@@");
				$this->db->set("active", "0");
				$this->db->update("receptionists_tbl");
			}
		}
		
		$time = time();
		$array = array();
		$array['time'] = date("m/d/Y h:i:s",$time);
		$array['timestamp'] = $time;

		$months = date('m', $time);
		$days = date('d', $time);
		$years = date('Y', $time);

		$hours = date('H', $time);
		$minutes = date('i', $time);
		$seconds = date('s', $time);

		//$overallTime = mktime($hours, $minutes, $seconds, $months, $days, $years);
		//date_consultation_datetime
		//appointment_timestamp_sub
		//12/04/2021 09:00:00
		$overallTime = $months . '/'. $days .'/'. $years .' ' .$hours. ':'.$minutes.':00';

		$appointment = $this->db->query("SELECT * FROM `appointments` WHERE appointment_timestamp_sub = '$overallTime' AND appointment_status = 'Approved' ORDER BY `appointment_timestamp_sub`");
		if($appointment->num_rows() > 0) {
			$count = 0;
			$arr = array();
			foreach($appointment->result_array() as $row) {
				$timestamp = $row['appointment_timestamp_sub'];
				$month = date('m', $timestamp);
				$day = date('d', $timestamp);
				$year = date('Y', $timestamp);

				$hour = date('H', $timestamp);
				$minute = date('i', $timestamp);
				$second = date('s', $timestamp);

				//$final = mktime($hour, $minute, $second, $month, $day, $year);
				$final = $month . '/'. $day .'/'. $years.' ' .$hour. ':'.$minute.':00';

				if($overallTime == $final) {
					if($row['interview_id'] == 0) {
					} else {
						$arr[] = $row['appointment_id'];
						$getDoctor = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$row['interview_id']."'")->result_array();
						$this->home_model->itexmo($getDoctor[0]['doctor_phonenumber'], "You have appointment right now!!\nDate start: ".$row['appointment_datetime']."\nDate end: ".$row['appointment_datetime_end']);
						$count++;
					}
				}
				
			}
			$array['appointment'] = $arr;
		}
		$consultation = $this->db->query("SELECT * FROM `consultations` WHERE date_consultation_datetime = '$overallTime' AND consultation_status = 'Approved' ORDER BY `date_consultation_sub`");
		if($consultation->num_rows() > 0) {
			$count = 0;
			$arr = array();
			foreach($consultation->result_array() as $row) {
				$timestamp = $row['date_consultation_sub'];
				$month = date('m', $timestamp);
				$day = date('d', $timestamp);
				$year = date('Y', $timestamp);

				$hour = date('H', $timestamp);
				$minute = date('i', $timestamp);
				$second = date('s', $timestamp);

				//$final = mktime($hour, $minute, $second, $month, $day, $year);
				$final = $month . '/'. $day .'/'. $years.' ' .$hour. ':'.$minute.':00';
				if($overallTime == $final) {
					if($row['interview_id'] == 0) {
					} else {
						$arr[] = $row['consultation_id'];
						$getDoctor = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$row['interview_id']."'")->result_array();
						$this->home_model->itexmo($getDoctor[0]['doctor_phonenumber'], "You have consultation right now!!\nDate start: ".$row['date_consultation_datetime']."\nDate end: ".$row['date_consultation_datetime_end']);
						$count++;
					}
				}
			}
			$array['consultation'] = $arr;
		}
		$immunization = $this->db->query("SELECT * FROM `immunization_record` WHERE comeback_for_timestamp_text = '$overallTime' ORDER BY `comeback_on_timestamp`");
		if($immunization->num_rows() > 0) {
			$arr = array();
			foreach($immunization->result_array() as $row) {
				$timestamp = $row['comeback_on_timestamp'];
				$month = date('m', $timestamp);
				$day = date('d', $timestamp);
				$year = date('Y', $timestamp);

				$hour = date('H', $timestamp);
				$minute = date('i', $timestamp);
				$second = date('s', $timestamp);

				//$final = mktime($hour, $minute, $second, $month, $day, $year);
				$final = $month . '/'. $day .'/'. $years.' ' .$hour. ':'.$minute.':00';
				if($overallTime == $final) {
					$arr[] =  $row['immunization_record_id'];
					$getPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['patient_id']."'")->result_array();
					$getParent = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$row['parent_id']."'")->result_array();
					
					$this->home_model->itexmo($getParent[0]['parent_phonenumber'], "REMINDER: ".$getPatient[0]['patient_name']." is expected to comeback today ".$row['date']." for ".$row['comeback_for']);
				}
			}
			$array['immunization'] = $arr;
		}
		echo json_encode($array);
	}
	public function generate_to_pdf($code = ""){

		$query = $this->db->query("SELECT * FROM `consultations`");

		$patient_name = "";
		$age = "";
		$sex = "";
		$address = "";
		$prescription = "";
		$date = "";
		$doctor_name = "";
		$ptr = "";
		$license_no = "";
		foreach($query->result_array() as $row) {
			$string = md5($row['date_consultation_datetime'] . ' ' . $row['date_consultation_datetime_end']);
			if($string == $code) {
				$count = 1;
				
				$patient_get = $this->db->query("SELECT * FROM `patients_tbl` WHERE patient_id = '".$row['consultation_patient_id']."'")->result_array();
				$parent_get = $this->db->query("SELECT * FROM `parents_tbl` WHERE parent_id = '".$row['consultation_parent_id']."'")->result_array();
				$birthDate = $patient_get[0]['patient_birthdate'];
				$from = new DateTime($birthDate);
				$to   = new DateTime('today');
				
				$prescription = $row['consultation_prescription'];

				$patient_name = $patient_get[0]['patient_name'];
				$age = $from->diff($to)->y;
				$sex = $patient_get[0]['patient_gender'];
				$address = $parent_get[0]['parent_address'];
				$date = $row['date_text'];

				$doctor_get = $this->db->query("SELECT * FROM `doctors_tbl` WHERE doctor_id = '".$row['interview_id']."'")->result_array();
				$doctor_name = $doctor_get[0]['doctor_name'];
				$ptr = $doctor_get[0]['ptr'];
				$license_no = $doctor_get[0]['license_no'];

				break;
			}
		}
		if($count != 1) {
			redirect("home?error=There\'s something wrong!");
		}

		// Get output html
        $html = $this->output->get_output();
        
        // Load pdf library
        $this->load->library('pdf');
        
		$html = '<!DOCTYPE html>
			<html lang="en">
			<head>
				<title>E-Prescription</title>
				<meta charset="utf-8">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
				<style>
				body {
					font-family: Arial;
				}

				.container {
					background-color: white;
					height: 450px;
				}

				.address {
					text-align: right;
					font-size: 10px;
				}

				.address2 {
					font-size: 8px;
				}

				.whealthlogoimg img {
					max-width: 200px;
					max-height: 200px;
				}

				.rximage img {
					max-width: 50px;
					max-height: 50px;
				}

				.rximage {
					margin-left: 30px;
					margin-top: 15px;
					margin-bottom: 15px;
				}

				hr {
					border: 2px solid black;
				}

				.patient-details {
					font-size: 10px;
					margin-left: 30px;
					margin-right: 30px;
				}

				.prescdetails {
					font-size: 10px;
					margin-left: 50px;
					margin-right: 30px;
					word-break: break-word;
					text-align: justify;
				}

				.doctor-details {
					font-size: 10px;
					text-align: right;
					align-self: flex-end;
					font-weight: bold;
					margin-right: 30px;
				}
				</style>
			</head>
			<body>
				<div class="container">
				<!--logo & address-->
				<div style="float:left">
					<div class="whealthlogoimg">
					<img src="data:image/png;base64,' . base64_encode(file_get_contents("https://peekabook.tech/assets/pdf/whealthlogo.jpg")).'" class="rounded float-start">
					</div>
				</div>
				<div style="float:right;">
					<br>
					<div class="address">
					<b>WHEALTH MEDICAL CLINIC AND DIAGNOSTIC CENTER <BR> (STA. MESA BRANCH) <br>
					</b>
					<div class="address2"> Unit A-004 1st Floor Building 23 GSIS Metrohomes Anonas Corner <br> Pureza St., Sta. Mesa, Natanghan, Manila <br> TEL NO: 0943-407-3591 <br> Email add: whealthmedicalclinic@yahoo.com </div>
					</div>
					<br>
					<div class="address">
					<b> WHEALTH MEDICAL CLINIC AND DIAGNOSTIC CENTER <BR> (MAGSAYSAY BRANCH) <br>
					</b>
					<div class="address2"> 2nd Floor, Area 1, 3086 Ramon Magsaysay Boulevard, Sta. Mesa, Manila <br> TEL NO: 0977-483-5513 <br> Email add: whealthmedicalclinic@yahoo.com </div>
					</div>
				</div>
				<div style="clear:both;"></div>
				<hr>
				<!--patient details-->
				<div class="patient-details">
					<div class="row">
					<div class="col-8">
						<b>Name: '.$patient_name.'</b>
					</div>
					<div class="col-4">
						<b>Age: '.$age.' Sex: '.$sex.'</b>
					</div>
					</div>
					<div class="row">
					<div class="col-8">
						<b>Address: '.$address.'</b>
					</div>
					<div class="col-4">
						<b>Date: '.$date.'</b>
					</div>
					</div>
				</div>
				<!-- prescription details-->
				<div class="rximage">
					<img src="data:image/png;base64,' . base64_encode(file_get_contents("https://peekabook.tech/assets/pdf/rx.jpg")).'">
				</div>
				<div class="prescdetails">'.$prescription.'</div>
				<!--doctor details-->
				<div class="doctor-details">
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<div class="row">
					<div class="col"> Requesting Physician: Dr. '.$doctor_name.' <br> PTR: '.$ptr.'<br> S2 License no: '.$license_no.'</div>
					</div>
				</div>
			</body>
			</html>';
        // Load HTML content
        $this->pdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation
        $this->pdf->setPaper('A5', 'portrait');
        
        // Render the HTML as PDF
        $this->pdf->render();
        
        // Output the generated PDF (1 = download and 0 = preview)
        $this->pdf->stream("whealth-consultation.pdf", array("Attachment"=>1));
	}
	public function generate_to_pdf2($code = ""){
		
		$query = $this->db->query("SELECT * FROM `medical_certificates`");

		$date_text = "";
		$patient_name = "";
		$age = ""; 
		$gender = "";
		$address = "";
		$diagnosis = "";
		$treatment_and_recommendation = "";

		$parent_name = "";
		$purpose_doctor = "";
		
		
		$doctor_name = "";
		$ptr = "";
		$license_no = "";

		foreach($query->result_array() as $row) {
			$string = md5($row['date_of_consultation'] . ' ' . $row['date_text'] . ' ' . $row['reference_number'] . ' ' . $row['timestamp']);
			if($string == $code) {
				$count = 1;
				
				$date_text = $row['date_text'];

				$patient_get = $this->db->query("SELECT * FROM `patients_tbl` WHERE patient_id = '".$row['patient_id']."'")->result_array();
				$parent_get = $this->db->query("SELECT * FROM `parents_tbl` WHERE parent_id = '".$row['parent_id']."'")->result_array();
				$birthDate = $patient_get[0]['patient_birthdate'];
				$from = new DateTime($birthDate);
				$to   = new DateTime('today');
				$parent_name = $parent_get[0]['parent_name'];
				$patient_name = $patient_get[0]['patient_name'];
				$age = $from->diff($to)->y;
				$gender = $patient_get[0]['patient_gender'];
				$address = $parent_get[0]['parent_address'];
				
				$diagnosis = $row['diagnosis'];
				$treatment_and_recommendation = $row['treatment_and_recommendation'];
				$purpose_doctor = $row['purpose_doctor'];
				

				$doctor_get = $this->db->query("SELECT * FROM `doctors_tbl` WHERE doctor_id = '".$row['interview_id']."'")->result_array();
				$doctor_name = $doctor_get[0]['doctor_name'];
				$ptr = $doctor_get[0]['ptr'];
				$license_no = $doctor_get[0]['license_no'];

				break;
			}
		}
		if($count != 1) {
			redirect("home?error=There\'s something wrong!");
		}

		// Get output html
        $html = $this->output->get_output();
        
        
        
		$html = '<!DOCTYPE html>
			<html lang="en">
			<head>
				<title>Medical Certificate</title>
				<meta charset="utf-8">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
				<style>
				body {
					font-family: Arial;
				}

				.container {
					background-color: white;
					height: 450px;
				}

				.address {
					text-align: right;
					font-size: 10px;
				}

				.address2 {
					font-size: 8px;
				}

				.whealthlogoimg img {
					max-width: 200px;
					max-height: 200px;
				}

				.rximage img {
					max-width: 50px;
					max-height: 50px;
				}

				.rximage {
					margin-left: 30px;
					margin-top: 15px;
					margin-bottom: 15px;
				}

				hr {
					border: 2px solid black;
				}

				.patient-details {
					font-size: 10px;
					margin-left: 30px;
					margin-right: 30px;
				}

				.prescdetails {
					font-size: 10px;
					margin-left: 50px;
					margin-right: 30px;
					word-break: break-word;
					text-align: justify;
				}

				.doctor-details {
					font-size: 10px;
					text-align: right;
					align-self: flex-end;
					font-weight: bold;
					margin-right: 30px;
				}
				</style>
			</head>
			<body>
				<div class="container">
				<!--logo & address-->
				<div style="float:left">
					<div class="whealthlogoimg">
					<img src="data:image/png;base64,' . base64_encode(file_get_contents("https://peekabook.tech/assets/pdf/whealthlogo.jpg")).'" class="rounded float-start">
					</div>
				</div>
				<div style="float:right;">
					<br>
					<div class="address">
					<b>WHEALTH MEDICAL CLINIC AND DIAGNOSTIC CENTER <BR> (STA. MESA BRANCH) <br>
					</b>
					<div class="address2"> Unit A-004 1st Floor Building 23 GSIS Metrohomes Anonas Corner <br> Pureza St., Sta. Mesa, Natanghan, Manila <br> TEL NO: 0943-407-3591 <br> Email add: whealthmedicalclinic@yahoo.com </div>
					</div>
					<br>
					<div class="address">
					<b> WHEALTH MEDICAL CLINIC AND DIAGNOSTIC CENTER <BR> (MAGSAYSAY BRANCH) <br>
					</b>
					<div class="address2"> 2nd Floor, Area 1, 3086 Ramon Magsaysay Boulevard, Sta. Mesa, Manila <br> TEL NO: 0977-483-5513 <br> Email add: whealthmedicalclinic@yahoo.com </div>
					</div>
				</div>
				<div style="clear:both;"></div>
				<hr>
				<!--patient details-->
				<center>
				<h2>
					<u>
						<i>Medical Certificate</i>
					</u>
				</h2>
				</center>
				<div style="font-size: 10px; margin-left: 50px; margin-right: 30px; word-break: break-word; text-align: justify;">
					<div class="date">
						<b>Date: <u>'.$date_text.'</u></b> <br><br>
					</div>
					This is a case of <b><u>'.$patient_name.', '.$age.'/'.$gender.'</u></b>
					currently residing at 
					<b><u>'.$address.'</u></b>.<br/>
					Patient consulted in our clinic in which the diagnosis was:
					<br>
					<br>
					<div style="margin-left: 30px;">
						<b>Diagnosis: </b><br>
						<b><u>'.$diagnosis.'</u></b><br><br>

						<b>Treatment/Recommendation: </b><br>
						<b><u>'.$treatment_and_recommendation.'</u></b><br><br>
                	</div>
					This Medical Certificate is issued upon the request of 
					<b><u>'.$parent_name.'</u></b>
					<br>
					For
					<b><u>'.$purpose_doctor.'</u></b>
					purposes only and not a legal document.
				</div>
				<div style="font-size: 10px; text-align: right; align-self: flex-end; font-weight: bold; margin-right: 30px;">
					<br><br><br><br><br>
					<div class="row">
						<div class="col">
							Attending Physician:'.$doctor_name.'<br>
							PTR:'.$ptr.'<br>
							S2 License no:'.$license_no.'
						</div>              
					</div>
				</div>
			</body>
			</html>';
        // Load HTML content
        $this->pdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation
        $this->pdf->setPaper('A5', 'portrait');
        
        // Render the HTML as PDF
        $this->pdf->render();
        
        // Output the generated PDF (1 = download and 0 = preview)
        $this->pdf->stream("whealth-medical-certificate.pdf", array("Attachment"=>1));
	}
	public function getPdf($code = ""){
		if($_SERVER["REQUEST_METHOD"] == "GET") {
			$client_service2 = "whealth-client";
    		$auth_key2 = "peekabookWhealthApi!@";

			$client_service = $this->input->get_request_header("Client-Service", TRUE);
			$auth_key = $this->input->get_request_header("Auth-Key", TRUE);
			if($client_service == $client_service2 && $auth_key == $auth_key2) {
				$query = $this->db->query("SELECT * FROM `consultations`");

				foreach($query->result_array() as $row) {
					$string = md5($row['date_consultation_datetime'] . ' ' . $row['date_consultation_datetime_end']);
					if($string == $code) {
						$count = 1;
						header('Content-type: application/pdf');
						header('Content-Disposition: inline; filename='.$string.'.pdf');
						@readfile("data:application/pdf;base64,".$row['send_it_to_parent']);
						break;
					} 
				}
				if($count != 1) {
					echo json_encode(array(
						'status' => 401,
						'message' => 'Unauthorized.'
					));
				}
			} else {
				echo json_encode(array(
					'status' => 401,
					'message' => 'Unauthorized.'
				));
			}
		} else {
			echo json_encode(array(
				'status' => 401,
				'message' => 'Unauthorized.'
			));
		}
	}
	public function getPdf2($code = ""){
		if($_SERVER["REQUEST_METHOD"] == "GET") {
			$client_service2 = "whealth-client";
    		$auth_key2 = "peekabookWhealthApi!@";

			$client_service = $this->input->get_request_header("Client-Service", TRUE);
			$auth_key = $this->input->get_request_header("Auth-Key", TRUE);
			if($client_service == $client_service2 && $auth_key == $auth_key2) {
				$query = $this->db->query("SELECT * FROM `medical_certificates`");

				foreach($query->result_array() as $row) {
					$string = md5($row['date_of_consultation'] . ' ' . $row['date_text'] . ' ' . $row['reference_number'] . ' ' . $row['timestamp']);
					if($string == $code) {
						$count = 1;
						header('Content-type: application/pdf');
						header('Content-Disposition: inline; filename='.$string.'.pdf');
						@readfile("data:application/pdf;base64,".$row['send_it_to_parent']);
						break;
					} 
				}
				if($count != 1) {
					echo json_encode(array(
						'status' => 401,
						'message' => 'Unauthorized.'
					));
				}
			} else {
				echo json_encode(array(
					'status' => 401,
					'message' => 'Unauthorized.'
				));
			}
		} else {
			echo json_encode(array(
				'status' => 401,
				'message' => 'Unauthorized.'
			));
		}
	}
	public function getPdf3($code = ""){
		if($_SERVER["REQUEST_METHOD"] == "GET") {
			$client_service2 = "whealth-client";
    		$auth_key2 = "peekabookWhealthApi!@";

			$client_service = $this->input->get_request_header("Client-Service", TRUE);
			$auth_key = $this->input->get_request_header("Auth-Key", TRUE);
			if($client_service == $client_service2 && $auth_key == $auth_key2) {
				$query = $this->db->query("SELECT * FROM `laboratory_results`");

				foreach($query->result_array() as $row) {
					$string = md5($row['parent_id'] . ' ' . $row['parent_name'] . ' ' . $row['patient_id'] . ' ' . $row['patient_name'] . ' ' . $row['date'] . ' ' . $row['timestamp']);
					if($string == $code) {
						$count = 1;
						header('Content-type: application/pdf');
						header('Content-Disposition: inline; filename='.$string.'.pdf');
						@readfile("data:application/pdf;base64,".$row['file']);
						break;
					} 
				}
				if($count != 1) {
					echo json_encode(array(
						'status' => 401,
						'message' => 'Unauthorized.'
					));
				}
			} else {
				echo json_encode(array(
					'status' => 401,
					'message' => 'Unauthorized.'
				));
			}
		} else {
			echo json_encode(array(
				'status' => 401,
				'message' => 'Unauthorized.'
			));
		}
	}
}
