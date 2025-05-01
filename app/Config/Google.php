<?php 

namespace App\Config;

class Google
{
    public static function getClientConfig()
    {
        return [
            'client_id'     => '1040449931916-0896r0lbr2ho6chhqqneap4p13fu8nv0.apps.googleusercontent.com',
            'client_secret' => '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',
            'redirect_uri'  => base_url('google-callback'),
            'scopes'        => ['email', 'profile'],
        ];
    }
}
