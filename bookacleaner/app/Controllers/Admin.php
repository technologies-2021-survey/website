<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function index()
    {
        $data = [
            'file' => 'login',
        ];
        return view('Admin/index', $data);
    }
}
