<?php
namespace App\Models;

use CodeIgniter\Model;

class Admin_model extends Model {
    public function can_login($user_name, $password) {
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
		
    }
}
?>