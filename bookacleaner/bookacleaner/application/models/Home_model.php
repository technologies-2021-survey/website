<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {
	
	public function insertInquiries($array) {
		$this->db->insert('inquiries_tbl', $array);
	}
	
	
	public function getCode($email,$selection) {
	    $q = "";
	    if($selection == "doctor") {
	        $q = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_emailaddress` = '".$email."'"); 
	    } else if($selection == "administrator") {
	        $q = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_emailaddress` = '".$email."'"); 
	    } else if($selection == "receptionist") {
	        $q = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_emailaddress` = '".$email."'");
	    }
		
		$userInfo = '';
		foreach($q->result() as $gettingInfo) {
			$userInfo = $gettingInfo;
		}
		
		if($selection == "doctor") {
		    return $userInfo->doctor_forgot_code;
		} else if($selection == "administrator") {
		    return $userInfo->admin_forgot_code;
		} else if($selection == "receptionist") {
		    return $userInfo->receptionist_forgot_code;
		} 
	}
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
	public function getCodeFromUsername($username,$selection) {
	    $q = "";
	    if($selection == "doctor") {
	        $q = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_username` = '".$username."'"); 
	    } else if($selection == "administrator") {
	        $q = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_username` = '".$username."'"); 
	    } else if($selection == "receptionist") {
	        $q = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_username` = '".$username."'");
	    }
	    
	    $userInfo = '';
		foreach($q->result() as $gettingInfo) {
			$userInfo = $gettingInfo;
		}
		if($selection == "doctor") {
		    return $userInfo->doctor_forgot_code;
		} else if($selection == "administrator") {
		    return $userInfo->admin_forgot_code;
		} else if($selection == "receptionist") {
		    return $userInfo->receptionist_forgot_code;
		}
	}
	public function getEmailToUsername($email,$selection) {
	    $q = "";
	    if($selection == "doctor") {
	        $q = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_emailaddress` = '".$email."'"); 
	    } else if($selection == "administrator") {
	        $q = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_emailaddress` = '".$email."'"); 
	    } else if($selection == "receptionist") {
	        $q = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_emailaddress` = '".$email."'");
	    }
	    
		$userInfo = '';
		foreach($q->result() as $gettingInfo) {
			$userInfo = $gettingInfo;
		}
		if($selection == "doctor") {
		    return $userInfo->doctor_username;
		} else if($selection == "administrator") {
		    return $userInfo->admin_username;
		} else if($selection == "receptionist") {
		    return $userInfo->receptionist_username;
		} 
	}
	public function forgotpassword($email,$selection) {
	    $q = "";
	    if($selection == "doctor") {
	        $this->db->where('doctor_emailaddress', $email);
		    $query = $this->db->get('doctors_tbl');
	    } else if($selection == "administrator") {
	        $this->db->where('doctor_emailaddress', $email);
		    $query = $this->db->get('admins_tbl');
	    } else if($selection == "receptionist") {
	        $this->db->where('receptionist_emailaddress', $email);
		    $query = $this->db->get('receptionists_tbl');
	    }
		

		if($query->num_rows() > 0) {
		    if($selection == "doctor") {
    			// success
    			$randomNumber = rand(100000,999999);
    			$finalRandom = $randomNumber; // get this code.
    			$this->db->where('doctor_emailaddress', $email);
    			$this->db->set('doctor_forgot_code', $finalRandom);
    			$this->db->update('doctors_tbl');
    			return 'Successfully';
		    } else if($selection == "administrator") {
    			// success
    			$randomNumber = rand(100000,999999);
    			$finalRandom = $randomNumber; // get this code.
    			$this->db->where('admin_emailaddress', $email);
    			$this->db->set('admin_forgot_code', $finalRandom);
    			$this->db->update('admins_tbl');
    			return 'Successfully';
		    } else if($selection == "receptionist") {
    			// success
    			$randomNumber = rand(100000,999999);
    			$finalRandom = $randomNumber; // get this code.
    			$this->db->where('receptionist_emailaddress', $email);
    			$this->db->set('receptionist_forgot_code', $finalRandom);
    			$this->db->update('receptionists_tbl');
    			return 'Successfully';
		    } 
		} else {
			// error
			return 'Error';
		}
	}
	public function removeCode($username, $selection) {
	    if($selection == "doctor") {
    		$this->db->where('doctor_username', $username);
    		$this->db->set('doctor_forgot_code', '');
    		$this->db->update('doctors_tbl');
	    } else if($selection == "administrator") {
    		$this->db->where('admin_username', $username);
    		$this->db->set('admin_forgot_code', '');
    		$this->db->update('admins_tbl');
	    } else if($selection == "receptionist") {
    		$this->db->where('receptionist_username', $username);
    		$this->db->set('receptionist_forgot_code', '');
    		$this->db->update('receptionists_tbl');
	    }
	}
	public function updatePassword($username, $password, $selection) {
	    if($selection == "doctor") {
    		$this->db->where('doctor_username', $username);
    		$this->db->set('doctor_password', $password);
    		$this->db->update('doctors_tbl');
	    } else if($selection == "administrator") {
    		$this->db->where('admin_username', $username);
    		$this->db->set('admin_password', $password);
    		$this->db->update('admins_tbl');
	    } else if($selection == "receptionist") {
    		$this->db->where('receptionist_username', $username);
    		$this->db->set('receptionist_password', $password);
    		$this->db->update('receptionists_tbl');
	    }
	}
	public function esc($val){
        $new = mysqli_real_escape_string($this->db->conn_id, htmlspecialchars($val));
        return $new;
    }
}