<?php
//ini_set("display_errors", 'On');
//error_reporting(E_ALL);

require_once 'utils/LoginUtil.php';

// ユーザーデータファイルのパス
const USERS_DATA_FILE = 'users/users.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    if( LoginUtil::Login( USERS_DATA_FILE, $user_id, $password ) )
    {
        header('Location: index.php'); // ダッシュボードなど、ログイン後のページにリダイレクト
        exit;
    }

    // ログイン失敗
    $error_message = "IDまたはパスワードが正しくありません。";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>ログイン</title>
</head>
<body>
    <h2>ログイン</h2>
    <?php if (isset($error_message)) { echo "<p>$error_message</p>"; } ?>
    <form method="POST" action="">
        <label for="user_id">ユーザーID:</label>
        <input type="text" id="user_id" name="user_id" required><br>
        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="ログイン">
    </form>
</body>
</html>
