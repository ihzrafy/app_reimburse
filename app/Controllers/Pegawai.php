<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Pegawai extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Pegawai Dashboard'
        ];
        return view('pegawai/dashboard', $data);
    }
}
