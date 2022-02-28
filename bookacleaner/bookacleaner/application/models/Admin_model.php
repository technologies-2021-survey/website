<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {
    public function login($username, $password) {
        $this->db->where('username', $username);
        $query = $this->db->get('accounts');

        if($query->num_rows() > 0) {
            // success
            foreach($query->result() as $row) { // row of this data
                if($row->password == md5($password)) {
                    $this->session->set_userdata('id', $row->id); // setup session
                    return 'Success';
                } else {
                    return 'Error! Wrong password!';
                }
            }
        } else {
            // error
            return 'Error! That user is not exist!';
        }
    }

    public function status($status = "", $message = "") {
		$array = array(
			'status' => $status,
			'message' => $message
		);
		return json_encode($array);
	}
}
?>