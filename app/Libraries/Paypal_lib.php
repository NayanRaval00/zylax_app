<?php

namespace App\Libraries;

use CodeIgniter\Config\BaseConfig;

class Paypal_lib
{
    private $lastError;
    private $ipnLog;
    private $ipnLogFile;
    private $ipnResponse;
    private $ipnData = [];
    private $fields = [];
    private $submitBtn = '';
    private $buttonPath = '';
    private $paypalUrl;
    private $config;

    public function __construct()
    {
        helper(['url', 'form']);

        $this->config = config('Paypal');

        $this->paypalUrl = $this->config->sandbox
            ? 'https://www.sandbox.paypal.com/cgi-bin/webscr'
            : 'https://www.paypal.com/cgi-bin/webscr';

        $this->lastError = '';
        $this->ipnResponse = '';
        $this->ipnLogFile = WRITEPATH . 'logs/paypal_ipn.log';
        $this->ipnLog = $this->config->paypal_lib_ipn_log;
        $this->buttonPath = $this->config->paypal_lib_button_path;

        // Default PayPal fields
        $this->addField('business', $this->config->business);
        $this->addField('cmd', '_cart');
        $this->addField('upload', '1');
        $this->addField('currency_code', $this->config->paypal_lib_currency_code);

        $this->button('Pay Now!');
    }

    public function button($value)
    {
        $this->submitBtn = '<input type="submit" name="pp_submit" value="' . esc($value) . '" />';
    }

    public function image($file)
    {
        $this->submitBtn = '<input type="image" name="add" src="' . base_url(trim($this->buttonPath, '/') . '/' . esc($file)) . '" border="0" />';
    }

    public function addField($field, $value)
    {
        $this->fields[$field] = $value;
    }

    public function paypalAutoForm()
    {
        $this->button("Click here if you're not automatically redirected...");
        echo '<html><head><title>Processing Payment...</title></head>';
        echo '<body style="text-align:center;" onLoad="document.forms[\'paypal_auto_form\'].submit();">';
        echo '<p>Please wait, your order is being processed and you will be redirected to PayPal.</p>';
        echo $this->paypalForm('paypal_auto_form');
        echo '</body></html>';
    }

    public function paypalForm($formName = 'paypal_form')
    {
        $str = '<form method="post" action="' . $this->paypalUrl . '" name="' . esc($formName) . '">';
        foreach ($this->fields as $name => $value) {
            $str .= '<input type="hidden" name="' . esc($name) . '" value="' . esc($value) . '">';
        }
        $str .= '<p>' . $this->submitBtn . '</p>';
        $str .= '</form>';
        return $str;
    }

    public function validateIpn($paypalReturn)
    {
        $ipnResponse = $this->curlPost($this->paypalUrl, $paypalReturn);
        if (stripos($ipnResponse, "VERIFIED") !== false) {
            return true;
        } else {
            $this->lastError = 'IPN Validation Failed.';
            $this->logIpnResults(false);
            return false;
        }
    }

    private function logIpnResults($success)
    {
        if (!$this->ipnLog) return;

        $text = '[' . date('m/d/Y g:i A') . '] - ';
        $text .= $success ? "SUCCESS!\n" : 'FAIL: ' . $this->lastError . "\n";
        $text .= "IPN POST Vars from PayPal:\n";
        foreach ($this->ipnData as $key => $value) {
            $text .= "$key=$value, ";
        }
        $text .= "\nIPN Response from PayPal Server:\n " . $this->ipnResponse;
        file_put_contents($this->ipnLogFile, $text . "\n\n", FILE_APPEND);
    }

    private function curlPost($paypalUrl, $paypalReturnArr)
    {
        $req = 'cmd=_notify-validate';
        foreach ($paypalReturnArr as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $paypalUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
    
        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
    
        if ($error) {
            file_put_contents(WRITEPATH . 'logs/paypal_debug.log', "CURL Error: $error\n", FILE_APPEND);
        }
    
        return $result;
    }
    
}
