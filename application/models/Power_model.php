<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Power_model extends CI_Model {
	public function itexmo($number, $message){
        $ch = curl_init();
        $itexmo = array('1' => $number, '2' => $message, '3' => 'ST-MICHA378601_D9QIG', 'passwd' => '4xbp9q!2sy');
        curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
        curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, 
                  http_build_query($itexmo));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec ($ch);
        curl_close ($ch);
    }

	//timeout
	public function autoTimeout() {
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
			if(($data['login_timeout'] <= $final)) {
				$this->db->where("doctor_id", $data['doctor_id']);
				$this->db->set("doctor_sms_code", "@@@@@@@@EXIT@@@@@@@@");
				$this->db->set("active", "0");
				$this->db->update("doctors_tbl");
			}
		}

		$query2 = $this->db->query("SELECT * FROM `admins_tbl`");
		foreach($query2->result_array() as $data2) {
			if(($data2['login_timeout'] <= $final)) {
				$this->db->where("admin_id", $data2['admin_id']);
				$this->db->set("admin_sms_code", "@@@@@@@@EXIT@@@@@@@@");
				$this->db->set("active", "0");
				$this->db->update("admins_tbl");
			}
		}

		$query3 = $this->db->query("SELECT * FROM `receptionists_tbl`");
		foreach($query3->result_array() as $data3) {
			if(($data3['login_timeout'] <= $final)) {
				$this->db->where("receptionist_id", $data3['receptionist_id']);
				$this->db->set("receptionist_sms_code", "@@@@@@@@EXIT@@@@@@@@");
				$this->db->set("active", "0");
				$this->db->update("receptionists_tbl");
			}
		}
	}
    public function timeout($id, $selection) {
		if($selection == "doctor") {
			$result = $this->power_model->getUserInfoDoctor($id);
			foreach($result as $data) {
				if((time() - $data['login_timeout']) > 10) {
					return 1;
				}
			}
		} else if($selection == "administrator") {
			$result = $this->power_model->getUserInfoAdmin($id);
			foreach($result as $data) {
				if((time() - $data['login_timeout']) > 10) {
					return 1;
				}
			}
		} else if($selection == "receptionist") {
			$result = $this->power_model->getUserInfoReceptionist($id);
			foreach($result as $data) {
				if((time() - $data['login_timeout']) > 10) {
					return 1;
				}
			}
		}
	}

	// stuffs
    public function submitOTP($id, $selection, $otp) {
		if($selection == "doctor") {
			$this->db->where('doctor_id', $id);
			$query = $this->db->get('doctors_tbl');
			if($query->num_rows() > 0) {
				// success
				foreach($query->result() as $row) { // row of this data
					if($row->doctor_sms_code == $otp) {
						$this->db->where('doctor_id', $row->doctor_id);
						$this->db->set('doctor_sms_code', "@@@@@@@@SUCCESS@@@@@@@@");
						$this->db->update('doctors_tbl');
						return 'successfully';
					} else {
						return 'otpNotSame';
					}
				}
			} else {
				// error
				return 'userNotExists';
			}
		} else if($selection == "receptionist") {
			$this->db->where('receptionist_id', $id);
			$query = $this->db->get('receptionists_tbl');
			if($query->num_rows() > 0) {
				// success
				foreach($query->result() as $row) { // row of this data
					if($row->receptionist_sms_code == $otp) {
						$this->db->where('receptionist_id', $row->receptionist_id);
						$this->db->set('receptionist_sms_code', "@@@@@@@@SUCCESS@@@@@@@@");
						$this->db->update('receptionists_tbl');
						return 'successfully';
					} else {
						return 'otpNotSame';
					}
				}
			} else {
				// error
				return 'userNotExists';
			}
		} else if($selection == "administrator") {
			$this->db->where('admin_id', $id);
			$query = $this->db->get('admins_tbl');
			if($query->num_rows() > 0) {
				// success
				foreach($query->result() as $row) { // row of this data
					if($row->admin_sms_code == $otp) {
						$this->db->where('admin_id', $row->admin_id);
						$this->db->set('admin_sms_code', "@@@@@@@@SUCCESS@@@@@@@@");
						$this->db->update('admins_tbl');
						return 'successfully';
					} else {
						return 'otpNotSame';
					}
				}
			} else {
				// error
				return 'userNotExists';
			}
		} else {
			return 'userNotExists';
		}
    }
    public function modelLogout($id, $selection) {
		if($selection == "doctor") {
			$this->db->where('doctor_id', $id);
			$this->db->set('doctor_sms_code', "@@@@@@@@EXIT@@@@@@@@");
			$this->db->set('active', "0");
			$this->db->update('doctors_tbl');
		} else if($selection == "receptionist") {
			$this->db->where('receptionist_id', $id);
			$this->db->set('receptionist_sms_code', "@@@@@@@@EXIT@@@@@@@@");
			$this->db->set('active', "0");
			$this->db->update('receptionists_tbl');
		} else if($selection == "administrator") {
			$this->db->where('admin_id', $id);
			$this->db->set('admin_sms_code', "@@@@@@@@EXIT@@@@@@@@");
			$this->db->set('active', "0");
			$this->db->update('admins_tbl');
		}
    }
	public function getId($id, $selection) {
		if($selection == "doctor") {
			$q = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$id."'"); 
			return $q->result();
		} else if($selection == "receptionist") {
			$q = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_id` = '".$id."'"); 
			return $q->result();
		} else if($selection == "administrator") {
			$q = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_id` = '".$id."'"); 
			return $q->result();
		}
	}
	
	// password
	public function getPassword($id, $selection) {
		if($selection == "doctor") {
			$q = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$id."'"); 
			return $q->result();
		} else if($selection == "receptionist") {
			$q = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_id` = '".$id."'"); 
			return $q->result();
		} else if($selection == "administrator") {
			$q = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_id` = '".$id."'"); 
			return $q->result();
		}
	}

	public function change_password($id, $selection, $oldpassword, $newpassword, $retypenewpassword) {
		if($selection == "doctor") {
			$this->db->where('doctor_id', $id);
			$query = $this->db->get('doctors_tbl');

			if($query->num_rows() > 0) {
				// success
				foreach($query->result() as $row) { // row of this data
					if($row->doctor_password == $oldpassword) {
						if($newpassword == $retypenewpassword) {
							$this->db->where('doctor_id', $id);
							$this->db->set('doctor_password', $newpassword);
							$this->db->update('doctors_tbl');
							return '';
						} else {
							return 'crNotSame';
						}
					} else {
						return 'passNotSame';
					}
				}
			} else {
				// error
				return 'userNotExists';
			}
		} else if($selection == "receptionist") {
			$this->db->where('receptionist_id', $id);
			$query = $this->db->get('receptionists_tbl');

			if($query->num_rows() > 0) {
				// success
				foreach($query->result() as $row) { // row of this data
					if($row->receptionist_password == $oldpassword) {
						if($newpassword == $retypenewpassword) {
							$this->db->where('receptionist_id', $id);
							$this->db->set('receptionist_password', $newpassword);
							$this->db->update('receptionists_tbl');
							return '';
						} else {
							return 'crNotSame';
						}
					} else {
						return 'passNotSame';
					}
				}
			} else {
				// error
				return 'userNotExists';
			}
		} else if($selection == "administrator") {
			$this->db->where('admin_id', $id);
			$query = $this->db->get('admins_tbl');

			if($query->num_rows() > 0) {
				// success
				foreach($query->result() as $row) { // row of this data
					if($row->admin_password == $oldpassword) {
						if($newpassword == $retypenewpassword) {
							$this->db->where('admin_id', $id);
							$this->db->set('admin_password', $newpassword);
							$this->db->update('admins_tbl');
							return '';
						} else {
							return 'crNotSame';
						}
					} else {
						return 'passNotSame';
					}
				}
			} else {
				// error
				return 'userNotExists';
			}
		} else {
			return 'userNotExists';
		}
	}

	// inquiries
	public function getInquiries() {
		$q = $this->db->query("SELECT * FROM `inquiries_tbl`"); 
		//
		// OR
		// $this->db->select('Inquiries_ID,Inquiries_title, Inquiries_body, Inquiries_Name, Inquiries_EmailAddress, Inquiries_PhoneNumber, Inquiries_DateTime');
		// $this->db->from("Inquiries_tbl"); 
		// $this->order_by('Inquiries_ID', DESC);
		// $q = $this->db->get(); 
		// this sql query will be "SELECT Inquiries_ID,Inquiries_title, Inquiries_body, Inquiries_Name, Inquiries_EmailAddress, Inquiries_PhoneNumber, Inquiries_DateTime FROM `Inquiries_tbl` ORDER BY `Inquiries_ID` DESC"
		//
		// OR
		// $this->db->select('Inquiries_ID,Inquiries_title, Inquiries_body, Inquiries_Name, Inquiries_EmailAddress, Inquiries_PhoneNumber, Inquiries_DateTime');
		// $q = $this->db->get("Inquiries_tbl");
		// this sql query will be "SELECT Inquiries_ID,Inquiries_title, Inquiries_body, Inquiries_Name, Inquiries_EmailAddress, Inquiries_PhoneNumber, Inquiries_DateTime FROM Inquiries_tbl"


		// you must add a code in application/config/autoload.php and also the code will be: autoload['libraries'] = array('database');
		// you must setup your database in application/config/database.php
		return $q->result();
	}
	public function getInquiriesById($param) {
	    $q = $this->db->query("SELECT * FROM `inquiries_tbl` WHERE Inquiries_ID = '".$param."'"); 
		//$q = $this->db->query("SELECT * FROM `inquiries_tbl` WHERE Inquiries_ID = '".$param."'"); 
		// for controllers:
		// $id = $this->uri->segment(3);
		// $this->home_model->getInquiriesById('Inquiries_tbl','Inquiries_ID', $id);
		return $q->result();
	}
	public function deleteInquiries($id) {
		$this->db->where('inquiries_id', $id);
		$this->db->delete('inquiries_tbl');
	}
	
	// chats
	public function send($array) {
		$this->db->insert('chats_history_tbl', $array);
	}
	
	/*
		public function getChats($id, $code) {

			$q = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_history_id` > '".$id."' AND `chats_info_code` = '".$code."' ORDER BY `chats_history_id` DESC LIMIT 10");		
			$arr = array();
			// success
			foreach($q->result_array() as $row) { 
				//1-doctor
				//2-admin
				//3-receptionist
				//4-parent 
				$getInfo = "";
				if($row['chats_account_type'] == 1) {
					$getts = $this->power_model->getUserInfoDoctor($row['chats_id']);
					foreach($getts as $getData) {
						$getInfo = array(
							'full_name' => $getData['doctor_name'],
							'active' => $getData['active'],
							'profile_picture' => $getData['profile_picture']
						);
					} 
				} else if($row['chats_account_type'] == 2) {
					$getts = $this->power_model->getUserInfoAdmin($row['chats_id']);
					foreach($getts as $getData) {
						$getInfo = array(
							'full_name' => $getData['admin_name'],
							'active' => $getData['active'],
							'profile_picture' => $getData['profile_picture']
						);
					} 
				} else if($row['chats_account_type'] == 3) {
					$getts = $this->power_model->getUserInfoReceptionist($row['chats_id']);
					foreach($getts as $getData) {
						$getInfo = array(
							'full_name' => $getData['receptionist_name'],
							'active' => $getData['active'],
							'profile_picture' => $getData['profile_picture']
						);
					} 
				} else if($row['chats_account_type'] == 4) {
					$getts = $this->power_model->getUserInfoParent($row['chats_id']);
					foreach($getts as $getData) {
						$getInfo = array(
							'full_name' => $getData['parent_name'],
							'active' => $getData['active'],
							'profile_picture' => $getData['profile_picture']
						);
					} 
				}
				
				$text = "";
				if($row['chats_account_type'] == 1) { $text = "Doctor"; } 
				else if($row['chats_account_type'] == 2) { $text = "Administrator"; }
				else if($row['chats_account_type'] == 3) { $text = "Receptionist"; }
				else if($row['chats_account_type'] == 4) { $text = "Parent"; }

				$newRow = array(
					'chats_history_id' => (int) $row['chats_history_id'],

					'full_name' => $getInfo['full_name'],
					'active' => $getInfo['active'],
					'profile_picture' => $getInfo['profile_picture'],
					
					'chats_account_type_text' => $text,
					'chats_id' => (int) $row['chats_id'],

					'chats_message' => $row['chats_message'],
					'time' => date('m-d-Y h:i:sA', $row['time']),
					'timestamp' => $row['time']
				);
				$arr[] = $newRow;
			}
			
			$newArr = array("chats" => $arr);
			return json_encode($newArr);
		}
		public function getPreviouslyChats($id, $myid, $selection, $user_id2, $selection2) {
			$minusId = $id-9;
			$q = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE (`chats_history_id` >= '".$minusId."' AND `chats_history_id` <= '".$id."') AND (`chats_account_type` = '".$selection."', `chats_id` = '".$myid."', `chats_to_account_type` = '".$selection2."', `chats_to` = '".$user_id2."') ORDER BY `chats_history_id` DESC LIMIT 10");	
			$arr = array(); 
			// success
			foreach($q->result_array() as $row) { 
				//1-doctor
				//2-admin
				//3-receptionist
				//4-parent
				$getInfo = "";
				if($row['chats_account_type'] == 1) {
					$getts = $this->power_model->getUserInfoDoctor($row['chats_id']);
					foreach($getts as $getData) {
						$getInfo = array(
							'full_name' => $getData['doctor_name'],
							'active' => $getData['active'],
							'profile_picture' => $getData['profile_picture']
						);
					} 
				} else if($row['chats_account_type'] == 2) {
					$getts = $this->power_model->getUserInfoAdmin($row['chats_id']);
					foreach($getts as $getData) {
						$getInfo = array(
							'full_name' => $getData['admin_name'],
							'active' => $getData['active'],
							'profile_picture' => $getData['profile_picture']
						);
					} 
				} else if($row['chats_account_type'] == 3) {
					$getts = $this->power_model->getUserInfoReceptionist($row['chats_id']);
					foreach($getts as $getData) {
						$getInfo = array(
							'full_name' => $getData['receptionist_name'],
							'active' => $getData['active'],
							'profile_picture' => $getData['profile_picture']
						);
					} 
				} else if($row['chats_account_type'] == 4) {
					$getts = $this->power_model->getUserInfoParent($row['chats_id']);
					foreach($getts as $getData) {
						$getInfo = array(
							'full_name' => $getData['parent_name'],
							'active' => $getData['active'],
							'profile_picture' => $getData['profile_picture']
						);
					} 
				}
				$text = "";
				if($row['chats_account_type'] == 1) { $text = "Doctor"; } 
				else if($row['chats_account_type'] == 2) { $text = "Administrator"; }
				else if($row['chats_account_type'] == 3) { $text = "Receptionist"; }
				else if($row['chats_account_type'] == 4) { $text = "DoParentctor"; }
				
				$newRow = array(
					'chats_history_id' => (int) $row['chats_history_id'],
					'chats_account_type_text' => $text,
					'full_name' => $getInfo['full_name'],
					'active' => $getInfo['active'],
					'profile_picture' => $getInfo['profile_picture'],
					'chats_id' => (int) $row['chats_id'],
					'chats_to' => (int) $row['chats_to'],
					'chats_message' => $row['chats_message'],
					'time' => date('m-d-Y h:i:sA', $row['time']),
					'timestamp' => $row['time']
				);
				$arr[] = $newRow;
			}
			
			$newArr = array("chats" => $arr);
			return json_encode($newArr);
		}
	*/
	public function getChats($code, $id) {
		$q = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$code."' AND `chats_next_id` > '".$id."' ORDER BY `chats_history_id` DESC LIMIT 10");		
		$arr = array();
		// success
		foreach($q->result_array() as $row) { 
			//1-doctor
			//2-admin
			//3-receptionist
			//4-parent 
			$getInfo = "";
			if($row['chats_account_type'] == 1) {
				$getts = $this->power_model->getUserInfoDoctor($row['chats_id']);
				foreach($getts as $getData) {
					$getInfo = array(
						'full_name' => $getData['doctor_name'],
						'active' => $getData['active'],
						'profile_picture' => base64_encode($getData['profile_picture'])
					);
				} 
			} else if($row['chats_account_type'] == 2) {
				$getts = $this->power_model->getUserInfoAdmin($row['chats_id']);
				foreach($getts as $getData) {
					$getInfo = array(
						'full_name' => $getData['admin_name'],
						'active' => $getData['active'],
						'profile_picture' => base64_encode($getData['profile_picture'])
					);
				} 
			} else if($row['chats_account_type'] == 3) {
				$getts = $this->power_model->getUserInfoReceptionist($row['chats_id']);
				foreach($getts as $getData) {
					$getInfo = array(
						'full_name' => $getData['receptionist_name'],
						'active' => $getData['active'],
						'profile_picture' => base64_encode($getData['profile_picture'])
					);
				} 
			} else if($row['chats_account_type'] == 4) {
				$getts = $this->power_model->getUserInfoParent($row['chats_id']);
				foreach($getts as $getData) {
					$getInfo = array(
						'full_name' => $getData['parent_name'],
						'active' => $getData['active'],
						'profile_picture' => base64_encode($getData['profile_picture'])
					);
				} 
			}
			
			$text = "";
			if($row['chats_account_type'] == 1) { $text = "Doctor"; } 
			else if($row['chats_account_type'] == 2) { $text = "Administrator"; }
			else if($row['chats_account_type'] == 3) { $text = "Receptionist"; }
			else if($row['chats_account_type'] == 4) { $text = "Parent"; }

			$newRow = array(
				'chats_next_id' => $row['chats_next_id'],
				'chats_history_id' => $row['chats_history_id'],
				'chats_info_code' => $row['chats_info_code'],

				'full_name' => $getInfo['full_name'],
				'active' => $getInfo['active'],
				'profile_picture' => $getInfo['profile_picture'],
				
				'chats_account_type_text' => htmlentities($text, ENT_QUOTES, 'UTF-8'),
				'chats_id' => $row['chats_id'],

				'chats_message' => $row['chats_message'],
				'time' => date('m-d-Y h:i:sA', $row['time']),
				'timestamp' => $row['time']
			);
			$arr[] = $newRow;
		}
		
		$newArr = array("chats" => $arr);
		return json_encode($newArr);
	}
	public function getPreviouslyChats($code, $id) {
		$minusId = $id - 10;
		
		$q = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE (`chats_next_id` >= '".$minusId."' AND `chats_next_id` <= '".$id."') AND `chats_info_code` = '".$code."' ORDER BY `chats_history_id` DESC LIMIT 10");
		$arr = array();
		// success
		foreach($q->result_array() as $row) { 
			//1-doctor
			//2-admin
			//3-receptionist
			//4-parent 
			$getInfo = "";
			if($row['chats_account_type'] == 1) {
				$getts = $this->power_model->getUserInfoDoctor($row['chats_id']);
				foreach($getts as $getData) {
					$getInfo = array(
						'full_name' => $getData['doctor_name'],
						'active' => $getData['active'],
						'profile_picture' => $getData['profile_picture']
					);
				} 
			} else if($row['chats_account_type'] == 2) {
				$getts = $this->power_model->getUserInfoAdmin($row['chats_id']);
				foreach($getts as $getData) {
					$getInfo = array(
						'full_name' => $getData['admin_name'],
						'active' => $getData['active'],
						'profile_picture' => $getData['profile_picture']
					);
				} 
			} else if($row['chats_account_type'] == 3) {
				$getts = $this->power_model->getUserInfoReceptionist($row['chats_id']);
				foreach($getts as $getData) {
					$getInfo = array(
						'full_name' => $getData['receptionist_name'],
						'active' => $getData['active'],
						'profile_picture' => $getData['profile_picture']
					);
				} 
			} else if($row['chats_account_type'] == 4) {
				$getts = $this->power_model->getUserInfoParent($row['chats_id']);
				foreach($getts as $getData) {
					$getInfo = array(
						'full_name' => $getData['parent_name'],
						'active' => $getData['active'],
						'profile_picture' => $getData['profile_picture']
					);
				} 
			}
			
			$text = "";
			if($row['chats_account_type'] == 1) { $text = "Doctor"; } 
			else if($row['chats_account_type'] == 2) { $text = "Administrator"; }
			else if($row['chats_account_type'] == 3) { $text = "Receptionist"; }
			else if($row['chats_account_type'] == 4) { $text = "Parent"; }

			$newRow = array(
				'chats_next_id' => $row['chats_next_id'],
				'chats_history_id' => (int) $row['chats_history_id'],
				'chats_info_code' => (int) $row['chats_info_code'],

				'full_name' => $getInfo['full_name'],
				'active' => $getInfo['active'],
				'profile_picture' => $getInfo['profile_picture'],
				
				'chats_account_type_text' => htmlentities($text, ENT_QUOTES, 'UTF-8'),
				'chats_id' => (int) $row['chats_id'],

				'chats_message' => $row['chats_message'],
				'time' => date('m-d-Y h:i:sA', $row['time']),
				'timestamp' => $row['time']
			);
			$arr[] = $newRow;
		}
		
		$newArr = array("chats" => $arr);
		return json_encode($newArr);
	}
	public function getLastChatId($code) {
		$q = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$code."' ORDER BY `chats_history_id` DESC LIMIT 1"); 
		if($q->num_rows() > 0) {
			// success
			foreach($q->result() as $row) { // row of this data
				return $row->chats_history_id;
			}
		}
	}
	public function checkChatCode($id, $selection, $code) {
		//1-doctor
		//2-admin
		//3-receptionist
		//4-parent
		$q = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `code` = '".$code."'"); 
		if($q->num_rows() > 0) {
			return '0';
		} else {
			return '1';
		}
		
	}
	public function getNameandActives($code, $id) {
		$q = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `code` = '".$code."'"); 
		if($q->num_rows() > 0) {
			// success
			$id_to = "";
			$selection_to = "";
			foreach($q->result() as $row) { // row of this data
				$id_to = $row->id_to;
				$selection_to = $row->selection_to;
				if($selection_to == 1) {
					$result = $this->power_model->getUserInfoDoctor($id_to);
					$array = "";
					foreach($result as $getData) {
						$array = array(
							'full_name' => $getData['doctor_name'],
							'active' => ($getData['active'] == 0) ? "Offline" : "Online"
						);
					}
					return json_encode($array);
				} else if($selection_to == 2) {
					$result = $this->power_model->getUserInfoAdmin($id_to);
					$array = "";
					foreach($result as $getData) {
						$array = array(
							'full_name' => $getData['admin_name'],
							'active' => ($getData['active'] == 0) ? "Offline" : "Online"
						);
					}
					return json_encode($array);
				} else if($selection_to == 3) {
					$result = $this->power_model->getUserInfoReceptionist($id_to);
					$array = "";
					foreach($result as $getData) {
						$array = array(
							'full_name' => $getData['receptionist_name'],
							'active' => ($getData['active'] == 0) ? "Offline" : "Online"
						);
					}
					return json_encode($array);
				} else if($selection_to == 4) {
					$result = $this->power_model->getUserInfoParent($id_to);
					$array = "";
					foreach($result as $getData) {
						$array = array(
							'full_name' => $getData['parent_name'],
							'active' => ($getData['active'] == 0) ? "Offline" : "Online"
						);
					}
					return json_encode($array);
				}
			}
		}
	}
	public function check($id, $selection, $arrays) {
		$status = '';
		for($i = 0; $i < count($arrays); $i++) {
			if($arrays[$i]['id'] == $id && $arrays[$i]['selection'] == $selection) {
				$status = 'exist';
				break;
			}
		}
		return $status;
	}
	public function addChat($array, $array2) {
		$this->db->insert('chats_info_tbl', $array);
		$this->db->insert('chats_info_tbl', $array2);
	}
	public function checkMsgs2($id, $selection, $id_to, $selection_to) {
		$q = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$id_to."' AND `selection_to` = '".$selection_to."'");

		if($q->num_rows() > 0) {
			// exist
			return 1;
		} else {
			// not exist
			return 0;
		}
	}
	public function checkMsgs($id, $selection, $id_to, $selection_to) {
		$q = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$id_to."' AND `selection_to` = '".$selection_to."'");

		if($q->num_rows() > 0) {
			// exist
			foreach($q->result_array() as $data) {

				$q2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$data['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
				if($q2->num_rows() > 0) {
					// exist
					$resultsz = array();
					foreach($q2->result_array() as $data2) {
						if($id == $data2['chats_id'] && $selection == $data2['chats_account_type']) {
							// success
							// it means, you're the last chatter
							$profile_picture = "";
							if($data2['chats_account_type'] == 1) {
								$profile_profile_query = $this->db->query("SELECT * FROM `doctors_tbl` WHERE doctor_id = '".$data2['chats_id']."'")->result_array();
								$profile_picture = base64_encode($profile_profile_query[0]['profile_picture']);
							} else if($data2['chats_account_type'] == 2) {
								$profile_profile_query = $this->db->query("SELECT * FROM `admins_tbl` WHERE admin_id = '".$data2['chats_id']."'")->result_array();
								$profile_picture = base64_encode($profile_profile_query[0]['profile_picture']);
							} else if($data2['chats_account_type'] == 3) {
								$profile_profile_query = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE receptionist_id = '".$data2['chats_id']."'")->result_array();
								$profile_picture = base64_encode($profile_profile_query[0]['profile_picture']);
							} else if($data2['chats_account_type'] == 4) {
								$profile_profile_query = $this->db->query("SELECT * FROM `parents_tbl` WHERE parent_id = '".$data2['chats_id']."'")->result_array();
								$profile_picture = base64_encode($profile_profile_query[0]['profile_picture']);
							}
							$resultsz = array(
								'chats_message' => $data2['chats_message'],
								'chats_account_type' => $data2['chats_account_type'],
								'chats_id' => $data2['chats_id'],
								'last_chat' => 'You',
								'time' => $data2['time'],
								'code' => $data2['chats_info_code'],
								'profile_picture' => $profile_picture
							);
						} else {
							// success
							// it means, the one you talked to, he's the last chatter
							// success
							// it means, you're the last chatter
							$name = "";
							if($data2['chats_account_type'] == 1) {
								$result = $this->power_model->getUserInfo($data2['chats_id'], 1);
								$results = (array) $result;
								$name = $results[0]['doctor_name'];
							} else if($data2['chats_account_type'] == 2) {
								$result = $this->power_model->getUserInfo($data2['chats_id'], 2);
								$results = (array) $result;
								$name = $results[0]['admin_name'];
							} else if($data2['chats_account_type'] == 3) {
								$result = $this->power_model->getUserInfo($data2['chats_id'], 3);
								$results = (array) $result;
								$name = $results[0]['receptionist_name'];
							} else if($data2['chats_account_type'] == 4) {
								$result = $this->power_model->getUserInfo($data2['chats_id'], 4);
								$results = (array) $result;
								$name = $results[0]['parent_name'];
							}
							$resultsz = array(
								'chats_message' => $data2['chats_message'],
								'chats_account_type' => $data2['chats_account_type'],
								'chats_id' => $data2['chats_id'],
								'last_chat' => $name,
								'time' => $data2['time'],
								'code' => $data2['chats_info_code']
							);
						}
					}
					return $resultsz;
				} else {
					$resultsz = array();
					// not exist
					$profile_picture = "";
					if($data['chats_selection'] == 1) {
						$profile_profile_query = $this->db->query("SELECT * FROM `doctors_tbl` WHERE doctor_id = '".$data['chats_id']."'")->result_array();
						$profile_picture = base64_encode($profile_profile_query[0]['profile_picture']);
					} else if($data['chats_selection'] == 2) {
						$profile_profile_query = $this->db->query("SELECT * FROM `admins_tbl` WHERE admin_id = '".$data['chats_id']."'")->result_array();
						$profile_picture = base64_encode($profile_profile_query[0]['profile_picture']);
					} else if($data['chats_selection'] == 3) {
						$profile_profile_query = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE receptionist_id = '".$data['chats_id']."'")->result_array();
						$profile_picture = base64_encode($profile_profile_query[0]['profile_picture']);
					} else if($data['chats_selection'] == 4) {
						$profile_profile_query = $this->db->query("SELECT * FROM `parents_tbl` WHERE parent_id = '".$data['chats_id']."'")->result_array();
						$profile_picture = base64_encode($profile_profile_query[0]['profile_picture']);
					}

					$resultsz = array(
						'chats_message' => '',
						'chats_account_type' => $data['chats_selection'],
						'chats_id' => $data['chats_id'],
						'last_chat' => '',
						'time' => '',
						'code' => $data['code'],
						'profile_picture' => $profile_picture
					);
					return $resultsz;
				}
			}
		} else {
			// not exist
			$resultsz = array(
				'chats_message' => '',
				'chats_account_type' => '',
				'chats_id' => '',
				'last_chat' => '',
				'time' => '',
				'code' => ''
			);
			return $resultsz;
		}
	}
	public function displayUsers($txt = "", $id = "", $selection = "") {
		$array = array();
			
		if($txt == "") {
			$q = $this->db->query("SELECT * FROM `doctors_tbl`");
			foreach($q->result_array() as $row1) {
                if($row1['doctor_id'] == $id && 1 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row1['doctor_id']."' AND `selection_to` = '1'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();
							$out = strlen($row1['doctor_name']) > 15 ? substr($row1['doctor_name'],0,15)."..." : $row1['doctor_name'];

                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row1['doctor_id'],
                                'selection' => '1',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row1['profile_picture'])
                            );
                        } else {
							$out = strlen($row1['doctor_name']) > 15 ? substr($row1['doctor_name'],0,15)."..." : $row1['doctor_name'];

                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row1['doctor_id'],
                                'selection' => '1',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row1['profile_picture'])
                            );
                        }
                    } else {
						$out = strlen($row1['doctor_name']) > 15 ? substr($row1['doctor_name'],0,15)."..." : $row1['doctor_name'];

                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row1['doctor_id'],
                            'selection' => '1',
                            'name' => $out,
                            'code' => "",
                            'profile_picture' => base64_encode($row1['profile_picture'])
                        );
                    }
                }
			}

			$q2 = $this->db->query("SELECT * FROM `admins_tbl`");
			foreach($q2->result_array() as $row2) {
                if($row2['admin_id'] == $id && 2 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row2['admin_id']."' AND `selection_to` = '2'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();
							$out = strlen($row2['admin_name']) > 15 ? substr($row2['admin_name'],0,15)."..." : $row2['admin_name'];
                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row2['admin_id'],
                                'selection' => '2',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row2['profile_picture'])
                            );
                        } else {
							$out = strlen($row2['admin_name']) > 15 ? substr($row2['admin_name'],0,15)."..." : $row2['admin_name'];
                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row2['admin_id'],
                                'selection' => '2',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row2['profile_picture'])
                            );
                        }
                    } else {
						$out = strlen($row2['admin_name']) > 15 ? substr($row2['admin_name'],0,15)."..." : $row2['admin_name'];
                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row2['admin_id'],
                            'selection' => '2',
                            'name' => $out,
                            'code' => "",
                            'profile_picture' => base64_encode($row2['profile_picture'])
                        );
                    }
                }
			}

			$q3 = $this->db->query("SELECT * FROM `receptionists_tbl`");
			foreach($q3->result_array() as $row3) {
                if($row3['receptionist_id'] == $id && 3 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row3['receptionist_id']."' AND `selection_to` = '3'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();
							$out = strlen($row3['receptionist_name']) > 15 ? substr($row3['receptionist_name'],0,15)."..." : $row3['receptionist_name'];
                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row3['receptionist_id'],
                                'selection' => '3',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row3['profile_picture'])
                            );
                        } else {
							$out = strlen($row3['receptionist_name']) > 15 ? substr($row3['receptionist_name'],0,15)."..." : $row3['receptionist_name'];
                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row3['receptionist_id'],
                                'selection' => '3',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row3['profile_picture'])
                            );
                        }
                    } else {
						$out = strlen($row3['receptionist_name']) > 15 ? substr($row3['receptionist_name'],0,15)."..." : $row3['receptionist_name'];
                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row3['receptionist_id'],
                            'selection' => '3',
                            'name' => $out,
                            'code' => "",
                            'profile_picture' => base64_encode($row3['profile_picture'])
                        );
                    }
                }
			}

			$q4 = $this->db->query("SELECT * FROM `parents_tbl`");
			foreach($q4->result_array() as $row4) {
                if($row4['parent_id'] == $id && 4 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row4['parent_id']."' AND `selection_to` = '4'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
						
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();
							$out = strlen($row4['parent_name']) > 15 ? substr($row4['parent_name'],0,15)."..." : $row4['parent_name'];
                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row4['parent_id'],
                                'selection' => '4',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row4['profile_picture'])
                            );
                        } else {
							$out = strlen($row4['parent_name']) > 15 ? substr($row4['parent_name'],0,15)."..." : $row4['parent_name'];
                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row4['parent_id'],
                                'selection' => '4',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row4['profile_picture'])
                            );
                        }
                    } else {
						$out = strlen($row4['parent_name']) > 15 ? substr($row4['parent_name'],0,15)."..." : $row4['parent_name'];
                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row4['parent_id'],
                            'selection' => '4',
                            'name' => $out,
                            'code' => "",
                            'profile_picture' => base64_encode($row4['profile_picture'])
                        );
                    }
                }
			}
		} else {
			
			$q1 = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_name` LIKE '".$txt."%'");
			foreach($q1->result_array() as $row1) {
                if($row1['doctor_id'] == $id && 1 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row1['doctor_id']."' AND `selection_to` = '1'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();
							$out = strlen($row1['doctor_name']) > 15 ? substr($row1['doctor_name'],0,15)."..." : $row1['doctor_name'];
                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row1['doctor_id'],
                                'selection' => '1',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row1['profile_picture'])
                            );
                        } else {
							$out = strlen($row1['doctor_name']) > 15 ? substr($row1['doctor_name'],0,15)."..." : $row1['doctor_name'];
                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row1['doctor_id'],
                                'selection' => '1',
                                'name' => $out,
                                'last_chat' => "",
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row1['profile_picture'])
                            );
                        }
                    } else {
						$out = strlen($row1['doctor_name']) > 15 ? substr($row1['doctor_name'],0,15)."..." : $row1['doctor_name'];
                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row1['doctor_id'],
                            'selection' => '1',
                            'name' => $out,
                            'code' => "",
                            'profile_picture' => base64_encode($row1['profile_picture'])
                        );
                    }
                }
			}

			$q2 = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_name` LIKE '".$txt."%'");
			foreach($q2->result_array() as $row2) {
                if($row2['admin_id'] == $id && 2 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row2['admin_id']."' AND `selection_to` = '2'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();

							$out = strlen($row2['admin_name']) > 15 ? substr($row2['admin_name'],0,15)."..." : $row2['admin_name'];

                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row2['admin_id'],
                                'selection' => '2',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row2['profile_picture'])
                            );
                        } else {
							$out = strlen($row2['admin_name']) > 15 ? substr($row2['admin_name'],0,15)."..." : $row2['admin_name'];
                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row2['admin_id'],
                                'selection' => '2',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row2['profile_picture'])
                            );
                        }
                    } else {
						$out = strlen($row2['admin_name']) > 15 ? substr($row2['admin_name'],0,15)."..." : $row2['admin_name'];
                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row2['admin_id'],
                            'selection' => '2',
                            'name' => $out,
                            'code' => "",
                            'profile_picture' => base64_encode($row2['profile_picture'])
                        );
                    }
                }
			}

			$q3 = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_name` LIKE '".$txt."%'");
			foreach($q3->result_array() as $row3) {
                if($row3['receptionist_id'] == $id && 3 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row3['receptionist_id']."' AND `selection_to` = '3'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();

							$out = strlen($row3['receptionist_name']) > 15 ? substr($row3['receptionist_name'],0,15)."..." : $row3['receptionist_name'];

                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row3['receptionist_id'],
                                'selection' => '3',
                                'name' => $out,
                                
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row3['profile_picture'])
                            );
                        } else {
							$out = strlen($row3['receptionist_name']) > 15 ? substr($row3['receptionist_name'],0,15)."..." : $row3['receptionist_name'];
                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row3['receptionist_id'],
                                'selection' => '3',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row3['profile_picture'])
                            );
                        }
                    } else {
						$out = strlen($row3['receptionist_name']) > 15 ? substr($row3['receptionist_name'],0,15)."..." : $row3['receptionist_name'];
                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row3['receptionist_id'],
                            'selection' => '3',
                            'name' => $out,
                            'code' => "",
                            'profile_picture' => base64_encode($row3['profile_picture'])
                        );
                    }
                }
			}

			$q4 = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_name` LIKE '".$txt."%'");
			foreach($q4->result_array() as $row4) {
                if($row4['receptionist_id'] == $id && 4 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row4['parent_id']."' AND `selection_to` = '4'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();
							$out = strlen($row4['parent_name']) > 15 ? substr($row4['parent_name'],0,15)."..." : $row4['parent_name'];
                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row4['parent_id'],
                                'selection' => '4',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row4['profile_picture'])
                            );
                        } else {
							$out = strlen($row4['parent_name']) > 15 ? substr($row4['parent_name'],0,15)."..." : $row4['parent_name'];
                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row4['parent_id'],
                                'selection' => '4',
                                'name' => $out,
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row4['profile_picture'])
                            );
                        }
                    } else {
						$out = strlen($row4['parent_name']) > 15 ? substr($row4['parent_name'],0,15)."..." : $row4['parent_name'];
                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row4['parent_id'],
                            'selection' => '4',
                            'name' => $out,
                            'last_chat' => "",
                            'code' => "",
                            'profile_picture' => base64_encode($row4['profile_picture'])
                        );
                    }
                }
			}
		}

		return $array;
	}

	// check id
	public function getUserInfoDoctor($id) {
		$q = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$id."'"); 
		return $q->result_array();
	}
	public function getUserInfoAdmin($id) {
		$q = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_id` = '".$id."'"); 
		return $q->result_array();
	}
	public function getUserInfoReceptionist($id) {
		$q = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_id` = '".$id."'"); 
		return $q->result_array();
	}
	public function getUserInfoParent($id) {
		$q = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$id."'"); 
		return $q->result_array();
	}
	public function getUserInfoPatient($id) {
		$q = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$id."'"); 
		return $q->result_array();
	}
	public function getUserInfoParentName($id) {
		$q = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$id."'"); 
		foreach($q->result() as $row) { 
			return $row->parent_name;
		}
	}
	public function getUserInfoPatientName($id) {
		$q = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$id."'"); 
		foreach($q->result() as $row) { 
			return $row->patient_name;
		}
	}
	public function getUserInfoPatientGetParentName($id) {
		$q = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$id."'"); 
		foreach($q->result() as $row) { 
			$qw = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$row->parent_id."'"); 
			foreach($qw->result() as $row2) { 
				return $row2->parent_name;
			}
		}
	}
	public function getUserInfoPatientGetParentId($id) {
		$q = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$id."'"); 
		foreach($q->result() as $row) { 
			$qw = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$row->parent_id."'"); 
			foreach($qw->result() as $row2) { 
				return $row2->parent_id;
			}
		}
	}
	public function getConsultationInfo($id) {
		$q = $this->db->query("SELECT * FROM `consultations` WHERE `consultation_id` = '".$id."'"); 
		return $q->result_array();
	}
	public function checkAccount($id, $selection) {
		//1-doctor
		//2-admin
		//3-receptionist
		//4-parent
		if($selection == "doctor") {
			$q = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$id."'"); 
			if($q->num_rows() > 0) {
				return '1';
			} else {
				return '0';
			}
		} else if($selection == "administrator") {
			$q = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_id` = '".$id."'"); 
			if($q->num_rows() > 0) {
				return '1';
			} else {
				return '0';
			}
		} else if($selection == "receptionist") {
			$q = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_id` = '".$id."'"); 
			if($q->num_rows() > 0) {
				return '1';
			} else {
				return '0';
			}
		} else if($selection == "parent") {
			$q = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$id."'"); 
			if($q->num_rows() > 0) {
				return '1';
			} else {
				return '0';
			}
		} else if($selection == "patient") {
			$q = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$id."'"); 
			if($q->num_rows() > 0) {
				return '1';
			} else {
				return '0';
			}
		} 
	}
	public function checkAccount2($id, $selection) {
		//1-doctor
		//2-admin
		//3-receptionist
		//4-parent
		if($selection == 1) {
			$q = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$id."'"); 
			if($q->num_rows() > 0) {
				return '1';
			} else {
				return '0';
			}
		} else if($selection == 2) {
			$q = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_id` = '".$id."'"); 
			if($q->num_rows() > 0) {
				return '1';
			} else {
				return '0';
			}
		} else if($selection == 3) {
			$q = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_id` = '".$id."'"); 
			if($q->num_rows() > 0) {
				return '1';
			} else {
				return '0';
			}
		} else if($selection == 4) {
			$q = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$id."'"); 
			if($q->num_rows() > 0) {
				return '1';
			} else {
				return '0';
			}
		} else if($selection == 5) {
			$q = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$id."'"); 
			if($q->num_rows() > 0) {
				return '1';
			} else {
				return '0';
			}
		} 
	}

	public function getUserInfo($id, $selection) {
		//1-doctor
		//2-admin
		//3-receptionist
		//4-parent
		if($selection == 1) {
			$q = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$id."'"); 
			return $q->result_array();
		} else if($selection == 2) {
			$q = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_id` = '".$id."'"); 
			return $q->result_array();
		} else if($selection == 3) {
			$q = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_id` = '".$id."'"); 
			return $q->result_array();
		} else if($selection == 4) {
			$q = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$id."'"); 
			return $q->result_array();
		} else if($selection == 5) {
			$q = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$id."'"); 
			return $q->result_array();
		}
	}

	public function getUserInfoNames($id, $selection) {
		//1-doctor
		//2-admin
		//3-receptionist
		//4-parent
		if($selection == 1) {
			$q = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$id."'"); 
			foreach($q->result() as $row) {  
				return $row->doctor_name;
			}
		} else if($selection == 2) {
			$q = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_id` = '".$id."'"); 
			foreach($q->result() as $row) {  
				return $row->admin_name;
			}
		} else if($selection == 3) {
			$q = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_id` = '".$id."'"); 
			foreach($q->result() as $row) {  
				return $row->receptionist_name;
			}
		} else if($selection == 4) {
			$q = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$id."'"); 
			foreach($q->result() as $row) {  
				return $row->parent_name;
			}
		} else if($selection == 5) {
			$q = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$id."'"); 
			foreach($q->result() as $row) {  
				return $row->patient_name;
			}
		}
	}

	// appointment
	public function getAppointment($doctor_id = ""){
		if($doctor_id != "") {
			$this->db->where('interview_id', $doctor_id);
		}
		$this->db->where('appointment_status', 'Approved');
		$this->db->order_by('appointment_id');

		return $this->db->get('appointments');
	}
	public function addAppointment($data) {
		$this->db->insert('appointments', $data);
	}
	public function updateAppointment($data, $id) {
		$this->db->where('appointment_id', $id);
		$this->db->update('appointments', $data);
	}
	public function deleteAppointment($id) {
		$this->db->where('id', $id);
		$this->db->delete('appointments');
	}
	public function getCheckTimeAppointment($start, $end) {
		$q = $this->db->query("SELECT * FROM `appointments` WHERE `appointment_timestamp` = '".$start."' AND `appointment_timestamp_end` = '".$end."' AND appointment_status = '' ORDER BY `appointment_id` DESC LIMIT 1"); 
		if($q->num_rows() > 0) {
			return 1;
		}
	}
	public function get_appointment_requests_count($filter, $date, $doctor_id) {
		if($filter != "") {
			$this->db->where('appointment_status', $filter);
		}
		if($date != "") {
			$this->db->where('date_text', $date);
		}
		if($doctor_id != "") {
			$this->db->where('interview_id', $doctor_id);
		}
		$this->db->order_by("appointment_timestamp_sub", "desc");
        return $this->db->count_all("appointments");
    }
    public function get_appointment_requests($filter, $date, $doctor_id, $limit, $start) {
        $this->db->limit($limit, $start);
		if($filter != "") {
			$this->db->where('appointment_status', $filter);
		}
		if($date != "") {
			$this->db->where('date_text', $date);
		}
		if($doctor_id != "") {
			$this->db->where('interview_id', $doctor_id);
		}
		$this->db->order_by("appointment_timestamp_sub", "desc");
		$q = $this->db->get("appointments");
		$array = array();

		foreach($q->result_array() as $row) {

			$result = $this->power_model->getUserInfoParent($row['appointment_parent_id']);


			$result2 = "";
			$name2 = "";
			if($row['appointment_approve_selection'] == 1) {
				// doctor
				$result2 = $this->power_model->getUserInfoDoctor($row['appointment_approve_by']);
				$name2 = $result2[0]['doctor_name'];
			} else if($row['appointment_approve_selection'] == 2) {
				// admin
				$result2 = $this->power_model->getUserInfoAdmin($row['appointment_approve_by']);
				$name2 = $result2[0]['admin_name'];
			} else if($row['appointment_approve_selection'] == 3) {
				// receptionist
				$result2 = $this->power_model->getUserInfoReceptionist($row['appointment_approve_by']);
				$name2 = $result2[0]['receptionist_name'];
			}
			$patient_name = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['appointment_patient_id']."'")->result_array();
			$getInfo = (array) $result;
			$newRow = array(
				'appointment_id' => (int) $row['appointment_id'],
				'appointment_parent_id' => (int) $row['appointment_parent_id'],
				'appointment_patient_id' => (int) $row['appointment_patient_id'],
				'parent_name' => $getInfo[0]['parent_name'],
				'patient_name' => $patient_name[0]['patient_name'],
				'appointment_timestamp' => (int) $row['appointment_timestamp'],
				'appointment_timestamp_end' => (int) $row['appointment_timestamp_end'],
				'appointment_description' => $row['appointment_description'],
				'appointment_datetime' => $row['appointment_datetime'],
				'appointment_datetime_end' => $row['appointment_datetime_end'],
				'interview_id' => $row['interview_id'],
				'reference_number' => $row['reference_number'],
				'money' => $row['money'],
				'survey_done' => $row['survey_done'],
				'staff_name' => $name2,
				'appointment_status' => $row['appointment_status']
			);
			$array[] = $newRow;
			
			
		}
		
		return array_reverse($array);
		
    }
	public function cancelAppointmentRequest($data, $id) {
		$q = $this->db->query("SELECT * FROM `appointments` WHERE `appointment_id` = '".$id."'")->result_array();
		$p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$q[0]['appointment_parent_id']."'")->result_array();
		$p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$q[0]['appointment_patient_id']."'")->result_array();
		$message = "Your appointment request for ".$p2[0]['patient_name']." has been cancelled!";
		$this->power_model->itexmo($p[0]['parent_phonenumber'], $message);

		$this->db->where('appointment_id', $id);
		$this->db->update('appointments', $data);
	}
	public function doneAppointmentRequest($data, $id) {
		$this->db->where('appointment_id', $id);
		$this->db->update('appointments', $data);
	}
	public function approveAppointmentRequest($data, $id) {
		$this->db->where('appointment_id', $id);
		$this->db->update('appointments', $data);

		$q = $this->db->query("SELECT * FROM `appointments` WHERE `appointment_id` = '".$id."'")->result_array();
		$p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$q[0]['appointment_parent_id']."'")->result_array();
		$p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$q[0]['appointment_patient_id']."'")->result_array();
		//$message = "Your appointment request has been approved!\n\nDate start: ".$q[0]['appointment_datetime']."\nDate end: ".$q[0]['appointment_datetime_end']."\n\nWe will message you via SMS as soon as the link is ready for your appointment";
		$message = "Your appointment request for ".$p2[0]['patient_name']." has been approved!\n\nDate start: ".$q[0]['appointment_datetime']."\nDate end: ".$q[0]['appointment_datetime_end'];
		$this->power_model->itexmo($p[0]['parent_phonenumber'], $message);
	}

	//consultation
	public function getConsultation($doctor_id = ""){
		if($doctor_id != "") {
			$this->db->where('interview_id', $doctor_id);
		}
		$this->db->order_by('date_consultation_sub', 'desc');
		$this->db->where('consultation_status', 'Approved');
		return $this->db->get('consultations');
	}
	public function get_consultations_count($filter, $date, $doctor_id) {
		if($filter != "") {
			$this->db->where('consultation_status', $filter);
		}
		if($date != "") {
			$this->db->where('date_text', $date);
		}
		if($doctor_id != "") {
			$this->db->where('interview_id', $doctor_id);
		}
		$this->db->order_by("date_consultation_sub", "desc");
        return $this->db->count_all("consultations");
    }
    public function get_consultations($filter, $date, $doctor_id, $limit, $start) {
        $this->db->limit($limit, $start);
		if($filter != "") {
			$this->db->where('consultation_status', $filter);
		}
		if($date != "") {
			$this->db->where('date_text', $date);
		}
		if($doctor_id != "") {
			$this->db->where('interview_id', $doctor_id);
		}
		$this->db->order_by("date_consultation_sub", "desc");
		$q = $this->db->get("consultations");
		$array = array();

		foreach($q->result_array() as $row) {

			$result = $this->power_model->getUserInfoParent($row['consultation_parent_id']);
			$result2 = $this->power_model->getUserInfoPatient($row['consultation_patient_id']);

			$getInfo = (array) $result;
			$getInfo2 = (array) $result2;
			$newRow = array(
				'consultation_id' => (int) $row['consultation_id'],
				'consultation_parent_id' => (int) $row['consultation_parent_id'],
				'consultation_patient_id' => (int) $row['consultation_patient_id'],
				'parent_name' => $getInfo[0]['parent_name'],
				'patient_name' => $getInfo2[0]['patient_name'],
				'date_consultation' => $row['date_consultation'],
				'date_consultation_end' => $row['date_consultation_end'],
				'date_consultation_sub' => $row['date_consultation_sub'],
				'date_consultation_sub_end' => $row['date_consultation_sub_end'],
				'date_consultation_datetime' => $row['date_consultation_datetime'],
				'date_consultation_datetime_end' => $row['date_consultation_datetime_end'],
				'googlelink' => $row['googlelink'],
				'interview_id' => $row['interview_id'],
				'send_it_to_parent' => $row['send_it_to_parent'],
				'reason' => $row['reason'],
				'money' => $row['money'],
				'choice' => $row['choice'],
				'consultation_prescription' => $row['consultation_prescription'],
				'consultation_status' => $row['consultation_status'],
				'healthHistory'        => $row['healthHistory'],
				'anyMedication'        => $row['anyMedication'],
				'anyAllergies'         => $row['anyAllergies']
			);
			$array[] = $newRow;
			
			
		}
		
		return array_reverse($array);
		
    }
	public function sendlink($data, $id) {
		$this->db->where('consultation_id', $id);
		$this->db->update('consultations', $data);

		$q = $this->db->query("SELECT * FROM `consultations` WHERE `consultation_id` = '".$id."'")->result_array();
		$p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$q[0]['consultation_parent_id']."'")->result_array();
		$p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$q[0]['consultation_patient_id']."'")->result_array();

		$message = "For patient name: ".$p2[0]['patient_name']."\n\nHere the meeting:\n".$q[0]['googlelink']."\n\nDate start: ".$q[0]['date_consultation_datetime']."\nDate end: ".$q[0]['date_consultation_datetime_end']."\n\nSee you there!";
		$this->power_model->itexmo($p[0]['parent_phonenumber'], $message);
	}
	public function addPrescription($data, $id) {
		$this->db->where('consultation_id', $id);
		$this->db->update('consultations', $data);
	}
	public function cancelConsultation($data, $id) {
		$q = $this->db->query("SELECT * FROM `consultations` WHERE `consultation_id` = '".$id."'")->result_array();
		$p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$q[0]['consultation_parent_id']."'")->result_array();
		$p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$q[0]['consultation_patient_id']."'")->result_array();
		$message = "Your consultation request for ".$p2[0]['patient_name']." has been cancelled!";
		$this->power_model->itexmo($p[0]['parent_phonenumber'], $message);

		$this->db->where('consultation_id', $id);
		$this->db->update('consultations', $data);
	}
	public function doneConsultation($data, $id) {
		$this->db->where('consultation_id', $id);
		$this->db->update('consultations', $data);
	}
	

	public function approveConsultation($data, $id) {
		$q = $this->db->query("SELECT * FROM `consultations` WHERE `consultation_id` = '".$id."'")->result_array();
		$p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$q[0]['consultation_parent_id']."'")->result_array();
		$p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$q[0]['consultation_patient_id']."'")->result_array();
		$message = "Your consultation request for ".$p2[0]['patient_name']." has been approved!\n\nDate start: ".$q[0]['date_consultation_datetime']."\nDate end: ".$q[0]['date_consultation_datetime_end']."\n\nWe will message you via SMS as soon as the link is ready for your consultation";
		$this->power_model->itexmo($p[0]['parent_phonenumber'], $message);

		$this->db->where('consultation_id', $id);
		$this->db->update('consultations', $data);
	}
	// transaction for consultation
	public function get_transactions_count() {
        return $this->db->count_all("transactions_tbl");
    }
    public function get_transactions($limit, $start) {
		$this->db->where("status =", "Approved")->limit($limit, $start);
		$q = $this->db->get("transactions_tbl");

		$array = array();

		foreach($q->result_array() as $row) {

			$result = $this->power_model->getUserInfoParent($row['parent_id']);
			$result2 = $this->power_model->getUserInfoPatient($row['patient_id']);
			$result3 = $this->power_model->getConsultationInfo($row['consultation_id']);

			$getInfo = (array) $result;
			$getInfo2 = (array) $result2;
			$getInfo3 = (array) $result3;

			$newRow = array(
				'transaction_id' => (int) $row['transaction_id'],
				'consultation_id' => (int) $row['consultation_id'],
				'parent_id' => (int) $row['parent_id'],
				'patient_id' => (int) $row['patient_id'],
				'parent_name' => $getInfo[0]['parent_name'],
				'patient_name' => $getInfo2[0]['patient_name'],
				'date_consultation' => $getInfo3[0]['date_consultation'],
				'consultation_status' => $getInfo3[0]['consultation_status'],
				'status' => $row['status']
			);
			$array[] = $newRow;
			
			
		}
		
		return array_reverse($array);
		
    }

	// immunization records
	public function get_immunizationrecords_count($patient = "", $vaccine = "", $date = "") {
		if($patient != "") {
			$this->db->where('patient_id', $patient);
		}
		if($date != "") {
			$this->db->like('date', $date);
		}
		if($vaccine != "") {
			$this->db->where('vaccine_id', $vaccine);
		}
		if($this->session->selection == "doctor") {
			$this->db->where('interview_id', $this->session->id);
		}
        return $this->db->count_all("immunization_record");
    }
	public function get_immunizationrecords($patient = "", $vaccine = "", $date = "", $limit, $start) {
		if($patient != "") {
			$this->db->where('patient_id', $patient);
		}
		if($date != "") {
			$this->db->like('date', $date);
		}
		if($vaccine != "") {
			$this->db->where('vaccine_id', $vaccine);
		}
		if($this->session->selection == "doctor") {
			$this->db->where('interview_id', $this->session->id);
		}
		$this->db->order_by('immunization_record_id','DESC')->limit($limit, $start);
		$q = $this->db->get("immunization_record");

		$array = array();

		foreach($q->result_array() as $row) {

			$result = $this->power_model->getUserInfoParent($row['parent_id']);
			$result2 = $this->power_model->getUserInfoPatient($row['patient_id']);
			$getInfo = (array) $result;
			$getInfo2 = (array) $result2;

			$newRow = array(
				'immunization_record_id' => (int) $row['immunization_record_id'],
				'parent_name' => $getInfo[0]['parent_name'],
				'patient_name' => $getInfo2[0]['patient_name'],
				'patient_id' => $row['patient_id'],
				'date' => $row['date'],
				'vaccine_id' => $row['vaccine_id'],
				'route' => $row['route'],
				'interview_id' => $row['interview_id']
			);
			$array[] = $newRow;
			
			
		}
		
		return array_reverse($array);
		
    }
	public function getImmunizationRecordPDF($patient = "", $vaccine = "", $date = "") {
		if($patient != "") {
			$this->db->where('patient_id', $patient);
		}
		if($date != "") {
			$this->db->like('date', $date);
		}
		if($vaccine != "") {
			$this->db->where('vaccine_id', $vaccine);
		}
		$this->db->order_by('immunization_record_id','DESC');
		$q = $this->db->get("immunization_record");

		$array = array();

		foreach($q->result_array() as $row) {

			$result = $this->power_model->getUserInfoParent($row['parent_id']);
			$result2 = $this->power_model->getUserInfoPatient($row['patient_id']);
			$getInfo = (array) $result;
			$getInfo2 = (array) $result2;
			$getVaccine = $this->db->query("SELECT * FROM `vaccine_terms_tbl` WHERE `vaccine_terms_id` = '".$row['vaccine_id']."'")->result_array();
			$array[] = array(
				'immunization_record_id' => (int) $row['immunization_record_id'],
				'parent_name' => $getInfo[0]['parent_name'],
				'patient_name' => $getInfo2[0]['patient_name'],
				'patient_id' => $row['patient_id'],
				'date' => $row['date'],
				'vaccine_name' => $getVaccine[0]['vaccine_terms_title'],
				'route' => $row['route'],
				'interview_id' => $row['interview_id']
			);
			
		}
		
		return array_reverse($array);
		
    }
	public function addImmunizationRecord($array) {
		$this->db->insert('immunization_record', $array);
	}
	public function getImmunizationRecord($id) {
		$q = $this->db->query("SELECT * FROM `immunization_record` WHERE `immunization_record_id` = '".$id."'"); 
		return $q->result_array();
	}
	public function editImmunizationRecord($data, $id) {
		$this->db->where('immunization_record_id', $id);
		$this->db->update('immunization_record', $data);
	}
	public function deleteImmunizationRecord($id) {
		$this->db->where('immunization_record_id', $id);
		$this->db->delete('immunization_record');
	}
	
	//parent lsikt
	public function get_parents_count() {
        return $this->db->count_all("parents_tbl");
    }
	public function get_parents($limit, $start) {
		$this->db->order_by('parent_id','DESC')->limit($limit, $start);
		$q = $this->db->get("parents_tbl");

		$array = array();

		foreach($q->result_array() as $row) {
			$newRow = array(
				'parent_id' => (int) $row['parent_id'],
				'parent_name' => $row['parent_name'],
				'parent_username' => $row['parent_username'],
				//test
				'parent_emailaddress' => $row['parent_emailaddress'],
				
				'parent_phonenumber' => $row['parent_phonenumber'],
				'verified' => $row['verified']
			);
			$array[] = $newRow;
			
			
		}
		
		return array_reverse($array);
		
    }

	//patient list
	public function get_patients_count($limit = "", $start = "") {
		if($this->session->selection != "doctor") {
        	return $this->db->count_all("patients_tbl");
		} else {
			$q = 'SELECT
			p.patient_id,
			p.patient_name,
			p.patient_birthdate,
			p.patient_picture,
			p.parent_id
		FROM
			patients_tbl as p
		LEFT JOIN
			consultations tb1 ON 
			p.patient_id = tb1.consultation_patient_id
		
		LEFT JOIN
			appointments tb2 ON 
			p.patient_id = tb2.appointment_patient_id
		
		WHERE tb1.interview_id = '.$this->session->id.' OR tb2.interview_id = '.$this->session->id.' ORDER BY patient_id DESC LIMIT '.$start.', '.$limit.'';
			$q = $this->db->query($q);
			return $q->num_rows();
		}
    }
	public function get_patients($limit, $start) {
		if($this->session->selection != "doctor") {
			$this->db->order_by('patient_id','DESC')->limit($limit, $start);
			$q = $this->db->get("patients_tbl");

			$array = array();

			foreach($q->result_array() as $row) {
				$arrayDoctor = array();
				$appointments = $this->db->query("SELECT * FROM `appointments` WHERE `appointment_patient_id` = '".$row['patient_id']."'");
				$consultations = $this->db->query("SELECT * FROM `consultations` WHERE `consultation_patient_id` = '".$row['patient_id']."'");
				$immunizations = $this->db->query("SELECT * FROM `immunization_record` WHERE `patient_id` = '".$row['patient_id']."'");
				if($appointments->num_rows() > 0) {
					// exist
					foreach($appointments->result_array() as $rows2) {
						$getDoctor = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id`= '".$rows2['interview_id']."'")->result_array();
						if(array_search($getDoctor[0]['doctor_name'],$arrayDoctor,true) == "") {
							$arrayDoctor[] = $getDoctor[0]['doctor_name'];
						}
					}
				}
				if($consultations->num_rows() > 0) {
					// exist
					foreach($consultations->result_array() as $rows2) {
						$getDoctor = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id`= '".$rows2['interview_id']."'")->result_array();
						if(array_search($getDoctor[0]['doctor_name'],$arrayDoctor,true) == "") {
							$arrayDoctor[] = $getDoctor[0]['doctor_name'];
						}
					}
				}
				if($immunizations->num_rows() > 0) {
					// exist
					foreach($immunizations->result_array() as $rows2) {
						$getDoctor = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id`= '".$rows2['interview_id']."'")->result_array();
						if(array_search($getDoctor[0]['doctor_name'],$arrayDoctor,true) == "") {
							$arrayDoctor[] = $getDoctor[0]['doctor_name'];
						}
					}
				}
				$doctors = "";
				foreach($arrayDoctor as $rowsDoctor) {
					$doctors .= $rowsDoctor.", ";
				}
				$newRow = array(
					'patient_id' => (int) $row['patient_id'],
					'patient_name' => $row['patient_name'],
					'patient_gender' => $row['patient_gender'],
					'patient_birthdate' => $row['patient_birthdate'],
					'parent_name' => $this->power_model->getUserInfoPatientGetParentName($row['patient_id']),
					'doctor_name' => $doctors
				);
				$array[] = $newRow;
			}
			
			return array_reverse($array);
		} else {
			$q = $this->db->query("SELECT * FROM patients_tbl");
			foreach($q->result_array() as $row) {
				$one = $this->db->query("SELECT * FROM appointments WHERE appointment_patient_id = '".$row['patient_id']."' AND `interview_id` = '".$this->session->id."'");

				if($one->num_rows() > 0) {
					// exist
					$newRow = array(
						'patient_id' => (int) $row['patient_id'],
						'patient_name' => $row['patient_name'],
						'patient_gender' => $row['patient_gender'],
						'patient_birthdate' => $row['patient_birthdate'],
						'parent_name' => $this->power_model->getUserInfoPatientGetParentName($row['patient_id'])
					);
					$array[] = $newRow;
					break;
				}
				$two = $this->db->query("SELECT * FROM consultations WHERE consultation_patient_id = '".$row['patient_id']."' AND `interview_id` = '".$this->session->id."'");
				if($two->num_rows() > 0) {
					// exist
					$newRow = array(
						'patient_id' => (int) $row['patient_id'],
						'patient_name' => $row['patient_name'],
						'patient_gender' => $row['patient_gender'],
						'patient_birthdate' => $row['patient_birthdate'],
						'parent_name' => $this->power_model->getUserInfoPatientGetParentName($row['patient_id'])
					);
					$array[] = $newRow;
					break;
				}
				$three = $this->db->query("SELECT * FROM immunization_record WHERE patient_id = '".$row['patient_id']."' AND `interview_id` = '".$this->session->id."'");
				if($three->num_rows() > 0) {
					// exist
					$newRow = array(
						'patient_id' => (int) $row['patient_id'],
						'patient_name' => $row['patient_name'],
						'patient_gender' => $row['patient_gender'],
						'patient_birthdate' => $row['patient_birthdate'],
						'parent_name' => $this->power_model->getUserInfoPatientGetParentName($row['patient_id'])
					);
					$array[] = $newRow;
					break;
				}
				
				
			}

			return array_reverse($array);
			/*
			$q = 'SELECT
			p.patient_id,
			p.patient_name,
			p.patient_birthdate,
			p.patient_gender,
			p.patient_picture,
			p.parent_id
		FROM
			patients_tbl as p
		LEFT JOIN
			consultations tb1 ON 
			p.patient_id = tb1.consultation_patient_id
		
		LEFT JOIN
			appointments tb2 ON 
			p.patient_id = tb2.appointment_patient_id
		
		WHERE tb1.interview_id = '.$this->session->id.' OR tb2.interview_id = '.$this->session->id.' ORDER BY patient_id DESC LIMIT '.$start.', '.$limit.'';
			$q = $this->db->query($q);

			$array = array();

			foreach($q->result_array() as $row) {
				if(array_search($row['patient_id'], array_column($array, 'patient_id')) == "") {
					$newRow = array(
						'patient_id' => (int) $row['patient_id'],
						'patient_name' => $row['patient_name'],
						'patient_gender' => $row['patient_gender'],
						'patient_birthdate' => $row['patient_birthdate'],
						'parent_name' => $this->power_model->getUserInfoPatientGetParentName($row['patient_id'])
					);
					$array[] = $newRow;
				}
			}
			
			return array_reverse($array);*/
		}
		
    }

	public function get_viewImmunizationRecords($patient_id) {
		$this->db->order_by('patient_id','DESC');
		$q = $this->db->where('patient_id', $patient_id)->get("immunization_record");

		$array = array();

		foreach($q->result_array() as $row) {
			$newRow = array(
				'immunization_record_id' => $row['immunization_record_id'],
				'patient_id' => $row['patient_id'],
				'date' => $row['date'],
				'vaccine_id' => $row['vaccine_id'],
				'route' => $row['route'],
				'age' => $row['age'],
				'weight' => $row['weight'],
				'length' => $row['length'],
				'bmi' => $row['bmi'],
				'doctors_instruction' => $row['doctors_instruction'],
				'head_circumference' => $row['head_circumference'],
				'comeback_on' => $row['comeback_on'],
				'comeback_for' => $row['comeback_for']
			);
			$array[] = $newRow;
		}
		
		return array_reverse($array);
		
    }

	//pediatric
	public function get_viewPediatricCharts($patient_id) {
		$this->db->order_by('patients_history_id','DESC');
		$q = $this->db->where('patient_id', $patient_id)->get("patients_history_tbl");

		$array = array();

		foreach($q->result_array() as $row) {
			$newRow = array(
				'patients_history_id' => $row['patients_history_id'],
				'patient_id' => $row['patient_id'],
				'patient_datetime' => $row['patient_datetime'],
				'chiefComplaint' => $row['chiefComplaint'],
				'medicalHistory' => $row['medicalHistory'],
				'pastMedicalHistory' => $row['pastMedicalHistory'],
				'medicalHistory' => $row['medicalHistory'],
				'familyHistory' => $row['familyHistory'],
				'birthHistory' => $row['birthHistory'],
				'feedingHistory' => $row['feedingHistory'],
				'immunization' => $row['immunization'],
				'earAndBodyPiercing' => $row['earAndBodyPiercing'],
				'circumcision' => $row['circumcision'],
				'developmentalHistory' => $row['developmentalHistory'],
				'bp' => $row['bp'],
				'cr' => $row['cr'],
				'rr' => $row['rr'],
				'temp' => $row['temp'],
				'O2Sat' => $row['O2Sat'],
				'weight' => $row['weight'],
				'Ht' => $row['Ht'],
				'hc' => $row['hc'],
				'cc' => $row['cc'],
				'ac' => $row['ac'],
				'height' => $row['height'],
				'skin' => $row['skin'],
				'skin' => $row['skin'],
				'thorax' => $row['thorax'],
				'abdomen' => $row['abdomen'],
				'rectalExamination' => $row['rectalExamination'],
				'extremities' => $row['extremities'],
				'assessment' => $row['assessment'],
				'lmp' => $row['lmp'],
				'obstretrics' => $row['obstretrics'],
				'Investigate' => $row['Investigate'],
				'therapy' => $row['therapy']
			);
			$array[] = $newRow;
		}
		
		return array_reverse($array);
		
    }
	public function addPediatricCharts($array) {
		$this->db->insert('patients_history_tbl', $array);
	}
	public function addPediatricChartsBatch2($data, $id, $getTime) {
		$this->db->where('patient_id', $id);
		$this->db->where('patient_datetime', $getTime);
		$this->db->update('patients_history_tbl', $data);
	}

	public function editPediatricCharts($array, $id) {
		$this->db->where('patients_history_id', $id);
		$this->db->update('patients_history_tbl', $array);
	}
	public function editPediatricChartsBatch2($array, $id) {
		$this->db->where('patients_history_id', $id);
		$this->db->update('patients_history_tbl', $array);
	}

	public function getPediatricCharts($id) {
		$q = $this->db->query("SELECT * FROM `patients_history_tbl` WHERE `patients_history_id` = '".$id."'"); 
		return $q->result_array();
	}
	public function deletePediatricCharts($id) {
		$this->db->where('patients_history_id', $id);
		$this->db->delete('patients_history_tbl');
	}
	//parents
	public function addParents($data) {
		$this->db->insert('parents_tbl', $data);
	}
	public function addChild($array) {
		$this->db->insert('patients_tbl', $array);
	}
	public function addChildInformation($array) {
		$this->db->insert('patients_medical_conditions_tbl', $array);
	}
	public function editParent($data, $id) {
		$this->db->where('parent_id', $id);
		$this->db->update('parents_tbl', $data);
	}
	public function editChild($data, $id) {
		$this->db->where('patient_id', $id);
		$this->db->update('patients_tbl', $data);
	}
	public function searchChild($array) {
		$this->db->where($array);
		$q = $this->db->get("patients_tbl");
		return $q->result_array();
	}

	public function deleteChild($id) {
		$this->db->where('patient_id', $id);
		$this->db->delete('patients_tbl');
	}
	public function get_listChilds($parent_id) {
		$this->db->order_by('patient_id','DESC');
		$q = $this->db->where('parent_id', $parent_id)->get("patients_tbl");

		$array = array();

		foreach($q->result_array() as $row) {
			$newRow = array(
				'patient_id' => $row['patient_id'],
				'patient_name' => $row['patient_name'],
				'patient_gender' => $row['patient_gender'],
				'patient_birthdate' => $row['patient_birthdate']
			);
			$array[] = $newRow;
		}
		
		return array_reverse($array);
		
    }

	// access privilege
	public function get_accessprivileges_count($selection) {
		if($selection == "doctor") {
        	return $this->db->query("SELECT * FROM doctors_tbl")->num_rows();
		} else if($selection == "receptionist") {
			return $this->db->query("SELECT * FROM receptionists_tbl")->num_rows();
		} else if($selection == "administrator") {
			return $this->db->query("SELECT * FROM admins_tbl")->num_rows();
		}
    }
    public function get_accessprivileges($selection, $limit, $start) {
		if($selection == "doctor") {
			$this->db->order_by('doctor_id','DESC')->limit($limit, $start);
			$q = $this->db->get("doctors_tbl");
			$array = array();

			foreach($q->result_array() as $row) {
				$newRow = array(
					'doctor_id' => (int) $row['doctor_id'],
					'doctor_name' => $row['doctor_name'],
					'selection' => 1
				);
				$array[] = $newRow;
			}
			return array_reverse($array);
		} else if($selection == "receptionist") {
			$this->db->order_by('receptionist_id','DESC')->limit($limit, $start);
			$q = $this->db->get("receptionists_tbl");
			$array = array();

			foreach($q->result_array() as $row) {
				$newRow = array(
					'receptionist_id' => (int) $row['receptionist_id'],
					'receptionist_name' => $row['receptionist_name'],
					'selection' => 3
				);
				$array[] = $newRow;
			}
			return array_reverse($array);
		} else if($selection == "administrator") {
			$this->db->order_by('admin_id','DESC')->limit($limit, $start);
			$q = $this->db->get("admins_tbl");
			$array = array();

			foreach($q->result_array() as $row) {
				$newRow = array(
					'admin_id' => (int) $row['admin_id'],
					'admin_name' => $row['admin_name'],
					'selection' => 2
				);
				$array[] = $newRow;
			}
			return array_reverse($array);
		}
    }
	public function getAccessPrivilege($id, $selection) {
		$q = $this->db->query("SELECT * FROM `access_privilege` WHERE `staff_id` = '".$id."' AND `staff_selection` = '".$selection."'"); 
		return $q->result_array();
	}
	public function getAccessPrivilegeRow($id, $selection, $category) {
		$q = $this->db->query("SELECT * FROM `access_privilege` WHERE `staff_id` = '".$id."' AND `staff_selection` = '".$selection."' AND `staff_category` = '".$category."'"); 
		if($q->num_rows() > 0) {
			return '1';
		} else {
			return '0';
		}
	}
	public function addAccessPrivilege($array) {
		$this->db->insert('access_privilege', $array);
	}
	public function deleteAccessPrivilege($id, $selection, $category) {
		$q = $this->db->query("SELECT * FROM `access_privilege` WHERE `staff_id` = '".$id."' AND `staff_selection` = '".$selection."' AND `staff_category` = '".$category."'"); 
		if($q->num_rows() > 0) {
			// success
			foreach($q->result_array() as $dataz) {
				$this->db->where('access_privilege_id', $dataz['access_privilege_id']);
				$this->db->delete('access_privilege');
				return '1';
			}
		} else {
			// error
			return '0';
		}
	}

	public function setAccessPrivilege($id, $selection) {
		$q = $this->db->query("SELECT * FROM `access_privilege` WHERE `staff_id` = '".$id."' AND `staff_selection` = '".$selection."'"); 
		if($q->num_rows() > 0) {
			// file name
			$name = array(
				'View Inquiry', // 1 @
                'Delete Inquiry', // 2
                'View Appointment', // 3 @
                'View Consultation', // 4 @
                'View Transaction', // 5 @
                'View Immunization Record', // 6 @
                'Add Immunization Record', // 7
                'Edit Immunization Record', // 8
                'Delete Immunization Record', // 9
                'View Parent List', // 10 @
                'Add Parent', // 11
                'View Child List', // 12 
                'Add Child', // 13
                'Edit Child', // 14
                'Delete Child', // 15
                'Patient List', // 16 @
                'Access Privilege', // 17
                'View Announcement', // 18
                'Add Announcement', // 19
                'Delete Announcement', // 20
                'Edit Announcement', // 21
                'Patient Satisfaction', //22
                'Management',//23
                'View Pediatric Chart', //24
                'Add Pediatric Chart', //25
                'Edit Pediatric Chart', //26
                'Delete Pediatric Chart', //27
                'Health Tips', // 28
                'Account List', // 29
                'Medical Certificate', // 30
                'Laboratory Result', // 31
				'Tabular Reports' //32
			);
			
			// exist
			$array = array();
			$count = 0;
			foreach($q->result_array() as $data) {
				$array[] = array(
					'name' => $name[$count],
					'category' => $data['staff_category']
				);
				$count++;
			}

			return $array;
		} else {
			// not exist
			return '[]';
		}
	}
	//patient_satisfaction
	public function get_patient_satisfaction_count() {
        return $this->db->count_all("surveyresults_tbl");
    }
	public function get_patient_satisfaction($limit, $start) {
		$this->db->order_by('surveyresults_tbl_id','DESC')->limit($limit, $start);
		$q = $this->db->get("surveyresults_tbl");

		$array = array();

		foreach($q->result_array() as $row) {
			$newRow = array(
				'surveyresults_tbl_id' => (int) $row['surveyresults_tbl_id'],
				'surveyresults_tbl_ans' => $row['surveyresults_tbl_ans']
			);
			$array[] = $newRow;
		}
		
		return array_reverse($array);
		
    }
	public function deletePatientSatisfaction($id) {
		$this->db->where('surveyresults_tbl_id', $id);
		$this->db->delete('surveyresults_tbl');
	}
	public function checkPatientSatisfaction($id) {
		$q = $this->db->query("SELECT * FROM `surveyresults_tbl` WHERE `surveyresults_tbl_id` = '".$id."'"); 
		if($q->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	public function getPatientSatisfaction($id) {
		$q = $this->db->query("SELECT * FROM `surveyresults_tbl` WHERE `surveyresults_tbl_id` = '".$id."'"); 
		return $q->result_array();
	}
	
	public function editPatientSatisfaction($data, $id) {
		$this->db->where('surveyresults_tbl_id', $id);
		$this->db->update('surveyresults_tbl', $data);
	}
	public function addPatientSatisfaction($array) {
		$this->db->insert('surveyresults_tbl', $array);
	}

	//anouncement
	public function get_announcements_count() {
        return $this->db->count_all("announcement_tbl");
    }
	public function get_announcements($limit, $start) {
		$this->db->order_by('announcement_tbl_id','DESC')->limit($limit, $start);
		$q = $this->db->get("announcement_tbl");

		$array = array();

		foreach($q->result_array() as $row) {
			$newRow = array(
				'announcement_tbl_id' => (int) $row['announcement_tbl_id'],
				'announcement_tbl_date' => $row['announcement_tbl_date'],
				'announcement_tbl_title' => $row['announcement_tbl_title'],
				'announcement_tbl_content' => $row['announcement_tbl_content'],
				'announcement_tbl_image' => base64_encode($row['announcement_tbl_image'])
			);
			$array[] = $newRow;
		}
		
		return array_reverse($array);
		
    }
	public function deleteAnnouncements($id) {
		$this->db->where('announcement_tbl_id', $id);
		$this->db->delete('announcement_tbl');
	}
	public function checkAnnouncements($id) {
		$q = $this->db->query("SELECT * FROM `announcement_tbl` WHERE `announcement_tbl_id` = '".$id."'"); 
		if($q->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	public function getAnnouncements($id) {
		$q = $this->db->query("SELECT * FROM `announcement_tbl` WHERE `announcement_tbl_id` = '".$id."'"); 
		return $q->result_array();
	}
	public function editAnnouncements($data, $id) {
		$this->db->where('announcement_tbl_id', $id);
		$this->db->update('announcement_tbl', $data);
	}
	public function addAnnouncements($array) {
		$this->db->insert('announcement_tbl', $array);
	}

	// index
	public function allTransactions() {
		date_default_timezone_set("Asia/Manila");
		
        $num_rowsss = 0;
		$timestamp = strtotime(date("M d, Y 00:00:00", time()));
        $q = $this->db->query("SELECT * FROM appointments WHERE `appointment_timestamp` >= '".$timestamp."' AND appointment_status = 'Approved' ".($this->session->selection == "doctor" ? 'AND `interview_id` = '.$this->session->id : "")." ORDER BY `appointment_id`");
		$array = array();
        $count = 1;

        if($q->num_rows() > 0) {
            foreach($q->result_array() as $row) {
				$getParent = $this->db->query("SELECT * FROM `parents_tbl` WHERE parent_id = '".$row['appointment_parent_id']."'")->result_array();
                $getPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE patient_id = '".$row['appointment_patient_id']."'")->result_array();

                $array[] = array(
                    'overallId'            => $count,
                    'category'             => 'appointments',
                    'id'                   => $row['appointment_id'],
                    'parent_id'            => $row['appointment_parent_id'],
                    'parent_name'         => $getParent[0]['parent_name'],
                    'patient_id'           => $row['appointment_patient_id'],
                    'patient_name'         => $getPatient[0]['patient_name'],
                    'timestamp'            => $row['appointment_timestamp'],
                    'timestamp_end'        => $row['appointment_timestamp_end'],
                    'prescription'         => '',
                    'description'          => $row['appointment_description'],
                    'datetime'             => $row['appointment_datetime'],
                    'datetime_end'         => $row['appointment_datetime_end'],
                    'reason'               => '', //
                    'approve_by'           => '', //
                    'approve_selection'    => '', //
					'interview_id'         => $row['interview_id'],
                    'googlelink'           => '', //
                    'money'                => $row['money'],
                    'survey_done'          => $row['survey_done'],
                    'status'               => $row['appointment_status'],
                    'reference_number'     => $row['reference_number']
                );
                $count++;
            }
        }

        $q2 = $this->db->query("SELECT * FROM consultations  WHERE `date_consultation` >= '".$timestamp."' AND consultation_status = 'Approved' ".($this->session->selection == "doctor" ? 'AND `interview_id` = '.$this->session->id : "")." ORDER BY `consultation_id`");

        if($q2->num_rows() > 0) {
            foreach($q2->result_array() as $row2) {
                $getParent = $this->db->query("SELECT * FROM `parents_tbl` WHERE parent_id = '".$row2['consultation_parent_id']."'")->result_array();
                $getPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE patient_id = '".$row2['consultation_patient_id']."'")->result_array();

                $array[] = array(
                    'overallId'            => $count,
                    'category'             => 'consultations', //
                    'id'                   => $row2['consultation_id'], //
                    'parent_id'            => $row2['consultation_parent_id'], //
                    'parent_name'         => $getParent[0]['parent_name'], //
                    'patient_id'           => $row2['consultation_patient_id'], //
                    'patient_name'         => $getPatient[0]['patient_name'], //
                    'timestamp'            => $row2['date_consultation'], //
                    'timestamp_end'        => $row2['date_consultation_end'], //
                    'prescription'         => $row2['consultation_prescription'], //
                    'description'          => '',
                    'datetime'             => $row2['date_consultation_datetime'], //
                    'datetime_end'         => $row2['date_consultation_datetime_end'], //
                    'reason'               => $row2['reason'], //
                    'approve_by'           => $row2['consultation_approve_by'], //
                    'approve_selection'    => $row2['consultation_approve_selection'], //
					'interview_id'		   => $row2['interview_id'],
                    'googlelink'           => $row2['googlelink'], //
                    'money'                => $row2['money'], //
                    'survey_done'          => $row2['survey_done'], //
                    'status'               => $row2['consultation_status'], //
                    'reference_number'     => $row2['reference_number'],
					'healthHistory'        => $row2['healthHistory'],
                    'anyMedication'        => $row2['anyMedication'],
                    'anyAllergies'         => $row2['anyAllergies']
                );
                $count++;
            }
        }

        function sortTime($a, $b) {
			$a = $a['timestamp'];
			$b = $b['timestamp'];
			if ($a == $b)
			  return 0;
			return ($a < $b) ? -1 : 1;
		}

		usort($array, "sortTime");
		return $array;

		/*
        if($num_rowsss == 0) {
			
        } else {
			
            return array(
                'status' => 204,
                'message' => "Transactions not found."
            );
        }*/
    }
	public function get_transaction_medical_certificate_count() {
		if($this->session->selection == "doctor") {
			$s = (int) $this->db->where('interview_id', $this->session->id)->count_all("medical_certificates");
			return $s;
		} else {
			$s = (int) $this->db->count_all("medical_certificates");
			return $s;
		}
    }

	public function get_transaction_medical_certificate($limit, $start) {
		date_default_timezone_set("Asia/Manila");

        $num_rowsss = 0;
		$timestamp = strtotime(date("M d, Y 00:00:00", time()));
		$q = "";
		if($this->session->selection == "doctor") {
        	$q = $this->db->where('interview_id', $this->session->id)->order_by('medical_certificates_id','DESC')->limit($limit, $start)->get("medical_certificates");
		} else {
        	$q = $this->db->order_by('medical_certificates_id','DESC')->limit($limit, $start)->get("medical_certificates");
		}
		$array = array();
        $count = 1;

        if($q->num_rows() > 0) {
            foreach($q->result_array() as $row) {
				$getParent = $this->db->query("SELECT * FROM `parents_tbl` WHERE parent_id = '".$row['parent_id']."'")->result_array();
                $getPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE patient_id = '".$row['patient_id']."'")->result_array();

                $array[] = array(
                    'overallId'            => $count,
                    'id'                   => $row['medical_certificates_id'],
                    'parent_id'            => $row['parent_id'],
                    'parent_name'         => $getParent[0]['parent_name'],
                    'patient_id'           => $row['patient_id'],
                    'patient_name'         => $getPatient[0]['patient_name'],
                    'date_of_consultation'            => $row['date_of_consultation'],
                    'date_text'        => $row['date_text'],
                    'money'          => $row['money'],
                    'reference_number'             => $row['reference_number'],
                    'purpose'           => $row['purpose'], //
                    'diagnosis'    => $row['diagnosis'], //
					'treatment_and_recommendation'         => $row['treatment_and_recommendation'],
                    'approve_by'           => $row['approve_by'],
                    'approve_selection'                => $row['approve_selection'],
                    'interview_id'          => $row['interview_id'],
                    'purpose_doctor'               => $row['purpose_doctor'],
                    'timestamp'     => $row['timestamp'],
                    'send_it_to_parent'     => $row['send_it_to_parent'],
                    'status'     => $row['status']
                );
                $count++;
            }
        }

		return $array;
    }

	public function get_transactionz_appointment_count() {
		if($this->session->selection == "doctor") {
			$s = (int) $this->db->where('interview_id', $this->session->id)->count_all("appointments");
			return $s;
		} else {
			$s = (int) $this->db->count_all("appointments");
			return $s;
		}
    }

	public function get_transactionz_consultation_count() {
		if($this->session->selection == "doctor") {
			$s = (int) $this->db->where('interview_id', $this->session->id)->count_all("consultations");
        	return $s;
		} else {
			$s = (int) $this->db->count_all("consultations");
        	return $s;
		}
    }

	public function get_transactionz_appointment($limit, $start) {
		date_default_timezone_set("Asia/Manila");

        $num_rowsss = 0;
		$timestamp = strtotime(date("M d, Y 00:00:00", time()));
		$q = "";
		if($this->session->selection == "doctor") {
			$q = $this->db->where('interview_id', $this->session->id)->order_by('appointment_id','DESC')->limit($limit, $start)->get("appointments");
        	
		} else {
			$q = $this->db->order_by('appointment_id','DESC')->limit($limit, $start)->get("appointments");
		}
		$array = array();
        $count = 1;

        if($q->num_rows() > 0) {
            foreach($q->result_array() as $row) {
				$getParent = $this->db->query("SELECT * FROM `parents_tbl` WHERE parent_id = '".$row['appointment_parent_id']."'")->result_array();
                $getPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE patient_id = '".$row['appointment_patient_id']."'")->result_array();

                $array[] = array(
                    'overallId'            => $count,
                    'category'             => 'appointments',
                    'id'                   => $row['appointment_id'],
                    'parent_id'            => $row['appointment_parent_id'],
                    'parent_name'         => $getParent[0]['parent_name'],
                    'patient_id'           => $row['appointment_patient_id'],
                    'patient_name'         => $getPatient[0]['patient_name'],
                    'timestamp'            => $row['appointment_timestamp'],
                    'timestamp_end'        => $row['appointment_timestamp_end'],
                    'prescription'         => '',
                    'description'          => $row['appointment_description'],
                    'datetime'             => $row['appointment_datetime'],
                    'datetime_end'         => $row['appointment_datetime_end'],
                    'reason'               => '', //
                    'approve_by'           => $row['appointment_approve_by'], //
                    'approve_selection'    => $row['appointment_approve_selection'], //
					'interview_id'         => $row['interview_id'],
                    'googlelink'           => '', //
                    'money'                => $row['money'],
                    'survey_done'          => $row['survey_done'],
                    'status'               => $row['appointment_status'],
                    'reference_number'     => $row['reference_number']
                );
                $count++;
            }
        }

        

        function sortTime2($a, $b) {
			$a = $a['timestamp'];
			$b = $b['timestamp'];
			if ($a == $b)
			  return 0;
			return ($a < $b) ? -1 : 1;
		}

		usort($array, "sortTime2");
		return $array;
    }

	public function get_transactionz_consultation($limit, $start) {
		$count = 1;
		$array = array();
		$q2 = "";
		if($this->session->selection == "doctor") {
			$q2 = $this->db->where('interview_id', $this->session->id)->order_by('consultation_id','DESC')->limit($limit, $start)->get("consultations");
		} else {
			$q2 = $this->db->order_by('consultation_id','DESC')->limit($limit, $start)->get("consultations");
		}

        if($q2->num_rows() > 0) {
            foreach($q2->result_array() as $row2) {
                $getParent = $this->db->query("SELECT * FROM `parents_tbl` WHERE parent_id = '".$row2['consultation_parent_id']."'")->result_array();
                $getPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE patient_id = '".$row2['consultation_patient_id']."'")->result_array();

                $array[] = array(
                    'overallId'            => $count,
                    'category'             => 'consultations', //
                    'id'                   => $row2['consultation_id'], //
                    'parent_id'            => $row2['consultation_parent_id'], //
                    'parent_name'         => $getParent[0]['parent_name'], //
                    'patient_id'           => $row2['consultation_patient_id'], //
                    'patient_name'         => $getPatient[0]['patient_name'], //
                    'timestamp'            => $row2['date_consultation'], //
                    'timestamp_end'        => $row2['date_consultation_end'], //
                    'prescription'         => $row2['consultation_prescription'], //
                    'description'          => '',
                    'datetime'             => $row2['date_consultation_datetime'], //
                    'datetime_end'         => $row2['date_consultation_datetime_end'], //
                    'reason'               => $row2['reason'], //
                    'approve_by'           => $row2['consultation_approve_by'], //
                    'approve_selection'    => $row2['consultation_approve_selection'], //
					'interview_id'         => $row2['interview_id'],
                    'googlelink'           => $row2['googlelink'], //
                    'money'                => $row2['money'], //
                    'survey_done'          => $row2['survey_done'], //
                    'status'               => $row2['consultation_status'], //
                    'reference_number'     => $row2['reference_number'],
					'healthHistory'        => $row2['healthHistory'],
                    'anyMedication'        => $row2['anyMedication'],
                    'anyAllergies'         => $row2['anyAllergies']
                );
                $count++;
            }
        }
		
		function sortTime3($a, $b) {
			$a = $a['timestamp'];
			$b = $b['timestamp'];
			if ($a == $b)
			  return 0;
			return ($a < $b) ? -1 : 1;
		}

		usort($array, "sortTime3");
		return $array;
	}
	public function upload_file($encoded_string){
		$target_dir = ''; // add the specific path to save the file
		$decoded_file = base64_decode($encoded_string); // decode the file
		$mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
		$extension = $this->power_model->mime2ext($mime_type); // extract extension from mime type
		$file = uniqid() .'.'. $extension; // rename file as a unique name
		$file_dir = $target_dir . uniqid() .'.'. $extension;
		try {
			file_put_contents($file_dir, $decoded_file); // save
			return $file;
		} catch (Exception $e) {
			return false;
		}
	
	}
	/*
	to take mime type as a parameter and return the equivalent extension
	*/
	public function mime2ext($mime){
		$all_mimes = '{"pdf":["application\/pdf","application\/octet-stream"]}';
		$all_mimes = json_decode($all_mimes,true);
		foreach ($all_mimes as $key => $value) {
			if(array_search($mime,$value) !== false) return $key;
		}
		return false;
	}
	public function esc($val){
        $new = mysqli_real_escape_string($this->db->conn_id, htmlspecialchars($val));
        return $new;
    }
	// medical certificate
	public function get_medical_certificates_count($filter, $date, $doctor_id) {
		if($filter != "") {
			$this->db->where('status', $filter);
		}
		if($date != "") {
			$this->db->where('date_text', $date);
		}
		if($this->session->selection == "doctor") {
			$this->db->where('interview_id', $this->session->id);
		} else {
			if(strlen($doctor_id) != 0) {
				$this->db->where('interview_id', $doctor_id);
			}
		}
		$this->db->order_by("medical_certificates_id", "desc");
        return $this->db->count_all("medical_certificates");
    }
    public function get_medical_certificates($filter, $date, $doctor_id, $limit, $start) {
        $this->db->limit($limit, $start);
		if($filter != "") {
			$this->db->where('status', $filter);
		}
		if($date != "") {
			$this->db->where('date_text', $date);
		}
		if($this->session->selection == "doctor") {
			$this->db->where('interview_id', $this->session->id);
		} else {
			if(strlen($doctor_id) != 0) {
				$this->db->where('interview_id', $doctor_id);
			}
		}
		$this->db->order_by("medical_certificates_id", "desc");
		$q = $this->db->get("medical_certificates");
		$array = array();

		foreach($q->result_array() as $row) {

			$result = $this->power_model->getUserInfoParent($row['parent_id']);


			$result2 = "";
			$name2 = "";
			if($row['approve_selection'] == 1) {
				// doctor
				$result2 = $this->power_model->getUserInfoDoctor($row['approve_by']);
				$name2 = $result2[0]['doctor_name'];
			} else if($row['approve_selection'] == 2) {
				// admin
				$result2 = $this->power_model->getUserInfoAdmin($row['approve_by']);
				$name2 = $result2[0]['admin_name'];
			} else if($row['approve_selection'] == 3) {
				// receptionist
				$result2 = $this->power_model->getUserInfoReceptionist($row['approve_by']);
				$name2 = $result2[0]['receptionist_name'];
			}
			$patient_name = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['patient_id']."'")->result_array();
			$getInfo = (array) $result;
			$newRow = array(
				'medical_certificates_id' => (int) $row['medical_certificates_id'],
				'parent_id' => (int) $row['parent_id'],
				'patient_id' => (int) $row['patient_id'],
				'parent_name' => $getInfo[0]['parent_name'],
				'patient_name' => $patient_name[0]['patient_name'],
				'date_of_consultation' => $row['date_of_consultation'],
				'date_text' => $row['date_text'],
				'money' => $row['money'],
				'reference_number' => $row['reference_number'],
				'purpose' => $row['purpose'],
				'diagnosis' => $row['diagnosis'],
				'treatment_and_recommendation' => $row['treatment_and_recommendation'],
				'approved_by' => $row['approved_by'],
				'approved_selection' => $row['approved_selection'],
				'interview_id' => $row['interview_id'],
				'purpose_doctor' => $row['purpose_doctor'],
				'send_it_to_parent' => $row['send_it_to_parent'],
				'timestamp' => $row['timestamp'],
				'staff_name' => $name2,
				'status' => $row['status']
			);
			$array[] = $newRow;
			
			
		}
		
		return array_reverse($array);
		
    }
	public function cancelMedicalCertificate($data, $id) {
		$q = $this->db->query("SELECT * FROM `medical_certificates` WHERE `medical_certificates_id` = '".$id."'")->result_array();
		$p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$q[0]['parent_id']."'")->result_array();
		$p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$q[0]['patient_id']."'")->result_array();
		$message = "Your medical certification request for ".$p2[0]['patient_name']." has been cancelled!";
		$this->power_model->itexmo($p[0]['parent_phonenumber'], $message);

		$this->db->where('medical_certificates_id', $id);
		$this->db->update('medical_certificates', $data);
	}
	public function doneMedicalCertificate($data, $id) {
		$this->db->where('medical_certificates_id', $id);
		$this->db->update('medical_certificates', $data);
	}
	public function approveMedicalCertificate($data, $id) {
		$this->db->where('medical_certificates_id', $id);
		$this->db->update('medical_certificates', $data);

		$q = $this->db->query("SELECT * FROM `medical_certificates` WHERE `medical_certificates_id` = '".$id."'")->result_array();
		$p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$q[0]['parent_id']."'")->result_array();
		$p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$q[0]['patient_id']."'")->result_array();
		$message = "Your medical certificate request for ".$p2[0]['patient_name']." has been approved!";
		$this->power_model->itexmo($p[0]['parent_phonenumber'], $message);
	}
	// laboratory results
	public function get_laboratory_results_count() {
		if($this->session->selection == "doctor") {
			$this->db->where('interview_id', $this->session->id)->order_by("laboratory_results_id", "desc");
        	return $this->db->count_all("laboratory_results");
		} else {
			$this->db->order_by("laboratory_results_id", "desc");
        	return $this->db->count_all("laboratory_results");
		}
		
    }
    public function get_laboratory_results($limit, $start) {
        $this->db->limit($limit, $start);
		$this->db->order_by("laboratory_results_id", "desc");
		$q = $this->db->get("laboratory_results");
		$array = array();

		foreach($q->result_array() as $row) {

			$result = $this->power_model->getUserInfoParent($row['parent_id']);
			$patient_name = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['patient_id']."'")->result_array();
			$getInfo = (array) $result;
			$newRow = array(
				'laboratory_results_id' => (int) $row['laboratory_results_id'],
				'parent_id' => (int) $row['parent_id'],
				'parent_name' => $getInfo[0]['parent_name'],
				'patient_id' => (int) $row['patient_id'],
				'patient_name' => $patient_name[0]['patient_name'],
				'date' => $row['date'],
				'type_of_laboratory' => $row['type_of_laboratory'],
				'file' => $row['file'],
				'timestamp' => $row['timestamp']
			);
			$array[] = $newRow;
			
			
		}
		
		return array_reverse($array);
		
    }

	// tabular_reports
	public function get_tabular_reports_count($type) {
		if($type == "") {
			if($this->session->selection == "doctor") {
				$this->db->where('interview_id', $this->session->id)->order_by("consultation_id", "desc");
        		return $this->db->count_all("consultations");
			} else {
				$this->db->order_by("consultation_id", "desc");
				return $this->db->count_all("consultations");
			}
		} else if($type == "appointments") {
			if($this->session->selection == "doctor") {
				$this->db->where('interview_id', $this->session->id)->order_by("appointment_id", "desc");
        		return $this->db->count_all("appointments");
			} else {
				$this->db->order_by("appointment_id", "desc");
        		return $this->db->count_all("appointments");
			}
		} else if($type == "number_of_patients") {
			if($this->session->selection == "doctor") {
				$this->db->where('interview_id', $this->session->id)->order_by("patient_id", "desc");
        		return $this->db->count_all("patients_tbl");
			} else {
				$this->db->order_by("patient_id", "desc");
        		return $this->db->count_all("patients_tbl");
			}
		} else if($type == "patient_satisfactions") {
			if($this->session->selection == "doctor") {
				$this->db->where('interview_id', $this->session->id)->order_by("survey_id", "desc");
				return $this->db->count_all("surveys_tbl");
			} else {
				$this->db->order_by("survey_id", "desc");
				return $this->db->count_all("surveys_tbl");
			}
		} else if($type == "immunizations") {
			if($this->session->selection == "doctor") {
				$this->db->where('interview_id', $this->session->id)->order_by("immunization_record_id", "desc");
        		return $this->db->count_all("immunization_record");
			} else {
				$this->db->order_by("immunization_record_id", "desc");
        		return $this->db->count_all("immunization_record");
			}
		}
		
    }
    public function get_tabular_reports($type, $limit, $start) {
		if($type == "") {
			$this->db->limit($limit, $start);
			if($this->session->selection == "doctor") {
				$this->db->where('interview_id', $this->session->id);
			}
			$this->db->order_by("consultation_id", "desc");
			$q = $this->db->get("consultations");
			$array = array();

			foreach($q->result_array() as $row) {

				$result = $this->power_model->getUserInfoParent($row['consultation_parent_id']);
				$patient_name = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['consultation_patient_id']."'")->result_array();
				$getInfo = (array) $result;
				$newRow = array(
					'parent_name' => $getInfo[0]['parent_name'],
					'patient_name' => $patient_name[0]['patient_name'],
					'datetime' => $row['date_consultation_datetime'],
					'datetime_end' => $row['date_consultation_datetime_end'],
					'status' => $row['consultation_status'],
				);
				$array[] = $newRow;
				
				
			}
			
			return array_reverse($array);
		} else if($type == "appointments") {
			$this->db->limit($limit, $start);
			if($this->session->selection == "doctor") {
				$this->db->where('interview_id', $this->session->id);
			}
			$this->db->order_by("appointment_id", "desc");
			$q = $this->db->get("appointments");
			$array = array();

			foreach($q->result_array() as $row) {

				$result = $this->power_model->getUserInfoParent($row['appointment_parent_id']);
				$patient_name = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['appointment_patient_id']."'")->result_array();
				$getInfo = (array) $result;
				$newRow = array(
					'parent_name' => $getInfo[0]['parent_name'],
					'patient_name' => $patient_name[0]['patient_name'],
					'datetime' => $row['appointment_datetime'],
					'datetime_end' => $row['appointment_datetime_end'],
					'status' => $row['appointment_status'],
				);
				$array[] = $newRow;
				
				
			}
			
			return array_reverse($array);
		} else if($type == "number_of_patients") {
			$this->db->limit($limit, $start);
			if($this->session->selection == "doctor") {
				$this->db->where('interview_id', $this->session->id);
			}
			$this->db->order_by("patient_id", "desc");
			$q = $this->db->get("patients_tbl");
			$array = array();

			foreach($q->result_array() as $row) {

				$patient_name = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['patient_id']."'")->result_array();
				$services = "";


				$appointmentQuery = $this->db->query("SELECT * FROM `appointments` WHERE appointment_patient_id = '".$row['patient_id']."'");
				if($appointmentQuery->num_rows() > 0) {
					$services .= "Appointment, ";
				}
				$consultationQuery = $this->db->query("SELECT * FROM `consultations` WHERE consultation_patient_id = '".$row['patient_id']."'");
				if($consultationQuery->num_rows() > 0) {
					$services .= "Online Consultation, ";
				}
				$consultationQuery = $this->db->query("SELECT * FROM `immunization_record` WHERE patient_id = '".$row['patient_id']."'");
				if($consultationQuery->num_rows() > 0) {
					$services .= "Immunization, ";
				}

				if($services == "") {
					$services = "No services.";
				}
				$newRow = array(
					'patient_name' => $patient_name[0]['patient_name'],
					'services' => $services
				);
				$array[] = $newRow;
				
				
			}
			
			return array_reverse($array);
		} else if($type == "patient_satisfactions") {
			$this->db->limit($limit, $start);
			if($this->session->selection == "doctor") {
				$this->db->where('interview_id', $this->session->id);
			}
			$this->db->order_by("survey_id", "desc");
			$q = $this->db->get("surveys_tbl");
			$array = array();

			foreach($q->result_array() as $row) {
				$hehe = $this->db->query("SELECT COUNT(*) as NUM FROM surveys_tbl GROUP BY `answer_one`")->result_array();
				$hehe2 = $this->db->query("SELECT COUNT(*) as NUM FROM surveys_tbl GROUP BY `answer_two`")->result_array();

				$haha = $this->db->query("SELECT * FROM surveys_tbl")->num_rows();
				$haha2 = $this->db->query("SELECT * FROM surveys_tbl")->num_rows();

				$array[] = array(
					'question' => 'How would you rate this service?',
					'answer' => $row['answer_one'],
					'percentage' => ($hehe[0]['NUM']/$haha)*100
				);
				$array[] = array(
					'question' => 'Would you recommend this app to others?',
					'answer' => $row['answer_two'],
					'percentage' => ($hehe2[0]['NUM']/$haha2)*100
				);
				if($row['answer_three'] != "null" && $row['answer_three'] != "") {
					$array[] = array(
						'question' => 'Feedback',
						'answer' => $row['answer_three'],
						'percentage' => ''
					);
				}
				
			}
			
			return array_reverse($array);
		} else if($type == "immunizations") {
			$this->db->limit($limit, $start);
			if($this->session->selection == "doctor") {
				$this->db->where('interview_id', $this->session->id);
			}
			$this->db->order_by("immunization_record_id", "desc");
			$q = $this->db->get("immunization_record");
			$array = array();

			foreach($q->result_array() as $row) {
				$vaccine_name = $this->db->query("SELECT * FROM `vaccine_terms_tbl` WHERE `vaccine_terms_id` = '".$row['vaccine_id']."'")->result_array();
				$patient_name = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['patient_id']."'")->result_array();
				$parent_name = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$row['parent_id']."'")->result_array();
				$array[] = array(
					'patient_name' => $patient_name[0]['patient_name'],
					
					'date' => $row['date'],
					'vaccine_name' => $vaccine_name[0]['vaccine_terms_title'],
					'route'	=> $row['route'],
					'parent_name' => $parent_name[0]['parent_name'],
				);
				
			}
			
			return array_reverse($array);
		}
		
    }
}
?>
