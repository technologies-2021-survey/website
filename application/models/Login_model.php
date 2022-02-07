<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
    public function itexmo($number, $message){
		$ch = curl_init();
		$itexmo = array('1' => $number, '2' => $message, '3' => 'ST-MICHA378601_38DKZ', 'passwd' => ')2hcz6prbn');
		curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
		curl_setopt($ch, CURLOPT_POST, 1);
		 curl_setopt($ch, CURLOPT_POSTFIELDS, 
		          http_build_query($itexmo));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		return curl_exec ($ch);
		curl_close ($ch);
    }
	public function getCurrentOTP($id, $selection) {
        if($selection == "doctor") {
            $this->db->where('doctor_id', $id);
			$query = $this->db->get('doctors_tbl');

			foreach($query->result() as $data) {
				return $data->doctor_sms_code;
			}

        } else if($selection == "receptionist") {
			$this->db->where('receptionist_id', $id);
			$query = $this->db->get('receptionists_tbl');

			foreach($query->result() as $data) {
				return $data->receptionist_sms_code;
			}
        } else if($selection == "administrator") {
            $this->db->where('admin_id', $id);
			$query = $this->db->get('admins_tbl');

			foreach($query->result() as $data) {
				return $data->admin_sms_code;
			}
        }
    }
	public function can_login($user_name, $password, $selection) {
		if($selection == "doctor") {
			$this->db->where('doctor_username', $user_name);
			$query = $this->db->get('doctors_tbl');

			if($query->num_rows() > 0) {
				// success
				foreach($query->result() as $row) { // row of this data
					if($row->doctor_password == md5($password)) {
						$otps = mt_rand(111111,999999);

						$copyOtp = $otps;
						
						$this->login_model->itexmo($row->doctor_phonenumber, $copyOtp . ' is your OTP. Please enter this to confirm your login.');
						
						$this->db->set('doctor_sms_code', $copyOtp);
						$this->db->set('active', '1');
						$this->db->set('login_timeout', time()+36000); // 10 hours
						$this->db->where('doctor_id', $row->doctor_id);
						$this->db->update('doctors_tbl');

						$this->session->set_userdata('id', $row->doctor_id); // setup session
						$this->session->set_userdata('selection', $selection); // setup session
						return '';
					} else {
						return 'WrongPassword';
					}
				}
			} else {
				// error
				return 'ReturnUsername';
			}
		} else if($selection == "receptionist") {
			$this->db->where('receptionist_username', $user_name);
			$query = $this->db->get('receptionists_tbl');

			if($query->num_rows() > 0) {
				// success
				foreach($query->result() as $row) { // row of this data
					if($row->receptionist_password == md5($password)) {
						$otps = mt_rand(111111,999999);
						$copyOtp = $otps;
						$this->login_model->itexmo($row->receptionist_phonenumber, $copyOtp . ' is your OTP. Please enter this to confirm your login.');

						
						$this->db->set('receptionist_sms_code', $copyOtp);
						$this->db->set('active', '1');
						$this->db->set('login_timeout', time()+36000); // 10 hours
						$this->db->where('receptionist_id', $row->receptionist_id);
						$this->db->update('receptionists_tbl');

						$this->session->set_userdata('id', $row->receptionist_id); // setup session
						$this->session->set_userdata('selection', $selection); // setup session
						return '';
					} else {
						return 'WrongPassword';
					}
				}
			} else {
				// error
				return 'ReturnUsername';
			}
		} else if($selection == "administrator") {
			$this->db->where('admin_username', $user_name);
			$query = $this->db->get('admins_tbl');

			if($query->num_rows() > 0) {
				// success
				foreach($query->result() as $row) { // row of this data
					if($row->admin_password == md5($password)) {
						$otps = mt_rand(111111,999999);
						$copyOtp = $otps;
						$this->login_model->itexmo($row->admin_phonenumber, $copyOtp . ' is your OTP. Please enter this to confirm your login.');

						
						$this->db->set('admin_sms_code', $copyOtp);
						$this->db->set('active', '1');
						$this->db->set('login_timeout', time()+36000); // 10 hours
						$this->db->where('admin_id', $row->admin_id);
						$this->db->update('admins_tbl');

						$this->session->set_userdata('id', $row->admin_id); // setup session
						$this->session->set_userdata('selection', $selection); // setup session
						return '';
					} else {
						return 'WrongPassword';
					}
				}
			} else {
				// error
				return 'ReturnUsername';
			}
		} else {
			redirect("home");
		}
		
	}
	public function esc($val){
        $new = mysqli_real_escape_string($this->db->conn_id, htmlspecialchars($val));
        return $new;
    }
}

?>