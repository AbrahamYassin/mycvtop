<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/utils.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h(APP_NAME) ?> — Créez votre CV moderne gratuitement</title>
  <meta name="description" content="<?= h(APP_NAME) ?> est un SaaS gratuit et mobile-first pour créer un CV professionnel et l’exporter en PDF.">
  <meta name="keywords" content="cv, curriculum vitae, gratuit, mobile, template, export pdf">
  <meta name="author" content="<?= h(APP_NAME) ?>">
  <link rel="canonical" href="<?= h(APP_URL) ?>">
  <meta property="og:title" content="<?= h(APP_NAME) ?> — Créez votre CV moderne gratuitement">
  <meta property="og:description" content="<?= h(APP_NAME) ?> est un SaaS gratuit et mobile-first pour créer un CV professionnel et l’exporter en PDF.">
  <meta property="og:url" content="<?= h(APP_URL) ?>">
  <meta property="og:type" content="website">
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='46' fill='%2316a34a'/%3E%3Ctext x='50' y='58' font-family='Arial' font-size='44' text-anchor='middle' fill='white'%3Em%3C/text%3E%3C/svg%3E">
  <style>
    :root{
      --green-50:#f0fdf4; --green-100:#dcfce7; --green-200:#bbf7d0; --green-300:#86efac; --green-400:#4ade80; --green-500:#22c55e; --green-600:#16a34a; --green-700:#15803d;
      --text:#0b1320; --muted:#475569; --border:#e2e8f0; --bg:#ffffff; --card:#ffffff;
      --radius:16px;
    }
    *{box-sizing:border-box}
    html,body{margin:0;padding:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,'Helvetica Neue',Arial,sans-serif;color:var(--text);background:var(--bg)}
    a{color:var(--green-700);text-decoration:none}
    .container{max-width:1160px;margin:0 auto;padding:0 18px}

    /* Header */
    .nav{position:sticky;top:0;z-index:50;backdrop-filter:saturate(180%) blur(10px);background:rgba(255,255,255,.9);border-bottom:1px solid var(--border)}
    .nav-inner{display:flex;align-items:center;justify-content:space-between;padding:12px 0}
    .brand{display:flex;align-items:center;gap:10px;color:var(--green-700);font-weight:800}
    .logo{width:36px;height:36px;border-radius:10px;display:grid;place-items:center;background:linear-gradient(135deg,var(--green-400),var(--green-700));color:#fff;font-weight:900}
    .nav-actions a{margin-left:8px}

    /* Buttons (mobile-friendly sizes) */
    .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 16px;border-radius:14px;border:1px solid var(--border);font-weight:700;min-height:44px}
    .btn-ghost{background:#fff;color:var(--text)}
    .btn-primary{background:var(--green-600);color:#fff;border-color:transparent;box-shadow:0 10px 24px rgba(22,163,74,.18)}
    .btn-primary:active{transform:translateY(1px)}
    .btn-outline{background:#fff;color:var(--green-700);border-color:var(--green-300)}

    /* Hero */
    .hero{position:relative;overflow:hidden;background:
      radial-gradient(900px 500px at 100% -10%, rgba(34,197,94,.10), transparent 60%),
      radial-gradient(700px 400px at -10% 10%, rgba(16,163,74,.10), transparent 60%),
      linear-gradient(180deg,#fff,var(--green-50));
      border-bottom:1px solid var(--border)
    }
    .hero-inner{padding:64px 0 40px;display:grid;grid-template-columns:1.1fr .9fr;gap:28px;align-items:center}
    .eyebrow{display:inline-flex;align-items:center;gap:8px;padding:6px 10px;border-radius:999px;background:var(--green-100);color:var(--green-700);font-weight:700;font-size:12px}
    h1{font-size:clamp(28px,7vw,54px);line-height:1.05;margin:12px 0 10px;letter-spacing:-.4px}
    .lead{color:var(--muted);font-size:clamp(14px,2.4vw,18px);margin:0 0 16px}
    .hero-cta{display:flex;gap:12px;flex-wrap:wrap;margin-top:8px}
    .hero-card{background:#fff;border:1px solid var(--border);border-radius:20px;padding:18px;box-shadow:0 20px 60px rgba(0,0,0,.06)}

    /* Stats */
    .stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:18px}
    .stat{background:#fff;border:1px solid var(--border);border-radius:14px;padding:14px;display:flex;align-items:center;gap:12px}
    .stat b{font-size:18px;color:var(--green-700)}

    /* Sections */
    .section{padding:48px 0}
    .section h2{font-size:clamp(22px,4.2vw,36px);margin:0 0 8px}
    .section p.lead{color:var(--muted);margin:0 0 24px}
    .grid{display:grid;gap:16px;grid-template-columns:repeat(3,1fr)}
    .card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:18px;box-shadow:0 10px 30px rgba(0,0,0,.05)}
    .card h3{margin:0 0 6px;font-size:18px}
    .icon{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--green-400),var(--green-600));display:grid;place-items:center;color:#fff;font-weight:800}

    /* Steps */
    .steps{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
    .step{border-left:4px solid var(--green-600);padding:14px 16px;background:#fff;border:1px solid var(--border);border-radius:12px}
    .step .num{font-weight:900;color:var(--green-700)}

    /* Showcase */
    .showcase{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .mock{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden;box-shadow:0 20px 50px rgba(22,163,74,.08)}
    .mock-head{background:var(--green-600);color:#fff;padding:12px 16px;font-weight:700}
    .mock-body{padding:16px;display:grid;gap:10px}
    .mock-line{height:10px;border-radius:6px;background:var(--green-100)}
    .mock-line.dark{background:var(--green-200);height:12px}

    /* FAQ */
    .faq{max-width:900px;margin:0 auto}
    .faq-item{border:1px solid var(--border);border-radius:14px;padding:14px 16px;margin:10px 0;background:#fff}
    .faq-q{display:flex;justify-content:space-between;align-items:center;cursor:pointer;font-weight:700}
    .faq-a{color:var(--muted);display:none;padding-top:8px}

    /* Footer */
    footer{border-top:1px solid var(--border);background:#fff}
    .foot{display:flex;flex-wrap:wrap;justify-content:space-between;gap:12px;padding:18px 0;font-size:14px;color:var(--muted)}

    /* Mobile-first tweaks */
    @media (max-width: 980px){
      .hero-inner{grid-template-columns:1fr;padding-top:48px}
      .grid{grid-template-columns:1fr}
      .steps{grid-template-columns:1fr}
      .showcase{grid-template-columns:1fr}
      .stats{grid-template-columns:1fr}
      .nav-actions .btn{padding:10px 14px}
    }
    @media (prefers-reduced-motion: reduce){
      .btn-primary{transition:none}
    }
  </style>
</head>
<body>
<header class="nav" role="banner">
  <div class="container nav-inner">
    <a class="brand" href="<?= h(APP_URL) ?>" aria-label="Accueil <?= h(APP_NAME) ?>">
      <span class="logo">m</span>
      <span><?= h(APP_NAME) ?></span>
    </a>
    <nav class="nav-actions" aria-label="Navigation principale">
      <a class="btn btn-ghost" href="login.php">Connexion</a>
      <a class="btn btn-primary" href="register.php">Créer un compte</a>
    </nav>
  </div>
</header>
<main>
<section class="hero">
  <div class="container hero-inner">
    <div>
      <span class="eyebrow">SaaS gratuit — Mobile‑first</span>
      <h1>Un <span style="color:var(--green-700)">CV professionnel</span> en quelques minutes, sur mobile comme sur ordinateur.</h1>
      <p class="lead">Modèles soignés, prévisualisation immédiate, export PDF A4. Pas de carte, pas de piège — le service est financé par la publicité.</p>
      <div class="hero-cta" role="group" aria-label="Appels à l'action">
        <a class="btn btn-primary" href="register.php">Commencer gratuitement</a>
        <a class="btn btn-outline" href="preview.php">Voir un aperçu</a>
      </div>
      <div class="stats" aria-label="Atouts">
        <div class="stat"><div class="icon">✓</div><div><b>100% gratuit</b><div style="color:var(--muted)">sans frais cachés</div></div></div>
        <div class="stat"><div class="icon">🎨</div><div><b>2 templates</b><div style="color:var(--muted)">Classic & Modern</div></div></div>
        <div class="stat"><div class="icon">⬇</div><div><b>Export PDF</b><div style="color:var(--muted)">qualité d’impression</div></div></div>
      </div>
    </div>
    <div class="hero-card" aria-hidden="true">
      <div class="mock">
        <div class="mock-head">Aperçu en direct</div>
        <div class="mock-body">
          <div class="mock-line dark" style="width:60%"></div>
          <div class="mock-line" style="width:90%"></div>
          <div class="mock-line" style="width:80%"></div>
          <div class="mock-line" style="width:70%"></div>
          <div class="mock-line" style="width:85%"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2>Pourquoi choisir <?= h(APP_NAME) ?> ?</h2>
    <p class="lead">Un outil clair, rapide et optimisé pour smartphone. Conçu pour être pertinent pour les recruteurs.</p>
    <div class="grid">
      <div class="card"><div class="icon">⚡</div><h3>Rapide & simple</h3><p>Remplissez vos infos, choisissez un modèle, téléchargez. Pas de complexité inutile.</p></div>
      <div class="card"><div class="icon">🧩</div><h3>Templates pro</h3><p>Deux modèles élégants (Classic, Modern). D’autres arrivent bientôt.</p></div>
      <div class="card"><div class="icon">🔒</div><h3>Respect des données</h3><p>Mots de passe hachés, HTTPS recommandé. <a href="privacy.php">Politique</a>.</p></div>
    </div>
  </div>
</section>

<section class="section" style="background:var(--green-50);border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
  <div class="container">
    <h2>Comment ça marche ?</h2>
    <div class="steps">
      <div class="step"><div class="num">1</div><b>Créez un compte</b><br><span style="color:var(--muted)">Inscription en 30 secondes</span></div>
      <div class="step"><div class="num">2</div><b>Renseignez vos infos</b><br><span style="color:var(--muted)">Profil, expériences, formations…</span></div>
      <div class="step"><div class="num">3</div><b>Exportez en PDF</b><br><span style="color:var(--muted)">Qualité A4, prêt à envoyer</span></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2>Un aperçu des modèles</h2>
    <p class="lead">Classic et Modern — sobres, lisibles, efficaces.</p>
    <div class="showcase">
      <div class="mock"><div class="mock-head">Classic</div><div class="mock-body"><div class="mock-line dark" style="width:55%"></div><div class="mock-line" style="width:92%"></div><div class="mock-line" style="width:88%"></div><div class="mock-line" style="width:70%"></div><div class="mock-line" style="width:80%"></div></div></div>
      <div class="mock"><div class="mock-head">Modern</div><div class="mock-body"><div class="mock-line dark" style="width:45%"></div><div class="mock-line" style="width:85%"></div><div class="mock-line" style="width:95%"></div><div class="mock-line" style="width:75%"></div><div class="mock-line" style="width:60%"></div></div></div>
    </div>
    <div style="margin-top:18px"><a class="btn btn-primary" href="register.php">Créer mon CV maintenant</a></div>
  </div>
</section>

<section class="section" style="background:var(--green-50);border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
  <div class="container faq">
    <h2>Questions fréquentes</h2>
    <div class="faq-item"><div class="faq-q" onclick="toggleFaq(this)">Est-ce vraiment gratuit ? <span>+</span></div><div class="faq-a">Oui. Le service est financé par la publicité. Vous n’avez pas besoin de cliquer pour continuer.</div></div>
    <div class="faq-item"><div class="faq-q" onclick="toggleFaq(this)">Comment fonctionne l’export PDF ? <span>+</span></div><div class="faq-a">Après une courte page d’attente sponsorisée, votre fichier PDF se télécharge automatiquement.</div></div>
    <div class="faq-item"><div class="faq-q" onclick="toggleFaq(this)">Mes données sont-elles en sécurité ? <span>+</span></div><div class="faq-a">Nous stockons le strict nécessaire. Les mots de passe sont hachés. Consultez la <a href="privacy.php">politique de confidentialité</a>.</div></div>
  </div>
</section>

</main>

<footer>
  <div class="container foot">
    <div>© <?= date('Y') ?> <?= h(APP_NAME) ?> — Tous droits réservés.</div>
    <div><a href="privacy.php">Confidentialité</a> · <a href="terms.php">Conditions</a></div>
  </div>
</footer>

<script>
  function toggleFaq(el){ const a=el.parentElement.querySelector('.faq-a'); a.style.display=a.style.display==='block'?'none':'block'; el.querySelector('span').textContent=a.style.display==='block'?'–':'+'; }
</script>
<script defer src="assets/app.js"></script>
</body>
</html>