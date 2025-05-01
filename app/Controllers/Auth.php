<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\Product_Cart;

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
        if ($checkMethod) {
            return $checkMethod;
        }

        $existingUser = $this->userModel
            ->where('email', $this->request->getPost('email'))
            ->first();

        if ($existingUser) {
            $isGuest = strtolower($existingUser['user_type'] ?? '') === 'guest';
            $hasNoPassword = empty($existingUser['password']);

            if ($isGuest && $hasNoPassword) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'This email ID is already associated with a guest user. To convert it to a regular account, please reset your password using the same email ID through the Forgot Password option.'
                ]);
            }
        }
    
        $validation = \Config\Services::validation();
    
        // Rules & Messages
        $rules = [
            'fname' => 'required|alpha_space|min_length[2]|max_length[50]',
            'lname' => 'required|alpha_space|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'mobile' => 'required|numeric|exact_length[10]',
            'password' => 'required|min_length[6]|max_length[20]',
            'confirmPassword' => 'required|matches[password]',
            'termsAgree' => 'required'
        ];
    
        $messages = [
            'fname' => [
                'required' => 'First name is required.',
                'alpha_space' => 'First name should contain only letters and spaces.'
            ],
            'lname' => [
                'required' => 'Last name is required.',
                'alpha_space' => 'Last name should contain only letters and spaces.'
            ],
            'email' => [
                'required' => 'Email is required.',
                'valid_email' => 'Please enter a valid email address.',
                'is_unique' => 'This email is already registered.'
            ],
            'mobile' => [
                'required' => 'Mobile number is required.',
                'numeric' => 'Only numbers are allowed.',
                'exact_length' => 'Mobile number must be 10 digits.'
            ],
            'password' => [
                'required' => 'Password is required.',
                'min_length' => 'Password must be at least 6 characters.'
            ],
            'confirmPassword' => [
                'required' => 'Please confirm your password.',
                'matches' => 'Password and Confirm Password do not match.'
            ],
            'termsAgree' => [
                'required' => 'You must agree to the terms and privacy policy.'
            ]
        ];
    
        $validation->setRules($rules, $messages);
    
        // Run validation
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $validation->getErrors()
            ]);
        }
    
        // Data prep
        $userData = [
            'ip_address' => $this->request->getIPAddress(),
            'username' => $this->request->getPost('email'),
            'fname' => $this->request->getPost('fname'),
            'lname' => $this->request->getPost('lname'),
            'email' => $this->request->getPost('email'),
            'mobile' => $this->request->getPost('mobile'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'created_on' => time(),
            'active' => 1,
            'company' => $this->request->getPost('companyname')
        ];
    
        // Insert user
        if ($userid = $this->userModel->insert($userData)) {
            $this->session->set('user_id', $userid);
    
            // Send registration email
            $emailHelper = new \App\Libraries\EmailHelper();
            $emailHelper->sendRegistrationEmail($userData['email'], $userData['username']);
    
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User registered successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Registration failed. Please try again.'
            ]);
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

                    // Check if there's a guest cart to merge
            if ($this->session->has('guest_id')) {
                $guestId = $this->session->get('guest_id');
                // Merge guest cart into the user's cart
                $this->merge_guest_cart_to_user($user['id'], $guestId);

                // Remove the guest_id from session after merging
                $this->session->remove('guest_id');
            }
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
        $confirmed = $this->request->getPost('confirmed'); // ✅ Get confirmed value from POST
        $validationRules = [
            'email' => 'required|valid_email',
        ];

    
        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ]);
        }

        $user = $this->userModel->select('id, password, user_type')->where('email', $email)->first();
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email not found.']);
        }elseif(empty($user['password']) && $user['user_type'] === 'guest' && !$confirmed){
            return $this->response->setJSON(['status' => 'guest_user', 'message' => 'we have guest order with this email account. Would you like to continue  to register yourself as a regular customer?']);
        }

        $token = bin2hex(random_bytes(50)); // Generate secure token

        if ($confirmed) {
            $user_type = $this->request->getPost('user_type'); 
            $active = $this->request->getPost('active'); 
            
            $updateData = [
                'forgotten_password_code' => $token,
                'active' => $active,
                'user_type' => $user_type
            ];
            
            $this->userModel->update($user['id'], $updateData);
        } else {
            $this->userModel->update($user['id'], ['forgotten_password_code' => $token]);
        }
        

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

    // Function to merge guest cart with logged-in cart (called when user logs in)
    public function merge_guest_cart_to_user($userId, $guestId)
    {
        $cartModel = new Product_Cart();
    
        // Get all guest cart items
        $guestItems = $cartModel->getCartItemsByGuestId($guestId);
    
        foreach ($guestItems as $guestItem) {
            // Check if the product already exists for the user
            $existing = $cartModel->checkExistingCartItem($userId, null, $guestItem['product_id'], $guestItem['configuration_hash']);
    
            if ($existing) {
                // If it exists, update quantity
                $newQuantity = $existing['quantity'] + $guestItem['quantity'];
                $newPrice = $guestItem['product_price'] * $newQuantity;
    
                $cartModel->update($existing['id'], [
                    'quantity' => $newQuantity,
                    'product_price' => $newPrice
                ]);
            } else {
                // Otherwise, insert the guest item into the user cart
                $cartModel->insert([
                    'user_id' => $userId,
                    'guest_userid' => null, // no guest user ID for logged-in users
                    'product_id' => $guestItem['product_id'],
                    'product_name' => $guestItem['product_name'],
                    'product_price' => $guestItem['product_price'] * $guestItem['quantity'],
                    'cat_id' => $guestItem['cat_id'],
                    'quantity' => $guestItem['quantity'],
                    'configuration' => $guestItem['configuration'],
                    'configuration_hash' => $guestItem['configuration_hash']
                ]);
            }
    
            // Optionally, you can remove the guest cart items after merging
            $cartModel->deleteCartItem($guestItem['id']);
        }
    
        return true;
    }

}
