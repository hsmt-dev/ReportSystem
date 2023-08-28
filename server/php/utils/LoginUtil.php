<?php
//ini_set("display_errors", 'On');
//error_reporting(E_ALL);

class LoginUtil 
{
    public static function Login( string $user_data_path, string $user_id, string $password ) 
    {
        // ユーザーデータファイルを読み込む
        $userData = file($user_data_path, FILE_IGNORE_NEW_LINES);

        // ユーザーの存在とパスワードの検証
        foreach ($userData as $line) 
        {
            list($storedUserId, $storedHashedPassword) = explode(',', $line);
            if ($user_id === $storedUserId && password_verify($password, $storedHashedPassword)) 
            {
                // ログイン成功
                session_start();
                $_SESSION['user_id'] = $user_id;
                return true;
            }
        }

        return false;
    }

    public static function Logout() 
    {
        session_start();
        $_SESSION = array();
        session_destroy();
    }

    public static function IsSession() 
    {
        session_start();
        return isset($_SESSION['user_id']);
    }


}
