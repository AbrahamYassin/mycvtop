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
  <title>Conditions d’utilisation — <?= h(APP_NAME) ?></title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<div class="container">
  <div class="card" style="max-width:900px;margin:20px auto">
    <h1>Conditions générales d’utilisation</h1>
    <p class="small">Dernière mise à jour : <?= h($today) ?></p>

    <h2>1) Objet</h2>
    <p>Les présentes CGU encadrent l’accès et l’utilisation du site <?= h(APP_URL) ?> exploité par <strong>[À compléter : Nom de l’exploitant]</strong>.
       En créant un compte ou en utilisant le Service, vous acceptez ces conditions.</p>

    <h2>2) Création de compte</h2>
    <ul class="list">
      <li>Vous devez fournir des informations exactes et maintenir la confidentialité de vos identifiants.</li>
      <li>Vous êtes responsable des activités réalisées depuis votre compte.</li>
    </ul>

    <h2>3) Service gratuit & publicité</h2>
    <p><strong><?= h(APP_NAME) ?></strong> est gratuit et financé par la publicité. Lors de l’export PDF, une page d’attente comportant des annonces peut s’afficher. Nous n’incitons pas au clic ; vous pouvez ignorer les publicités.</p>

    <h2>4) Utilisation du Service</h2>
    <ul class="list">
      <li>Interdiction d’usage illicite, diffamatoire, frauduleux, de contenu offensant ou violant des droits tiers.</li>
      <li>Interdiction d’ingénierie inverse, surcharges ou perturbations du service.</li>
      <li>Nous pouvons suspendre/supprimer un compte en cas de manquement aux CGU ou risque pour la sécurité.</li>
    </ul>

    <h2>5) Contenu & propriété intellectuelle</h2>
    <ul class="list">
      <li>Vous conservez vos droits sur le contenu de votre CV. Vous nous accordez une licence limitée pour l’héberger et le traiter afin de fournir le Service.</li>
      <li>Le site, les templates et éléments graphiques restent la propriété de l’éditeur ou de ses concédants.</li>
    </ul>

    <h2>6) Export PDF</h2>
    <p>L’export PDF est fourni « en l’état ». Nous nous efforçons d’assurer un rendu fidèle, sans garantir l’absence totale d’erreurs d’affichage, d’interruptions ou de pertes de données.</p>

    <h2>7) Responsabilité</h2>
    <p>Dans la limite autorisée par la loi, nous ne saurions être responsables des dommages indirects, perte de chance, perte de revenus ou de données résultant de l’usage du Service.</p>

    <h2>8) Données personnelles</h2>
    <p>Le traitement des données personnelles est décrit dans notre <a href="privacy.php">Politique de confidentialité</a>, qui fait partie intégrante des présentes.</p>

    <h2>9) Durée, suspension et résiliation</h2>
    <p>Le contrat est conclu pour une durée indéterminée à compter de l’acceptation des CGU. Vous pouvez supprimer votre compte à tout moment. Nous pouvons suspendre/résilier en cas de manquement grave, fraude, ou exigence légale.</p>

    <h2>10) Modifications des CGU</h2>
    <p>Nous pouvons modifier ces conditions. Les changements s’appliquent dès publication. En cas de modification substantielle, nous pourrons vous en informer par email ou notification sur le site.</p>

    <h2>11) Loi applicable & juridiction</h2>
    <p>Ces CGU sont soumises au droit <strong>[À compléter : pays]</strong>. En cas de litige, et à défaut d’accord amiable, compétence est attribuée aux tribunaux de <strong>[À compléter : ville/pays]</strong>.</p>

    <h2>12) Contact</h2>
    <p>Questions relatives aux CGU : <a href="mailto:[À compléter: email contact]">[À compléter: email contact]</a></p>
  </div>
</div>
</body>
</html>
