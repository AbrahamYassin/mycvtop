<?php
require_once __DIR__.'/includes/auth.php'; require_once __DIR__.'/includes/utils.php'; require_once __DIR__.'/includes/db.php'; require_auth();
$pdo=db(); $uid=current_user_id(); $s=$pdo->prepare("SELECT * FROM cvs WHERE user_id=? LIMIT 1"); $s->execute([$uid]); $cv=$s->fetch(); if(!$cv){ header('Location: dashboard.php'); exit; }
$data=json_decode($cv['data_json']??'{}',true)?:[]; $tpl=$cv['template_slug']??'classic'; $file=__DIR__."/templates/{$tpl}.php"; if(!file_exists($file)) $file=__DIR__."/templates/classic.php";
ob_start(); $data_export=$data; include $file; $html=ob_get_clean();
?><!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Aperçu — mycvtop</title>
<link rel="stylesheet" href="assets/styles.css"></head><body><div class="container"><div class="header"><h2>Aperçu</h2><div>
<a class="btn" href="dashboard.php">Retour</a><a class="btn btn-primary" href="ads.php">Exporter en PDF</a></div></div><div class="card"><div class="code"><?= $html ?></div></div></div></body></html>
