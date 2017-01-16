<html>
<head>
  <meta charset="utf-8">
</head>
<body>
  請輸入資料以註冊一個新帳號
  <form action="" method="post" accept-charset="utf8">
    <input type="text" name="name" placeholder="請輸入帳號, 最多30字">
    <input type="password" name="password" placeholder="請輸入數字密碼, 最多8字">
    <input type="submit" name="submit" value="註冊">
  </form>
  <div>
  <?php
  if (isset($_POST['submit'])) {
      if (!isset($_POST['name']) || !isset($_POST['password']) || empty($_POST['name']) || empty($_POST['password'])) {
          echo '未設定帳號或密碼';
      } elseif (!is_numeric($_POST['password'])) {
          echo '請確定輸入的密碼為數字密碼';
      } elseif (lengthIsOver($_POST['name'], $_POST['password'])) {
          echo '帳號或密碼長度超過了，帳號最多30字，密碼最多8字';
      } else {
          require_once 'db_config.php';
          $dbSetting = new DBSetting();

          try {
              $dbh = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);
              $stmt = $dbh->prepare('INSERT INTO account (name, password) VALUES (:name, :password)');
              $stmt->bindParam(':name', $_POST['name']);
              $stmt->bindParam(':password', $_POST['password']);
              $success = $stmt->execute();
              if ($success) {
                  header('Location:register_success.html');
                  exit();
              } else {
                  echo '註冊失敗，請再試一次';
              }
              $dbh->close();
          } catch (PDOException $e) {
              echo 'Connection failed: '.$e->getMessage();
          }
      }
  }
  function lengthIsOver($name, $password){
    return (strlen($name) > 30 || strlen($password) > 8);
  }
  ?>
</div>
</body>
</html>
