<?php
// ads.php — mycvtop (UTC pour la génération du token)
require_once __DIR__.'/includes/auth.php';
require_once __DIR__.'/includes/utils.php';
require_once __DIR__.'/includes/db.php';
require_once __DIR__.'/includes/csrf.php';
require_auth();

$pdo = db();
$uid = current_user_id();

// Crée un token valable 15 minutes (en UTC pour éviter tout décalage PHP/MySQL)
$token = bin2hex(random_bytes(16));
$pdo->prepare("
  INSERT INTO export_tokens (user_id, token, expires_at)
  VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL 15 MINUTE))
")->execute([$uid, $token]);

// Récupère le code pub interstitiel actif
$ad = $pdo->query("SELECT ad_code FROM ad_slots WHERE placement='interstitial' AND is_active=1 ORDER BY id DESC LIMIT 1")->fetch();
$ad_code = $ad['ad_code'] ?? '<div class="small">[Espace publicitaire interstitiel]</div>';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Préparation du PDF — mycvtop</title>
  <link rel="stylesheet" href="assets/styles.css">
  <script defer src="assets/app.js"></script>
</head>
<body>
<div class="container">
  <div class="card">
    <h2>Votre téléchargement est presque prêt</h2>
    <p class="small">Merci d’utiliser mycvtop 🙏. Le service est gratuit grâce aux annonces.</p>

    <div style="margin:16px 0">
      <?php echo $ad_code; ?>
    </div>

    <p class="small">Le bouton sera activé après un court délai.</p>
    <div id="count" class="countdown">10</div>

    <div style="margin-top:12px">
      <a id="dl" class="btn btn-primary" href="export.php?token=<?= h($token) ?>" style="pointer-events:none;opacity:.5">Télécharger le PDF</a>
      <a class="btn" href="dashboard.php">Retour</a>
    </div>
  </div>
</div>

<script>
// Compte à rebours 10s
let s = 10;
const el = document.getElementById('count');
const btn = document.getElementById('dl');
const iv = setInterval(() => {
  s--;
  if (s <= 0) { clearInterval(iv); el.textContent = '0'; btn.style.pointerEvents='auto'; btn.style.opacity='1'; }
  else { el.textContent = s; }
}, 1000);

// Log vue interstitiel
try {
  const body = new URLSearchParams({event_type:'ad_page_view', route: location.pathname});
  fetch('api/event.php', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body});
} catch(e){}
</script>
</body>
</html>
