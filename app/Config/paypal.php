<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class paypal extends BaseConfig
{
    public $mode = 'sandbox'; // Change to 'live' for production
    public $clientId = 'ARlcGFJcBWswKNHwQ7BZFX9fORJkNzm6vAqQ8OpkCRvLuZT4QkDKl2nsiVPSHmQPJkUjNkzgUSEIMWjd';
    public $secret = 'EBYovDmgfjI7S1OMN5MG--_TMY2nrsQT25Z3QnWopZUnKDYuYIm_9mpfCP2AUTi5HjjnGloUJAoRQPB3';

    public $baseUrl;
    public $returnUrl;
    public $cancelUrl;

    public function __construct()
    {
        $this->baseUrl = $this->mode === 'sandbox' 
            ? 'https://api-m.sandbox.paypal.com' 
            : 'https://api-m.paypal.com';

        $this->returnUrl = base_url('success');
        $this->cancelUrl = base_url('cancel');
    }
}
