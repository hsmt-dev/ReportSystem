<?php
//ini_set("display_errors", 'On');
//error_reporting(E_ALL);

require_once 'utils/LoginUtil.php';

if( !LoginUtil::IsSession() ) 
{
    // ログインページにリダイレクト
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="imagetoolbar" content="no" />

    <title>レポート</title>
    <style>
        /* 画像のスタイル */
        .thumbnail {
            max-width: 60%;
            display: block;
            margin: 0 auto; /* 画像をブラウザの60%でセンターリング */
            border: 1px solid #ccc;
        }

        /* 表のスタイル */
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left; /* 左寄せに変更 */
        }
    </style>
</head>
<body>

    <p><a href="index.php" style="float:left;">レポート一覧</a>　<a href="logout.php" style="float:right;">ログアウト</a></p>
    <hr>

    <?php
    if (isset($_GET['id'])) {
        // 投稿IDを取得
        $postId = $_GET['id'];

        // 投稿のデータを取得
        $jsonFilePath = 'entries/' . $postId . '.json';
        if (file_exists($jsonFilePath)) {
            $data = json_decode(file_get_contents($jsonFilePath), true);
            $title = htmlspecialchars($data['title']);
            $postDate = htmlspecialchars($data['post_date']);
            $imagePath = htmlspecialchars($data['image']);

            $date_time = date('Y-m-d H:i:s', htmlspecialchars($data['date_time']));
            $operating_system = htmlspecialchars($data['operating_system']);
            $device_model = htmlspecialchars($data['device_model']);
            $system_memory_size = htmlspecialchars($data['system_memory_size']);
            $use_memory_size = htmlspecialchars($data['use_memory_size']);

/*
            echo '<h2>' . $title . '</h2>';
            echo '<img class="thumbnail" src="' . $imagePath . '" alt="' . $title . '">';
            echo '<p>投稿日時：' . $postDate . '</p>';
            echo '<p>端末日時：' . $date_time . '</p>';
            echo '<p>端末情報：' . $device_model . '</p>';
            echo '<p>OperatingSystem：' . $operating_system . '</p>';
            echo '<p>SystemMemorySize：' . $system_memory_size . '</p>';
            echo '<p>UseMemorySize：' . $use_memory_size . '</p>';
*/

            echo '<div class="well" style="width: 90vw;">';
            echo '<h2><strong>' . $title . '</strong></h2>';
//            echo '<div>';
            echo '<table style="width: 100%; table-layout:fixed;" >';
            echo '<tr>';
            echo '<div style="max-width: 1600px; height: auto; padding: 20px; text-align: center;">';
            echo '<img src=' . $imagePath . ' width="70%">';
            echo '</div>';
            echo '</tr>';

            echo '<tr><th colspan="2">投稿時間</th>';
            echo '<td colspan="7">' . $postDate . '</td></tr>';

            echo '<tr><th colspan="2">端末日時</th>';
            echo '<td colspan="7">' . $date_time . '</td></tr>';

            echo '<tr><th colspan="2">端末情報</th>';
            echo '<td colspan="7">' . $device_model . '</td></tr>';

            echo '<tr><th colspan="2">OperatingSystem</th>';
            echo '<td colspan="7">' . $operating_system . '</td></tr>';

            echo '<tr><th colspan="2">SystemMemorySize</th>';
            echo '<td colspan="7">' . $system_memory_size . '</td></tr>';

            echo '<tr><th colspan="2">UseMemorySize</th>';
            echo '<td colspan="7">' . $use_memory_size . '</td></tr>';



            echo '</table>';
            echo '</div>';
//            echo '</div>';

        } else {
            echo '<p>投稿が見つかりません。</p>';
        }
    } else {
        echo '<p>投稿が指定されていません。</p>';
    }
    ?>
</body>
</html>
