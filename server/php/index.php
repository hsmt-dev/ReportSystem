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

    <!-- 画像とタイトルのアップロードフォーム
    <form method="post" action="upload.php" enctype="multipart/form-data">
        <input type="text" name="title" required placeholder="タイトル">
        <input type="file" name="image" required>
        <input type="submit" value="Upload">
    </form>
    <hr>
    -->

    <!-- ページのリンクを表示 -->
    <div>
        <?php
        // 1ページに表示する投稿数
        $postsPerPage = 15;

        // ページ番号を取得
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

        // 画像とタイトルと日付の一覧を取得
        $entries = glob('entries/*.json');

        // ファイルの最終更新日時（投稿日時）で降順にソート
        usort($entries, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        // ページネーションのために配列を分割
        $pagedEntries = array_chunk($entries, $postsPerPage);

        // ページのリンクを表示する関数
        function displayPagination($pagedEntries) {
            for ($i = 1; $i <= count($pagedEntries); $i++) {
                if ($i <= count($pagedEntries)) {
                    echo '<a href="?page=' . $i . '">' . $i . '</a> ';
                }
            }
        }
        displayPagination($pagedEntries);
        ?>
    </div>


    <!-- 投稿の一覧表示 -->
    <table>
        <tr>
            <th>画像</th>
            <th>タイトル</th>
            <th>日付</th>
        </tr>
        <?php
        if (isset($pagedEntries[$page - 1])) {
            // 現在のページに対応する投稿を表示
            foreach ($pagedEntries[$page - 1] as $jsonFilePath) {
                $data = json_decode(file_get_contents($jsonFilePath), true);
                $title = htmlspecialchars($data['title']);
                $postDate = htmlspecialchars($data['post_date']);
                $imagePath = htmlspecialchars($data['image']);

                // 投稿IDを取得
                $postId = basename($jsonFilePath, '.json');

                echo '<tr>';
                echo '<td width="25%"><a href="post.php?id=' . $postId . '"><img width="100%" src="' . $imagePath . '" alt="' . $title . '"></a></td>';
                echo '<td ><a href="post.php?id=' . $postId . '">' . $title . '</a></td>';
                echo '<td width="20%">' . $postDate . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="3">ページが存在しません。</td></tr>';
        }
        ?>
    </table>

    <!-- ページネーションのリンクを表示 -->
    <div>
        <?php
        displayPagination($pagedEntries);
        ?>
    </div>

</body>
</html>
