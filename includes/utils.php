<?php
function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function redirect($u){ header("Location: $u"); exit; }
function app_asset($p){ return $p; }
?>
