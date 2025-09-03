<?php
require __DIR__.'/includes/config.php';
require __DIR__.'/includes/utils.php';
$today = date('Y-m-d');
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Politique de confidentialité — <?= h(APP_NAME) ?></title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<div class="container">
  <div class="card" style="max-width:900px;margin:20px auto">
    <h1>Politique de confidentialité</h1>
    <p class="small">Dernière mise à jour : <?= h($today) ?></p>

    <h2>1) Qui sommes-nous ?</h2>
    <p><strong><?= h(APP_NAME) ?></strong> (« Nous », « le Service ») est un outil gratuit pour créer et exporter des CV en PDF, accessible à l’adresse : <?= h(APP_URL) ?>.
      Responsable de traitement : <strong>[À compléter : Nom de l’exploitant]</strong>, <strong>[adresse postale]</strong>,
      contact : <a href="mailto:[À compléter: email contact]">[À compléter: email contact]</a>.</p>

    <h2>2) Données que nous traitons</h2>
    <ul class="list">
      <li><strong>Compte</strong> : nom, email, mot de passe (haché), date de création.</li>
      <li><strong>Contenu du CV</strong> : informations que vous saisissez (entête, expériences, formations, compétences, langues…).</li>
      <li><strong>Usage et analytics</strong> : pages visitées, événement « export PDF », vues de la page interstitielle publicitaire, adresse IP, user-agent (journal technique minimal).</li>
      <li><strong>Cookies et technologies similaires</strong> : nécessaires au fonctionnement, mesure d’audience, et diffusion d’annonces.</li>
    </ul>

    <h2>3) Finalités & bases légales (RGPD)</h2>
    <ul class="list">
      <li><strong>Fournir le Service</strong> (création/édition/export du CV, sécurité, authentification) — <em>exécution du contrat</em>.</li>
      <li><strong>Amélioration & statistiques</strong> (mesure d’audience, performances) — <em>intérêt légitime</em> et/ou <em>consentement</em> selon votre région.</li>
      <li><strong>Publicité</strong> (Google AdSense/Ad Manager) — <em>consentement</em> lorsque requis (UE/EEE : CMP conforme recommandée).</li>
      <li><strong>Lutte anti-fraude et sécurité</strong> — <em>intérêt légitime</em>.</li>
    </ul>

    <h2>4) Publicités (Google) & cookies</h2>
    <p>Nous affichons des annonces via des partenaires (notamment Google). Des cookies et identifiants peuvent être utilisés pour la diffusion d’annonces et la mesure de leur performance.
       En UE/EEE, un bandeau de consentement peut s’appliquer. Plus d’informations :</p>
    <ul class="list">
      <li>Google : <a href="https://policies.google.com/technologies/ads" target="_blank" rel="noopener">policies.google.com/technologies/ads</a></li>
      <li>Paramètres des annonces Google : <a href="https://adssettings.google.com/" target="_blank" rel="noopener">adssettings.google.com</a></li>
    </ul>

    <h2>5) Mesure d’audience</h2>
    <p>Nous pouvons utiliser des statistiques internes et/ou Google Analytics 4. Les données sont agrégées et pseudonymisées. Vous pouvez refuser les traceurs non essentiels via le bandeau de consentement, le cas échéant.</p>

    <h2>6) Destinataires & sous-traitants</h2>
    <ul class="list">
      <li><strong>Hébergeur</strong> : pour stocker la base de données et servir le site.</li>
      <li><strong>Publicité</strong> : Google (AdSense/Ad Manager) et ses partenaires.</li>
      <li><strong>Mesure d’audience</strong> : Google Analytics (si activé).</li>
    </ul>
    <p>Nous ne vendons pas vos données. Nous ne partageons que ce qui est nécessaire à l’exécution des services ci-dessus.</p>

    <h2>7) Durées de conservation</h2>
    <ul class="list">
      <li>Compte et contenu du CV : jusqu’à suppression du compte par l’utilisateur ou inactivité prolongée <em>(ex. 24 mois sans connexion)</em>.</li>
      <li>Journaux techniques / analytics bruts : <em>jusqu’à 12 mois</em> maximum.</li>
      <li>Informations liées aux consentements : selon les exigences légales applicables.</li>
    </ul>

    <h2>8) Localisation & transferts</h2>
    <p>Les données sont hébergées chez notre prestataire. Des transferts hors UE/EEE peuvent se produire (ex. Google). Lorsque c’est le cas, ils sont encadrés par des mécanismes légaux (ex. Clauses Contractuelles Types).</p>

    <h2>9) Sécurité</h2>
    <p>Nous appliquons des mesures techniques et organisationnelles raisonnables (mots de passe hachés, TLS/HTTPS, limitation d’accès). Aucun système n’est totalement sécurisé ; alertez-nous en cas de suspicion.</p>

    <h2>10) Vos droits (RGPD)</h2>
    <p>Vous disposez des droits d’accès, rectification, effacement, limitation, portabilité, et opposition (notamment au profilage publicitaire), ainsi que du droit de retirer votre consentement à tout moment.</p>

    <h2>11) Nous contacter</h2>
    <p>Pour exercer vos droits ou poser une question : <a href="mailto:[À compléter: email contact]">[À compléter: email contact]</a>.
       Vous pouvez également contacter l’autorité de contrôle compétente dans votre pays.</p>

    <h2>12) Modifications</h2>
    <p>Nous pouvons mettre à jour cette politique à tout moment. La version en vigueur est celle publiée sur cette page, avec date de mise à jour.</p>
  </div>
</div>
</body>
</html>
