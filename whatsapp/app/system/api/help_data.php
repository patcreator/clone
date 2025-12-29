<?php
header('Content-Type: application/json');
if (file_exists("../cogs/db.php")) include_once '../cogs/db.php';
if (file_exists("../../api/db_helper.php")) include_once '../../api/db_helper.php';
if (file_exists("app/system/cogs/db.php")) include_once 'app/system/cogs/db.php';
if (file_exists("app/api/db_helper.php")) include_once 'app/api/db_helper.php';
try{



    // Handle AJAX requests
    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action === 'list') {
            $stmt = $pdo->query("SELECT * FROM help_articles ORDER BY updated_at DESC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;
        }

        if ($action === 'add') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $contentx = preg_replace('/\s/', ', ', $content);
            echo json_encode(executeQuery("INSERT INTO `help_articles`(`title`, `slug`, `category`, `content`, `keywords`, `is_active`, `created_at`, `updated_at`, `status`) VALUES ('$title',uuid(),'all','$content','$contentx','1',current_timestamp(),current_timestamp(),'active')"));
            exit;
        }

        if ($action === 'update') {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $stmt = $pdo->prepare("UPDATE help_articles SET title=?, content=? WHERE id=?");
            $stmt->execute([$title, $content, $id]);
            echo json_encode(['success'=>true,'message'=>'Content changed']);
            exit;
        }

        if ($action === 'delete') {
            $id = $_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM help_articles WHERE id=?");
            $stmt->execute([$id]);
            echo json_encode(['success'=>true,'message'=>'Data deleted']);
            exit;
        }
    }else{
        echo json_encode(['success'=>false,'message'=>'Action is required']);
    }
}
catch( Exception $e){
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);

}
?>
