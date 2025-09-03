<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/utils.php';
require_once __DIR__.'/includes/auth.php';
require_once __DIR__.'/includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_verify();
  $name  = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';
  if (!$name || !$email || !$pass) {
    $error = "Veuillez remplir tous les champs.";
  } else {
    [$ok, $res] = register_user($name, $email, $pass);
    if ($ok) { login_user($email, $pass); @file_get_contents('api/event.php?event_type=signup'); redirect('dashboard.php'); }
    else { $error = $res ?: "Impossible de créer le compte."; }
  }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Créer un compte — <?= h(APP_NAME) ?></title>
  <style>
    :root{--green-100:#dcfce7;--green-300:#86efac;--green-600:#16a34a;--green-700:#15803d;--border:#e2e8f0;--muted:#475569;--text:#0b1320}
    *{box-sizing:border-box} html,body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;color:var(--text)}
    a{color:var(--green-700);text-decoration:none}
    .container{max-width:1160px;margin:0 auto;padding:0 18px}
    .nav{position:sticky;top:0;z-index:40;background:rgba(255,255,255,.9);backdrop-filter:saturate(180%) blur(10px);border-bottom:1px solid var(--border)}
    .nav-inner{display:flex;align-items:center;justify-content:space-between;padding:12px 0}
    .brand{display:flex;align-items:center;gap:10px;color:var(--green-700);font-weight:800}
    .logo{width:36px;height:36px;border-radius:10px;display:grid;place-items:center;background:linear-gradient(135deg,#4ade80,#15803d);color:#fff;font-weight:900}
    .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 16px;border-radius:14px;border:1px solid var(--border);font-weight:700;min-height:44px}
    .btn-primary{background:var(--green-600);color:#fff;border-color:transparent}
    .wrap{min-height:calc(100dvh - 56px);display:grid;place-items:center;background:linear-gradient(180deg,#fff,#f0fdf4)}
    .card{width:100%;max-width:560px;background:#fff;border:1px solid var(--border);border-radius:18px;padding:20px;box-shadow:0 20px 60px rgba(0,0,0,.06)}
    .title{font-size:clamp(22px,4.5vw,28px);margin:0 0 4px}
    .muted{color:var(--muted);margin:0 0 14px}
    .label{display:block;margin:12px 0 6px;font-weight:600}
    .input{width:100%;padding:12px 14px;border-radius:12px;border:1px solid var(--border)}
    .row{display:flex;justify-content:space-between;align-items:center;gap:10px}
    .notice{background:#dcfce7;border:1px solid #bbf7d0;padding:10px;border-radius:12px;margin:8px 0}
    @media (max-width: 640px){ .nav-inner{padding:10px 0} }
  </style>
</head>
<body>
<header class="nav">
  <div class="container nav-inner">
    <a class="brand" href="index.php" aria-label="Accueil <?= h(APP_NAME) ?>"><span class="logo">m</span><span><?= h(APP_NAME) ?></span></a>
    <nav><a class="btn" href="login.php">Connexion</a></nav>
  </div>
</header>

<main class="wrap">
  <div class="card" role="form" aria-labelledby="regTitle">
    <h1 id="regTitle" class="title">Créer un compte</h1>
    <p class="muted">Simple, gratuit et sans carte bancaire.</p>
    <?php if (!empty($error)): ?><div class="notice" style="background:#fff0f0;border-color:#fecaca;color:#7f1d1d"><?= h($error) ?></div><?php endif; ?>
    <form method="post" novalidate>
      <?php csrf_field(); ?>
      <label class="label" for="name">Nom complet</label>
      <input class="input" id="name" name="name" autocomplete="name" required>

      <label class="label" for="email">Email</label>
      <input class="input" id="email" type="email" name="email" inputmode="email" autocomplete="email" required>

      <label class="label" for="password">Mot de passe</label>
      <input class="input" id="password" type="password" name="password" autocomplete="new-password" required>

      <div class="row" style="margin-top:10px">
        <span class="muted" style="font-size:13px">Déjà inscrit ? <a href="login.php">Se connecter</a></span>
        <button class="btn btn-primary" type="submit">Créer mon compte</button>
      </div>
    </form>
    <p class="muted" style="margin-top:12px;font-size:12px">En créant un compte, vous acceptez nos <a href="terms.php">Conditions</a> et notre <a href="privacy.php">Politique de confidentialité</a>.</p>
  </div>
</main>
<script defer src="assets/app.js"></script>
</body>
</html>
