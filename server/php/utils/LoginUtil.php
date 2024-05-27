<?php
//ini_set("display_errors", 'On');
//error_reporting(E_ALL);

class LoginUtil 
{
    // ユーザー登録
    public static function Register( string $user_data_path, string $user_id, string $password )
    {
        // ユーザーデータファイルが存在する場合、既存ユーザーのチェック
        if (file_exists($user_data_path)) 
        {
            $userData = file($user_data_path, FILE_IGNORE_NEW_LINES);
            foreach ($userData as $line) {
                list($storedUserId, $storedHashedPassword) = explode(',', $line);
                if ($user_id === $storedUserId) 
                {
                    // 既存ユーザーIDが見つかった場合
                    return false;
                }
            }
        }

        // パスワードをハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if ($hashedPassword === false) 
        {
            die('パスワードのハッシュ化に失敗しました。');
        }

        // ユーザー情報をファイルに追加
        $newUser = $user_id . ',' . $hashedPassword . PHP_EOL;
        file_put_contents($user_data_path, $newUser, FILE_APPEND);

        return true;
    }

    // ログイン
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

    // ログアウト
    public static function Logout() 
    {
        session_start();
        $_SESSION = array();
        session_destroy();
    }

    // セッションが有効か
    public static function IsSession() 
    {
        session_start();
        return isset($_SESSION['user_id']);
    }

}
