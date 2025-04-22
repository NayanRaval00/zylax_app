<?php

namespace App\Controllers\Admin;

use App\Models\AdminModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/home');
        }

        $data = array(
            'title' => 'Login Page',
            'logo' => get_settings('logo')
        );

        // echo "<pre>"; 
        // print_r($data);
        // exit;

        return view('admin/login', $data);
    }

    public function loginProcess()
    {
        $session = session();
        $model = new AdminModel();
        $identity = $this->request->getPost('identity');
        $password = $this->request->getPost('password');
        
        $user = $model->where('username', $identity)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            $session->set('isLoggedIn', true);
            $session->set('username', $user['username']);
            return redirect()->to('/admin/home');
        } else {
            $session->setFlashdata('msg', 'Invalid username or password');
            return redirect()->to('/admin/login');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/admin/login');
    }
}