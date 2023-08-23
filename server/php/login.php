<?php
// ユーザーデータファイルのパス
$userDataFile = 'users/users.txt';

// ログインフォームから送信されたデータ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    // ユーザーデータファイルを読み込む
    $userData = file($userDataFile, FILE_IGNORE_NEW_LINES);

    // ユーザーの存在とパスワードの検証
    foreach ($userData as $line) {
        list($storedUserId, $storedHashedPassword) = explode(',', $line);
        if ($user_id === $storedUserId && password_verify($password, $storedHashedPassword)) {
            // ログイン成功
            session_start();
            $_SESSION['user_id'] = $user_id;
            header('Location: index.php'); // ダッシュボードなど、ログイン後のページにリダイレクト
            exit;
        }
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
