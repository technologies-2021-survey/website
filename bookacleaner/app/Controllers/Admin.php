<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Login',
            'file' => 'login',
        ];
        return view('Admin/index.php', $data);
    }
}
