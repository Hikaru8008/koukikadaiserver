<?php
require 'db_config.php';
$current_user_id = 1;

$base_id = (int)($_GET['base_id'] ?? 0);
$material_id = (int)($_GET['material_id'] ?? 0);
if (!$base_id || !$material_id) die("必要な情報がありません");

try {
    $pdo->beginTransaction();

    // ベースカードID取得
    $stmt = $pdo->prepare("SELECT card_id FROM user_cards WHERE user_card_id = :id");
    $stmt->execute([':id' => $base_id]);
    $card_id = $stmt->fetchColumn();

    // 次カード取得
    $stmt = $pdo->prepare("SELECT next_card_id FROM cards WHERE card_id = :cid");
    $stmt->execute([':cid' => $card_id]);
    $next_card_id = $stmt->fetchColumn();
    if (!$next_card_id) {
        throw new Exception("これ以上強化できません");
    }

    // ベース更新
    $stmt = $pdo->prepare("
        UPDATE user_cards
        SET card_id = :next
        WHERE user_card_id = :id
    ");
    $stmt->execute([
        ':next' => $next_card_id,
        ':id' => $base_id
    ]);

    // 素材削除
    $stmt = $pdo->prepare("DELETE FROM user_cards WHERE user_card_id = :id");
    $stmt->execute([':id' => $material_id]);

    $pdo->commit();
    $success = true;

} catch (Exception $e) {
    $pdo->rollBack();
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head><meta charset="UTF-8"><title>強化結果</title></head>
<body>
<?php if (!empty($success)): ?>
<h1>🎉 強化成功</h1>
<p>カードは次の段階に進化しました。</p>
<?php else: ?>
<h1>❌ 強化失敗</h1>
<p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<a href="forge_entrance.php">最初に戻る</a>
</body>
</html>
