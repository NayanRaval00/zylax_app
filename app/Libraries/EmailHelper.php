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

    public function contactUsFormSubmit($toEmail, $firstName, $lastName, $email, $phone, $country, $subjectTitle, $messageContent)
    {
        $subject = 'Contact Us Email';
        $message = view('emails/contact_us', ['firstName' => $firstName, 'lastName' => $lastName, 'email' => $email, 'phone' => $phone, 'country' => $country, 'subjectTitle' => $subjectTitle, 'messageContent' => $messageContent]);

        return $this->sendEmail($toEmail, $subject, $message);
    }

    public function pageFormSubmit($toEmail, $data, $image)
    {
        $subject = $data['slug_url'];
        $message = view('emails/page_form', ['data' => $data]);

        return $this->sendEmail($toEmail, $subject, $message, $image);
    }

    private function sendEmail($toEmail, $subject, $message, $imagePath = null)
    {
        $adminEmail = env('ADMIN_EMAIL');
        $adminBccEmail = env('ADMIN_BCC_EMAIL');
        $this->email->setFrom($adminEmail, 'Zylax Admin');
        $this->email->setTo($toEmail);
        if($adminBccEmail){
            $this->email->setBCC($adminBccEmail);
        }
        $this->email->setSubject($subject);
        $this->email->setMessage($message);
        $this->email->setMailType('html');

        // Attach image if path is provided
        if ($imagePath && file_exists($imagePath)) {
            $this->email->attach($imagePath);
        }

        if ($this->email->send()) {
            return true;
        } else {
            log_message('error', $this->email->printDebugger(['headers']));
            return false;
        }
    }

}
