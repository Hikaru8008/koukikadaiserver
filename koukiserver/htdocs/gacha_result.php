<?php
require_once __DIR__ . '/db_config.php';

// ===== パラメータ =====
$gachaId = 1;
$drawCount = 10;

// ===== ガチャアイテム取得 =====
$sql = "
    SELECT g.item_id, i.item_name, g.weight
    FROM gacha_items g
    JOIN items i ON g.item_id = i.item_id
    WHERE g.gacha_id = :gacha_id
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['gacha_id' => $gachaId]);
$items = $stmt->fetchAll();

if (!$items) {
    die('ガチャデータがありません');
}

// ===== 重み合計 =====
$totalWeight = array_sum(array_column($items, 'weight'));

// ===== ガチャ処理 =====
$results = [];
for ($i = 0; $i < $drawCount; $i++) {
    $rand = mt_rand(1, $totalWeight);
    $current = 0;
    foreach ($items as $item) {
        $current += $item['weight'];
        if ($rand <= $current) {
            $results[] = $item;
            break;
        }
    }
}

// ===== ガチャ履歴保存（任意） =====
$userId = $_POST['user_id'] ?? 1;

// 履歴保存
$stmt = $pdo->prepare("INSERT INTO gacha_histories (user_id, gacha_id) VALUES (:user_id, :gacha_id)");
$stmt->execute(['user_id' => $userId, 'gacha_id' => $gachaId]);
$historyId = $pdo->lastInsertId();

// アイテム履歴保存
$stmt = $pdo->prepare("INSERT INTO gacha_history_items (history_id, item_id) VALUES (:history_id, :item_id)");
foreach ($results as $item) {
    $stmt->execute(['history_id' => $historyId, 'item_id' => $item['item_id']]);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ガチャ結果</title>
</head>
<body>
    <h1>ガチャ結果</h1>

    <?php foreach ($results as $index => $item): ?>
        <div>
            <?= $index + 1 ?>：<?= htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endforeach; ?>

    <br>
    <a href="gacha_form.php">戻る</a>
</body>
</html>
