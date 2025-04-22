<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Facebook\Facebook;

class FacebookAuth extends Controller
{
    protected $facebook;

    public function __construct()
    {
        session();
        
        $this->facebook = new Facebook([
            'app_id'     => getenv('FACEBOOK_APP_ID'),
            'app_secret' => getenv('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v18.0',
        ]);
    }

    public function login()
    {
        $helper = $this->facebook->getRedirectLoginHelper();
        
        // Ensure session is started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $permissions = ['email'];
        $loginUrl = $helper->getLoginUrl(getenv('FACEBOOK_REDIRECT_URI'), $permissions);

        return redirect()->to($loginUrl);
    }

    public function callback()
    {
        $helper = $this->facebook->getRedirectLoginHelper();


        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        try {
            $accessToken = $helper->getAccessToken();
            if (!$accessToken) {
                return redirect()->to('/')->with('error', 'Facebook login failed.');
            }

            $response = $this->facebook->get('/me?fields=id,name,email', $accessToken);
            $user = $response->getGraphUser();

            //echo "<pre>"; print_r($user); exit;

            // Extract user details
            $email = $user->getField('email');
            $fullName = $user->getField('name');
            $nameParts = explode(' ', $fullName, 2);
            $fname = $nameParts[0] ?? ''; // First Name
            $lname = $nameParts[1] ?? ''; // Last Name (if available)

            // Load UserModel
            $userModel = new \App\Models\UserModel();

            // Check if the user already exists in the database
            $existingUser = $userModel->where('email', $email)->first();

            if (!$existingUser) {
                // Insert new user into the database
                $userData = [
                    "ip_address" => $this->request->getIPAddress(),
                    'email'  => $email,
                    'username' => $email,
                    'fname'  => $fname,
                    'lname'  => $lname,
                    'status' => 1, // Active user
                ];
                $userId = $userModel->insert($userData, true); // Get the inserted user ID
            } else {
                $userId = $existingUser['id']; // Get existing user ID
            }

            // Set session with database user ID
            session()->set([
                'user_id'    => $userId, // Database user ID
                'user_name'  => $fullName,
                'user_email' => $email,
                'logged_in'  => true
            ]);

            if (!empty($existingUser['image']) && filter_var(base_url($existingUser['image']), FILTER_VALIDATE_URL)) {
                session()->set('user_image', base_url($existingUser['image']));
            } else {
                session()->remove('user_image'); // Remove session if image is invalid
            }


            return redirect()->to('/auth/my-account')->with('success', 'Logged in successfully!');
        } catch (\Exception $e) {
            //die($e->getMessage());
            return redirect()->to('/')->with('error', 'Authentication error: ' . $e->getMessage());
        }
    }
}
