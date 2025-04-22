<?php 

namespace App\Controllers;

use Google\Client as GoogleClient;
use Google\Service\Oauth2;
use App\Models\UserModel;
use App\Models\NewsletterModel;
use CodeIgniter\Controller;


class GoogleAuth extends Controller
{
    public function login()
    {
        $googleConfig = \App\Config\Google::getClientConfig();
        $client = new GoogleClient();
        $client->setClientId($googleConfig['client_id']);
        $client->setClientSecret($googleConfig['client_secret']);
        $client->setRedirectUri($googleConfig['redirect_uri']);
        $client->addScope($googleConfig['scopes']);

        return redirect()->to($client->createAuthUrl());
    }

    public function callback()
    {
        $googleConfig = \App\Config\Google::getClientConfig();
        $client = new GoogleClient();
        $client->setClientId($googleConfig['client_id']);
        $client->setClientSecret($googleConfig['client_secret']);
        $client->setRedirectUri($googleConfig['redirect_uri']);
        $client->addScope($googleConfig['scopes']);

        if ($this->request->getGet('code')) {
            $client->fetchAccessTokenWithAuthCode($this->request->getGet('code'));
            $oauth2 = new Oauth2($client);
            $googleUser = $oauth2->userinfo->get(); // Fetch user details

            // Extract user details
            $email = $googleUser->email;
            $fullName = $googleUser->name;
            $nameParts = explode(' ', $fullName, 2);
            $fname = $nameParts[0] ?? '';
            $lname = $nameParts[1] ?? '';

            // Load UserModel
            $userModel = new UserModel();

            // Check if user already exists
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
                $userId = $userModel->insert($userData, true);
            } else {
                $userId = $existingUser['id'];
            }

            // Set session data
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

            return redirect()->to('/auth/my-account'); // Redirect to dashboard
        }

        return redirect()->to('/login')->with('error', 'Google login failed.');
    }

    public function subscribeNewsletter()
    {
        if(!empty($_POST)){
            $recaptcha_secret = "6LfyYusqAAAAABVbPZHNLpR5cfPppYZ3zlK6gPp9"; // Replace with your secret key
            $recaptcha_response = $this->request->getPost('recaptcha_response');

            // Verify reCAPTCHA
            $verify_url = "https://www.google.com/recaptcha/api/siteverify";
            $response = file_get_contents("$verify_url?secret=$recaptcha_secret&response=$recaptcha_response");
            $responseKeys = json_decode($response, true);

            if (!$responseKeys["success"]) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'reCAPTCHA verification failed.'])->setStatusCode(403);
            }

            // Get and validate email
            $email = trim($this->request->getPost('email'));

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid email address.'])->setStatusCode(403);
            }

            // Load model
            $newsletterModel = new NewsletterModel();

            // Check if email already exists
            if ($newsletterModel->isEmailExists($email)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Already Subscribed.'])->setStatusCode(403);
            }

            // Insert email into database
            $newsletterModel->insert(['email_id' => $email, 'grecaptcha_res' => json_encode($responseKeys)]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Subscription successful!'])->setStatusCode(200);
        }
        return $this->response->setJSON(['status' => 'success', 'message' => 'Invalid Request!'])->setStatusCode(400);
    }
}
