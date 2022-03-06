<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Manila");

class Auth extends CI_Controller {
    public function index() {
        json_output(
            400,
            array(
                "status" => 400,
                "message" => 'Bad Request'
            )
        );
    }
    public function login() {
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $username = $params['username'];
                $password = $params['password'];

                $response = $this->auth_model->login($username, $password);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function staff_login() {
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $username = $params['username'];
                $password = $params['password'];

                $response = $this->auth_model->staff_login($username, $password);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
}
?>