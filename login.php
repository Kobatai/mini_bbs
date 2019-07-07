<?php
  require('dbconnect.php');

  //クッキーに値があれば$emailに代入
  if($_COOKIE['email'] !== ''){
    $email = $_COOKIE['email'];
  }

  //POSTされれば以下の処理
  if(!empty($_POST)){
    //新たにPOSTした値でcookieの値を上書きする これで値を打ち直した場合にそれも保存されるようになる
    $email = $_POST['email'];
    //メールとパスワードがからじゃければdbにアクセス
    if($_POST['email'] !== '' && $_POST['password'] !== ''){
      $login =$db->prepare('SELECT * FROM members WHERE email = ? AND password = ?');
    }
    $login->execute(array(
      $_POST['email'],
      //sha1は同じ値なら同じ暗号になる
      sha1($_POST['password'])
    ));

    $member =$login->fetch();
    //ログインできたらセッションにidと現在の時間を保持してindex.phpへ遷移
    if($member){
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();
      //ログインを保持するがonならばクッキーのemailにPOSTされたemailを保存する
      if($_POST['save'] = 'on'){
        //time()+60*60*24*14 = 有効期限は2週間
        setcookie('email',$_POST['email'],time()+60*60*24*14);
      }
      header('Location: index.php');
      exit();
    //ログインに失敗したら
    }else{
      $error['login'] = 'failed';
    }
    // if($_POST['email'] !== '' && $_POST['password'] !== '')に対するelse emailかpasswordどちらかの値が空ならば
  }else{
    $error['login'] = 'blank'
  }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>ログインする</title>
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ログインする</h1>
  </div>
  <div id="content">
    <div id="lead">
      <p>メールアドレスとパスワードを記入してログインしてください。</p>
      <p>入会手続きがまだの方はこちらからどうぞ。</p>
      <p>&raquo;<a href="join/">入会手続きをする</a></p>
    </div>
    <form action="" method="post">
      <dl>
        <dt>メールアドレス</dt>
        <dd>
          <!-- value="<?php print(htmlspecialchars($_POST['email']), ENT_QUOTES)); ?>"で入力をミスしたら値がPOSTで保持される -->
          <!-- cookieの値を入れるverに変更 -->
          <input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($email, ENT_QUOTES)); ?>" />
          <?php if(error['login']==='blank'): ?>
            <p class = "error">＊メールアドレスとパスワードを記入してください</p>
          <?php endif; ?>
           <?php if(error['login']==='failed'): ?>
            <p class = "error">＊ログインに失敗しました。正しく入力してください</p>
          <?php endif; ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
          <input type="password" name="password" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['password']), ENT_QUOTES)); ?>" />
        </dd>
        <dt>ログイン情報の記録</dt>
        <dd>
          <input id="save" type="checkbox" name="save" value="on">
          <label for="save">次回からは自動的にログインする</label>
        </dd>
      </dl>
      <div>
        <input type="submit" value="ログインする" />
      </div>
    </form>
  </div>
  <div id="foot">
    <p><img src="images/txt_copyright.png" width="136" height="15" alt="(C) H2O Space. MYCOM" /></p>
  </div>
</div>
</body>
</html>
