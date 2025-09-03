<?php
// export.php — mycvtop (version corrigée: vérif d'expiration côté MySQL/UTC)
require_once __DIR__.'/includes/auth.php';
require_once __DIR__.'/includes/utils.php';
require_once __DIR__.'/includes/db.php';
require_auth();

$pdo     = db();
$user_id = current_user_id();
$token   = $_GET['token'] ?? '';

if (!$token) {
    http_response_code(400);
    exit('Token manquant.');
}

// On valide le token directement côté MySQL pour éviter les décalages d’heure PHP/MySQL.
$stmt = $pdo->prepare("
    SELECT id
    FROM export_tokens
    WHERE token = ?
      AND user_id = ?
      AND used_at IS NULL
      AND expires_at >= UTC_TIMESTAMP()
    LIMIT 1
");
$stmt->execute([$token, $user_id]);
$row = $stmt->fetch();

if (!$row) {
    http_response_code(400);
    exit('Token invalide ou expiré.');
}

// Marquer le token comme utilisé (UTC)
$pdo->prepare("UPDATE export_tokens SET used_at = UTC_TIMESTAMP() WHERE id = ?")
    ->execute([$row['id']]);

// Récupérer les données du CV
$stmt = $pdo->prepare("SELECT template_slug, data_json FROM cvs WHERE user_id = ? LIMIT 1");
$stmt->execute([$user_id]);
$cv = $stmt->fetch();
if (!$cv) {
    http_response_code(400);
    exit('CV introuvable.');
}

$data     = json_decode($cv['data_json'] ?? '{}', true) ?: [];
$template = $cv['template_slug'] ?? 'classic';

$templateFile = __DIR__ . "/templates/{$template}.php";
if (!file_exists($templateFile)) {
    $templateFile = __DIR__ . "/templates/classic.php";
}

// Rendu HTML via le template
ob_start();
$data_export = $data; // dispo dans le template
include $templateFile;
$html = ob_get_clean();

// Dompdf
$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Dépendance manquante : vendor/autoload.php non trouvé.\n";
    echo "Installez Dompdf : composer require dompdf/dompdf\n";
    exit;
}
require_once $autoload;

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="cv_mycvtop.pdf"');
echo $dompdf->output();

// Log analytics
try { @file_get_contents('api/event.php?event_type=export'); } catch (Exception $e) {}

exit;
