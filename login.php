<html>
<head>
</head>
<body>
  <div>
    <form action="" method="post" accept-charset="utf-8">
        帳號：<input type="text" name="name" placeholder="輸入暱稱">
        密碼：<input type="password" name="password" placeholder="輸入密碼">
        <input type="submit" name="login" value="登入">
      </form>
  </div>
  <div>
    <a href="register.php">沒有帳號？點我註冊</a>
  </div>
  <div>
  <?php
  require_once 'db_init.php';
  require_once 'db_table_init.php';
  require_once 'cookie_setting.php';

  session_save_path(realpath(dirname($_SERVER['DOCUMENT_ROOT']).'/../session'));
  $cookieSetting = new CookieSetting();

  if (isset($_COOKIE['session_id']) && !empty($_COOKIE['session_id'])) {
      session_id($_COOKIE['session_id']);
      session_start();
  } else {
      session_start();
      setcookie('session_id', session_id(), $cookieSetting->getExpires(), $cookieSetting->path, $cookieSetting->domain, $cookieSetting->secure, $cookieSetting->httpOnly);
  }

  if (isset($_SESSION['LoginSuccess']) && $_SESSION['LoginSuccess'] === true) {
      header('Location:guestbook.php');
      exit();
  } else {
      $_SESSION['LoginSuccess'] = false;
  }

  if (!isset($_POST['name']) || !isset($_POST['password'])) {
  } elseif (empty($_POST['name']) || empty($_POST['password'])) {
      echo '<br>未輸入帳號或密碼';
  } else {
      $result = checkLoginData($_POST['name'], $_POST['password']);
      if ($result['loginSuccess']) {
          $_SESSION['LoginSuccess'] = true;
          $_SESSION['UserName'] = $result['username'];
          header('Location:guestbook.php');
          exit();
      } else {
          echo '<br>帳號或密碼錯誤';
      }
  }

  function checkLoginData($account, $password)
  {
      require_once 'db_config.php';
      $dbSetting = new DBSetting();
      try {
          $pdo = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);
          $pdo->query('SET NAMES utf8');
          $sql = 'SELECT * FROM account WHERE(name = ? AND password = ? )';

          // Avoid SQL Injection with pdo prepare/ execute
          $sth = $pdo->prepare($sql);
          $success = $sth->execute(array($account, $password));
          $result = $sth->fetch(PDO::FETCH_OBJ);

      } catch (PDOException $e) {
          echo 'check login data'.$e->getMessage();
      }
      unset($pdo);
      return array('loginSuccess' => $result, 'username' => ($result->name));
  }
  ?>
</div>
</body>
</html>
