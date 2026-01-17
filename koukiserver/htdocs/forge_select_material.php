<?php
require 'db_config.php';
$current_user_id = 1;

$card_id = (int)($_GET['card_id'] ?? 0);
$base_id = (int)($_GET['base_id'] ?? 0);
if (!$card_id || !$base_id) die("不正なアクセス");

$stmt = $pdo->prepare("
SELECT user_card_id
FROM user_cards
WHERE user_id = :uid
  AND card_id = :cid
  AND user_card_id != :base
  AND in_team = 0
");
$stmt->execute([
    ':uid' => $current_user_id,
    ':cid' => $card_id,
    ':base' => $base_id
]);
$materials = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head><meta charset="UTF-8"><title>素材カード選択</title></head>
<body>
<h1>素材カード選択</h1>

<table border="1">
<tr><th>ID</th><th></th></tr>
<?php foreach ($materials as $m): ?>
<tr>
<td><?= $m['user_card_id'] ?></td>
<td>
<a href="forge_result.php?base_id=<?= $base_id ?>&material_id=<?= $m['user_card_id'] ?>">
強化開始
</a>
</td>
</tr>
<?php endforeach; ?>
</table>

<a href="forge_select_base.php?card_id=<?= $card_id ?>">戻る</a>
</body>
</html>
