<?php
require_once __DIR__.'/db.php';
require_once __DIR__.'/config.php';
if(session_status()!==PHP_SESSION_ACTIVE){ session_name(SESSION_NAME); session_start(); }
function current_user_id(){ return $_SESSION['user_id'] ?? null; }
function require_auth(){ if(!current_user_id()){ header('Location: login.php'); exit; } }
function require_admin(){ if(!(isset($_SESSION['admin_id']) && $_SESSION['admin_id']>0)){ header('Location: admin_login.php'); exit; } }
function register_user($name,$email,$password){
  $pdo=db(); $s=$pdo->prepare("SELECT id FROM users WHERE email=? LIMIT 1"); $s->execute([$email]);
  if($s->fetch()) return [false,"Email déjà enregistré."];
  $hash=password_hash($password,PASSWORD_DEFAULT);
  $s=$pdo->prepare("INSERT INTO users(name,email,password_hash) VALUES(?,?,?)"); $s->execute([$name,$email,$hash]);
  return [true,$pdo->lastInsertId()];
}
function login_user($email,$password){
  $pdo=db(); $s=$pdo->prepare("SELECT id,password_hash FROM users WHERE email=? LIMIT 1"); $s->execute([$email]); $u=$s->fetch();
  if(!$u || !password_verify($password,$u['password_hash'])) return false;
  $_SESSION['user_id']=(int)$u['id']; return true;
}
function logout_user(){ $_SESSION=[]; if(ini_get("session.use_cookies")){ $p=session_get_cookie_params(); setcookie(session_name(),'',
  time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']); } session_destroy(); }
function login_admin($username,$password){
  $pdo=db(); $s=$pdo->prepare("SELECT id,password_hash FROM admins WHERE username=? LIMIT 1"); $s->execute([$username]); $a=$s->fetch();
  if(!$a || !password_verify($password,$a['password_hash'])) return false; $_SESSION['admin_id']=(int)$a['id']; return true;
}
function logout_admin(){ unset($_SESSION['admin_id']); }
?>
