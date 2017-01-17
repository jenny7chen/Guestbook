<html>
<head>
  <meta charset="utf-8">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link href="main.css?version=2" rel="stylesheet">
</head>
<body id="edit_bg">
  <div id="edit" class="edit">
    <?php
    require_once 'db_init.php';
    require_once 'db_table_init.php';
    require_once 'cookie_setting.php';
    require_once 'Post.php';

    session_save_path(realpath(dirname($_SERVER['DOCUMENT_ROOT']).'/../session'));
    $cookieSetting = new CookieSetting();

    if (isset($_COOKIE['session_id']) && !empty($_COOKIE['session_id'])) {
        session_id($_COOKIE['session_id']);
        session_start();
    } else {
        session_start();
        setcookie('session_id', session_id(), $cookieSetting->getExpires(), $cookieSetting->path, $cookieSetting->domain, $cookieSetting->secure, $cookieSetting->httpOnly);
    }
    if (isset($_POST['submit_create']) || isset($_POST['submit_modify'])) {
        $post = new Post();
        if (isset($_POST['submit_modify'])) {
            require_once 'DBPostActions.php';
            $dbAction = new DBPostActions();
            $post = $dbAction->getPostData($_POST['post_id']);
        }
        $dom = new DOMDocument('1.0', 'utf-8');
        appendEditor($dom, $post);
        $node = str_replace('&zwnj;', '', $dom->saveHTML());
        echo html_entity_decode($node, ENT_NOQUOTES | ENT_HTML401, 'UTF-8');

    } elseif (isset($_POST['submit_edit_finished']) || !empty($_POST['submit_edit_finished'])) {
        if (!isset($_POST['content']) || empty($_POST['content'])) {
        } elseif (!isset($_POST['title']) || empty($_POST['title'])) {
        } else {
            require_once 'DBPostActions.php';
            $dbAction = new DBPostActions();
            $filePath = '';
            $todaysDate = date('Y-m-d H:i:s');
            $success = false;
            if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
                $success = $dbAction->modifyPost($_POST['post_id'], $_POST['title'], $_POST['content'], $filePath, $todaysDate);
            } else {
                $success = $dbAction->createNewPost($_POST['title'], $_POST['content'], $_SESSION['UserName'], $filePath, $todaysDate);
            }
            if ($success) {
                header('Location:guestbook.php');
                exit();
            } else {
                echo '儲存失敗，請再試一次';
            }
        }
    }

    function appendEditor($parent, $post)
    {
        $node = new DOMDocument('1.0', 'utf-8');
        // We need to validate our document before refering to the id
        $node->validateOnParse = true;
        $node->preserveWhiteSpace = false;
        $node->formatOutput = true;
        $node->loadHTMLfile('edit_post.html');
        $titleNode = $node->getElementById('edit_title');
        $contentNode = $node->getElementById('edit_content');
        $postId = $node->getElementById('post_id');
        $titleNode->setAttribute('value', $post->title);
        $content_text_node = $node->createTextNode($post->content);
        $contentNode->appendChild($content_text_node);
        $postId->setAttribute('value', $post->id);
        $all = $node->getElementById('node');
        $wrap = $parent->createElement('div', $node->saveHTML($all));
        $wrap = $parent->importNode($wrap);
        $parent->appendChild($wrap);
    }
    ?>
</div>
</body>
</html>
