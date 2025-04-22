<?php 

namespace App\Config;

class Google
{
    public static function getClientConfig()
    {
        return [
            'client_id'     => '1080603963174-9nududm3emanumqn2ni2hodatu42h6fl.apps.googleusercontent.com',
            'client_secret' => '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',
            'redirect_uri'  => base_url('google-callback'),
            'scopes'        => ['email', 'profile'],
        ];
    }
}
