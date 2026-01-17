<?php
require 'db_config.php';
$current_user_id = 1;

$card_id = isset($_GET['card_id']) ? (int)$_GET['card_id'] : 0;
if (!$card_id) die("カードIDが指定されていません");

$stmt = $pdo->prepare("
SELECT user_card_id, in_team
FROM user_cards
WHERE user_id = :uid AND card_id = :cid
");
$stmt->execute([
    ':uid' => $current_user_id,
    ':cid' => $card_id
]);
$cards = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head><meta charset="UTF-8"><title>ベースカード選択</title></head>
<body>
<h1>ベースカード選択</h1>

<table border="1">
<tr><th>ID</th><th>チーム</th><th></th></tr>
<?php foreach ($cards as $c): ?>
<tr>
<td><?= $c['user_card_id'] ?></td>
<td><?= $c['in_team'] ? '編成中' : '未編成' ?></td>
<td>
<?php if ($c['in_team'] == 0): ?>
<a href="forge_select_material.php?card_id=<?= $card_id ?>&base_id=<?= $c['user_card_id'] ?>">
ベースにする
</a>
<?php else: ?>
不可
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</table>

<a href="forge_entrance.php">戻る</a>
</body>
</html>
