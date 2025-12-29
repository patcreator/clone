<?php
header('Content-Type: application/json');
include_once '../../system/cogs/db.php'; 


if(isset($_GET['action']) && $_GET['action']=='bulk_export' && !empty($_GET['ids'])){
    $ids = implode(',', array_map('intval', explode(',', $_GET['ids'])));
    $stmt = $pdo->query("SELECT * FROM blog WHERE Blog_id IN ($ids)");
    $blog = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Export CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=blog_export.csv');
    $output = fopen('php://output','w');
    fputcsv($output, array_keys($blog[0])); // Header row
    foreach($blog as $row){
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

if (isset($_GET['action']) && $_GET['action']=='export'){
            // export all blog as CSV
            $stmt = $pdo->query("SELECT * FROM blog");
            $blog = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $filename = 'blog_export_'.date('Ymd_His').'.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            $out = fopen('php://output', 'w');
            fputcsv($out, array_keys($blog[0]));
            foreach($blog as $u) fputcsv($out, $u);
            fclose($out);
            exit;

}



$action = $_POST['action'] ?? '';
$response = ['success' => false, 'message' => 'Invalid action'];

// Helper: fetch JSON input for bulk actions
$ids = $_POST['ids'] ?? [];
if(is_string($ids)) $ids = explode(',', $ids);

// Helper: validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isNot($a = []) {
    foreach ($a as $item){
        if(!$item){
            throw new Exception("$item field are required.");
        }
    }
}


function isUnique($table, $field, $value) {
    global $pdo;

    // Basic validation to prevent SQL injection via table/field names
    $allowedChars = '/^[a-zA-Z0-9_]+$/';
    if (!preg_match($allowedChars, $table) || !preg_match($allowedChars, $field)) {
        throw new Exception("Invalid table or field name");
    }

    $sql = "SELECT COUNT(*) FROM `$table` WHERE `$field` = :value";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':value' => $value]);

    $count = $stmt->fetchColumn();

    // If count is 0, the value is unique (not found in DB)
    return $count == 0;
}

function isUniqueLoop($table, $data) {
    foreach ($data as $field => $value) {
        if (isUnique($table, $field, $value)) {
            return [
                'success' => true,
                'message' => "Field '$field' with value '$value' is unique.",
                'field'   => $field,
                'value'   => $value
            ];
        }
    }

    // If no field was unique
    return [
        'success' => false,
        'message' => "No unique fields found.",
        'field'   => null,
        'value'   => null
    ];
}


function unique_cols($table){
    global $pdo;
    // 2. Get table description
    $stmt = $pdo->query("DESCRIBE `$table`");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Extract only unique columns
    $unique_cols = [];
    foreach ($columns as $col) {
        if ($col['Key'] === 'UNI') {
            $unique_cols[] = $col['Field'];
        }
    }
    return $unique_cols;
}


function autoIsUniqueChecker($table) {
    global $pdo;

    // 1. Validate table name
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        throw new Exception("Invalid table name.");
    }

    // 2. Get table description
    $stmt = $pdo->query("DESCRIBE `$table`");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Extract only unique columns
    $unique_cols = [];
    foreach ($columns as $col) {
        if ($col['Key'] === 'UNI') {
            $unique_cols[] = $col['Field'];
        }
    }

    // 4. Filter POST data to keep only fields that are unique in DB
    $data_to_check = [];
    foreach ($_POST as $key => $value) {
        if (in_array($key, $unique_cols, true)) {
            $data_to_check[$key] = $value;
        }
    }

    // 5. If no unique columns in POST, return early
    if (empty($data_to_check)) {
        return [
            'success' => true,
            'message' => "No unique columns found in POST data to check.",
            'field' => null,
            'value' => null
        ];
    }

    // 6. Loop through and check each unique column
    foreach ($data_to_check as $field => $value) {
        if (!isUnique($table, $field, $value)) {
            return [
                'success' => false,
                'message' => "Value '$value' for field '$field' already exists in table '$table'.",
                'field' => $field,
                'value' => $value
            ];
        }
    }

    // 7. If all unique fields pass
    return [
        'success' => true,
        'message' => "All unique fields are unique.",
        'field' => null,
        'value' => null
    ];
}




try {
    switch($action) {

        case 'insert':
              
                    $Blog_id = trim($_POST['Blog_id'] ?? '');
                 
                    $Title = trim($_POST['Title'] ?? '');
                 
                    $Slug = trim($_POST['Slug'] ?? '');
                 
                    $Author = trim($_POST['Author'] ?? '');
                 
                    $Post_image = trim($_POST['Post_image'] ?? '');
                 
                    $Content = trim($_POST['Content'] ?? '');
                 
                    $short_description = trim($_POST['short_description'] ?? '');
                 
                    $Category = trim($_POST['Category'] ?? '');
                 
                    $Tags = trim($_POST['Tags'] ?? '');
                 
                    $Read_Time = trim($_POST['Read_Time'] ?? '');
                 
                    $Status = trim($_POST['Status'] ?? '');
                 
                    $Publish_Date = trim($_POST['Publish_Date'] ?? '');
                 
                    $Last_Updated = trim($_POST['Last_Updated'] ?? '');
                 
                    $Meta_Title = trim($_POST['Meta_Title'] ?? '');
                 
                    $Meta_Description = trim($_POST['Meta_Description'] ?? '');
                 
                    $Comments_Enabled = trim($_POST['Comments_Enabled'] ?? '');
                 
                    $allow_category_number = trim($_POST['allow_category_number'] ?? '');
                 
                    $created_at = trim($_POST['created_at'] ?? '');
                
            isNot([ 
                    'Blog_id',
                 
                    'Title',
                 
                    'Slug',
                 
                    'Author',
                 
                    'Post_image',
                 
                    'Content',
                 
                    'short_description',
                 
                    'Category',
                 
                    'Tags',
                 
                    'Read_Time',
                 
                    'Status',
                 
                    'Publish_Date',
                 
                    'Last_Updated',
                 
                    'Meta_Title',
                 
                    'Meta_Description',
                 
                    'Comments_Enabled',
                 
                    'allow_category_number',
                 
                    'created_at',]);
            // Check if column exists
            
            $stmt = $pdo->prepare("INSERT INTO blog ( 
                    Blog_id,
                 
                    Title,
                 
                    Slug,
                 
                    Author,
                 
                    Post_image,
                 
                    Content,
                 
                    short_description,
                 
                    Category,
                 
                    Tags,
                 
                    Read_Time,
                 
                    Status,
                 
                    Publish_Date,
                 
                    Last_Updated,
                 
                    Meta_Title,
                 
                    Meta_Description,
                 
                    Comments_Enabled,
                 
                    allow_category_number,
                 
                    created_at,) VALUES ( 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,)");
            if($stmt->execute([ 
                    $Blog_id,
                 
                    $Title,
                 
                    $Slug,
                 
                    $Author,
                 
                    $Post_image,
                 
                    $Content,
                 
                    $short_description,
                 
                    $Category,
                 
                    $Tags,
                 
                    $Read_Time,
                 
                    $Status,
                 
                    $Publish_Date,
                 
                    $Last_Updated,
                 
                    $Meta_Title,
                 
                    $Meta_Description,
                 
                    $Comments_Enabled,
                 
                    $allow_category_number,
                 
                    $created_at,])) {
                $response = ['success'=>true, 'message'=>'blog added successfully', 'id'=>$pdo->lastInsertId()];
            } else {
                throw new Exception("Failed to insert blog");
            }
            break;

        case 'edit':
            $id = intval($_POST['id'] ?? 'unknow');
             
                    $Blog_id = trim($_POST['Blog_id'] ?? '');
                 
                    $Title = trim($_POST['Title'] ?? '');
                 
                    $Slug = trim($_POST['Slug'] ?? '');
                 
                    $Author = trim($_POST['Author'] ?? '');
                 
                    $Post_image = trim($_POST['Post_image'] ?? '');
                 
                    $Content = trim($_POST['Content'] ?? '');
                 
                    $short_description = trim($_POST['short_description'] ?? '');
                 
                    $Category = trim($_POST['Category'] ?? '');
                 
                    $Tags = trim($_POST['Tags'] ?? '');
                 
                    $Read_Time = trim($_POST['Read_Time'] ?? '');
                 
                    $Status = trim($_POST['Status'] ?? '');
                 
                    $Publish_Date = trim($_POST['Publish_Date'] ?? '');
                 
                    $Last_Updated = trim($_POST['Last_Updated'] ?? '');
                 
                    $Meta_Title = trim($_POST['Meta_Title'] ?? '');
                 
                    $Meta_Description = trim($_POST['Meta_Description'] ?? '');
                 
                    $Comments_Enabled = trim($_POST['Comments_Enabled'] ?? '');
                 
                    $allow_category_number = trim($_POST['allow_category_number'] ?? '');
                 
                    $created_at = trim($_POST['created_at'] ?? '');
                
        
            isNot([ 
                    'Blog_id',
                 
                    'Title',
                 
                    'Slug',
                 
                    'Author',
                 
                    'Post_image',
                 
                    'Content',
                 
                    'short_description',
                 
                    'Category',
                 
                    'Tags',
                 
                    'Read_Time',
                 
                    'Status',
                 
                    'Publish_Date',
                 
                    'Last_Updated',
                 
                    'Meta_Title',
                 
                    'Meta_Description',
                 
                    'Comments_Enabled',
                 
                    'allow_category_number',
                 
                    'created_at',]);
            
            $stmt = $pdo->prepare("UPDATE blog SET  
                   Blog_id=?, 
                 
                   Title=?, 
                 
                   Slug=?, 
                 
                   Author=?, 
                 
                   Post_image=?, 
                 
                   Content=?, 
                 
                   short_description=?, 
                 
                   Category=?, 
                 
                   Tags=?, 
                 
                   Read_Time=?, 
                 
                   Status=?, 
                 
                   Publish_Date=?, 
                 
                   Last_Updated=?, 
                 
                   Meta_Title=?, 
                 
                   Meta_Description=?, 
                 
                   Comments_Enabled=?, 
                 
                   allow_category_number=?, 
                 
                   created_at=?, WHERE Blog_id=?");
            if($stmt->execute([ 
                    $Blog_id,
                 
                    $Title,
                 
                    $Slug,
                 
                    $Author,
                 
                    $Post_image,
                 
                    $Content,
                 
                    $short_description,
                 
                    $Category,
                 
                    $Tags,
                 
                    $Read_Time,
                 
                    $Status,
                 
                    $Publish_Date,
                 
                    $Last_Updated,
                 
                    $Meta_Title,
                 
                    $Meta_Description,
                 
                    $Comments_Enabled,
                 
                    $allow_category_number,
                 
                    $created_at, $Blog_id])) {
                $response = ['success'=>true, 'message'=>'blog updated successfully'];
            } else {
                throw new Exception("Failed to update blog");
            }
            break;

        case 'delete':
            $id = intval($_POST['id'] ?? 'unknow');
            if($id == 'unknow') throw new Exception("Invalid ID");
            $stmt = $pdo->prepare("DELETE FROM blog WHERE Blog_id=?");
            if($stmt->execute([$id])) {
                $response = ['success'=>true, 'message'=>'Data has been removed!'];
            } else {
                throw new Exception("Failed to delete data with id : $id");
            }
            break;

        case 'view':
            $id = intval($_POST['id'] ?? 'unknow');
            if($id == 'unknow') throw new Exception("Invalid ID");
            $stmt = $pdo->prepare("SELECT * FROM blog WHERE Blog_id=?");
            $stmt->execute([$id]);
            $blog = $stmt->fetch(PDO::FETCH_ASSOC);
            if($blog) $response = ['success'=>true, 'data'=>$blog];
            else throw new Exception("blog not found");
            break;

        case 'bulk_view':
            $ids = $_POST['ids']; // array of IDs
            $in  = str_repeat('?,', count($ids) - 1) . '?';
            $stmt = $pdo->prepare("SELECT * FROM blog WHERE id IN ($in)");
            $stmt->execute($ids);
            $blog = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success'=>true, 'data'=>$blog]);
            exit;

        case 'bulk_edit':
            foreach($_POST['ids'] as $id){
                $stmt = $pdo->prepare("UPDATE blog SET  
                   Blog_id=?, 
                 
                   Title=?, 
                 
                   Slug=?, 
                 
                   Author=?, 
                 
                   Post_image=?, 
                 
                   Content=?, 
                 
                   short_description=?, 
                 
                   Category=?, 
                 
                   Tags=?, 
                 
                   Read_Time=?, 
                 
                   Status=?, 
                 
                   Publish_Date=?, 
                 
                   Last_Updated=?, 
                 
                   Meta_Title=?, 
                 
                   Meta_Description=?, 
                 
                   Comments_Enabled=?, 
                 
                   allow_category_number=?, 
                 
                   created_at=?, WHERE Blog_id=?");
                $stmt->execute([ 
                    $Blog_id,
                 
                    $Title,
                 
                    $Slug,
                 
                    $Author,
                 
                    $Post_image,
                 
                    $Content,
                 
                    $short_description,
                 
                    $Category,
                 
                    $Tags,
                 
                    $Read_Time,
                 
                    $Status,
                 
                    $Publish_Date,
                 
                    $Last_Updated,
                 
                    $Meta_Title,
                 
                    $Meta_Description,
                 
                    $Comments_Enabled,
                 
                    $allow_category_number,
                 
                    $created_at, Blog_id]);
            }
            echo json_encode(['success'=>true,'message'=>'Data updated successfully']);
            exit;

        case 'copy':
            $id = intval($_POST['id'] ?? 'unknow');
            if($id == 'unknow') throw new Exception("Invalid ID");
            $stmt = $pdo->prepare("SELECT * FROM blog WHERE Blog_id=?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$row) throw new Exception("Data not found");

            $stmt = $pdo->prepare("INSERT INTO blog ( 
                    Blog_id,
                 
                    Title,
                 
                    Slug,
                 
                    Author,
                 
                    Post_image,
                 
                    Content,
                 
                    short_description,
                 
                    Category,
                 
                    Tags,
                 
                    Read_Time,
                 
                    Status,
                 
                    Publish_Date,
                 
                    Last_Updated,
                 
                    Meta_Title,
                 
                    Meta_Description,
                 
                    Comments_Enabled,
                 
                    allow_category_number,
                 
                    created_at,) VALUES ( 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,)");
            $stmt->execute([ 
                    $Blog_id,
                 
                    $Title,
                 
                    $Slug,
                 
                    $Author,
                 
                    $Post_image,
                 
                    $Content,
                 
                    $short_description,
                 
                    $Category,
                 
                    $Tags,
                 
                    $Read_Time,
                 
                    $Status,
                 
                    $Publish_Date,
                 
                    $Last_Updated,
                 
                    $Meta_Title,
                 
                    $Meta_Description,
                 
                    $Comments_Enabled,
                 
                    $allow_category_number,
                 
                    $created_at,]);
            $response = ['success'=>true, 'message'=>'new data is recorded', 'id'=>$pdo->lastInsertId()];
            break;
        case 'bulk_copy':
            if(empty($ids)) throw new Exception("No IDs provided");
            foreach($_POST['ids'] as $id){
                 
                    $Blog_id = trim($_POST['Blog_id'] ?? '');
                 
                    $Title = trim($_POST['Title'] ?? '');
                 
                    $Slug = trim($_POST['Slug'] ?? '');
                 
                    $Author = trim($_POST['Author'] ?? '');
                 
                    $Post_image = trim($_POST['Post_image'] ?? '');
                 
                    $Content = trim($_POST['Content'] ?? '');
                 
                    $short_description = trim($_POST['short_description'] ?? '');
                 
                    $Category = trim($_POST['Category'] ?? '');
                 
                    $Tags = trim($_POST['Tags'] ?? '');
                 
                    $Read_Time = trim($_POST['Read_Time'] ?? '');
                 
                    $Status = trim($_POST['Status'] ?? '');
                 
                    $Publish_Date = trim($_POST['Publish_Date'] ?? '');
                 
                    $Last_Updated = trim($_POST['Last_Updated'] ?? '');
                 
                    $Meta_Title = trim($_POST['Meta_Title'] ?? '');
                 
                    $Meta_Description = trim($_POST['Meta_Description'] ?? '');
                 
                    $Comments_Enabled = trim($_POST['Comments_Enabled'] ?? '');
                 
                    $allow_category_number = trim($_POST['allow_category_number'] ?? '');
                 
                    $created_at = trim($_POST['created_at'] ?? '');
                
        
                isNot([ 
                    'Blog_id',
                 
                    'Title',
                 
                    'Slug',
                 
                    'Author',
                 
                    'Post_image',
                 
                    'Content',
                 
                    'short_description',
                 
                    'Category',
                 
                    'Tags',
                 
                    'Read_Time',
                 
                    'Status',
                 
                    'Publish_Date',
                 
                    'Last_Updated',
                 
                    'Meta_Title',
                 
                    'Meta_Description',
                 
                    'Comments_Enabled',
                 
                    'allow_category_number',
                 
                    'created_at',]);
                
                $stmt = $pdo->prepare("INSERT INTO blog ( 
                    Blog_id,
                 
                    Title,
                 
                    Slug,
                 
                    Author,
                 
                    Post_image,
                 
                    Content,
                 
                    short_description,
                 
                    Category,
                 
                    Tags,
                 
                    Read_Time,
                 
                    Status,
                 
                    Publish_Date,
                 
                    Last_Updated,
                 
                    Meta_Title,
                 
                    Meta_Description,
                 
                    Comments_Enabled,
                 
                    allow_category_number,
                 
                    created_at,) VALUES ( 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,)");
                $stmt->execute([ 
                    $Blog_id,
                 
                    $Title,
                 
                    $Slug,
                 
                    $Author,
                 
                    $Post_image,
                 
                    $Content,
                 
                    $short_description,
                 
                    $Category,
                 
                    $Tags,
                 
                    $Read_Time,
                 
                    $Status,
                 
                    $Publish_Date,
                 
                    $Last_Updated,
                 
                    $Meta_Title,
                 
                    $Meta_Description,
                 
                    $Comments_Enabled,
                 
                    $allow_category_number,
                 
                    $created_at,]);
            }
            $response = ['success'=>true,'message'=>'data copied successfully'];
            break;
        case 'bulk_delete':
            if(empty($ids)) throw new Exception("No IDs provided");
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $pdo->prepare("DELETE FROM blog WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $response = ['success'=>true, 'message'=>count($ids).' blog deleted'];
            break;

        case 'copy_many':
            if(empty($ids)) throw new Exception("No IDs provided");
            $stmt = $pdo->prepare("SELECT * FROM blog WHERE id IN (".implode(',', array_fill(0,count($ids),'?')).")");
            $stmt->execute($ids);
            $blog = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($blog as $u){
                $stmt2 = $pdo->prepare("INSERT INTO blog ( 
                    Blog_id,
                 
                    Title,
                 
                    Slug,
                 
                    Author,
                 
                    Post_image,
                 
                    Content,
                 
                    short_description,
                 
                    Category,
                 
                    Tags,
                 
                    Read_Time,
                 
                    Status,
                 
                    Publish_Date,
                 
                    Last_Updated,
                 
                    Meta_Title,
                 
                    Meta_Description,
                 
                    Comments_Enabled,
                 
                    allow_category_number,
                 
                    created_at,) VALUES ( 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,
                 
                    ?,)");
                $stmt2->execute([ 
                $u['Blog_id'],
                 
                $u['Title'],
                 
                $u['Slug'],
                 
                $u['Author'],
                 
                $u['Post_image'],
                 
                $u['Content'],
                 
                $u['short_description'],
                 
                $u['Category'],
                 
                $u['Tags'],
                 
                $u['Read_Time'],
                 
                $u['Status'],
                 
                $u['Publish_Date'],
                 
                $u['Last_Updated'],
                 
                $u['Meta_Title'],
                 
                $u['Meta_Description'],
                 
                $u['Comments_Enabled'],
                 
                $u['allow_category_number'],
                 
                $u['created_at'],]);
            }
            $response = ['success'=>true, 'message'=>count($blog).' data copied (registed)'];
            break;
            case 'import':
                    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                        http_response_code(400);
                        echo 'File upload failed.';
                        exit;
                    }

                    $file = $_FILES['file'];
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                    if (!in_array($ext, ['csv', 'sql'])) {
                        http_response_code(400);
                        echo 'Invalid file type. Only CSV and SQL files are allowed.';
                        exit;
                    }

                    // Save file to temporary location (you can change this path)
                    $uploadDir = '../../system/filemanager/imports/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                    $destination = $uploadDir . basename($file['name']);
                    if (move_uploaded_file($file['tmp_name'], $destination)) {
                        $response = ['success'=>true, 'message'=>'File uploaded successfully: ' . htmlspecialchars($file['name'])];
                    // =========================
                    // Handle SQL file
                    // =========================
                    if ($ext === 'sql') {
                        try {
                            $sql = file_get_contents($destination);
                            $pdo->exec($sql);
                            $response = ['success'=>false, 'message'=>'SQL file imported successfully.'];
                        } catch (PDOException $e) {
                            http_response_code(500);
                            $response = ['success'=>false, 'message'=>'SQL import error: ' . $e->getMessage()];
                        }
                        exit;
                    }

                    // =========================
                    //  Handle CSV file
                    // =========================
                    if ($ext === 'csv') {
                        $handle = fopen($destination, 'r');
                        if ($handle === false) {
                            http_response_code(500);
                            $response = ['success'=>false, 'message'=>'Could not open CSV file.'];
                            exit;
                        }

                        $headers = fgetcsv($handle); // First row: column headers
                        if (!$headers) {
                            http_response_code(400);
                            $response = ['success'=>false, 'message'=>'Invalid CSV format, first row must be header.'];
                            exit;
                        }

                        // Prepare insert query dynamically based on headers
                        $placeholders = implode(', ', array_fill(0, count($headers), '?'));
                        $columns = implode(', ', array_map(fn($h) => "`" . trim($h) . "`", $headers));
                        $stmt = $pdo->prepare("INSERT INTO blog ($columns) VALUES ($placeholders)");

                        $rowCount = 0;
                        while (($row = fgetcsv($handle)) !== false) {
                            // Skip empty rows
                            if (count(array_filter($row)) === 0) continue;

                            try {
                                $stmt->execute($row);
                                $rowCount++;
                            } catch (PDOException $e) {
                                $response = ['success'=>false, 'message'=>"To import please your file  must be sql(insert) or csv(first row is header). "];
                            }
                        }

                    } else {
                        http_response_code(500);
                        $response = ['success'=>false, 'message'=>'Failed to move uploaded file.'];
                    }
                }

            break;
        case 'update_many':
            if(empty($ids)) throw new Exception("No IDs provided");
             
                    $Blog_id = trim($_POST['Blog_id'] ?? '');
                 
                    $Title = trim($_POST['Title'] ?? '');
                 
                    $Slug = trim($_POST['Slug'] ?? '');
                 
                    $Author = trim($_POST['Author'] ?? '');
                 
                    $Post_image = trim($_POST['Post_image'] ?? '');
                 
                    $Content = trim($_POST['Content'] ?? '');
                 
                    $short_description = trim($_POST['short_description'] ?? '');
                 
                    $Category = trim($_POST['Category'] ?? '');
                 
                    $Tags = trim($_POST['Tags'] ?? '');
                 
                    $Read_Time = trim($_POST['Read_Time'] ?? '');
                 
                    $Status = trim($_POST['Status'] ?? '');
                 
                    $Publish_Date = trim($_POST['Publish_Date'] ?? '');
                 
                    $Last_Updated = trim($_POST['Last_Updated'] ?? '');
                 
                    $Meta_Title = trim($_POST['Meta_Title'] ?? '');
                 
                    $Meta_Description = trim($_POST['Meta_Description'] ?? '');
                 
                    $Comments_Enabled = trim($_POST['Comments_Enabled'] ?? '');
                 
                    $allow_category_number = trim($_POST['allow_category_number'] ?? '');
                 
                    $created_at = trim($_POST['created_at'] ?? '');
                
        
            if(!$status) throw new Exception("all field with * is required.");
            $stmt = $pdo->prepare("UPDATE blog SET  
                   Blog_id=?, 
                 
                   Title=?, 
                 
                   Slug=?, 
                 
                   Author=?, 
                 
                   Post_image=?, 
                 
                   Content=?, 
                 
                   short_description=?, 
                 
                   Category=?, 
                 
                   Tags=?, 
                 
                   Read_Time=?, 
                 
                   Status=?, 
                 
                   Publish_Date=?, 
                 
                   Last_Updated=?, 
                 
                   Meta_Title=?, 
                 
                   Meta_Description=?, 
                 
                   Comments_Enabled=?, 
                 
                   allow_category_number=?, 
                 
                   created_at=?, WHERE id IN (".implode(',', array_fill(0,count($ids),'?')).")");
            $stmt->execute(array_merge([ 
                $Blog_id,
                 
                $Title,
                 
                $Slug,
                 
                $Author,
                 
                $Post_image,
                 
                $Content,
                 
                $short_description,
                 
                $Category,
                 
                $Tags,
                 
                $Read_Time,
                 
                $Status,
                 
                $Publish_Date,
                 
                $Last_Updated,
                 
                $Meta_Title,
                 
                $Meta_Description,
                 
                $Comments_Enabled,
                 
                $allow_category_number,
                 
                $created_at,
                 ], $ids));
            $response = ['success'=>true, 'message'=>count($ids).' data updated in blog'];
            break;
        case 'inline_update':
            $id = intval($_POST['id']);
            $field = $_POST['field'];
            $value = $_POST['value'];

            $allowed_fields = [ 
                    'Blog_id',
                 
                    'Title',
                 
                    'Slug',
                 
                    'Author',
                 
                    'Post_image',
                 
                    'Content',
                 
                    'short_description',
                 
                    'Category',
                 
                    'Tags',
                 
                    'Read_Time',
                 
                    'Status',
                 
                    'Publish_Date',
                 
                    'Last_Updated',
                 
                    'Meta_Title',
                 
                    'Meta_Description',
                 
                    'Comments_Enabled',
                 
                    'allow_category_number',
                 
                    'created_at',];
            if (!in_array($field, $allowed_fields)) {
                echo json_encode(['success' => false, 'message' => 'Invalid field']);
                exit;
            }

            $unique = isUniqueLoop('blog',[$field=>$value]);

            $unique_cols = unique_cols('blog'); 
            if (in_array($field, $unique_cols)) {
                $unique = isUnique($table, $field, $value);
            }else{
                $unique = false;
            }

            if ($unique) {
                echo json_encode(['message' => $field." exists",'success' => false]);
                exit;
            }else{
                $stmt = $pdo->prepare("UPDATE blog SET `$field` = :value WHERE id = :id");
                $success = $stmt->execute(['value' => $value, 'id' => $id]);
                echo json_encode(['message' => 'Data updated','success' => $success]);
                
               exit;
            }
            
        default:
            throw new Exception("Unknown action");

    }

} catch(Exception $e){
    $response = ['success'=>false, 'message'=>$e->getMessage()];
}

echo json_encode($response);
?>
                    