<?php
require_once __DIR__ . '/db_config.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>10連ガチャ</title>
</head>
<body>
<h1>10連ガチャ</h1>

<form action="gacha_result.php" method="post">
    <input type="hidden" name="user_id" value="1">
    <button type="submit">回す</button>
</form>

</body>
</html>
