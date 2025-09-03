<?php
require_once __DIR__.'/config.php';
if(session_status()!==PHP_SESSION_ACTIVE){ session_name(SESSION_NAME); session_start(); }
function csrf_token(){ if(empty($_SESSION['csrf_token'])) $_SESSION['csrf_token']=bin2hex(random_bytes(32)); return $_SESSION['csrf_token']; }
function csrf_field(){ $t=htmlspecialchars(csrf_token(),ENT_QUOTES,'UTF-8'); echo '<input type="hidden" name="csrf_token" value="'.$t.'">'; }
function csrf_verify(){ $ok=isset($_POST['csrf_token'],$_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'],$_POST['csrf_token']); if(!$ok){ http_response_code(400); die('CSRF token invalide.'); } }
?>
