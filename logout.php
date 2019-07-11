<?php
session_start();

//セッションを空の配列で上書きすることでセッションを切る
$_SESSION = array();


//php.iniでcookieを使うかというところのcheck
if(ini_set('session.use_cookies')){
  //cookieを取得する
  $params = session_get_cookie_params();
  //time() -42000でcookieの有効期限を切る + cookieで使ったoptionについても有効期限を切ってあげる
  setcookie(session_name() . '', time() -42000 ,
    $params['path'],$params['domain'],$params['secure'],
    $params['httponly']);
}


session_destroy();

//cookieに保存されたemailについても削除
setcookie('email', '' , time() - 3600);

header('Location: login.php');

exit();

?>
