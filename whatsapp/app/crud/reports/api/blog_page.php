<?php
// Auto-generated report API for table: blog_page

require_once '../../../system/cogs/db.php';
// Handle AJAX request
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');

    $query = $_GET['query'] ?? '';
    $status = $_GET['status'] ?? '';
    $sort = $_GET['sort'] ?? '';
    $dir = $_GET['dir'] ?? 'DESC';

    $sql = "SELECT * FROM `blog_page`";
    $params = [];
    if ($query) {
        $likeParts = [];
        
                $likeParts[] = "blog_page_id LIKE ?";
                
                $likeParts[] = "view_search LIKE ?";
                
                $likeParts[] = "view_categories LIKE ?";
                
                $likeParts[] = "view_recent_blog LIKE ?";
                
                $likeParts[] = "recent_blog_number LIKE ?";
                
                $likeParts[] = "view_blog_tags LIKE ?";
                
                $likeParts[] = "custom_html LIKE ?";
                
                $likeParts[] = "img_after_recent_post LIKE ?";
                
                $likeParts[] = "img_after_tag LIKE ?";
                
                $likeParts[] = "Plain_Text_title LIKE ?";
                
                $likeParts[] = "Plain_Text_description LIKE ?";
                
                $likeParts[] = "blog_style LIKE ?";
                
                $likeParts[] = "pagination_style LIKE ?";
                
                $likeParts[] = "show_author LIKE ?";
                
                $likeParts[] = "show_author_img LIKE ?";
                
                $likeParts[] = "show_single_category LIKE ?";
                
                $likeParts[] = "title_limit LIKE ?";
                
                $likeParts[] = "description_limit LIKE ?";
                
                $likeParts[] = "cta_label LIKE ?";
                
                $likeParts[] = "go-to-page LIKE ?";
                
                $likeParts[] = "show-date LIKE ?";
                
                $likeParts[] = "show-pagination LIKE ?";
                
                $likeParts[] = "show-comment LIKE ?";
                
                $likeParts[] = "showCategoryIcon LIKE ?";
                
                $likeParts[] = "number_of_post LIKE ?";
                
                $likeParts[] = "created_at LIKE ?";
                
        $sql .= " AND (" . implode(' OR ', $likeParts) . ")";
        foreach ($likeParts as $lp) { $params[] = "%$query%"; }
    }
    if ($status) {
        $sql .= " AND status = ?";
        $params[] = $status;
    }
    $allowedSort = ['blog_page_id','created_at','updated_at'];
    if (!in_array($sort, $allowedSort)) {
        $sort = $allowedSort[0];
    }
    $dir = strtoupper($dir) === 'ASC' ? 'ASC' : 'DESC';
    $sql .= " ORDER BY $sort $dir";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Optional: Add created_month if created_at exists
    if (in_array('created_at', array_keys($rows[0] ?? []))) {
        foreach ($rows as &$r) {
            $r['created_month'] = date('M Y', strtotime($r['created_at']));
        }
    }

    echo json_encode($rows);
    exit;
}
?>