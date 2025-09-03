<?php
require_once __DIR__.'/../includes/auth.php'; require_once __DIR__.'/../includes/db.php';
if(!current_user_id()){ http_response_code(401); die('unauthorized'); }
$cv_id=(int)($_POST['cv_id']??0); $json=$_POST['data_json']??'{}'; $pdo=db();
$s=$pdo->prepare("UPDATE cvs SET data_json=?, updated_at=NOW() WHERE id=? AND user_id=?"); $s->execute([$json,$cv_id,current_user_id()]); echo "ok";
