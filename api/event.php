<?php
require_once __DIR__.'/../includes/config.php'; require_once __DIR__.'/../includes/db.php';
if(session_status()!==PHP_SESSION_ACTIVE){ session_name(SESSION_NAME); session_start(); }
$pdo=db(); $event_type=$_POST['event_type'] ?? $_GET['event_type'] ?? 'visit'; $route=$_POST['route'] ?? $_GET['route'] ?? ($_SERVER['REQUEST_URI'] ?? '');
$user_id=$_SESSION['user_id'] ?? null; $session_id=session_id(); $ua=$_SERVER['HTTP_USER_AGENT'] ?? ''; $ip=$_SERVER['REMOTE_ADDR'] ?? '';
$s=$pdo->prepare("INSERT INTO analytics_events(event_type,route,user_id,session_id,user_agent,ip,created_at) VALUES(?,?,?,?,?,?,NOW())"); $s->execute([$event_type,$route,$user_id,$session_id,$ua,$ip]);
echo "ok";
