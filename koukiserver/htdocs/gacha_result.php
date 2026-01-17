<?php
require_once __DIR__ . '/db_config.php';

$userId = $_POST['user_id'] ?? null;
if (!$userId) die("ユーザーIDが指定されていません。");

$gachaId = 1;
$drawCount = 10;

$sql = "
    SELECT g.item_id, i.item_name, i.rarity, i.image, g.weight
    FROM gacha_items g
    JOIN items i ON g.item_id = i.item_id
    WHERE g.gacha_id = :gacha_id
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['gacha_id' => $gachaId]);
$items = $stmt->fetchAll();
if (!$items) die('ガチャデータがありません');

$totalWeight = array_sum(array_column($items, 'weight'));

$results = [];
for ($i=0; $i<$drawCount; $i++){
    $rand = mt_rand(1, $totalWeight);
    $current = 0;
    foreach($items as $item){
        $current += $item['weight'];
        if($rand <= $current){
            $results[] = $item;
            break;
        }
    }
}

$rarityColors = [
    '神レア'=>'gold',
    '超レア'=>'purple',
    'レア'=>'blue',
    '普通'=>'gray',
    'ガラクタ'=>'brown'
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ガチャ結果</title>
<style>
body { font-family: sans-serif; }
.item {
    font-size: 1.2em;
    margin-bottom: 20px;
    opacity: 0;
    transform: rotateX(90deg);
    transition: all 0.5s ease;
    display: inline-block;
    text-align: center;
}
.item.show {
    opacity: 1;
    transform: rotateX(0deg);
}
.item img {
    border-radius: 8px;
    box-shadow: 0 0 8px rgba(0,0,0,0.5);
    width: 100px;
    display: block;
    margin: 0 auto 5px;
}
</style>
</head>
<body>
<h1>ガチャ結果</h1>

<div id="results-container">
<?php if(!empty($results)): ?>
    <?php foreach($results as $index=>$item): ?>
        <div class="item" style="color: <?= $rarityColors[$item['rarity']] ?? 'black' ?>;" data-index="<?= $index ?>">
            <img src="<?= htmlspecialchars($item['image'],ENT_QUOTES) ?>" alt="<?= htmlspecialchars($item['item_name'],ENT_QUOTES) ?>">
            <?= $index+1 ?>：<?= htmlspecialchars($item['item_name'],ENT_QUOTES) ?> (<?= $item['rarity'] ?>)
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>ガチャ結果はありません。</p>
<?php endif; ?>
</div>

<br>
<a href="gacha_form.php">戻る</a>

<script>
document.addEventListener("DOMContentLoaded", function(){
    const items = document.querySelectorAll(".item");
    items.forEach((item,index)=>{
        setTimeout(()=>{ item.classList.add("show"); }, index*500);
    });
});
</script>
</body>
</html>
