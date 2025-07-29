<?php

namespace Ostap\Gate\Controllers;

use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        return view('Ostap\Gate\Views\login_form');
    }
}
