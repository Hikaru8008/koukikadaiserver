<?php
require 'db_config.php';
$current_user_id = 1;

$sql = "
SELECT c.card_id, c.card_name, COUNT(*) AS cnt
FROM user_cards uc
JOIN cards c ON uc.card_id = c.card_id
WHERE uc.user_id = :uid
GROUP BY c.card_id
HAVING cnt >= 2
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':uid' => $current_user_id]);
$cards = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head><meta charset="UTF-8"><title>強化対象カード選択</title></head>
<body>
<h1>強化対象カード選択</h1>

<?php if (empty($cards)): ?>
<p>強化可能なカードはありません。</p>
<?php else: ?>
<table border="1">
<tr><th>ID</th><th>名前</th><th>所持枚数</th><th></th></tr>
<?php foreach ($cards as $c): ?>
<tr>
<td><?= $c['card_id'] ?></td>
<td><?= htmlspecialchars($c['card_name']) ?></td>
<td><?= $c['cnt'] ?></td>
<td>
<a href="forge_select_base.php?card_id=<?= $c['card_id'] ?>">強化</a>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

</body>
</html>
