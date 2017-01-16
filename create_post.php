<html>
<head>
  <meta charset="utf-8">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link href="main.css" rel="stylesheet">
</head>
<body id="edit_bg">
  <div id="edit">
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
      if (!isset($_POST['content']) || empty($_POST['content'])) {
      } elseif (!isset($_POST['title']) || empty($_POST['title'])) {
      } else {
          createNewPost($_POST['title'], $_POST['content']);
      }

      function createNewPost($title, $content)
      {
          require_once 'db_config.php';
          $dbSetting = new DBSetting();
          $filePath = '';
          $todaysDate = date('Y-m-d H:i:s');
          try {
              $pdo = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);
              $pdo->query('SET NAMES utf8');
              $sql = 'INSERT INTO posts (author, creation_time, title, content, filepath) VALUES (:author, :creation_time, :title, :content, :filepath)';
              $stmt = $pdo->prepare($sql);
              $stmt->bindParam(':author', $_SESSION['UserName']);
              $stmt->bindParam(':creation_time', $todaysDate, PDO::PARAM_STR, 10);
              $stmt->bindParam(':title', $title);
              $stmt->bindParam(':content', $content);
              $stmt->bindParam(':filepath', $filePath);
              $success = $stmt->execute();

              if ($success) {
                  header('Location:guestbook.php');
                  exit();
              } else {
                  echo '發表失敗，請再試一次';
                  echo $dbh->errorInfo();
              }
              $dbh->close();
          } catch (PDOException $e) {
              echo 'create data'.$e->getMessage();
          }
          unset($pdo);
      }
      ?>
    </div>
    <div class="create_title">
      <h2 class="create_title_text">Create a new post</h2>
    </div>
    <form action="" method='post' class="create_form">
      <input type="text" name="title" cols="40" rows="5"></input>
      <textarea class="create_text" name="content" cols="40" rows="5"></textarea>
      <input class="right_btn" type='submit' name='submit' value='Save'>
    </form>
  </div>
</body>
</html>