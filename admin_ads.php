<?php require_once __DIR__.'/includes/auth.php'; require_once __DIR__.'/includes/utils.php'; require_once __DIR__.'/includes/db.php'; require_once __DIR__.'/includes/csrf.php'; require_admin();
$pdo=db(); if($_SERVER['REQUEST_METHOD']==='POST'){ csrf_verify(); $name=trim($_POST['name']??''); $code=$_POST['ad_code']??''; $is_active=isset($_POST['is_active'])?1:0;
  $s=$pdo->prepare("INSERT INTO ad_slots(name, placement, ad_code, is_active, created_at) VALUES(?,?,?,?,NOW())"); $s->execute([$name or 'Interstitial','interstitial',$code,$is_active]); redirect('admin_ads.php'); }
$ads=$pdo->query("SELECT * FROM ad_slots ORDER BY id DESC LIMIT 20")->fetchAll(); ?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin — ADS</title><link rel="stylesheet" href="assets/styles.css"></head>
<body><div class="container"><div class="header"><h2>Gestion des ADS</h2><div><a class="btn" href="admin_dashboard.php">Retour</a></div></div>
<div class="grid"><div class="card"><h3>Ajouter un code (AdSense / Ad Manager)</h3><form method="post"><?php csrf_field(); ?><label class="label">Nom</label><input class="input" name="name" placeholder="Interstitial">
<label class="label">Code d’annonce</label><textarea class="input" name="ad_code" rows="8" placeholder="Collez ici le script Google"></textarea><label><input type="checkbox" name="is_active" checked> Activer</label>
<div style="margin-top:12px"><button class="btn btn-primary">Enregistrer</button></div></form></div>
<div class="card"><h3>Historique</h3><table class="table"><tr><th>#</th><th>Nom</th><th>Placement</th><th>Actif</th><th>Créé</th></tr>
<?php foreach($ads as $a): ?><tr><td><?= (int)$a['id'] ?></td><td><?= h($a['name']) ?></td><td><?= h($a['placement']) ?></td><td><?= $a['is_active']?'Oui':'Non' ?></td><td><?= h($a['created_at']) ?></td></tr><?php endforeach; ?>
</table></div></div><div class="notice small" style="margin-top:12px">Respectez les politiques Google. Pas d’incitation au clic.</div></div></body></html>
