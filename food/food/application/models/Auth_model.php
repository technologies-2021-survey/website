<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
    var $client_service = "cafelidia-client";
    var $auth_key = "cafelidiaApi!@";

    public function check_auth() {
        $client_service = $this->input->get_request_header("Client-Service", TRUE);
        $auth_key = $this->input->get_request_header("Auth-Key", TRUE);

        if($client_service == $this->client_service && $auth_key == $this->auth_key) { 
            return true;
        } else {
            return json_output(
                401,
                array(
                    'status' => 401,
                    'message' => 'Unauthorized.'
                )
            );
        }
    }
    public function login($username, $password) {
        $q = $this->db->select('id, username, password')->from("customer_accounts")->where(
            array(
                'username' => $username, 
                'password' => md5($password))
            )->get()->row();
        if($q == "") {
            return array(
                'status' => 204,
                'message' => "Invalid username/password."
            );
        } else {
            return array(
                'status' => 200,
                'message' => "Successfully login!",
                'full_name' => $q->full_name,
                'username' => $username,
                'customer_id' => $q->id
            );
        }
    }

    public function staff_login($username, $password) {
        $q = $this->db->select('id, full_name, username, password')->from("accounts")->where(
            array(
                'username' => $username, 
                'password' => md5($password))
            )->get()->row();
        if($q == "") {
            return array(
                'status' => 204,
                'message' => "Invalid username/password."
            );
        } else {
            return array(
                'status' => 200,
                'message' => "Successfully login!",
                'username' => $username,
                'full_name' => $q->full_name,
                'id' => $q->id,
                'account_level' => $q->account_level,
            );
        }
    }
    public function test() {
        echo'haha';
    }
}
?>