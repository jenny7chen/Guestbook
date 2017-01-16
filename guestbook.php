<html>
<head>
  <meta charset="utf-8">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link href="main.css?version=1" rel="stylesheet">
  <script type="text/javascript" link="guestbook.js"></script>
</head>
<body>
  <?php
  require_once 'cookie_setting.php';
  require_once 'post.php';
  session_save_path(realpath(dirname($_SERVER['DOCUMENT_ROOT']).'/../session'));
  $cookieSetting = new CookieSetting();

  if (isset($_COOKIE['session_id']) && !empty($_COOKIE['session_id'])) {
      session_id($_COOKIE['session_id']);
      session_start();
  } else {
      session_start();
      setcookie('session_id', session_id(), $cookieSetting->getExpires(), $cookieSetting->path, $cookieSetting->domain, $cookieSetting->secure, $cookieSetting->httpOnly);
  }
  ?>

  <div id="top_bar">
    <?php
    if ($_SESSION['LoginSuccess']) {
        echo "<a class='login_user' href='logout.php'>Log Out</a>";
    }
    ?>
    <h4 class="login_user"><?php echo '&nbsp;'.$_SESSION['UserName']; ?></h4>
  </div>
  <div id="content">
    <?php
    if ($_SESSION['LoginSuccess']) {
        echo <<< 'CREATE_BTN'
        <form action="create_post.php" method='post'>
            <input class="button" type='submit' class="button" name='submit' value='Create a Post'>
        </form>
CREATE_BTN;

        if ($_SESSION['UserName'] == 'admin') {
            echo <<< 'CLEAR_BTN'
        <form action="" method='post'>
            <input class="button" type='submit' class="button" name='submit_clear_all' value='Clear All Post'>
        </form>
CLEAR_BTN;
        }

        generatePostList();

        if (isset($_POST['submit_clear_all']) && !empty($_POST['submit_clear_all']) && $_SESSION['UserName'] == 'admin') {
            clearAllPost();
        }

        if (isset($_POST['submit_delete']) && isset($_POST['post_id']) && !empty($_POST['post_id'])) {
            deletePostById($_POST['post_id']);
        }
    }
    function generatePostList()
    {
        $posts = fetchPostsFromDB();
        $dom = new DOMDocument('1.0', 'utf-8');
        foreach ($posts as $post) {
            appendHTML($dom, $post);
        }
        $hhhh = str_replace('&zwnj;', '', $dom->saveHTML());
        echo html_entity_decode($hhhh, ENT_NOQUOTES | ENT_HTML401, 'UTF-8');
    }

    function appendHTML(DOMNode &$parent, Post $post)
    {
        $node = new DOMDocument('1.0', 'utf-8');
        // We need to validate our document before refering to the id
        $node->validateOnParse = true;
        $node->preserveWhiteSpace = false;
        $node->formatOutput = true;
        $node->loadHTMLfile('guestbook_item.html');
        $titleNode = $node->getElementById('text_title');
        $timeNode = $node->getElementById('text_time');
        $contentNode = $node->getElementById('text_content');
        $deleteBtn = $node->getElementById('post_delete_btn');
        $postId = $node->getElementById('post_id');
        $title_text_node = $node->createTextNode("$post->title");
        $time_text_node = $node->createTextNode($post->author."&nbsppublished on&nbsp$post->creation_time");
        $content_text_node = $node->createTextNode($post->content);
        $titleNode->appendChild($title_text_node);
        $timeNode->appendChild($time_text_node);
        $contentNode->appendChild($content_text_node);
        if ($post->author !== $_SESSION['UserName']) {
            $deleteBtn->setAttribute('style', '‌display:none;');
        } else {
            $postId->setAttribute('value', $post->id);
        }
        $all = $node->getElementById('node');
        $all->setAttribute('id', $post->id);
        $wrap = $parent->createElement('div', $node->saveHTML($all));
        $wrap = $parent->importNode($wrap);
        $parent->appendChild($wrap);
    }

    function fetchPostsFromDB()
    {
        require_once 'db_config.php';
        $dbSetting = new DBSetting();
        try {
            $pdo = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);
            $pdo->query('SET NAMES utf8');
            $sql = 'SELECT * FROM posts ORDER BY creation_time DESC';
            $sth = $pdo->prepare($sql);
            $success = $sth->execute();
            $result = $sth->fetchAll();
        } catch (PDOException $e) {
            echo 'fetch post data : '.$e->getMessage();
        }
        unset($pdo);

        $postArray = array();
        foreach ($result as $value) {
            $post = new Post();
            $post->content = $value['content'];
            $post->filePath = $value['filepath'];
            $post->author = $value['author'];
            $post->title = $value['title'];
            $post->id = $value['id'];
            $post->creation_time = $value['creation_time'];
            array_push($postArray, $post);
        }

        return $postArray;
    }

    function clearAllPost()
    {
        require_once 'db_config.php';
        $dbSetting = new DBSetting();
        try {
            $pdo = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);
            $pdo->query('SET NAMES utf8');
            $sql = 'DELETE FROM posts';
            $sth = $pdo->prepare($sql);
            $success = $sth->execute();
        } catch (PDOException $e) {
            echo 'delete post data : '.$e->getMessage();
        }
        unset($pdo);
        header('Refresh:0');
    }

    function deletePostById($id)
    {
        require_once 'db_config.php';
        $dbSetting = new DBSetting();
        try {
            $pdo = new PDO("mysql:host=$dbSetting->host;dbname=$dbSetting->dbname;port=$dbSetting->port", $dbSetting->user1, $dbSetting->user1pass);
            $pdo->query('SET NAMES utf8');
            $sql = "DELETE FROM posts WHERE id=$id";
            $sth = $pdo->prepare($sql);
            $success = $sth->execute();
        } catch (PDOException $e) {
            echo 'delete post data : '.$e->getMessage();
        }
        unset($pdo);
        header('Refresh:0');
    }

    ?>
    </div>
  </div>
</body>
</html>
