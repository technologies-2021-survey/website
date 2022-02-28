<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function index()
    {
        $this->load->model('admin_model', null, true);

        $data = [
            'title' => 'Login',
            'file' => 'login',
        ];
        return view('Admin/index.php', $data);
    }
    public function login($username = "", $password = "") {
        $this->load->model('admin_model', null, true);

        $this->form_validation->set_rules('user_name', 'Username', 'required|alpha_numeric|min_length[5]', 
            array(
                "min_length" => "Your username is incorrect."
            )
        );
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]', 
            array(
                "min_length" => "Your password is incorrect."
            )
        );

        if($this->form_validation->run() == FALSE) {
            $array = array(
                'status' => 204,
                'messages' => 'Error! You must input your username and password!'
            );
            return json_encode($array);
        } else {
            $result = $this->login_model->can_login($this->input->post('user_name'), $this->input->post('password'));
			if($result == '') {
                redirect('admin/main');
			} else {
				redirect('login?error='.$result);
			}
        }
    }
}
