<?php
// USe Login below to test in sandbox mode as buyer
#zylax-buyer@paypal.com
# pwd: X3%['g]5

define('PAYPAL_MODE', 'test');
define('PAYPAL_CLIENT_ID', 'ARlcGFJcBWswKNHwQ7BZFX9fORJkNzm6vAqQ8OpkCRvLuZT4QkDKl2nsiVPSHmQPJkUjNkzgUSEIMWjd');
define('PAYPAL_SECRET', 'EBYovDmgfjI7S1OMN5MG--_TMY2nrsQT25Z3QnWopZUnKDYuYIm_9mpfCP2AUTi5HjjnGloUJAoRQPB3');

if(PAYPAL_MODE == 'test'){
    define('PAYPAL_BASE_URL', 'https://api-m.sandbox.paypal.com'); // Use this for sandbox, change to live when in production
    define('PAYPAL_IPN_URL', 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr'); // Use this for sandbox, change to live when in production
    // define('SITE_URL', 'https://localhost.vinod/test/paypal-vr/'); // Use this for sandbox, change to live when in production
    define('SITE_URL', 'https://dev2.zylaxonline.com.au/paypal-vr/'); // Use this for sandbox, change to live when in production
}
else{
    define('PAYPAL_BASE_URL', 'https://api-m.paypal.com'); // Use this for sandbox, change to live when in production
    define('PAYPAL_IPN_URL', 'https://ipnpb.paypal.com/cgi-bin/webscr'); // Use this for sandbox, change to live when in production
    define('SITE_URL', 'https://dev2.zylaxonline.com.au/paypal-vr/'); // Use this for sandbox, change to live when in production
}


function getAccessToken() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, PAYPAL_BASE_URL . "/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ":" . PAYPAL_SECRET);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    
    $headers = [];
    $headers[] = "Accept: application/json";
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close($ch);
    
    $json = json_decode($result, true);
    return $json['access_token'];
}
?>
