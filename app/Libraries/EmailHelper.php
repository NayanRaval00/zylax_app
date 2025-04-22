<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;
use App\Models\orders;

class EmailHelper
{
    protected $email;

    public function __construct()
    {
        $this->email = \Config\Services::email();
    }

    public function sendForgotPasswordEmail($toEmail, $token)
    {
        $resetLink = base_url('reset-password/' . $token);
        //echo $resetLink; exit;
        $subject = 'Reset Your Password';
        $message = view('emails/forgot_password', ['resetLink' => $resetLink]);

        return $this->sendEmail($toEmail, $subject, $message);
    }

    public function sendRegistrationEmail($toEmail, $username)
    {
        $subject = 'Welcome to Our Platform!';
        $message = view('emails/registration', ['username' => $username]);

        return $this->sendEmail($toEmail, $subject, $message);
    }

    public function sendOrderConfirmationEmail($toEmail, $tracking_id)
    {
        $order = new orders();
        $data['orders_details'] = $order->getOrderDetails($tracking_id);
        $subject = 'Your Order Confirmation';
        $message = view('admin/pages/view/download_Invoice', $data);

        return $this->sendEmail($toEmail, $subject, $message);
    }

    public function sendNewPassword($toEmail, $user, $password)
    {
        $subject = 'New Password';
        $message = view('emails/changepassword', ['user' => $user, 'password' => $password]);

        return $this->sendEmail($toEmail, $subject, $message);
    }


    private function sendEmail($toEmail, $subject, $message)
    {
        $this->email->setFrom('vinod.bodypower@gmail.com', 'Zylax Admin');
        $this->email->setTo($toEmail);
        $this->email->setBCC("stikadia@gmail.com");
        $this->email->setSubject($subject);
        $this->email->setMessage($message);
        $this->email->setMailType('html');

        if ($this->email->send()) {
            return true;
        } else {
            log_message('error', $this->email->printDebugger(['headers']));
            return false;
        }
    }

}
