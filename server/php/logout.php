<?php
//ini_set("display_errors", 'On');
//error_reporting(E_ALL);

require_once 'utils/LoginUtil.php';

LoginUtil::Logout();
?>

<!DOCTYPE html>
<html>
<head>
    <title>ログアウト</title>
</head>
<body>
    <h1>ログアウトしました</h1>
    <p><a href='login.php'>ログインページに戻る</a></p>
</body>
</html>
