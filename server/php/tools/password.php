<?php
// ログインフォームから送信されたデータ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 平文のパスワード
    $plainPassword = $_POST['plain_password'];

    // パスワードをハッシュ化
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    if ($hashedPassword === false) {
        // ハッシュ生成が失敗した場合のエラー処理
        echo "ハッシュ生成に失敗しました。";
    } else {
        // ハッシュ生成が成功した場合、ハッシュを表示または保存します
        echo "ハッシュ化されたパスワード: " . $hashedPassword;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>パスワードのハッシュ生成</title>
</head>
<body>
    <h2>パスワード</h2>
    <form method="POST" action="">
        <label for="plain_password">平文のパスワード:</label>
        <input type="plain_password" id="plain_password" name="plain_password" required><br>
        <input type="submit" value="ログイン">
    </form>
</body>
</html>
