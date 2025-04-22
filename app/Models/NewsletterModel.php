<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsletterModel extends Model
{
    protected $table      = 'newsletter';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email_id', 'grecaptcha_res'];
    
    // Function to check if email already exists
    public function isEmailExists($email)
    {
        return $this->where('email_id', $email)->countAllResults() > 0;
    }
}
