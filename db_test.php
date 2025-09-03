<?php
require_once __DIR__.'/includes/db.php';
header('Content-Type: text/plain; charset=utf-8');
try { $pdo=db(); echo "✅ Connexion MySQL OK\n"; $v=$pdo->query('SELECT VERSION() v')->fetch()['v'] ?? 'unknown'; echo "MySQL version: $v\n"; }
catch (Exception $e) { echo "❌ Erreur: ".$e->getMessage()."\n"; }
