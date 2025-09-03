<?php $h=fn($s)=>htmlspecialchars($s??'',ENT_QUOTES,'UTF-8'); $hdr=$data_export['header']??[]; $sections=$data_export['sections']??[]; ?>
<style>body{font-family:DejaVu Sans,sans-serif;font-size:12px;color:#111;margin:24px}h1{font-size:24px;margin:0}h2{font-size:14px;border-bottom:1px solid #ccc;padding-bottom:4px;margin-top:16px}.item{margin:6px 0}.small{color:#333}</style>
<div><h1><?= $h($hdr['full_name']??'') ?></h1><div class="small"><?= $h($hdr['title']??'') ?></div>
<div class="small"><?= $h($hdr['email']??'') ?> — <?= $h($hdr['phone']??'') ?> — <?= $h($hdr['address']??'') ?></div>
<?php foreach($sections as $sec): ?><h2><?= $h(ucfirst($sec['type'])) ?></h2><?php foreach(($sec['items']??[]) as $it): ?><div class="item"><strong><?= $h($it['title']??'') ?></strong> — <?= $h($it['subtitle']??'') ?> <span class="small"><?= $h($it['period']??'') ?></span><br><span><?= $h($it['desc']??'') ?></span></div><?php endforeach; ?><?php endforeach; ?></div>
