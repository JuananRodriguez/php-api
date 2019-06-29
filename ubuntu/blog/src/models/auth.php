<?php
use Firebase\JWT\JWT;

class Auth
{
    private static $secret_key = 'Sdw1s9x8@';
    private static $encrypt = array('HS256');
    private static $aud = null;
    private static $expire_time = 60*60;

    public static function SignIn($data)
    {
        $token = array(
            'exp' => time() + self::$expire_time,
            'aud' => self::Aud(),
            'data' => $data
        );

        return JWT::encode($token, self::$secret_key);
    }

    public static function Check($token)
    {

        $decode = '';

        if(empty($token))
        {
            return "Invalid token supplied.";
        }
/*
        try {
            $decode = JWT::decode(
                $token,
                self::$secret_key,
                self::$encrypt
            );
        } catch (Error $e) {
            return $e;
        }
*/
        if($decode->aud !== self::Aud())
        {
            return "Invalid user logged in.";
        }

        return $decode;


    }

    public static function GetData($token)
    {

        $decoded = JWT::decode(
            $token,
            self::$secret_key,
            self::$encrypt
        )->data;
        return  print_r($decoded,true);
    }

    private static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }
}
