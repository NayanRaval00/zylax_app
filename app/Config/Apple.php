<?php 

namespace App\Config;

class Apple
{
    public static function getClientConfig()
    {
        return [
            'team_id'       => 'YOUR_TEAM_ID',
            'client_id'     => 'YOUR_CLIENT_ID',
            'key_id'        => 'YOUR_KEY_ID',
            'redirect_uri'  => base_url('apple-callback'),
            'private_key'   => file_get_contents(WRITEPATH . 'keys/AuthKey_YOUR_KEY_ID.p8'),
        ];
    }
}
