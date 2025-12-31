 <?php
 include_once 'db_helper.php';
 include_once 'crud.php';
 $path = "/clone/whatsapp/";
 header("Content-Type:application/json");
 session_start();
 $id = $_SESSION['user_id'];
/**
 * Get detailed info about a filesystem entry (file or directory).
 *
 * Returns an associative array with:
 *  - name
 *  - path (realpath)
 *  - type (mimetype)
 *  - is_file, is_dir
 *  - size_bytes, size_mb
 *  - inode
 *  - owner_uid, owner_name (if available)
 *  - group_gid, group_name (if available)
 *  - perms_octal (string like "0755")
 *  - perms_human (rwxr-xr-x)
 *  - created_at (timestamp or null)  -- on many Unix systems this is inode change time (ctime)
 *  - modified_at (timestamp)
 *  - accessed_at (timestamp)
 */

class validator{
    function isEmpty(array $data, $params = []) {
        foreach($data as $index => $d){
            if(empty($d)) {echo json_encode(['success'=>false, 'message'=>$params[$index]??''.' is empty']);exit;}
            else continue;
        }
        return false;
    }
    function isCSV(array $data) {
        foreach($data as $d){
            if (count(explode(',', $d)) <= 1) {echo json_encode(['success'=>false, 'message'=>$d.' is not CSV']);exit;}
        }
        return true;
    }

    function isChunk(array $data, string $sep = ',') {
        foreach($data as $d){
            if (count(explode($sep, $d)) <= 1) {echo json_encode(['success'=>false, 'message'=>$d.' is not CSV']);exit;}
        }
        return true;
    }

    function isNumber(array $data) {
        foreach($data as $d){
            $d = (int)$d;
            if (!is_int($d)) {echo json_encode(['success'=>false, 'message'=>$d.' is not a number']);exit;}
        }
        return true;
    }
} 
$validator = new validator();

class send{
    function __construct($message, $receiver, $sender, $data) {
        $this->message = $message;
        $this->receiver = $receiver;
        $this->sender = $sender;
        $this->data = $data;
    }
    public function document($file) {
        
    }
    public function photo($data) {
        
    }
    public function video($data) {
        
    }
    public function audio($data) {
        
    }
    public function poll($data) {
        
    }
    public function event($data) {
        
    }
    public function new_sticker($data) {
        
    }
    public function catalog($data) {
        
    }
    public function quick_replay($data) {
        
    }
    public function location($data) {
        
    }
}


class profile{
     public function __construct($id) {
        $this->id = $id;
     }
     public static function upload($files) {
        if(!is_array($files)){
            return;
        }
          // Handles Dropzone uploads
    $basePath = '../../' . ltrim($_GET['path'] ?? $_GET['file'], './');

    if (!is_dir($basePath)) {
        echo json_encode(['success' => false, 'message' => 'Invalid upload directory']);
        exit;
    }

    if (!empty($_FILES['file']['name'])) {
                $tmp = $_FILES['file']['tmp_name'];
                $name = basename($_FILES['file']['name']);
                $target = rtrim($basePath, '/') . '/' . $name;

                if (move_uploaded_file($tmp, $target)) {
                    echo json_encode(['success' => true, 'message' => 'File uploaded successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No file uploaded']);
            }
     }
     public function change_photo($path, $type = 'profile') {
        $files = $_FILES['file']??[];
        upload($files,$path);
        switch ($type) {
            case 'profile':
                $data = executeQuery("
                    UPDATE users set image=? where id = ?
                ","",[$path,$id]);
                return json_encode($data);
                break;
            case 'background':
                $data = executeQuery("
                    UPDATE users set background=? where id = ?
                ","",[$path,$id]);
                return json_encode($data);
                break;
            
            default:
                return json_encode(["message"=>'Invalid option', 'success'=>false,'data'=>null]);
                break;
        }
     }


}

$tables = executeQuery("SHOW TABLES","COLUMN")['data'];

function get_file_details(string $path): array
{
    $result = [
        'exists' => false,
        'name' => null,
        'path' => null,
        'type' => null,
        'is_file' => false,
        'is_dir' => false,
        'size_bytes' => null,
        'size_mb' => null,
        'inode' => null,
        'owner_uid' => null,
        'owner_name' => null,
        'group_gid' => null,
        'group_name' => null,
        'perms_octal' => null,
        'perms_human' => null,
        'created_at' => null,
        'modified_at' => null,
        'accessed_at' => null,
    ];

    // basic existence check
    if (!file_exists($path)) {
        return $result;
    }

    $result['exists'] = true;

    // full canonical path (if available)
    $real = realpath($path);
    $pth = $real !== false ? $real : $path;
    $result['path'] = str_replace('\\', "/\n",$pth);
    $result['name'] = basename($path);
    $result['is_file'] = is_file($path);
    $result['is_dir']  = is_dir($path);

    // size
    if (is_file($path)) {
        $size = filesize($path);
        $result['size_bytes'] = $size === false ? null : $size;
        $result['size_mb'] = ($size === false) ? null : round($size / 1024 / 1024, 3);
    } else {
        $result['size_bytes'] = null;
        $result['size_mb'] = null;
    }

    // inode & times via stat
    $st = @stat($path);
    if ($st !== false) {
        $result['inode'] = $st['ino'] ?? null;
        $result['modified_at'] = $st['mtime'] ?? null;
        $result['accessed_at'] = $st['atime'] ?? null;
        // NOTE: on many Unix systems filectime is inode-change-time, not creation time.
        $result['created_at'] = $st['ctime'] ?? null;
    }

    // owner/group
    $owner = @fileowner($path);
    if ($owner !== false) {
        $result['owner_uid'] = $owner;
        // try to resolve name if posix functions exist
        if (function_exists('posix_getpwuid')) {
            $pw = posix_getpwuid($owner);
            $result['owner_name'] = $pw['name'] ?? null;
        }
    }
    $group = @filegroup($path);
    if ($group !== false) {
        $result['group_gid'] = $group;
        if (function_exists('posix_getgrgid')) {
            $gr = posix_getgrgid($group);
            $result['group_name'] = $gr['name'] ?? null;
        }
    }

    // permissions
    $perms = fileperms($path);
    if ($perms !== false) {
        $result['perms_octal'] = sprintf('%04o', $perms & 0x0FFF);
        $result['perms_human'] = perms_to_string($perms);
    }

    // mime/type detection (for files)
    if (is_file($path)) {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $mt = finfo_file($finfo, $path);
                finfo_close($finfo);
                $result['type'] = $mt ?: null;
            }
        }
        // fallback
        if (empty($result['type']) && function_exists('mime_content_type')) {
            $result['type'] = @mime_content_type($path) ?: null;
        }
    } else {
        // directories can be reported as "directory"
        $result['type'] = 'directory';
    }

    return $result;
}

/**
 * Convert fileperms() int to rwxr-xr-x string.
 * Source: adapted common implementation.
 */
function perms_to_string(int $perms): string
{
    $info = '';

    // file type
    if (($perms & 0xC000) === 0xC000) {
        $info .= 's'; // socket
    } elseif (($perms & 0xA000) === 0xA000) {
        $info .= 'l'; // symbolic link
    } elseif (($perms & 0x8000) === 0x8000) {
        $info .= '-'; // regular
    } elseif (($perms & 0x6000) === 0x6000) {
        $info .= 'b'; // block special
    } elseif (($perms & 0x4000) === 0x4000) {
        $info .= 'd'; // directory
    } elseif (($perms & 0x2000) === 0x2000) {
        $info .= 'c'; // character special
    } elseif (($perms & 0x1000) === 0x1000) {
        $info .= 'p'; // fifo pipe
    } else {
        $info .= 'u'; // unknown
    }

    // owner
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ?
                (($perms & 0x0800) ? 's' : 'x') :
                (($perms & 0x0800) ? 'S' : '-'));

    // group
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ?
                (($perms & 0x0400) ? 's' : 'x') :
                (($perms & 0x0400) ? 'S' : '-'));

    // world
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ?
                (($perms & 0x0200) ? 't' : 'x') :
                (($perms & 0x0200) ? 'T' : '-'));

    return $info;
}

/**
 * Recursively list a directory and return details for each entry.
 * - $path must be a directory
 * - returns array of file detail arrays
 */
// function list_directory_details(string $path, bool $recursive = false): array
// {
//     $out = [];
//     if (!is_dir($path)) return $out;

//     $it = new DirectoryIterator($path);
//     foreach ($it as $node) {
//         if ($node->isDot()) continue;
//         $p = $node->getPathname();
//         $out[] = get_file_details($p);
//         if ($recursive && $node->isDir()) {
//             $out = array_merge($out, list_directory_details($p, true));
//         }
//     }
//     return $out;
// }

/* Example usage */
// single file
// $info = get_file_details('/path/to/file.txt');
// echo json_encode($info, JSON_PRETTY_PRINT);

// directory recursive
// $all = list_directory_details('/path/to/dir', true);
// echo json_encode($all, JSON_PRETTY_PRINT);


// api.php

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    // 1️⃣ Latest 5 blogs
    case 'get_latest_blogs':
        $stmt = $pdo->query("SELECT Blog_id, Title, Slug, Publish_Date 
                             FROM blog 
                             WHERE Status='Published' 
                             ORDER BY Publish_Date DESC 
                             LIMIT 5");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
        exit;

    case 'get_roles':
        $stmt = $pdo->query("SELECT * FROM roles WHERE status='active' ORDER BY id DESC");
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $roles]);
        exit;
    case 'get-users':
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->query("SELECT id, username, image, email, phone, status FROM users WHERE status='active' ORDER BY id DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['id' => $user_id, 'success' => true, 'message' =>'successfully retrived', 'data' => $data]);
        exit;
    case 'get-grouped-users':
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->query("SELECT id, username, image, email, phone, status FROM users WHERE status='active' ORDER BY id DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $grouped = [];
        foreach ($data as $contact) {
            $letter = strtoupper($contact['username'][0]??'');
            $grouped[$letter][] = $contact;
        }
        // print_r($grouped);

        echo json_encode(['id' => $user_id, 'success' => true, 'message' =>'successfully retrived', 'data' => $grouped]);
        exit;

    case 'create-group':
    $created_by = $_SESSION['user_id'] ?? 0;

    if (!$created_by) {
        echo json_encode([
            'success' => false,
            'message' => 'Unauthorized'
        ]);
        exit;
    }

    $group_name = trim($_POST['group_name'] ?? '');
    $user_ids   = $_POST['user_ids'] ?? [];

    if (empty($user_ids)) {
        echo json_encode([
            'success' => false,
            'message' => 'No users selected'
        ]);
        exit;
    }

    /* ---------- IMAGE UPLOAD ---------- */
    $imagePath = null;

    if (!empty($_FILES['group_icon']['name'])) {

        $uploadDir = '../system/filemanager/uploads/groups/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['group_icon']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid image type'
            ]);
            exit;
        }

        $fileName = 'group_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $target   = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['group_icon']['tmp_name'], $target)) {
            echo json_encode([
                'success' => false,
                'message' => 'Image upload failed'
            ]);
            exit;
        }

        $imagePath = $fileName;
    }

    try {

        $pdo->beginTransaction();

        /* ---------- INSERT GROUP ---------- */
        $stmt = $pdo->prepare("
            INSERT INTO groups (name, image, created_by)
            VALUES (:name, :image, :created_by)
        ");

        $stmt->execute([
            ':name'       => $group_name ?: null,
            ':image'      => $imagePath,
            ':created_by' => $created_by
        ]);

        $group_id = $pdo->lastInsertId();

        /* ---------- ADD CREATOR ---------- */
        $stmtUser = $pdo->prepare("
            INSERT INTO groups_and_users (`group`, `user`)
            VALUES (:group, :user)
        ");

        $stmtUser->execute([
            ':group' => $group_id,
            ':user'  => $created_by
        ]);

        /* ---------- ADD MEMBERS ---------- */
        foreach ($user_ids as $user_id) {

            if ($user_id == $created_by) continue;

            $stmtUser->execute([
                ':group' => $group_id,
                ':user'  => (int)$user_id
            ]);
        }

        $pdo->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Group created successfully',
            'group_id' => $group_id
        ]);
        exit;

    } catch (Exception $e) {

        $pdo->rollBack();

        echo json_encode([
            'success' => false,
            'message' => 'Failed to create group',
            'error'   => $e->getMessage()
        ]);
        exit;
    }
    exit;














    case 'get-my-id':
            $user_id = $_SESSION['user_id']; // the logged-in user’s ID
            echo json_encode(['success' => true, 'message' =>'successfully retrived', 'id' => $user_id]);
        exit;
    case 'get-my-messages':
        $sender = $_POST['sender'] ?? $_GET['sender'] ?? '';
        $receiver = $_POST['receiver'] ?? $_GET['receiver'] ?? '';
        if (
            isset($receiver) && 
            !empty($receiver) && 
            is_numeric($receiver) && 
            isset($sender) && 
            !empty($sender) && 
            is_numeric($sender)
        ) {
            $not_me = $receiver == $_SESSION['user_id'] ? $sender : $receiver;
            //$sql = "SELECT *,m.id as mid FROM message m inner join users u on u.id=m.sender where m.sender = $sender and m.receiver = $receiver or m.sender = $receiver and m.receiver = $sender ORDER by m.created_at asc";
            $messages = executeQuery("SELECT 
                    
                    m.*, m.id as mid, substr(time(m.created_at),1,5) as `time`


                FROM message m inner join users u on u.id=m.sender where m.sender = ? and m.receiver = ? or m.sender = ? and m.receiver = ? ORDER by m.created_at asc","",[$sender,$receiver,$receiver,$sender])['data'];
            $not_me_data = executeQuery("SELECT 
                    `id`,
                    `username`,
                    `email`,
                    `phone`,
                    `image`,
                    `created_at`,
                    `updated_at`,
                    `status`
             FROM users where id = ? limit 1", "", [$not_me])['data'][0]??[];
            echo json_encode(['success'=>true, 'message'=>'data retrived', 'messages'=>$messages, 'not-me'=>$not_me_data]);
            }else{
                echo json_encode(['success' => false, 'message' =>'Invalid data']);
                exit;
            }
            exit;
    case 'send':
        $sender = $_POST['sender']??null;
        $receiver = $_POST['receiver']??null;
        $content = $_POST['content']??null;
        $reply = $_POST['reply']??null;
        $type = $_POST['type']??null;
        $is_group = $_POST['is_group']??null;
        $stmt = $pdo->prepare(" INSERT INTO `message`(`sender`, `receiver`, `content`, `content_type`, `created_at`, `updated_at`, `status`, `is_group`, `deleted_by_sender`, `deleted_by_receiver`, `reply`) VALUES(?, ?, ?, ?, current_timestamp(), current_timestamp(), 'pending', ?, null, null, ?)");
        $stmt->execute([$sender, $receiver, $content, $type, $is_group, $reply]);
        header("location:$path?r=$receiver&s=$sender");
        echo json_encode(['success' => true, 'message'=>"message sent"]);
        exit;
    case 'forward':
        $users = $_POST['users']??$_GET['users']??'';
        $messages = $_POST['messages']??$_GET['messages']??'';
        $from=$_POST['from'] ?? $_GET['from'] ?? '';
        $isgroup = $_POST['is_group']??$_GET['is_group']??'';
        $validator->isEmpty([$users,$messages,$from]);
        $validator->isCSV([$users, $messages]);
        $validator->isNumber([$from,$isgroup]);
        $users = explode(',', $users);
        $messages= explode(',', $messages);
        $isgroup = $isgroup == 1?1:0;
        foreach ($users as $u){
            foreach($messages as $m){
                $pdo->beginTransaction();
                executeQuery("INSERT INTO forwards (sender, receiver, message_id) values (?, ?, ?)","",[$from, $u, $m]);
                executeQuery("INSERT INTO message (sender, receiver, content, content_type, is_group) values (?, ?, ?, ?, ?)","",[$from, $u, $m, 'forward', $isgroup]);
                $pdo->commit();
            }
        }
         echo json_encode([
                'success' => true,
                'message' => 'Message forwarded'
            ]);
       exit; 

    case 'delete-selected-messages':
        $messages = $_POST['messages'] ?? $_GET['messages'] ?? null;
        $id= $_SESSION['user_id']??null;
        $isgroup = $_POST['is_group']??$_GET['is_group']??'';
        $validator->isEmpty([$messages,$id],['messages','id']);
        $validator->isNumber([$id,$isgroup]);
        $isgroup = $isgroup == 1?1:0;
        $messages = explode(',', $messages);
        $pdo->beginTransaction();
        foreach($messages as $m){
            $sql = "UPDATE `message` SET deleted_by_sender = if(sender = ?,1,0), deleted_by_receiver = if(receiver = ?,1,0) where id = ?;";
            executeQuery($sql,'',[$id,$id,$m]);
        }
        $pdo->commit();
        echo json_encode([
            'success' => true,
            'message' => 'Message deleted'
        ]);
       exit;
    case 'star-messages':
        $messages = $_POST['messages'] ?? $_GET['messages'] ?? null;
        $id= $_SESSION['user_id']??null;
        $isgroup = $_POST['is_group']??$_GET['is_group']??'';
        $validator->isEmpty([$messages,$id],['messages','id']);
        $validator->isNumber([$id,$isgroup]);
        $isgroup = $isgroup == 1?1:0;
        $messages = explode(',', $messages);
        $pdo->beginTransaction();
        foreach($messages as $m){
            $sql = "INSERT INTO `started_messages`(message_id, started_by) VALUES (?, ?)";
            executeQuery($sql,'',[$m,$id]);
        }
        $pdo->commit();
        echo json_encode([
            'success' => true,
            'message' => 'Message Stared'
        ]);
       exit; 
    case 'download-messages':
            $fullPath = '../../';
            $messages = $_POST['messages']??$_GET['messages']??'';
            $msg_arr = explode(',', $messages);
            $prm = array_map(fn($item) => '?', $msg_arr);
            $prm = implode(',', $prm);
            $validator->isEmpty([$messages]);
            $validator->isCSV([$messages]);
            $validator->isNumber($msg_arr);
            $fetch = executeQuery("SELECT 
                                    m.id AS mid,
                                    u.id AS uid,
                                    m.sender,
                                    m.receiver,
                                    sn.username AS 'from',
                                    rc.username AS 'to',
                                    m.content as message,
                                    date(m.created_at) as 'date',
                                    substr(time(m.created_at),1,5) as 'time'
                                FROM message m
                                    INNER JOIN users u ON u.id = m.sender
                                    INNER JOIN users sn ON sn.id = m.sender
                                    INNER JOIN users rc ON rc.id = m.receiver
                                WHERE m.id IN ($prm)
                                AND (m.sender = ? OR m.receiver = ?);", 
                    '', 
                    array_merge($msg_arr, [$id, $id]))['data']??[];
            foreach ($fetch as $value) {
                array_shift($value);
                array_shift($value);
                array_shift($value);
                array_shift($value);
                $msg[] = $value;
            }
            $msg = json_encode($msg);
            // echo $msg;
            // exit;
            file_put_contents("$fullPath.json", $msg);
            // Set headers to force download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . "uruganiriro-chat.json" . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize("$fullPath.json"));
            // Clear output buffer and read the file
            readfile("$fullPath.json");
            unlink("$fullPath.json");
        exit;
    case 'upload-file':

        if (!isset($_FILES['file'])) {
            echo json_encode([
                'success' => false,
                'message' => 'No file received'
            ]);
            exit;
        }

        $file = $_FILES['file'];
        $uploadDir = '../system/filemanager/uploads/documents/';
        $fileName = time() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {

            $sender = $_GET['sender'] ?? $_POST['sender']??null;
            $receiver = $_GET['receiver'] ?? $_POST['receiver']??null;
            $content = 'document:'.$fileName;
            $type = 'document';
            $is_group = $_GET['is_group'] ?? $_POST['is_group']??null;
            $stmt = $pdo->prepare(" INSERT INTO `message`(`sender`, `receiver`, `content`, `content_type`, `created_at`, `updated_at`, `status`, `is_group`, `deleted_by_sender`, `deleted_by_receiver`) VALUES(?, ?, ?, ?, current_timestamp(), current_timestamp(), 'pending', ?, null, null)");
            $stmt->execute([$sender, $receiver, $content, $type, $is_group ]);
            echo json_encode([
                'success' => true,
                'message' =>'Document uploaded successfully',
                'data' => [
                    'file_name' => $fileName,
                    'file_size' => $file['size'],
                    'file_type' => $file['type']
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'File upload failed'
            ]);
        }

        exit;


    case 'get_role_permissions':
        $role_id = (int)$_POST['role_id'];
        $stmt = $pdo->prepare("SELECT table_name, can_access FROM role_permissions WHERE role_id=?");
        $stmt->execute([$role_id]);
        $perms = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        echo json_encode(['success' => true, 'data' => $perms]);
        exit;
    case 'mute-notifications':
        $time = $_POST['time']??$_GET['time']??null;
        $muted_id = $_POST['user']??$_GET['user']??null;
        $now = new DateTime();
        $plus8Hours = clone $now;
        $plus8Hours->modify('+8 hours');
        $plus1Week = clone $now;
        $plus1Week->modify('+1 week');
        $now = $now->format("Y-m-d H:i:s");
        switch($time){
            case '1 week':
                $end = $plus1Week->format("Y-m-d H:i:s");
                break;
            case '8 hours':
                $end = $plus8Hours->format("Y-m-d H:i:s");
                break;
            case'always':
                $end = NULL;
                break;
            default:
            $end = NULL;
        }
        $data = executeQuery("INSERT INTO `mute_notifications`(
                `muted_user`,
                `muted_by`,
                `muted_at`,
                `ended_at`,
                `muted_option`
            )
            VALUES(
                ?, ?, ?, ?, ?
            )",'',[$muted_id,$id,$now,$end,$time]);
            echo json_encode($data);
        exit;
    case 'disappearing-message':
                $time = $_POST['time']??$_GET['time']??null;
                $receiver = $_POST['user']??$_GET['user']??null;
                $now = new DateTime();
                $plus24Hours = clone $now;
                $plus24Hours->modify('24 hours');
                $plus1Week = clone $now;
                $plus1Week->modify('+7 days');
                $plus3Months = clone $now;
                $plus3Months->modify('+90 days');
                $now = $now->format("Y-m-d H:i:s");
                switch($time){
                    case '24 hours':
                        $end = $plus24Hours->format("Y-m-d H:i:s");
                        break;
                    case '7 days':
                        $end = $plus1Week->format("Y-m-d H:i:s");
                        break;
                    case'90 days':
                        $end = $plus3Months->format("Y-m-d H:i:s");
                        break;
                    default:
                    $end = NULL;
                }
                $data = executeQuery("INSERT INTO `disappearing_messages`(
                        `sender`,
                        `receiver`,
                        `start_date`,
                        `end_date`,
                        `time_interval`
                    )
                    VALUES(
                        ?, ?, ?, ?, ?
                    )",'',[$id,$receiver,$now,$end,$time]);
                    echo json_encode($data);
                exit;
    case 'report-user':
                $receiver = $_POST['user']??$_GET['user']??null;
                $data = executeQuery("INSERT INTO `reported_users`(
                        `sender`,
                        `receiver`
                    )
                    VALUES(
                        ?, ?
                    )",'',[$id,$receiver]);
                    echo json_encode($data);
                exit;
    case 'client-notes':
            $receiver = $_POST['user']??$_GET['user']??null;
            $note = $_POST['note']??$_GET['note']??null;
            $data = executeQuery("INSERT INTO `client_notes`(
                    `sender`,
                    `receiver`,
                    `note`
                )
                VALUES(
                    ?, ?, ?
                )",'',[$id,$receiver, $note]);
                echo json_encode($data);
            exit;
    case 'reaction':
            $receiver = $_POST['user']??$_GET['user']??null;
            $reaction = $_POST['reaction']??$_GET['reaction']??null;
            $data = executeQuery("INSERT INTO `reactions`(
                    `sender`,
                    `receiver`,
                    `reaction`
                )
                VALUES(
                    ?, ?, ?
                )",'',[$id,$receiver, $reaction]);
                echo json_encode($data);
            exit;
    case 'pin-message':
        $time = $_POST['time']??$_GET['time']??null;
        $receiver = $_POST['user']??$_GET['user']??null;
        $message = $_POST['message']??$_GET['message']??null;
        $validator->isEmpty([$message,$receiver,$time],['message','receiver','time']);
        $validator->isNumber([$message,$receiver,$time]);
        $now = new DateTime();
        $plus24Hours = clone $now;
        $plus24Hours->modify('24 hours');
        $plus1Week = clone $now;
        $plus1Week->modify('+7 days');
        $plus3Months = clone $now;
        $plus3Months->modify('+90 days');
        $now = $now->format("Y-m-d H:i:s");
        switch($time){
            case '24 hours':
                $end = $plus24Hours->format("Y-m-d H:i:s");
                break;
            case '7 days':
                $end = $plus1Week->format("Y-m-d H:i:s");
                break;
            case'90 days':
                $end = $plus3Months->format("Y-m-d H:i:s");
                break;
            default:
                $end = $plus1Week->format("Y-m-d H:i:s");
        }
        $data = executeQuery("INSERT INTO `pinned_messages`(
                `message_id`,
                `sender`,
                `receiver`,
                `end_date`,
                `time_interval`
            )
            VALUES(
                ?, ?, ?, ?, ?
            )",'',[$message, $id, $receiver, $end, $time]);
            echo json_encode($data);
        exit;
    case 'block-user':
                $receiver = $_POST['user']??$_GET['user']??null;
                $data = executeQuery("INSERT INTO `blocked_users`(
                        `sender`,
                        `receiver`
                    )
                    VALUES(
                        ?, ?
                    )",'',[$id,$receiver]);
                    echo json_encode($data);
                exit;
    case 'clear-chat':
                $receiver = $_POST['user']??$_GET['user']??null;
                $data = executeQuery("INSERT INTO `clear_chats`(
                        `sender`,
                        `receiver`
                    )
                    VALUES(
                        ?, ?
                    )",'',[$id,$receiver]);
                    echo json_encode($data);
                exit;
    case 'delete-chat':
                $receiver = $_POST['user']??$_GET['user']??null;
                $data = executeQuery("INSERT INTO `delete_chats`(
                        `sender`,
                        `receiver`
                    )
                    VALUES(
                        ?, ?
                    )",'',[$id,$receiver]);
                    echo json_encode($data);
                exit;
    case 'message-info':
            $message = $_POST['message']??$_GET['message']??null;
            $data = executeQuery("SELECT * FROM message where id = ?",'',[$message])['data']??[];
            echo json_encode($data);
            exit;
        
    case 'create_role':
        $name = trim($_POST['name'] ?? '');
        if ($name === '') exit(json_encode(['success' => false, 'error' => 'Role name required']));
        $stmt = $pdo->prepare("INSERT INTO roles (name) VALUES (?)");
        $stmt->execute([$name]);
        echo json_encode(['success' => true]);
        exit;

    case 'update_role_permissions':
        $role_id = (int)$_POST['role_id'];
        $permissions = json_decode($_POST['permissions'] ?? '{}', true);
        if (!$role_id || !$permissions) exit(json_encode(['success' => false]));

        $pdo->prepare("DELETE FROM role_permissions WHERE role_id=?")->execute([$role_id]);
        $stmt = $pdo->prepare("INSERT INTO role_permissions (role_id, table_name, can_access) VALUES (?, ?, ?)");
        foreach ($permissions as $table => $can) {
            $stmt->execute([$role_id, $table, (int)$can]);
        }
        echo json_encode(['success' => true]);
        exit;
        
        // GET ALL MESSAGE FROM USERS
    case 'all-messages':
            $data = executeQuery("
                SELECT 
                    m.id,
                    m.sender,
                    m.receiver,
                    if(
                        CHAR_LENGTH(m.content) >= 20,
                        concat(substr(m.content,1,20) ,'...'),
                        m.content
                       ) as content,
                    m.content_type,
                    m.created_at as date_time,
                    date(m.created_at) as date,
                    substr(time(m.created_at),1,5) as time,
                    m.status,
                    m.is_group,
                    u.username,
                    u.phone,
                    u.email,
                    u.image,
                    count(u.username) as total_msg
                FROM `message` m inner join users u on u.id = m.sender where m.receiver = ? group by u.username ORDER BY m.`id` DESC
            ","",[$_SESSION['user_id']]);
            echo json_encode($data);
        exit;


    // 2️⃣ Categories list
    case 'get_categories':
        $stmt = $pdo->query("SELECT blog_category_name AS category, COUNT(b.Blog_id) AS total
                             FROM blog_categories c
                             LEFT JOIN blog b ON b.Category = c.blog_category_id
                             GROUP BY c.blog_category_id
                             ORDER BY total DESC");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
        exit;

    // 3️⃣ Latest comments
    case 'get_latest_comments':
        $stmt = $pdo->query("SELECT firstname, lastname, Email, message, createdDate
                             FROM blog_comments 
                             WHERE Status='active' 
                             ORDER BY createdDate DESC 
                             LIMIT 5");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
        exit;

    // 4️⃣ Top 5 authors
    case 'get_top_authors':
        $stmt = $pdo->query("SELECT a.blog_author_name AS author, COUNT(b.Blog_id) AS posts
                             FROM blog_author a
                             LEFT JOIN blog b ON b.Author = a.blog_author_id
                             GROUP BY a.blog_author_id
                             ORDER BY posts DESC
                             LIMIT 5");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
        exit;

    // 5️⃣ Blog page settings
    case 'get_blog_page_settings':
        $stmt = $pdo->query("SELECT `view_search`,`view_categories`,`view_recent_blog`,`recent_blog_number`,`view_blog_tags`,`custom_html`,`img_after_recent_post`,`img_after_tag`,`Plain_Text_title`,`Plain_Text_description`,`blog_style`,`pagination_style`,`show_author`,`show_author_img`,`show_single_category`,`title_limit`,`description_limit`,`cta_label`,`go-to-page`,`show-date`,`show-pagination`,`show-comment`,`showCategoryIcon`,`number_of_post` FROM blog_page LIMIT 1");
        echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
        exit;
    case 'update_blog_settings':
            try {
                $fields = $_POST;
                unset($fields['action']); // remove the action key

                $setParts = [];
                $params = [];

                foreach ($fields as $key => $val) {
                    // replace invalid characters with underscore for placeholder
                    $placeholder = str_replace('-', '_', $key);
                    $setParts[] = "`$key` = :$placeholder";
                    $params[$placeholder] = $val;
                }

                $sql = "UPDATE blog_page SET " . implode(', ', $setParts) . " ORDER BY blog_page_id ASC LIMIT 1";
                $stmt = $pdo->prepare($sql);

                foreach ($params as $placeholder => $value) {
                    $stmt->bindValue(":$placeholder", $value);
                }

                $stmt->execute();
                echo json_encode(['success' => true, 'message' => 'Settings updated successfully']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            exit;
        case 'get_settings':
            $stmt = $pdo->query("SELECT name, value FROM settings WHERE status = 'active'");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = [];
            foreach ($rows as $r) $data[$r['name']] = $r['value'];
            echo json_encode(['success' => true, 'data' => $data]);
            exit;
        case 'get_table_weekly_counts':
                $counts = [];
                $days = ['mon','tue','wed','thu','fri','sat','sun'];

                try {
                    foreach ($tables as $table) {
                        $tableCounts = array_fill_keys($days, 0);

                        // Count rows per weekday (assuming created_at exists)
                            $stmt = $pdo->prepare("
                                SELECT 
                                DAYOFWEEK(created_at) AS dow, COUNT(*) AS cnt
                                FROM `$table`
                                WHERE created_at IS NOT NULL AND status != 'deleted'
                                GROUP BY dow
                            ");
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // MySQL DAYOFWEEK(): 1=Sun, 2=Mon, 3=Tue...7=Sat
                            $map = [1=>'sun',2=>'mon',3=>'tue',4=>'wed',5=>'thu',6=>'fri',7=>'sat'];
                            $dayName = $map[$row['dow']] ?? null;
                            if ($dayName) $tableCounts[$dayName] = (int)$row['cnt'];
                        }

                        // Add total
                        $tableCounts['total'] = array_sum($tableCounts);
                        $counts[$table] = $tableCounts;
                    }

                    echo json_encode(['success' => true, 'data' => $counts]);
                } catch (PDOException $e) {
                    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                }
                exit;

            case 'get_new_feedback':
                try {
                    // Fetch the latest 10 active feedback entries
                    $stmt = $pdo->query("
                        SELECT name, subject, message, rating, type, created_at
                        FROM feedback
                        WHERE status = 'active'
                        ORDER BY created_at DESC 
                        LIMIT 5
                    ");
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    echo json_encode(['success' => true, 'data' => $rows]);
                } catch (PDOException $e) {
                    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                }
                exit;
            case 'get_latest_newsletters':
                    try {
                        // Fetch latest 5 subscribers
                        $stmt = $pdo->prepare("
                            SELECT email, status, created_at
                            FROM subscribers
                            ORDER BY created_at DESC limit 10
                        ");
                        $stmt->execute();
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        echo json_encode(['success' => true, 'data' => $rows]);
                    } catch (PDOException $e) {
                        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                    }
                    exit;

                // Save message (as sent or draft)
            case 'save_message':
                    try {
                        $recipient = trim($_POST['recipient'] ?? '');
                        $subject   = trim($_POST['subject'] ?? '');
                        $message   = trim($_POST['message'] ?? '');
                        $status    = ($_POST['status'] ?? 'draft') === 'sent' ? 'sent' : 'draft';

                        if (empty($recipient) || empty($message)) {
                            echo json_encode(['success' => false, 'error' => 'Recipient and message are required.']);
                            exit;
                        }

                        $stmt = $pdo->prepare("
                            INSERT INTO messages (recipient, subject, message, status)
                            VALUES (:recipient, :subject, :message, :status)
                        ");
                        $stmt->execute([
                            ':recipient' => $recipient,
                            ':subject' => $subject,
                            ':message' => $message,
                            ':status' => $status
                        ]);

                        echo json_encode(['success' => true, 'message' => 'Message saved successfully.', 'data' => [
                            'id' => $pdo->lastInsertId(),
                            'status' => $status
                        ]]);
                    } catch (PDOException $e) {
                        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                    }
                    exit;

                // (Optional) Fetch all messages
            case 'get_messages':
                    try {
                        $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        echo json_encode(['success' => true, 'data' => $rows]);
                    } catch (PDOException $e) {
                        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                    }
                    exit;
            case 'get_icons':
                $file ='../system/api/icons.json';
                if (!file_exists($file)) {
                    echo json_encode(['success' => false, 'error' => 'icons.json not found']);
                    exit;
                }

                $json = json_decode(file_get_contents($file), true);
                echo json_encode(['success' => true, 'data' => $json]);
                exit;

            case 'update_icon':
                $file ='../system/api/icons.json';
                $table = $_POST['table'] ?? '';
                $icon  = $_POST['icon'] ?? '';

                if (!$table || !$icon) {
                    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
                    exit;
                }

                $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
                $data[$table] = $icon;

                file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

                echo json_encode(['success' => true, 'message' => 'Icon updated', 'data' => $data]);
                exit;

        case 'get_tasks':
            $stmt = $pdo->query("SELECT id, task, completed FROM todos WHERE status='active' ORDER BY created_at DESC LIMIT 10");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            exit;

        case 'add_task':
            $task = trim($_POST['task'] ?? '');
            if ($task === '') exit(json_encode(['success' => false, 'error' => 'Empty task']));
            $stmt = $pdo->prepare("INSERT INTO todos (task) VALUES (?)");
            $stmt->execute([$task]);
            echo json_encode(['success' => true]);
            exit;

        case 'toggle_task':
            $id = (int)$_POST['id'];
            $completed = (int)$_POST['completed'];
            $stmt = $pdo->prepare("UPDATE todos SET completed=? WHERE id=?");
            $stmt->execute([$completed, $id]);
            echo json_encode(['success' => true]);
            exit;

        case 'delete_task':
            $id = (int)$_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM todos WHERE id=?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
            exit;
         case 'save_content':
                try {
                    $title     = trim($_POST['title'] ?? '');
                    $path      = trim($_POST['path'] ?? '');
                    $extension = trim($_POST['extension'] ?? 'txt');
                    $body      = trim($_POST['body'] ?? '');
                    $status    = $_POST['status'] === 'publish' ? 'publish' : 'draft';

                    if (empty($title) || empty($body)) {
                        echo json_encode(['success' => false, 'error' => 'Title and content are required.']);
                        exit;
                    }

                    $stmt = $pdo->prepare("
                        INSERT INTO contents (title, path, extension, body, status)
                        VALUES (:title, :path, :extension, :body, :status)
                    ");
                    $stmt->execute([
                        ':title' => $title,
                        ':path' => $path,
                        ':extension' => $extension,
                        ':body' => $body,
                        ':status' => $status
                    ]);

                    echo json_encode(['success' => true, 'message' => 'Content saved successfully.']);
                } catch (PDOException $e) {
                    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                }
                exit;


        case 'change_password':
            if (!isset($_SESSION['user_id'])) {
              echo json_encode(['success' => false, 'error' => 'Not authenticated.']);
              exit;
            }

            $user_id = $_SESSION['user_id']; // the logged-in user’s ID
            $current = $_POST['current'] ?? '';
            $new     = $_POST['new'] ?? '';
            $confirm = $_POST['confirm'] ?? '';

            if (empty($current) || empty($new) || empty($confirm)) {
              echo json_encode(['success' => false, 'error' => 'All fields are required.']);
              exit;
            }

            if ($new !== $confirm) {
              echo json_encode(['success' => false, 'error' => 'New passwords do not match.']);
              exit;
            }

            try {
              // Fetch current hashed password
              $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
              $stmt->execute([':id' => $user_id]);
              $user = $stmt->fetch(PDO::FETCH_ASSOC);

              if (!$user || !password_verify($current, $user['password'])) {
                echo json_encode(['success' => false, 'error' => 'Current password is incorrect.']);
                exit;
              }

              // Hash new password
              $hashed = password_hash($new, PASSWORD_DEFAULT);

              // Update password
              $update = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
              $update->execute([':password' => $hashed, ':id' => $user_id]);

              echo json_encode(['success' => true, 'message' => 'Password changed successfully.']);
            } catch (PDOException $e) {
              echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }

            exit;
        case 'filetree':
            function listDir($dir) {
                $result = [];
                $files = scandir($dir);
                foreach ($files as $file) {
                    if ($file === '.' || $file === '..') continue;
                    $path = $dir . DIRECTORY_SEPARATOR . $file;
                    $pathx = str_replace('../../\\', '', $path);
                    $pathx = str_replace('\\', '/', $pathx);
                    if (is_dir($path)) {
                        $result[] = [
                            "type" => "folder",
                            "name" => $file,
                            "path" => $path,
                            "children" => listDir($path)
                        ];
                    } else {
                        $result[] = [
                            "type" => "file",
                            "name" => $file,
                            "path" => $pathx
                        ];
                    }
                }
                return $result;
            }

            header("Content-Type: application/json");
            echo json_encode(listDir('../../'), JSON_PRETTY_PRINT);

            exit;


        case 'loadfile':
             $path = $_POST['file'] ?? $_GET['file']??'';
             echo file_get_contents('../../'.$path);
            exit;
        case 'savefile':
             $path = $_POST['path'] ?? $_GET['path']??'';
             $content = $_POST['content'] ?? $_GET['content']??'';
             if (!empty($content) && !empty($path)) {
                if (file_exists("../../$path")) {
                     $file = basename($path);
                     file_put_contents("../../$path", $content);
                     file_put_contents("../../app/system/settings/backup/files/$file", $content);
                    echo json_encode([
                        'success' => true, 
                        'message'=>'Change saved!', 
                        'text' => 'success'
                    ]);
                 } else{
                    file_put_contents("../../$path", $content);
                    echo json_encode([
                    'success' => false, 
                    'message'=>'New file was created', 
                    'text' => 'error'
                ]);
                 }
             }else{
                echo json_encode([
                    'success' => false, 
                    'message'=>'path and content are required', 
                    'text' => 'error'
                ]);
             }
            exit;


        case 'get_new_users':
            try {
                $stmt = $pdo->prepare("SELECT  username, email FROM users ORDER BY created_at DESC LIMIT 10");
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode(['success' => true, 'data' => $users]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            exit;
        case 'add_user':
            try {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, status) VALUES (:username, :email, :password, 'active')");
                $stmt->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':password' => $password
                ]);

                echo json_encode(['success' => true]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            exit;

        case 'update_settings':
            try {
                $fields = $_POST;
                unset($fields['action']);

                $stmt = $pdo->prepare("UPDATE settings SET value = :value, updated_at = NOW() WHERE name = :name");

                foreach ($fields as $key => $val) {
                    $stmt->execute([':value' => $val, ':name' => $key]);
                }

                echo json_encode(['success' => true]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            exit;
            // New Users Summary
    case 'get_new_users_summary':
        try {
            // --- Total users ---
            $stmt = $pdo->query("SELECT COUNT(*) AS total FROM users");
            $totalUsers = (int)$stmt->fetchColumn();

            // --- Active users ---
            $stmt = $pdo->query("SELECT COUNT(*) AS active FROM users WHERE status = 'active'");
            $activeUsers = (int)$stmt->fetchColumn();

            // --- Not active users ---
            $stmt = $pdo->query("SELECT COUNT(*) AS not_active FROM users WHERE status = 'not-active'");
            $notActiveUsers = (int)$stmt->fetchColumn();

            // --- Users registered today ---
            $stmt = $pdo->query("SELECT COUNT(*) AS day_users 
                                 FROM users 
                                 WHERE DATE(created_at) = CURDATE()");
            $dayUsers = (int)$stmt->fetchColumn();

            // --- Users registered this week ---
            $stmt = $pdo->query("SELECT COUNT(*) AS week_users 
                                 FROM users 
                                 WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)");
            $weekUsers = (int)$stmt->fetchColumn();

            // --- Users registered this month ---
            $stmt = $pdo->query("SELECT COUNT(*) AS month_users 
                                 FROM users 
                                 WHERE YEAR(created_at) = YEAR(CURDATE()) 
                                 AND MONTH(created_at) = MONTH(CURDATE())");
            $monthUsers = (int)$stmt->fetchColumn();

            // --- Registration trend (last 30 days) ---
            $stmt = $pdo->query("
                SELECT DATE(created_at) AS date, COUNT(*) AS count
                FROM users
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY DATE(created_at)
                ORDER BY DATE(created_at) ASC
            ");
            $trendData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // --- Response ---
            $data = [
                'summary' => "User statistics overview with registration trends over the last 30 days.",
                'metrics' => [
                    ['label' => 'Total Users', 'value' => $totalUsers],
                    ['label' => 'Active Users', 'value' => $activeUsers],
                    ['label' => 'Not Active Users', 'value' => $notActiveUsers],
                    ['label' => 'Today\'s Sign-ups', 'value' => $dayUsers],
                    ['label' => 'This Week', 'value' => $weekUsers],
                    ['label' => 'This Month', 'value' => $monthUsers]
                ],
                'chart' => $trendData
            ];

            echo json_encode(['success' => true, 'data' => $data]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;




}




if ($action === 'get_visitor_stats') {
    $period = $_POST['period'] ?? 'day'; // default grouping

    // Build SQL dynamically based on period
    switch ($period) {
        case 'hour':
            $groupBy = "DATE_FORMAT(visit_time, '%Y-%m-%d %H:00')";
            break;
        case 'day':
            $groupBy = "DATE(visit_time)";
            break;
        case 'week':
            $groupBy = "YEARWEEK(visit_time, 1)";
            break;
        case 'month':
            $groupBy = "DATE_FORMAT(visit_time, '%Y-%m')";
            break;
        case 'year':
            $groupBy = "YEAR(visit_time)";
            break;
        default:
            $groupBy = "DATE(visit_time)";
    }

    $sql = "SELECT $groupBy AS label, COUNT(*) AS visits
            FROM website_visitors
            GROUP BY label
            ORDER BY label ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

elseif (isset($_POST['action']) && $_POST['action'] == 'get_tables') {
    $data = executeQuery("SHOW TABLES","COLUMN")['data'];
    echo json_encode(['success' => true, 'message' => 'data fetched successfully','data'=>$data]);
    exit;

}elseif (isset($_POST['action']) && $_POST['action'] == 'get_active_users') {
    $sql = "SELECT status, COUNT(*) AS count FROM users GROUP BY status";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $data = [];
    while ($row = $stmt->fetch()) {
        $data[$row['status']] = (int)$row['count'];
    }

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
    exit;

}
// Make sure a file path is provided
elseif (!isset($_GET['action'])  || empty($_GET['action']) ) {
    http_response_code(400);
    echo json_encode(['error' => 'No action specified']);
    exit;
}

// Sanitize input to prevent directory traversal attacks
// $file = basename($_GET['file']); // Only allow the file name, no directories
// $directory = __DIR__ . '/uploads/'; // Path to your files folder
// $fullPath = $directory . $file;

$fullPath = $_GET['file']??'./';
$fullPath = ltrim($fullPath,'./');
if ($_GET['action'] == 'file_detail') {
    $info = get_file_details('../../'.$fullPath);
    echo json_encode($info, JSON_PRETTY_PRINT);
}elseif ($_GET['action'] == 'file_delete') {
    $fullPath = '../../' . ltrim($_GET['file'], './');

    if (!file_exists($fullPath)) {
        echo json_encode(['message' => 'File or folder not found', 'success' => false]);
        exit;
    }

    // If it's a directory, recursively delete
    if (is_dir($fullPath)) {
        $deleted = delete_directory($fullPath);
        if ($deleted) {
            echo json_encode(['message' => 'Folder deleted successfully', 'success' => true]);
        } else {
            echo json_encode(['message' => 'Failed to delete folder', 'success' => false]);
        }
    } else {
        // It's a file
        if (unlink($fullPath)) {
            echo json_encode(['message' => 'File deleted successfully', 'success' => true]);
        } else {
            echo json_encode(['message' => 'Failed to delete file', 'success' => false]);
        }
    }
    exit;
}

elseif ($_GET['action'] == 'download_folder') {
    $fullPath = '../../' . ltrim($_GET['file'], './');

    if (!is_dir($fullPath)) {
        http_response_code(404);
        echo json_encode(['message' => 'Folder not found', 'success' => false]);
        exit;
    }

    $zipName = basename($fullPath) . '.zip';
    $zipPath = sys_get_temp_dir() . '/' . $zipName;
    include_once"../system/cogs/functions.php";
    $zip = zipFolder($fullPath,"$fullPath.zip");
    echo json_encode($zip);

    exit;
}elseif ($_GET['action'] == 'delete_download_folder') {
    $fullPath = trim($_GET['file']);

    if (!is_dir($fullPath)) {
        http_response_code(404);
        echo json_encode(['message' => 'Folder not found', 'success' => false]);
        exit;
    }

    header('Content-Disposition: attachment; filename="' . $fullPath . '"');
    header('Content-Length: ' . filesize($zipPath));
    readfile($zipPath);

    // Delete the temporary zip after sending
    unlink($zipPath);
    echo json_encode(['message'=>'done',"success"=>true]);
    exit;
}


elseif ($_GET['action'] == 'file_edit') {
    if (is_dir('../../'.$fullPath)) {
        echo json_encode(['message'=>'Failed to edit given file','success'=>false]);
        exit;
    }
    if (file_exists('../../'.$fullPath)) {
        $content = file_get_contents('../../'.$fullPath);
        $content = htmlentities($content);
        // echo json_encode(['message'=>'Data fetched from file '.$fullPath,'success'=>true,'data'=>$content]);
        if ($content && !empty($content)) {
            echo json_encode(['message'=>'Data fetched from file '.$fullPath,'success'=>true,'data'=>$content]);
        }else{
            echo json_encode(['message'=>'File is Empty','success'=>false,'data'=>$content]);
        }
    }else{
        echo json_encode(['message'=>'File not found','success'=>false]);
    }
}elseif ($_GET['action'] == 'save_file_edited_content') {
    $fullPath = trim($_GET['file']);
    if (is_dir('../../'.$fullPath)) {
        echo json_encode(['message'=>'Failed to edit given file','success'=>false]);
        exit;
    }
    if (file_exists('../../'.$fullPath)) {
        $content = $_POST['content']??'';
        if (file_put_contents('../../'.$fullPath,$content)) {
            echo json_encode(['message'=>'Content changed: '.$fullPath,'success'=>true]);
        }else{
            echo json_encode(['message'=>'Content not changed','success'=>false]);
        }
    }else{
        echo json_encode(['message'=>'File not found','success'=>false]);
    }
}elseif ($_GET['action'] == 'create_folder') {
    // Example: ?action=create_folder&file=path/to/dir&name=NewFolder
    $basePath = '../../' . ltrim($_GET['file'], './');
    $folderName = $_GET['name'] ?? '';
    $targetPath = rtrim($basePath, '/') . '/' . $folderName;

    if (empty($folderName)) {
        echo json_encode(['message' => 'Folder name cannot be empty', 'success' => false]);
        exit;
    }

    if (file_exists($targetPath)) {
        echo json_encode(['message' => 'ALL MESSAGE FROM USERS folder or file with that name already exists', 'success' => false]);
        exit;
    }

    if (mkdir($targetPath, 0777, true)) {
        echo json_encode(['message' => 'Folder created successfully', 'success' => true]);
    } else {
        echo json_encode(['message' => 'Failed to create folder', 'success' => false]);
    }

}elseif ($_GET['action'] == 'create_file') {
    // POST: name, content; GET: file (path)
    $basePath = '../../' . ltrim($_POST['path'] ?? $_GET['file'], './');
    $fileName = $_POST['name'] ?? '';
    $content = $_POST['content'] ?? '';
    $targetPath = rtrim($basePath, '/') . '/' . $fileName;

    if (empty($fileName)) {
        echo json_encode(['message' => 'File name cannot be empty', 'success' => false]);
        exit;
    }

    if (file_exists($targetPath)) {
        echo json_encode(['message' => 'ALL MESSAGE FROM USERS file or folder with that name already exists', 'success' => false]);
        exit;
    }

    if (file_put_contents($targetPath, $content) !== false) {
        echo json_encode(['message' => 'File created successfully', 'success' => true]);
    } else {
        echo json_encode(['message' => 'Failed to create file', 'success' => false]);
    }

}elseif ($_GET['action'] == 'file_upload') {
    // Handles Dropzone uploads
    $basePath = '../../' . ltrim($_GET['path'] ?? $_GET['file'], './');

    if (!is_dir($basePath)) {
        echo json_encode(['success' => false, 'message' => 'Invalid upload directory']);
        exit;
    }

    if (!empty($_FILES['file']['name'])) {
        $tmp = $_FILES['file']['tmp_name'];
        $name = basename($_FILES['file']['name']);
        $target = rtrim($basePath, '/') . '/' . $name;

        if (move_uploaded_file($tmp, $target)) {
            echo json_encode(['success' => true, 'message' => 'File uploaded successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    }

}else{
    http_response_code(400);
    echo json_encode(['error' => 'Unknown action']);
}
// if (!file_exists($fullPath)) { 
//     http_response_code(404);
//     echo json_encode(['error' => 'File not found']);
//     exit;
// }

// Set headers to force download
// header('Content-Description: File Transfer');
// header('Content-Type: application/octet-stream');
// header('Content-Disposition: attachment; filename="' . $file . '"');
// header('Expires: 0');
// header('Cache-Control: must-revalidate');
// header('Pragma: public');
// header('Content-Length: ' . filesize($fullPath));

// Clear output buffer and read the file
// ob_clean();
// flush();
// readfile($fullPath);
exit;

?>