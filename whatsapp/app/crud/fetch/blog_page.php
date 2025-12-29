
                <?php
  $stmt = $pdo->query("SELECT * FROM blog_page");
  $blog_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$now = new DateTime();
$today = $now->format('Y-m-d');
$thisWeek = $now->format('W');
$thisMonth = $now->format('m');
$thisYear = $now->format('Y');

$counts = [
  'today' => 0,
  'week' => 0,
  'month' => 0,
  'year' => 0,
  'years' => []
];

foreach ($blog_page as $item) {
  $createdAt = new DateTime($item['created_at']);
  $itemYear = $createdAt->format('Y');

  if ($createdAt->format('Y-m-d') === $today) {
    $counts['today']++;
  }
  if ($createdAt->format('W') === $thisWeek && $createdAt->format('Y') === $thisYear) {
    $counts['week']++;
  }
  if ($createdAt->format('m') === $thisMonth && $createdAt->format('Y') === $thisYear) {
    $counts['month']++;
  }
  if ($createdAt->format('Y') === $thisYear) {
    $counts['year']++;
  }

  // Grouping by year for the 'years' dataset
  if (!isset($counts['years'][$itemYear])) {
    $counts['years'][$itemYear] = 0;
  }
  $counts['years'][$itemYear]++;
}
$columns = ['blog_page_id','view_search','view_categories','view_recent_blog','recent_blog_number','view_blog_tags','custom_html','img_after_recent_post','img_after_tag','Plain_Text_title','Plain_Text_description','blog_style','pagination_style','show_author','show_author_img','show_single_category','title_limit','description_limit','cta_label','go-to-page','show-date','show-pagination','show-comment','showCategoryIcon','number_of_post','created_at'];
?>
<div class="bg-white dark:bg-gray-800 dark:border-gray-800 border mx-5 my-6 py-8 px-4 rounded-3xl my-5">
<!-- fontawesome + mdi -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- jquery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- DataTables CSS (latest) -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">

<!-- DataTables JS (latest) -->
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>

    <h1 class="text-2xl font-bold mb-6 px-8">blog_page</h1>

    <!-- Tabs -->
    <div class="tabs flex flex-wrap gap-2 mb-4 ml-4">
      <button type="button" class="tabBtn px-4 py-2 flex items-center gap-2" data-tab="tables">
        <i class="mdi mdi-table"></i> Table
      </button>
      <button type="button" class="tabBtn px-4 py-2 flex items-center gap-2" data-tab="charts">
        <i class="mdi mdi-chart-bar"></i> Charts
      </button>
      <button type="button" class="tabBtn px-4 py-2 flex items-center gap-2" data-tab="grid">
        <i class="mdi mdi-grid"></i> Grid
      </button>
      <button type="button" class="tabBtn px-4 py-2 flex items-center gap-2" data-tab="list">
        <i class="mdi mdi-format-list-bulleted"></i> List
      </button>
      <button type="button" class="tabBtn px-4 py-2 flex items-center gap-2" data-tab="print">
        <i class="mdi mdi-printer"></i> Print
      </button>
      <button type="button" class="tabBtn px-4 py-2 flex items-center gap-2" data-tab="import">
        <i class="mdi mdi-database-import"></i> Import
      </button>
      <button type="button" class="tabBtn px-4 py-2 flex items-center gap-2" data-tab="export">
        <i class="mdi mdi-database-export"></i> Export
      </button>
      <button type="button" class="tabBtn px-4 py-2 flex items-center gap-2" data-tab="share">
        <i class="mdi mdi-share-variant"></i> Share
      </button>
    </div>

    <hr class="dark:border-gray-700">

    <!-- Bulk Actions -->
    <select id="bulkActions" class="ml-8 mb-4 px-2 mt-5 py-1 dark:bg-gray-800 border border-2 rounded">
      <option value="">Bulk Actions</option>
      <option value="edit">Edit</option>
      <option value="copy">Copy</option>
      <option value="delete">Delete</option>
      <option value="view">View</option>
      <option value="export">Export</option>
    </select>
    <button id="applyBulkAction" class="px-4 py-2 bg-gray-500 text-white rounded">Apply</button>

    <!-- Add blog_page Button -->
    <a href="#" id="addblog_pageBtn" class="mb-4 ml-8 px-4 py-2 bg-red-500 text-white rounded inline-block">
      Add blog_page
    </a>

    <!-- Reusable Modal -->
    <div id="blog_pageModal" class="fixed inset-0 w-full h-full bg-white dark:bg-gray-900 dark:text-white z-50 overflow-y-auto hidden">
      <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
        <button class="modal-close flex items-center text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white">
          <i class="fa fa-arrow-left mr-2"></i> Back
        </button>
        <h2 class="text-xl font-bold" id="modalTitle">blog_page</h2>
        <span></span>
      </div>
      <div class="p-6 max-w-2xl mx-auto" id="modalContent"></div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto px-8 pt-2">
      <div id="tab-tables">
        <table id="blog_pageTable" class="display min-w-full bg-white rounded shadow w-full text-sm text-left text-gray-700 dark:text-gray-200">
          <thead>
            <tr>
              <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left"><input type="checkbox" id="selectAll"></th>
              <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">#</th>
               <?php foreach ($columns as $col): ?>
                  <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3"><?= $col ?></th>
                 
               <?php endforeach ?>
               <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=1; foreach($blog_page as $item): ?>
            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 hover:dark:bg-gray-700">
                <td class="border-0 px-4 py-4 dark:bg-gray-800"><input type="checkbox" class="rowCheckbox" data-id="<?= $item['blog_page_id'] ?>"></td>

                <td class="border-0 px-4 py-4 dark:bg-gray-800"><?= $i++ ?></td>

                 
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="blog_page_id" data-values=''><?= htmlspecialchars($item['blog_page_id']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="view_search" data-values=''><?= htmlspecialchars($item['view_search']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="view_categories" data-values=''><?= htmlspecialchars($item['view_categories']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="view_recent_blog" data-values=''><?= htmlspecialchars($item['view_recent_blog']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="recent_blog_number" data-values=''><?= htmlspecialchars($item['recent_blog_number']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="view_blog_tags" data-values=''><?= htmlspecialchars($item['view_blog_tags']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="custom_html" data-values=''><?= htmlspecialchars($item['custom_html']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="img_after_recent_post" data-values=''><?= htmlspecialchars($item['img_after_recent_post']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="img_after_tag" data-values=''><?= htmlspecialchars($item['img_after_tag']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="Plain_Text_title" data-values=''><?= htmlspecialchars($item['Plain_Text_title']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="Plain_Text_description" data-values=''><?= htmlspecialchars($item['Plain_Text_description']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="blog_style" data-values=''><?= htmlspecialchars($item['blog_style']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="pagination_style" data-values=''><?= htmlspecialchars($item['pagination_style']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="show_author" data-values=''><?= htmlspecialchars($item['show_author']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="show_author_img" data-values=''><?= htmlspecialchars($item['show_author_img']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="show_single_category" data-values=''><?= htmlspecialchars($item['show_single_category']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="title_limit" data-values=''><?= htmlspecialchars($item['title_limit']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="description_limit" data-values=''><?= htmlspecialchars($item['description_limit']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="cta_label" data-values=''><?= htmlspecialchars($item['cta_label']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="go-to-page" data-values=''><?= htmlspecialchars($item['go-to-page']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="show-date" data-values=''><?= htmlspecialchars($item['show-date']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="show-pagination" data-values=''><?= htmlspecialchars($item['show-pagination']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="show-comment" data-values=''><?= htmlspecialchars($item['show-comment']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="showCategoryIcon" data-values=''><?= htmlspecialchars($item['showCategoryIcon']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="number_of_post" data-values=''><?= htmlspecialchars($item['number_of_post']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_page_id'] ?>" data-field="created_at" data-values=''><?= htmlspecialchars($item['created_at']) ?></td>
                    
                <td class="border-0 px-4 py-4 dark:bg-gray-800">
                  <button class="viewBtn p-2 hover:bg-gray-100 rounded-full" data-id="<?= $item['blog_page_id'] ?>" title="view"><i class="mdi-24px mdi mdi-eye"></i></button>
                  <button class="editBtn p-2 hover:bg-gray-100 rounded-full" data-id="<?= $item['blog_page_id'] ?>" title="edit"><i class="mdi-24px mdi mdi-pen"></i></button>
                  <button class="deleteBtn p-2 hover:bg-gray-100 rounded-full" data-id="<?= $item['blog_page_id'] ?>" title="delete"><i class="mdi-24px mdi mdi-delete"></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <!-- Charts Tab -->
<div id="tab-charts" class="hidden px-8 pt-4">
  <canvas id="blog_pageChart" height="120"></canvas>
</div>

<!-- Grid Tab -->
<div id="tab-grid" class="hidden px-8 pt-4">
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <?php foreach($blog_page as $key => $item): ?>
      <div
        class="group bg-white dark:bg-gray-800 rounded-lg shadow-md border border-transparent hover:border-red-500 focus-within:border-red-500 active:border-red-700 transition duration-300 overflow-hidden"
        x-data="{ open: false }"
      >
        <img
          src="<?= htmlspecialchars($item['image'] ?? 'https://placehold.co/600x400') ?>"
          alt="blog_page Image"
          class="w-full h-40 object-cover"
        />
        <div class="p-4">
          <h3 class="text-lg font-bold text-red-600"><?= htmlspecialchars($item['blog_page_id']) ?></h3>
          <p class="text-gray-600 dark:text-gray-300"><?= htmlspecialchars($item['view_search']??'') ?></p>
          <p class="text-sm text-gray-500 dark:text-gray-400">Status: <?= htmlspecialchars($item['view_categories']??'') ?></p>

          <div class="mt-4">
            <button
              @click="open = !open"
              class="text-sm text-red-500 hover:text-red-700 focus:outline-none focus:underline"
            >
              <span x-show="!open">More &darr;</span>
              <span x-show="open">Less &uarr;</span>
            </button>

            <div
              x-show="open"
              x-transition
              class="mt-2 text-gray-700 dark:text-gray-300 text-sm"
            >

              <p><?= htmlspecialchars($item['view_recent_blog'] ?? '') ?></p> <p><?= htmlspecialchars($item['recent_blog_number'] ?? '') ?></p> <p><?= htmlspecialchars($item['view_blog_tags'] ?? '') ?></p> <p><?= htmlspecialchars($item['custom_html'] ?? '') ?></p> <p><?= htmlspecialchars($item['img_after_recent_post'] ?? '') ?></p> <p><?= htmlspecialchars($item['img_after_tag'] ?? '') ?></p> <p><?= htmlspecialchars($item['Plain_Text_title'] ?? '') ?></p> <p><?= htmlspecialchars($item['Plain_Text_description'] ?? '') ?></p> <p><?= htmlspecialchars($item['blog_style'] ?? '') ?></p> <p><?= htmlspecialchars($item['pagination_style'] ?? '') ?></p> <p><?= htmlspecialchars($item['show_author'] ?? '') ?></p> <p><?= htmlspecialchars($item['show_author_img'] ?? '') ?></p> <p><?= htmlspecialchars($item['show_single_category'] ?? '') ?></p> <p><?= htmlspecialchars($item['title_limit'] ?? '') ?></p> <p><?= htmlspecialchars($item['description_limit'] ?? '') ?></p> <p><?= htmlspecialchars($item['cta_label'] ?? '') ?></p> <p><?= htmlspecialchars($item['go-to-page'] ?? '') ?></p> <p><?= htmlspecialchars($item['show-date'] ?? '') ?></p> <p><?= htmlspecialchars($item['show-pagination'] ?? '') ?></p> <p><?= htmlspecialchars($item['show-comment'] ?? '') ?></p> <p><?= htmlspecialchars($item['showCategoryIcon'] ?? '') ?></p> <p><?= htmlspecialchars($item['number_of_post'] ?? '') ?></p> <p><?= htmlspecialchars($item['created_at'] ?? '') ?></p> 
            </div>
          </div>
        </div>
      </div>

    <?php endforeach; ?>
  </div>
</div>


<!-- List Tab -->
<div id="tab-list" class="hidden px-8 pt-4">
  <ul class="space-y-4">
    <?php foreach($blog_page as $item): ?>
       
<li class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:border-red-500 transition">
        <div class="flex items-center space-x-4">
          <!-- Optional blog_page Image -->
          <img
            src="<?= htmlspecialchars($item['image'] ?? 'https://placehold.co/600x400') ?>"
            alt="Image"
            class="w-10 h-10 rounded-full object-cover"
          />

          <!-- blog_page Info -->
          <div>
            <p class="font-semibold text-red-600 dark:text-red-400"><?= htmlspecialchars($item['blog_page_id']) ?></p>
            <p class="font-semibold text-red-600 dark:text-red-400"><?= htmlspecialchars($item['view_search']) ?></p>
            <p class="font-semibold text-red-600 dark:text-red-400"><?= htmlspecialchars($item['view_categories']) ?></p>







          <div class="mt-4">
            <button
              @click="open = !open"
              class="text-sm text-red-500 hover:text-red-700 focus:outline-none focus:underline"
            >
              <span x-show="!open">More &darr;</span>
              <span x-show="open">Less &uarr;</span>
            </button>

            <div
              x-show="open"
              x-transition
              class="mt-2 text-gray-700 dark:text-gray-300 text-sm"
            >
                <p><?= htmlspecialchars($item['view_recent_blog'] ?? '') ?></p> <p><?= htmlspecialchars($item['recent_blog_number'] ?? '') ?></p> <p><?= htmlspecialchars($item['view_blog_tags'] ?? '') ?></p> <p><?= htmlspecialchars($item['custom_html'] ?? '') ?></p> <p><?= htmlspecialchars($item['img_after_recent_post'] ?? '') ?></p> <p><?= htmlspecialchars($item['img_after_tag'] ?? '') ?></p> <p><?= htmlspecialchars($item['Plain_Text_title'] ?? '') ?></p> <p><?= htmlspecialchars($item['Plain_Text_description'] ?? '') ?></p> <p><?= htmlspecialchars($item['blog_style'] ?? '') ?></p> <p><?= htmlspecialchars($item['pagination_style'] ?? '') ?></p> <p><?= htmlspecialchars($item['show_author'] ?? '') ?></p> <p><?= htmlspecialchars($item['show_author_img'] ?? '') ?></p> <p><?= htmlspecialchars($item['show_single_category'] ?? '') ?></p> <p><?= htmlspecialchars($item['title_limit'] ?? '') ?></p> <p><?= htmlspecialchars($item['description_limit'] ?? '') ?></p> <p><?= htmlspecialchars($item['cta_label'] ?? '') ?></p> <p><?= htmlspecialchars($item['go-to-page'] ?? '') ?></p> <p><?= htmlspecialchars($item['show-date'] ?? '') ?></p> <p><?= htmlspecialchars($item['show-pagination'] ?? '') ?></p> <p><?= htmlspecialchars($item['show-comment'] ?? '') ?></p> <p><?= htmlspecialchars($item['showCategoryIcon'] ?? '') ?></p> <p><?= htmlspecialchars($item['number_of_post'] ?? '') ?></p> <p><?= htmlspecialchars($item['created_at'] ?? '') ?></p> 
            </div>
          </div>









          </div>
        </div>
      </li>

    <?php endforeach; ?>
  </ul>
</div>




<!-- Print Tab -->
<div id="tab-print" class="hidden px-8 pt-4 print:block">
  <!-- Print Button (visible only on screen, hidden in print) -->
  <div class="mb-4 no-print">
    <button onclick="window.print()" class="bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-600 transition">
      Print this table
    </button>
  </div>

  <!-- Printable Table -->
  <div class="overflow-auto">
    <table id="blog_pageTable" class="min-w-full bg-white rounded shadow text-sm">
      <thead>
        <tr>
          <th class="bg-gray-100 px-4 py-2 text-left dark:bg-gray-800">#</th>
         <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">blog_page_id</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">view_search</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">view_categories</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">view_recent_blog</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">recent_blog_number</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">view_blog_tags</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">custom_html</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">img_after_recent_post</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">img_after_tag</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">Plain_Text_title</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">Plain_Text_description</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">blog_style</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">pagination_style</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">show_author</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">show_author_img</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">show_single_category</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">title_limit</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">description_limit</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">cta_label</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">go-to-page</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">show-date</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">show-pagination</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">show-comment</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">showCategoryIcon</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">number_of_post</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">created_at</th> 
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; foreach($blog_page as $item): ?>
        <tr class="border-t">
          <td class="px-4 py-2 dark:bg-gray-800"><?= $i++ ?></td>
            <td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['view_recent_blog']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['recent_blog_number']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['view_blog_tags']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['custom_html']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['img_after_recent_post']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['img_after_tag']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['Plain_Text_title']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['Plain_Text_description']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['blog_style']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['pagination_style']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['show_author']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['show_author_img']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['show_single_category']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['title_limit']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['description_limit']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['cta_label']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['go-to-page']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['show-date']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['show-pagination']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['show-comment']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['showCategoryIcon']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['number_of_post']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>


<!-- Import Tab -->
<div id="tab-import" class="hidden px-8 pt-4">
  <form id="importForm" enctype="multipart/form-data">
    <label for="importFile" class="block mb-2 text-sm font-medium">Upload CSV or SQL</label>
    <input type="hidden" name="action" value="import">
    <input
      type="file"
      id="importFile"
      name="file"
      accept=".csv, .sql"
      class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-red-50 file:text-red-700 hover:file:bg-red-100"
      required
    />
    <button
      type="submit"
      class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition"
    >
      Upload
    </button>
  </form>

  <!-- Feedback messages -->
  <div id="importMessage" class="mt-4 text-sm"></div>
</div>


<!-- Export Tab -->
<div id="tab-export" class="hidden px-8 pt-4 h-1/3">
  <a href="<?= $path?>app/crud/api/blog_page.php?action=export" class="bg-green-500 text-white px-4 py-2 rounded">Export All blog_page</a>
</div>

<!-- Share Tab -->
<div id="tab-share" class="hidden px-8 pt-4 space-y-6">

</div>

</div>


<script>
$.post('<?= $path ?>/app/crud/fetch/widgets/share.php', function(res){
  $("#tab-share").html(res);
});
$('.tabBtn').on('click', function() {
  var tab = $(this).data('tab');
  
  // Hide all tab contents
  $('[id^="tab-"]').hide();

  // Show selected
  $('#tab-' + tab).fadeIn(150);

  // Optional: Highlight active tab
  $('.tabBtn').removeClass('bg-red-500 text-white').addClass('bg-gray-200 dark:bg-gray-700');
  $(this).addClass('bg-red-500 text-white').removeClass('bg-gray-200 dark:bg-gray-700');
});

$('.tabBtn[data-tab="tables"]').trigger('click');










$(document).ready(function(){
const ctx = document.getElementById('blog_pageChart');
if (ctx) {
  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [
        "Today",
        "This Week",
        "This Month",
        "This Year",
        ...<?= json_encode(array_keys($counts['years'])) ?>
      ],
      datasets: [{
        label: 'blog_page Created',
        data: [
          <?= $counts['today'] ?>,
          <?= $counts['week'] ?>,
          <?= $counts['month'] ?>,
          <?= $counts['year'] ?>,
          ...<?= json_encode(array_values($counts['years'])) ?>
        ],
        backgroundColor: [
          'rgba(59, 130, 246, 0.7)',
          'rgba(96, 165, 250, 0.7)',
          'rgba(147, 197, 253, 0.7)',
          'rgba(191, 219, 254, 0.7)',
          ...Array(<?= count($counts['years']) ?>).fill('rgba(59, 130, 246, 0.5)')
        ],
        borderColor: 'rgba(59, 130, 246, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false }
      },
      scales: {
        y: {
          beginAtZero: true,
          title: { display: true, text: 'blog_page Count' }
        }
      }
    }
  });
}



  // --- Toast ---
  function showToast(message,type='success'){
    const toast = $('<div class="fixed top-5 right-5 p-4 rounded shadow text-white z-50"></div>');
    toast.text(message).addClass(type==='success'?'bg-green-500':'bg-red-500');
    $('body').append(toast);
    setTimeout(()=> toast.fadeOut(500, ()=> toast.remove()),3000);
  }




$(document).on('click', 'td.editable', function () {
  let td = $(this);
  if (td.find('input, select').length) return; // Already editing

  let originalValue = td.text().trim();
  let field = td.data('field');
  let id = td.data('id');
  let values = td.data('values');
  let ref = td.data('ref');
  let input;

  if (field === 'enum') {
    input = $(`
      <select class="w-full p-1 border rounded">
        ${values}
      </select>
    `);
    input.val(originalValue.toLowerCase());
  }else if (field === 'set') {
    input = $(`
      <select class="w-full p-1 border rounded multiple">
        ${values}
      </select>
    `);
    input.val(originalValue.toLowerCase());
  }else if (field === 'fk') {
    input = $(`
      <select data-ref="ref" class="w-full p-1 border rounded">
        ${values}
      </select>
    `);
    input.val(originalValue.toLowerCase());
  } else if (field === 'datetime' || field ==='timestamp') {
    input = $(`<input type="datetime-local" class="w-full p-1 border rounded" />`);
    let date = new Date(originalValue);
    let iso = date.toISOString().slice(0, 16); // "YYYY-MM-DDTHH:MM"
    input.val(iso);
  } else if (field === 'time') {
    input = $(`<input type="time" class="w-full p-1 border rounded" />`);
    let date = new Date(originalValue);
    let iso = date.toISOString().slice(10, 16); // "HH:MM"
    input.val(iso);
  } else if (field === 'date') {
    input = $(`<input type="date" class="w-full p-1 border rounded" />`);
    let date = new Date(originalValue);
    let iso = date.toISOString().slice(0, 10); // "YYYY-MM-DD"
    input.val(iso);
  } else if (field === 'email') {
    input = $(`<input type="email" maxlength="${values}" class="w-full p-1 border rounded" value="${originalValue}" />`);
  } else if (field === 'year') {
    input = $(`<input type="number" maxlength="4" minlength="4" min="1901" max="2155" class="w-full p-1 border rounded" value="${originalValue}" />`);
  } else if (field === 'int' || field === 'bigint'  || field === 'mediumint'  || field === 'tinyint' ) {
    input = $(`<input type="number" maxlength="${values}" class="w-full p-1 border rounded" value="${originalValue}" />`);
  } else if (field === 'some_number_field') {
    input = $(`<input type="number" maxlength="${values}" class="w-full p-1 border rounded" value="${originalValue}" />`);
  } else if (field === 'decimal' || field === 'float' || field === 'double') {
    input = $(`<input type="number" maxlength="${values}" step='0.0000' class="w-full p-1 border rounded" value="${originalValue}" />`);
  } else {
    input = $(`<input type="text" maxlength="${values}" class="w-full p-1 border rounded" value="${originalValue}" />`);
  }

  td.html(input);
  input.focus();

  // Save on blur or Enter
  input.on('blur keydown', function (e) {
    if (e.type === 'blur' || (e.type === 'keydown' && e.key === 'Enter')) {
      let newValue = input.val();

      // If datetime, convert to string for DB
      if (field === 'timestamp' || field === 'datetime' || field === 'created_at') {
        newValue = new Date(newValue).toISOString().slice(0, 19).replace('T', ' ');
      }

      $.post('<?= $path?>app/crud/api/blog_page.php', {
        action: 'inline_update',
        id: id,
        field: field,
        value: newValue
      }, function (res) {
        if (res.success) {
          showToast('Updated');
          td.text(newValue);
        } else {
          showToast(res.message, 'error');
          td.text(originalValue); // revert
        }
      }, 'json').fail((xhr) => {
        alert(JSON.stringify(xhr));
        showToast('Server error', 'error');
        td.text(originalValue); // revert
      });
    }
  });
});










  // --- Modal open/close ---
  function openModal(title,content){
    $('#modalTitle').text(title);
    $('#modalContent').html(content);
    $('#blog_pageModal').hide().removeClass('hidden').fadeIn(200);
  }

  $(document).on('click','.modal-close', function(){
    $('#blog_pageModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
  });

  // --- Add blog_page ---
  $('#addblog_pageBtn').click(function(e){
    e.preventDefault();
      
    $.post('<?= $path?>app/crud/forms/create/blog_page.php', function(res){
      if(res){
        openModal('Add blog_page',res);
      } else showToast(res.message,'error');
    }).fail(()=>showToast('Server error:Register Data failed ','error'));

  });

  // --- Submit Add ---
  $(document).on('submit','#addblog_pageForm', function(e){
    e.preventDefault();
    $.post('<?= $path?>app/crud/api/blog_page.php', $(this).serialize()+'&action=insert', function(res){
      if(res.success){
        showToast(res.message);
        $('#blog_pageModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
        // $("#blog_pageTable").load(location.href + " #blog_pageTable");
        window.location.reload();
      } else { showToast(res.message,'error'); }
    },'json').fail(()=>showToast('Server error','error'));
  });

  // --- Save & Continue ---
  $(document).on('click','#saveContinueBtn', function(){
    $.post('<?= $path?>app/crud/api/blog_page.php', $('#addblog_pageForm').serialize()+'&action=insert', function(res){
      if(res.success){
        showToast(res.message);
        // $("#blog_pageTable").load(location.href + " #blog_pageTable");
        window.location.reload();
        $('#addblog_pageForm')[0].reset();
      } else {showToast(res.message,'error');}
    },'json').fail(()=>showToast('Server error','error'));
  });

// --- View blog_page ---
$(document).on('click','.viewBtn', function(){
    let id=$(this).data('id');
    $.post('<?= $path?>app/crud/api/blog_page.php',{action:'view',id}, function(res){
      if(res.success){
        let html = `
          <div class="bg-red-500 text-white p-4 rounded mb-4 text-lg font-semibold">
            blog_page
          </div>
          <div class="grid grid-cols-2 gap-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
            <p class="text-gray-700 dark:text-gray-200"><strong>blog_page_id:</strong> ${res.data.blog_page_id}</p> <p class="text-gray-700 dark:text-gray-200"><strong>view_search:</strong> ${res.data.view_search}</p> <p class="text-gray-700 dark:text-gray-200"><strong>view_categories:</strong> ${res.data.view_categories}</p> <p class="text-gray-700 dark:text-gray-200"><strong>view_recent_blog:</strong> ${res.data.view_recent_blog}</p> <p class="text-gray-700 dark:text-gray-200"><strong>recent_blog_number:</strong> ${res.data.recent_blog_number}</p> <p class="text-gray-700 dark:text-gray-200"><strong>view_blog_tags:</strong> ${res.data.view_blog_tags}</p> <p class="text-gray-700 dark:text-gray-200"><strong>custom_html:</strong> ${res.data.custom_html}</p> <p class="text-gray-700 dark:text-gray-200"><strong>img_after_recent_post:</strong> ${res.data.img_after_recent_post}</p> <p class="text-gray-700 dark:text-gray-200"><strong>img_after_tag:</strong> ${res.data.img_after_tag}</p> <p class="text-gray-700 dark:text-gray-200"><strong>Plain_Text_title:</strong> ${res.data.Plain_Text_title}</p> <p class="text-gray-700 dark:text-gray-200"><strong>Plain_Text_description:</strong> ${res.data.Plain_Text_description}</p> <p class="text-gray-700 dark:text-gray-200"><strong>blog_style:</strong> ${res.data.blog_style}</p> <p class="text-gray-700 dark:text-gray-200"><strong>pagination_style:</strong> ${res.data.pagination_style}</p> <p class="text-gray-700 dark:text-gray-200"><strong>show_author:</strong> ${res.data.show_author}</p> <p class="text-gray-700 dark:text-gray-200"><strong>show_author_img:</strong> ${res.data.show_author_img}</p> <p class="text-gray-700 dark:text-gray-200"><strong>show_single_category:</strong> ${res.data.show_single_category}</p> <p class="text-gray-700 dark:text-gray-200"><strong>title_limit:</strong> ${res.data.title_limit}</p> <p class="text-gray-700 dark:text-gray-200"><strong>description_limit:</strong> ${res.data.description_limit}</p> <p class="text-gray-700 dark:text-gray-200"><strong>cta_label:</strong> ${res.data.cta_label}</p> <p class="text-gray-700 dark:text-gray-200"><strong>go-to-page:</strong> ${res.data.go-to-page}</p> <p class="text-gray-700 dark:text-gray-200"><strong>show-date:</strong> ${res.data.show-date}</p> <p class="text-gray-700 dark:text-gray-200"><strong>show-pagination:</strong> ${res.data.show-pagination}</p> <p class="text-gray-700 dark:text-gray-200"><strong>show-comment:</strong> ${res.data.show-comment}</p> <p class="text-gray-700 dark:text-gray-200"><strong>showCategoryIcon:</strong> ${res.data.showCategoryIcon}</p> <p class="text-gray-700 dark:text-gray-200"><strong>number_of_post:</strong> ${res.data.number_of_post}</p> <p class="text-gray-700 dark:text-gray-200"><strong>created_at:</strong> ${res.data.created_at}</p> 
          <div class="flex justify-end mt-4">
            <button type="button" class="modal-close bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">Close</button>
          </div>
        `;
        openModal('View blog_page', html);
      } else showToast(res.message,'error');
    },'json').fail(()=>showToast('Server error','error'));
});


  // --- Edit blog_page ---
  $(document).on('click','.editBtn', function(){
    let id=$(this).data('id');
    $.get('<?= $path?>app/crud/forms/update/blog_page.php', {id}, function(res){
        openModal('Edit blog_page',res);
    }).fail(()=>showToast('Server error','error'));
  });


  // --- Submit Edit ---
  $(document).on('submit','#editblog_pageForm', function(e){
    e.preventDefault();
    $.post('<?= $path?>app/crud/api/blog_page.php', $(this).serialize()+'&action=edit', function(res){
      if(res.success){
        showToast(res.message);
        $('#blog_pageModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
        // $("#blog_pageTable").load(location.href + " #blog_pageTable");
        window.location.reload();
      } else showToast(res.message,'error');
    },'json').fail(()=>showToast('Server error','error'));
  });

  // --- Delete blog_page ---
  $(document).on('click','.deleteBtn', function(){
    let id=$(this).data('id');
    let html=`
      <p>Are you sure you want to delete this blog_page?</p>
      <div class="flex justify-end gap-3 mt-4">
        <button id="confirmDeleteBtn" data-id="${id}" class="bg-red-500 text-white px-6 py-2 rounded">Delete</button>
        <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Cancel</button>
      </div>`;
    openModal('Confirm Delete',html);
  });

  $(document).on('click','#confirmDeleteBtn', function(){
    let id=$(this).data('id');
    $.post('<?= $path?>app/crud/api/blog_page.php',{action:'delete',id}, function(res){
      if(res.success){
        showToast(res.message);
        $('#blog_pageModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
        // $("#blog_pageTable").load(location.href + " #blog_pageTable");
        window.location.reload();
      } else showToast(res.message,'error');
    },'json').fail(()=>showToast('Server error','error'));
  });











  // --- Bulk Actions ---
$('#applyBulkAction').on('click', function(){
    var action = $('#bulkActions').val();
    var ids = $('.rowCheckbox:checked').map(function(){ return $(this).data('id'); }).get();
    if(!action) return alert('Select a bulk action.');
    if(ids.length === 0) return alert('Select at least one row.');







    if(action === 'copy'){
        // Bulk Copy: fetch each blog_page data
        $.post('<?= $path?>app/crud/api/blog_page.php',{action:'bulk_view', ids: ids}, function(res){
            if(res.success){
                let html = '<form id="bulkCopyForm">';
                res.data.forEach(blog_page => {
                    html += `
                    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow mb-4">
                        <h3 class="font-bold text-lg mb-2 text-green-500">Copy blog_page</h3>
                        <input type="hidden" name="ids[]" value="${blog_page.id}">
                        <div class="grid grid-cols-2 gap-4">
                        
                            <div>
                                <label for="blog_page_id">blog_page_id</label>
                                <input type="text" id="blog_page_id" name="blog_page_id[${blog_page.id}]" value="${blog_page.blog_page_id}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="view_search">view_search</label>
                                <input type="text" id="view_search" name="view_search[${blog_page.id}]" value="${blog_page.view_search}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="view_categories">view_categories</label>
                                <input type="text" id="view_categories" name="view_categories[${blog_page.id}]" value="${blog_page.view_categories}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="view_recent_blog">view_recent_blog</label>
                                <input type="text" id="view_recent_blog" name="view_recent_blog[${blog_page.id}]" value="${blog_page.view_recent_blog}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="recent_blog_number">recent_blog_number</label>
                                <input type="text" id="recent_blog_number" name="recent_blog_number[${blog_page.id}]" value="${blog_page.recent_blog_number}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="view_blog_tags">view_blog_tags</label>
                                <input type="text" id="view_blog_tags" name="view_blog_tags[${blog_page.id}]" value="${blog_page.view_blog_tags}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="custom_html">custom_html</label>
                                <input type="text" id="custom_html" name="custom_html[${blog_page.id}]" value="${blog_page.custom_html}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="img_after_recent_post">img_after_recent_post</label>
                                <input type="text" id="img_after_recent_post" name="img_after_recent_post[${blog_page.id}]" value="${blog_page.img_after_recent_post}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="img_after_tag">img_after_tag</label>
                                <input type="text" id="img_after_tag" name="img_after_tag[${blog_page.id}]" value="${blog_page.img_after_tag}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="Plain_Text_title">Plain_Text_title</label>
                                <input type="text" id="Plain_Text_title" name="Plain_Text_title[${blog_page.id}]" value="${blog_page.Plain_Text_title}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="Plain_Text_description">Plain_Text_description</label>
                                <input type="text" id="Plain_Text_description" name="Plain_Text_description[${blog_page.id}]" value="${blog_page.Plain_Text_description}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="blog_style">blog_style</label>
                                <input type="text" id="blog_style" name="blog_style[${blog_page.id}]" value="${blog_page.blog_style}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="pagination_style">pagination_style</label>
                                <input type="text" id="pagination_style" name="pagination_style[${blog_page.id}]" value="${blog_page.pagination_style}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="show_author">show_author</label>
                                <input type="text" id="show_author" name="show_author[${blog_page.id}]" value="${blog_page.show_author}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="show_author_img">show_author_img</label>
                                <input type="text" id="show_author_img" name="show_author_img[${blog_page.id}]" value="${blog_page.show_author_img}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="show_single_category">show_single_category</label>
                                <input type="text" id="show_single_category" name="show_single_category[${blog_page.id}]" value="${blog_page.show_single_category}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="title_limit">title_limit</label>
                                <input type="text" id="title_limit" name="title_limit[${blog_page.id}]" value="${blog_page.title_limit}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="description_limit">description_limit</label>
                                <input type="text" id="description_limit" name="description_limit[${blog_page.id}]" value="${blog_page.description_limit}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="cta_label">cta_label</label>
                                <input type="text" id="cta_label" name="cta_label[${blog_page.id}]" value="${blog_page.cta_label}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="go-to-page">go-to-page</label>
                                <input type="text" id="go-to-page" name="go-to-page[${blog_page.id}]" value="${blog_page.go-to-page}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="show-date">show-date</label>
                                <input type="text" id="show-date" name="show-date[${blog_page.id}]" value="${blog_page.show-date}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="show-pagination">show-pagination</label>
                                <input type="text" id="show-pagination" name="show-pagination[${blog_page.id}]" value="${blog_page.show-pagination}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="show-comment">show-comment</label>
                                <input type="text" id="show-comment" name="show-comment[${blog_page.id}]" value="${blog_page.show-comment}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="showCategoryIcon">showCategoryIcon</label>
                                <input type="text" id="showCategoryIcon" name="showCategoryIcon[${blog_page.id}]" value="${blog_page.showCategoryIcon}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="number_of_post">number_of_post</label>
                                <input type="text" id="number_of_post" name="number_of_post[${blog_page.id}]" value="${blog_page.number_of_post}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="created_at">created_at</label>
                                <input type="text" id="created_at" name="created_at[${blog_page.id}]" value="${blog_page.created_at}" class="w-full p-2 border rounded mb-2">
                            </div> 
                        </div>
                    </div>
                    `;
                });

                html += `<div class="flex justify-end gap-3 mt-4">
                            <button id="bulkCopySaveBtn" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">Save as New</button>
                            <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Cancel</button>
                         </div></form>`;

                openModal('Bulk Copy blog_page', html);
            } else showToast(res.message,'error');
        },'json').fail(()=>showToast('Server error','error'));
    }



    else if(action === 'delete'){
        let html = `<p>Are you sure you want to delete selected blog_page?</p>
                    <div class="flex justify-end gap-3 mt-4">
                      <button id="bulkDeleteBtn" class="bg-red-500 text-white px-6 py-2 rounded">Delete</button>
                      <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Cancel</button>
                    </div>`;
        openModal('Confirm Bulk Delete', html);
    }


      else if(action === 'view'){
          $.post('<?= $path?>app/crud/api/blog_page.php',{action:'bulk_view', ids: ids}, function(res){
              if(res.success){
                  let html = '';
                  res.data.forEach(blog_page => {
                      html += `<div class="bg-white dark:bg-gray-800 p-4 rounded shadow mb-4">
                                  
                                        <p>blog_page_id: ${blog_page.blog_page_id}</p>
                                    
                                        <p>view_search: ${blog_page.view_search}</p>
                                    
                                        <p>view_categories: ${blog_page.view_categories}</p>
                                    
                                        <p>view_recent_blog: ${blog_page.view_recent_blog}</p>
                                    
                                        <p>recent_blog_number: ${blog_page.recent_blog_number}</p>
                                    
                                        <p>view_blog_tags: ${blog_page.view_blog_tags}</p>
                                    
                                        <p>custom_html: ${blog_page.custom_html}</p>
                                    
                                        <p>img_after_recent_post: ${blog_page.img_after_recent_post}</p>
                                    
                                        <p>img_after_tag: ${blog_page.img_after_tag}</p>
                                    
                                        <p>Plain_Text_title: ${blog_page.Plain_Text_title}</p>
                                    
                                        <p>Plain_Text_description: ${blog_page.Plain_Text_description}</p>
                                    
                                        <p>blog_style: ${blog_page.blog_style}</p>
                                    
                                        <p>pagination_style: ${blog_page.pagination_style}</p>
                                    
                                        <p>show_author: ${blog_page.show_author}</p>
                                    
                                        <p>show_author_img: ${blog_page.show_author_img}</p>
                                    
                                        <p>show_single_category: ${blog_page.show_single_category}</p>
                                    
                                        <p>title_limit: ${blog_page.title_limit}</p>
                                    
                                        <p>description_limit: ${blog_page.description_limit}</p>
                                    
                                        <p>cta_label: ${blog_page.cta_label}</p>
                                    
                                        <p>go-to-page: ${blog_page.go-to-page}</p>
                                    
                                        <p>show-date: ${blog_page.show-date}</p>
                                    
                                        <p>show-pagination: ${blog_page.show-pagination}</p>
                                    
                                        <p>show-comment: ${blog_page.show-comment}</p>
                                    
                                        <p>showCategoryIcon: ${blog_page.showCategoryIcon}</p>
                                    
                                        <p>number_of_post: ${blog_page.number_of_post}</p>
                                    
                                        <p>created_at: ${blog_page.created_at}</p>
                                    
                                </div>`;
                  });
                  html += `<div class="flex justify-end mt-4">
                              <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Close</button>
                          </div>`;
                  openModal('View blog_page', html);
              }
          },'json');
      }

      else if(action === 'export'){
          let idsParam = ids.join(',');
          window.location.href = `<?= $path?>app/crud/api/blog_page.php?action=bulk_export&ids=${idsParam}`;
      }


    else if(action === 'edit'){
        // Bulk Edit: fetch each blog_page's data
        $.post('<?= $path?>app/crud/api/blog_page.php',{action:'bulk_view', ids: ids}, function(res){
            if(res.success){
                // Create forms for each blog_page
                let html = '';
                res.data.forEach(blog_page => {
                    html += `
                        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow mb-4">
                            <h3 class="font-bold text-lg mb-2 text-red-500">Edit blog_page</h3>
                            <input type="hidden" name="ids[]" value="${blog_page.id}">
                            <div class="grid grid-cols-2 gap-4">

                                
                                        <div>
                                            <label for="view_recent_blog" class="block text-gray-700 dark:text-gray-200">view_recent_blog</label>
                                            <input type="text" id="view_recent_blog" name="view_recent_blog[${blog_page.id}]" value="${blog_page.view_recent_blog}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="recent_blog_number" class="block text-gray-700 dark:text-gray-200">recent_blog_number</label>
                                            <input type="text" id="recent_blog_number" name="recent_blog_number[${blog_page.id}]" value="${blog_page.recent_blog_number}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="view_blog_tags" class="block text-gray-700 dark:text-gray-200">view_blog_tags</label>
                                            <input type="text" id="view_blog_tags" name="view_blog_tags[${blog_page.id}]" value="${blog_page.view_blog_tags}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="custom_html" class="block text-gray-700 dark:text-gray-200">custom_html</label>
                                            <input type="text" id="custom_html" name="custom_html[${blog_page.id}]" value="${blog_page.custom_html}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="img_after_recent_post" class="block text-gray-700 dark:text-gray-200">img_after_recent_post</label>
                                            <input type="text" id="img_after_recent_post" name="img_after_recent_post[${blog_page.id}]" value="${blog_page.img_after_recent_post}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="img_after_tag" class="block text-gray-700 dark:text-gray-200">img_after_tag</label>
                                            <input type="text" id="img_after_tag" name="img_after_tag[${blog_page.id}]" value="${blog_page.img_after_tag}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="Plain_Text_title" class="block text-gray-700 dark:text-gray-200">Plain_Text_title</label>
                                            <input type="text" id="Plain_Text_title" name="Plain_Text_title[${blog_page.id}]" value="${blog_page.Plain_Text_title}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="Plain_Text_description" class="block text-gray-700 dark:text-gray-200">Plain_Text_description</label>
                                            <input type="text" id="Plain_Text_description" name="Plain_Text_description[${blog_page.id}]" value="${blog_page.Plain_Text_description}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="blog_style" class="block text-gray-700 dark:text-gray-200">blog_style</label>
                                            <input type="text" id="blog_style" name="blog_style[${blog_page.id}]" value="${blog_page.blog_style}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="pagination_style" class="block text-gray-700 dark:text-gray-200">pagination_style</label>
                                            <input type="text" id="pagination_style" name="pagination_style[${blog_page.id}]" value="${blog_page.pagination_style}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="show_author" class="block text-gray-700 dark:text-gray-200">show_author</label>
                                            <input type="text" id="show_author" name="show_author[${blog_page.id}]" value="${blog_page.show_author}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="show_author_img" class="block text-gray-700 dark:text-gray-200">show_author_img</label>
                                            <input type="text" id="show_author_img" name="show_author_img[${blog_page.id}]" value="${blog_page.show_author_img}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="show_single_category" class="block text-gray-700 dark:text-gray-200">show_single_category</label>
                                            <input type="text" id="show_single_category" name="show_single_category[${blog_page.id}]" value="${blog_page.show_single_category}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="title_limit" class="block text-gray-700 dark:text-gray-200">title_limit</label>
                                            <input type="text" id="title_limit" name="title_limit[${blog_page.id}]" value="${blog_page.title_limit}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="description_limit" class="block text-gray-700 dark:text-gray-200">description_limit</label>
                                            <input type="text" id="description_limit" name="description_limit[${blog_page.id}]" value="${blog_page.description_limit}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="cta_label" class="block text-gray-700 dark:text-gray-200">cta_label</label>
                                            <input type="text" id="cta_label" name="cta_label[${blog_page.id}]" value="${blog_page.cta_label}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="go-to-page" class="block text-gray-700 dark:text-gray-200">go-to-page</label>
                                            <input type="text" id="go-to-page" name="go-to-page[${blog_page.id}]" value="${blog_page.go-to-page}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="show-date" class="block text-gray-700 dark:text-gray-200">show-date</label>
                                            <input type="text" id="show-date" name="show-date[${blog_page.id}]" value="${blog_page.show-date}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="show-pagination" class="block text-gray-700 dark:text-gray-200">show-pagination</label>
                                            <input type="text" id="show-pagination" name="show-pagination[${blog_page.id}]" value="${blog_page.show-pagination}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="show-comment" class="block text-gray-700 dark:text-gray-200">show-comment</label>
                                            <input type="text" id="show-comment" name="show-comment[${blog_page.id}]" value="${blog_page.show-comment}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="showCategoryIcon" class="block text-gray-700 dark:text-gray-200">showCategoryIcon</label>
                                            <input type="text" id="showCategoryIcon" name="showCategoryIcon[${blog_page.id}]" value="${blog_page.showCategoryIcon}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="number_of_post" class="block text-gray-700 dark:text-gray-200">number_of_post</label>
                                            <input type="text" id="number_of_post" name="number_of_post[${blog_page.id}]" value="${blog_page.number_of_post}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="created_at" class="block text-gray-700 dark:text-gray-200">created_at</label>
                                            <input type="text" id="created_at" name="created_at[${blog_page.id}]" value="${blog_page.created_at}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
        
                            </div>
                        </div>
                    `;
                });

                html += `<div class="flex justify-end gap-3 mt-4">
                            <button id="bulkSaveBtn" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">Save</button>
                            <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Cancel</button>
                         </div>`;

                openModal('Bulk Edit blog_page', html);
            } else showToast(res.message,'error');
        },'json').fail(()=>showToast('Server error','error'));
    } else {
        alert('Bulk action not implemented yet.');
    }
});



$(document).on('click','#bulkDeleteBtn', function(){
    let ids = $('.rowCheckbox:checked').map(function(){ return $(this).data('id'); }).get();
    $.post('<?= $path?>app/crud/api/blog_page.php',{action:'bulk_delete',ids:ids}, function(res){
        if(res.success){
            showToast(res.message);
            $('#blog_pageModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
            // $("#blog_pageTable").load(location.href + " #blog_pageTable");
            window.location.reload();
        } else showToast(res.message,'error');
    },'json');
});


$(document).on('click','#bulkCopySaveBtn', function(e){
    e.preventDefault();
    let formData = { action: 'bulk_copy', ids: [] };
    $('#modalContent').find('input[name="ids[]"]').each(function(){
        formData.ids.push($(this).val());
    });

// Collect all blog_page fields
const excluded = [
    'blog_page_id',
    
    'view_search',
    
    'view_categories',
    
    'view_recent_blog',
    
    'recent_blog_number',
    
    'view_blog_tags',
    
    'custom_html',
    
    'img_after_recent_post',
    
    'img_after_tag',
    
    'Plain_Text_title',
    
    'Plain_Text_description',
    
    'blog_style',
    
    'pagination_style',
    
    'show_author',
    
    'show_author_img',
    
    'show_single_category',
    
    'title_limit',
    
    'description_limit',
    
    'cta_label',
    
    'go-to-page',
    
    'show-date',
    
    'show-pagination',
    
    'show-comment',
    
    'showCategoryIcon',
    
    'number_of_post',
    
    'created_at',
    ];

$('#modalContent').find('input, select').each(function() {
    if (!excluded.some(key => this.name.includes(key))) {
        formData[this.name] = $(this).val();
    }
});

    $.post('<?= $path?>app/crud/api/blog_page.php', formData, function(res){
        if(res.success){
            showToast(res.message);
            $('#blog_pageModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
            // $("#blog_pageTable").load(location.href + " #blog_pageTable");
            window.location.reload();
        } else {showToast(res.message,'error');}
    },'json').fail((xhr)=>showToast(JSON.stringify(xhr)+'Server error','error'));
});






// --- Save Bulk Edit ---
$(document).on('click','#bulkSaveBtn', function(){
      let formData = { action: 'bulk_edit', ids: [] };

      $('#modalContent').find('input[name="ids[]"]').each(function(){
          formData.ids.push($(this).val());
      });

    // Collect all blog_page fields
    const excluded = [
    'blog_page_id',
    
    'view_search',
    
    'view_categories',
    
    'view_recent_blog',
    
    'recent_blog_number',
    
    'view_blog_tags',
    
    'custom_html',
    
    'img_after_recent_post',
    
    'img_after_tag',
    
    'Plain_Text_title',
    
    'Plain_Text_description',
    
    'blog_style',
    
    'pagination_style',
    
    'show_author',
    
    'show_author_img',
    
    'show_single_category',
    
    'title_limit',
    
    'description_limit',
    
    'cta_label',
    
    'go-to-page',
    
    'show-date',
    
    'show-pagination',
    
    'show-comment',
    
    'showCategoryIcon',
    
    'number_of_post',
    
    'created_at',
    ];

    $('#modalContent').find('input, select').each(function() {
        if (!excluded.some(key => this.name.includes(key))) {
            formData[this.name] = $(this).val();
        }
    });


    formData['action'] = 'bulk_edit';

    $.post('<?= $path?>app/crud/api/blog_page.php', formData, function(res){
        if(res.success){
            showToast(res.message);
            $('#blog_pageModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
            // $("#blog_pageTable").load(location.href + " #blog_pageTable");
            window.location.reload();
        } else showToast(res.message,'error');
    },'json').fail((xhr)=>showToast(JSON.stringify(xhr)+':Server error','error'));
});


// If all individual checkboxes are checked, check header; otherwise, uncheck
$(document).on('change', '.rowCheckbox', function() {
    var allChecked = $('.rowCheckbox').length === $('.rowCheckbox:checked').length;
    $('#selectAll').prop('checked', allChecked);
});



// Select/Deselect all checkboxes
$('#selectAll').on('change', function() {
    var checked = $(this).is(':checked');
    $('.rowCheckbox').prop('checked', checked);
});


});
</script>


<script>
$('#importForm').on('submit', function(e) {
  e.preventDefault();

  const fileInput = $('#importFile')[0];
  const file = fileInput.files[0];

  // Check if a file is selected
  if (!file) {
    $('#importMessage').html('<p class="text-red-500">Please select a file.</p>');
    return;
  }

  // Validate file type
  const allowedExtensions = ['csv', 'sql'];
  const fileExtension = file.name.split('.').pop().toLowerCase();

  if (!allowedExtensions.includes(fileExtension)) {
    $('#importMessage').html('<p class="text-red-500">Only CSV or SQL files are allowed.</p>');
    return;
  }

  const formData = new FormData(this);

  $.ajax({
    url: '<?= $path?>app/crud/api/blog_page.php',
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    beforeSend: function() {
      $('#importMessage').html('<p class="text-red-500">Uploading...</p>');
    },
    success: function(response) {
      // alert(response);
      if (response.success) {
       $('#importMessage').html('<p class="text-green-600">' + response.message + '</p>');
      }else{
       $('#importMessage').html('<p class="text-red-600">' + response.message + '</p>');
      }
    },
    error: function(xhr) {
      $('#importMessage').html('<p class="text-red-500">Error: ' + xhr.responseText + '</p>');
    }
  });
});
</script>



<script>
$(document).ready(function(){

  // Initialize DataTable
  let table = new DataTable('#blog_pageTable', {
    pageLength: 5,
    lengthMenu: [5, 10, 20, 50, { label: "All", value: -1 }],
    searching: true,
    ordering: true,
    paging: true,
    responsive: true,
    autoWidth: false,
    columnDefs: [
      { orderable: false, targets: [0, 6] } // disable sort on checkbox + actions
    ],
    language: {
      lengthMenu: "Show _MENU_ entries",
      search: "Search:",
      paginate: {
        next: "›",
        previous: "‹"
      }
    }
  });

  // Handle dark mode styling
  function applyDarkMode(){
    if($('html').hasClass('dark')){
      $('#blog_pageTable').addClass('stripe hover text-gray-200 bg-gray-900');
    } else {
      $('#blog_pageTable').removeClass('text-gray-200 bg-gray-900');
    }
  }

  applyDarkMode();

  // If your site toggles dark mode dynamically, re-apply styles
  const observer = new MutationObserver(applyDarkMode);
  observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

});


$(document).on('keydown', function(event) {
    if (event.key === "Escape" || event.keyCode === 27) {
        $('.modal.show').modal('hide');
    }
});

</script>
