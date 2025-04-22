<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    /**
     * User Registration
     */
    public function register()
    {
        $checkMethod = $this->checkRequestMethod();
        
        if($checkMethod)
        {
            return $checkMethod;
        }

        $validation = \Config\Services::validation();

        // Define validation rules
        $rules = [
            'fname' => 'required|alpha_space|min_length[2]|max_length[50]',
            'lname' => 'required|alpha_space|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'mobile' => 'required|numeric|exact_length[10]',
            'password' => 'required|min_length[6]|max_length[20]',
            'confirmPassword' => 'required|matches[password]',
            'termsAgree' => 'required'
        ];

        // Custom error messages
        $messages = [
            'email' => [
                'is_unique' => 'This email is already registered.',
                'valid_email' => 'Please enter a valid email address.'
            ],
            'confirmPassword' => [
                'matches' => 'Password and Confirm Password do not match.'
            ],
            'termsAgree' => [
                'required' => 'You must agree to the terms and privacy policy.'
            ]
        ];

        $validation->setRules($rules, $messages);

        // Validate input
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ]);
        }

        // Prepare user data for insertion
        $userData = [
            "ip_address" => $this->request->getIPAddress(),
            'username' => $this->request->getPost('email'),
            'fname' => $this->request->getPost('fname'),
            'lname' => $this->request->getPost('lname'),
            'email' => $this->request->getPost('email'),
            'mobile' => $this->request->getPost('mobile'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'created_on' => time(),
            'active' => 1,
        ];

        // Save user data
        if ($userid = $this->userModel->insert($userData)) {
            $this->session->set('user_id', $userid);
            // Send Registration Email
            $emailHelper = new \App\Libraries\EmailHelper();
            $emailHelper->sendRegistrationEmail($userData['email'], $userData['username']);

            return $this->response->setJSON(['status' => 'success', 'message' => 'User registered successfully.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Registration failed.']);
        }
    }


    public function checkRequestMethod($type="post")
    {
        if (strtolower($this->request->getMethod()) !== $type) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Method Not Allowed'
            ])->setStatusCode(405); // 405 - Method Not Allowed
        }
    }

    /**
     * User Login
     */
    public function login()
    {
        $checkMethod = $this->checkRequestMethod();
        
        if($checkMethod)
        {
            return $checkMethod;
        }

        $emailOrUsername = $this->request->getPost('email_or_username');
        $password = $this->request->getPost('password');

        $validationRules = [
            'email_or_username' => 'required',
            'password' => 'required|min_length[6]'
        ];
    
        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ]);
        }

        $user = $this->userModel->getUserByEmailOrUsername($emailOrUsername);

        if ($user && password_verify($password, $user['password'])) {

            $unique_cart_token = "zy_" . uniqid(mt_rand(), true);
            $this->session->set('user_id', $user['id']);
            $this->session->set('user_name', $user['fname']." ".$user['lname']);
            $this->session->set('user_email', $user['email']);
            if (!empty($user['image']) && filter_var(base_url($user['image']), FILTER_VALIDATE_URL)) {
                $this->session->set('user_image', base_url($user['image']));
            } else {
                $this->session->remove('user_image'); // Remove session if image is invalid
            }
            $this->session->set('logged_in', true);  
            return $this->response->setJSON(['status' => 'success', 'message' => 'Login successful.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid email/username or password.']);
        }
    }

    /**
     * Forgot Password
     */
    public function forgotPassword()
    {
        $checkMethod = $this->checkRequestMethod();
        
        if($checkMethod)
        {
            return $checkMethod;
        }

        $email = $this->request->getPost('email');
        $validationRules = [
            'email' => 'required|valid_email',
        ];

    
        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ]);
        }

        $user = $this->userModel->where('email', $email)->first();
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email not found.']);
        }

        $token = bin2hex(random_bytes(50)); // Generate secure token
        $this->userModel->update($user['id'], ['forgotten_password_code' => $token]);

        // Send email using the helper
        $emailHelper = new \App\Libraries\EmailHelper();
        if ($emailHelper->sendForgotPasswordEmail($email, $token)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Password reset link sent.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to send email.']);
        }
    }

    
    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        session()->destroy(); // Destroy the session
        return redirect()->to('/')->with('message', 'Logged out successfully.');
    }

    public function resetPasswordForm($token)
    {
        $userModel = new UserModel();
        $user = $userModel->where('forgotten_password_code', $token)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Invalid or expired reset link.');
        }

        return view('frontend/reset_password', ['token' => $token]);
    }

    public function resetPasswordSubmit()
    {
        $userModel = new UserModel();
        $validationRules = [
            'token' => 'required',
            'newpassword' => 'required|min_length[6]|max_length[20]|regex_match[/^(?=.*[A-Z])(?=.*\d).{8,}$/]',
            'confirmpassword' => 'required|matches[newpassword]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $token = $this->request->getPost('token');
        $user = $userModel->where('forgotten_password_code', $token)->first();

        if (!$user) {
            return redirect()->to('/')->with('error', 'Invalid or expired reset link.');
        }

        $newPassword = $this->request->getPost('newpassword');
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password and clear the token
        $userModel->update($user['id'], ['password' => $hashedPassword, 'forgotten_password_code' => null]);
        
        $emailHelper = new \App\Libraries\EmailHelper();
        if (!$emailHelper->sendNewPassword($user["email"], $user, $newPassword)) {
            return redirect()->back()->with('errors', ['email' => 'Failed to send email notification.']);
        }

        return redirect()->to('/thankyou')->with('success', 'Password changed successfully. You can now log in.');
    }

}
