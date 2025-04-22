<?php 

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use App\Models\UserModel;
use CodeIgniter\Controller;

class AppleAuth extends Controller
{
    public function login()
    {
        $appleConfig = \App\Config\Apple::getClientConfig();
        $state = bin2hex(random_bytes(16));
        $nonce = bin2hex(random_bytes(16));

        // Apple Authorization URL
        $authUrl = "https://appleid.apple.com/auth/authorize?"
            . "response_type=code%20id_token"
            . "&client_id=" . urlencode($appleConfig['client_id'])
            . "&redirect_uri=" . urlencode($appleConfig['redirect_uri'])
            . "&scope=name%20email"
            . "&state=" . $state
            . "&nonce=" . $nonce;

        return redirect()->to($authUrl);
    }

    public function callback()
    {
        $appleConfig = \App\Config\Apple::getClientConfig();
        $code = $this->request->getGet('code');
        $idToken = $this->request->getGet('id_token');

        if (!$code || !$idToken) {
            return redirect()->to('/login')->with('error', 'Apple login failed.');
        }

        // Decode ID Token
        $applePublicKeys = file_get_contents("https://appleid.apple.com/auth/keys");
        $keys = json_decode($applePublicKeys, true);

        try {
            $jwtPayload = JWT::decode($idToken, JWK::parseKeySet($keys), ['RS256']);
            $email = $jwtPayload->email ?? null;
            $fullName = $this->request->getPost('user') ? json_decode($this->request->getPost('user'))->name->firstName . ' ' . json_decode($this->request->getPost('user'))->name->lastName : '';

            if (!$email) {
                return redirect()->to('/login')->with('error', 'Apple login failed.');
            }

            // Extract first name & last name
            $nameParts = explode(' ', $fullName, 2);
            $fname = $nameParts[0] ?? '';
            $lname = $nameParts[1] ?? '';

            // Load UserModel
            $userModel = new UserModel();

            // Check if user already exists
            $existingUser = $userModel->where('email', $email)->first();

            if (!$existingUser) {
                // Insert new user into database
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

            // Store in session
            session()->set([
                'user_id'    => $userId,
                'user_name'  => $fullName,
                'user_email' => $email,
                'logged_in'  => true
            ]);

            return redirect()->to('/auth/my-account')->with('success', 'Logged in successfully!');
        } catch (\Exception $e) {
            return redirect()->to('/login')->with('error', 'Invalid Apple token.');
        }
    }
}
