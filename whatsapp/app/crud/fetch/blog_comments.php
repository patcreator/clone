
                <?php
  $stmt = $pdo->query("SELECT * FROM blog_comments");
  $blog_comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

foreach ($blog_comments as $item) {
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
$columns = ['blog_comment_id','blog','firstname','lastname','Email','message','createdDate','Status','created_at'];
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

    <h1 class="text-2xl font-bold mb-6 px-8">blog_comments</h1>

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

    <!-- Add blog_comments Button -->
    <a href="#" id="addblog_commentsBtn" class="mb-4 ml-8 px-4 py-2 bg-red-500 text-white rounded inline-block">
      Add blog_comments
    </a>

    <!-- Reusable Modal -->
    <div id="blog_commentsModal" class="fixed inset-0 w-full h-full bg-white dark:bg-gray-900 dark:text-white z-50 overflow-y-auto hidden">
      <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
        <button class="modal-close flex items-center text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white">
          <i class="fa fa-arrow-left mr-2"></i> Back
        </button>
        <h2 class="text-xl font-bold" id="modalTitle">blog_comments</h2>
        <span></span>
      </div>
      <div class="p-6 max-w-2xl mx-auto" id="modalContent"></div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto px-8 pt-2">
      <div id="tab-tables">
        <table id="blog_commentsTable" class="display min-w-full bg-white rounded shadow w-full text-sm text-left text-gray-700 dark:text-gray-200">
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
            <?php $i=1; foreach($blog_comments as $item): ?>
            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 hover:dark:bg-gray-700">
                <td class="border-0 px-4 py-4 dark:bg-gray-800"><input type="checkbox" class="rowCheckbox" data-id="<?= $item['blog_comment_id'] ?>"></td>

                <td class="border-0 px-4 py-4 dark:bg-gray-800"><?= $i++ ?></td>

                 
                    <td class="editable" data-id="<?= $item['blog_comment_id'] ?>" data-field="blog_comment_id" data-values=''><?= htmlspecialchars($item['blog_comment_id']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_comment_id'] ?>" data-field="blog" data-values=''><?= htmlspecialchars($item['blog']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_comment_id'] ?>" data-field="firstname" data-values=''><?= htmlspecialchars($item['firstname']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_comment_id'] ?>" data-field="lastname" data-values=''><?= htmlspecialchars($item['lastname']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_comment_id'] ?>" data-field="Email" data-values=''><?= htmlspecialchars($item['Email']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_comment_id'] ?>" data-field="message" data-values=''><?= htmlspecialchars($item['message']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_comment_id'] ?>" data-field="createdDate" data-values=''><?= htmlspecialchars($item['createdDate']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_comment_id'] ?>" data-field="Status" data-values=''><?= htmlspecialchars($item['Status']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['blog_comment_id'] ?>" data-field="created_at" data-values=''><?= htmlspecialchars($item['created_at']) ?></td>
                    
                <td class="border-0 px-4 py-4 dark:bg-gray-800">
                  <button class="viewBtn p-2 hover:bg-gray-100 rounded-full" data-id="<?= $item['blog_comment_id'] ?>" title="view"><i class="mdi-24px mdi mdi-eye"></i></button>
                  <button class="editBtn p-2 hover:bg-gray-100 rounded-full" data-id="<?= $item['blog_comment_id'] ?>" title="edit"><i class="mdi-24px mdi mdi-pen"></i></button>
                  <button class="deleteBtn p-2 hover:bg-gray-100 rounded-full" data-id="<?= $item['blog_comment_id'] ?>" title="delete"><i class="mdi-24px mdi mdi-delete"></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <!-- Charts Tab -->
<div id="tab-charts" class="hidden px-8 pt-4">
  <canvas id="blog_commentsChart" height="120"></canvas>
</div>

<!-- Grid Tab -->
<div id="tab-grid" class="hidden px-8 pt-4">
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <?php foreach($blog_comments as $key => $item): ?>
      <div
        class="group bg-white dark:bg-gray-800 rounded-lg shadow-md border border-transparent hover:border-red-500 focus-within:border-red-500 active:border-red-700 transition duration-300 overflow-hidden"
        x-data="{ open: false }"
      >
        <img
          src="<?= htmlspecialchars($item['image'] ?? 'https://placehold.co/600x400') ?>"
          alt="blog_comments Image"
          class="w-full h-40 object-cover"
        />
        <div class="p-4">
          <h3 class="text-lg font-bold text-red-600"><?= htmlspecialchars($item['blog_comment_id']) ?></h3>
          <p class="text-gray-600 dark:text-gray-300"><?= htmlspecialchars($item['blog']??'') ?></p>
          <p class="text-sm text-gray-500 dark:text-gray-400">Status: <?= htmlspecialchars($item['firstname']??'') ?></p>

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

              <p><?= htmlspecialchars($item['lastname'] ?? '') ?></p> <p><?= htmlspecialchars($item['Email'] ?? '') ?></p> <p><?= htmlspecialchars($item['message'] ?? '') ?></p> <p><?= htmlspecialchars($item['createdDate'] ?? '') ?></p> <p><?= htmlspecialchars($item['Status'] ?? '') ?></p> <p><?= htmlspecialchars($item['created_at'] ?? '') ?></p> 
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
    <?php foreach($blog_comments as $item): ?>
       
<li class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:border-red-500 transition">
        <div class="flex items-center space-x-4">
          <!-- Optional blog_comments Image -->
          <img
            src="<?= htmlspecialchars($item['image'] ?? 'https://placehold.co/600x400') ?>"
            alt="Image"
            class="w-10 h-10 rounded-full object-cover"
          />

          <!-- blog_comments Info -->
          <div>
            <p class="font-semibold text-red-600 dark:text-red-400"><?= htmlspecialchars($item['blog_comment_id']) ?></p>
            <p class="font-semibold text-red-600 dark:text-red-400"><?= htmlspecialchars($item['blog']) ?></p>
            <p class="font-semibold text-red-600 dark:text-red-400"><?= htmlspecialchars($item['firstname']) ?></p>







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
                <p><?= htmlspecialchars($item['lastname'] ?? '') ?></p> <p><?= htmlspecialchars($item['Email'] ?? '') ?></p> <p><?= htmlspecialchars($item['message'] ?? '') ?></p> <p><?= htmlspecialchars($item['createdDate'] ?? '') ?></p> <p><?= htmlspecialchars($item['Status'] ?? '') ?></p> <p><?= htmlspecialchars($item['created_at'] ?? '') ?></p> 
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
    <table id="blog_commentsTable" class="min-w-full bg-white rounded shadow text-sm">
      <thead>
        <tr>
          <th class="bg-gray-100 px-4 py-2 text-left dark:bg-gray-800">#</th>
         <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">blog_comment_id</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">blog</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">firstname</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">lastname</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">Email</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">message</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">createdDate</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">Status</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">created_at</th> 
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; foreach($blog_comments as $item): ?>
        <tr class="border-t">
          <td class="px-4 py-2 dark:bg-gray-800"><?= $i++ ?></td>
            <td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['lastname']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['Email']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['message']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['createdDate']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['Status']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['created_at']) ?></td>
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
  <a href="<?= $path?>app/crud/api/blog_comments.php?action=export" class="bg-green-500 text-white px-4 py-2 rounded">Export All blog_comments</a>
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
const ctx = document.getElementById('blog_commentsChart');
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
        label: 'blog_comments Created',
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
          title: { display: true, text: 'blog_comments Count' }
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

      $.post('<?= $path?>app/crud/api/blog_comments.php', {
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
    $('#blog_commentsModal').hide().removeClass('hidden').fadeIn(200);
  }

  $(document).on('click','.modal-close', function(){
    $('#blog_commentsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
  });

  // --- Add blog_comments ---
  $('#addblog_commentsBtn').click(function(e){
    e.preventDefault();
      
    $.post('<?= $path?>app/crud/forms/create/blog_comments.php', function(res){
      if(res){
        openModal('Add blog_comments',res);
      } else showToast(res.message,'error');
    }).fail(()=>showToast('Server error:Register Data failed ','error'));

  });

  // --- Submit Add ---
  $(document).on('submit','#addblog_commentsForm', function(e){
    e.preventDefault();
    $.post('<?= $path?>app/crud/api/blog_comments.php', $(this).serialize()+'&action=insert', function(res){
      if(res.success){
        showToast(res.message);
        $('#blog_commentsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
        // $("#blog_commentsTable").load(location.href + " #blog_commentsTable");
        window.location.reload();
      } else { showToast(res.message,'error'); }
    },'json').fail(()=>showToast('Server error','error'));
  });

  // --- Save & Continue ---
  $(document).on('click','#saveContinueBtn', function(){
    $.post('<?= $path?>app/crud/api/blog_comments.php', $('#addblog_commentsForm').serialize()+'&action=insert', function(res){
      if(res.success){
        showToast(res.message);
        // $("#blog_commentsTable").load(location.href + " #blog_commentsTable");
        window.location.reload();
        $('#addblog_commentsForm')[0].reset();
      } else {showToast(res.message,'error');}
    },'json').fail(()=>showToast('Server error','error'));
  });

// --- View blog_comments ---
$(document).on('click','.viewBtn', function(){
    let id=$(this).data('id');
    $.post('<?= $path?>app/crud/api/blog_comments.php',{action:'view',id}, function(res){
      if(res.success){
        let html = `
          <div class="bg-red-500 text-white p-4 rounded mb-4 text-lg font-semibold">
            blog_comments
          </div>
          <div class="grid grid-cols-2 gap-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
            <p class="text-gray-700 dark:text-gray-200"><strong>blog_comment_id:</strong> ${res.data.blog_comment_id}</p> <p class="text-gray-700 dark:text-gray-200"><strong>blog:</strong> ${res.data.blog}</p> <p class="text-gray-700 dark:text-gray-200"><strong>firstname:</strong> ${res.data.firstname}</p> <p class="text-gray-700 dark:text-gray-200"><strong>lastname:</strong> ${res.data.lastname}</p> <p class="text-gray-700 dark:text-gray-200"><strong>Email:</strong> ${res.data.Email}</p> <p class="text-gray-700 dark:text-gray-200"><strong>message:</strong> ${res.data.message}</p> <p class="text-gray-700 dark:text-gray-200"><strong>createdDate:</strong> ${res.data.createdDate}</p> <p class="text-gray-700 dark:text-gray-200"><strong>Status:</strong> ${res.data.Status}</p> <p class="text-gray-700 dark:text-gray-200"><strong>created_at:</strong> ${res.data.created_at}</p> 
          <div class="flex justify-end mt-4">
            <button type="button" class="modal-close bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">Close</button>
          </div>
        `;
        openModal('View blog_comments', html);
      } else showToast(res.message,'error');
    },'json').fail(()=>showToast('Server error','error'));
});


  // --- Edit blog_comments ---
  $(document).on('click','.editBtn', function(){
    let id=$(this).data('id');
    $.get('<?= $path?>app/crud/forms/update/blog_comments.php', {id}, function(res){
        openModal('Edit blog_comments',res);
    }).fail(()=>showToast('Server error','error'));
  });


  // --- Submit Edit ---
  $(document).on('submit','#editblog_commentsForm', function(e){
    e.preventDefault();
    $.post('<?= $path?>app/crud/api/blog_comments.php', $(this).serialize()+'&action=edit', function(res){
      if(res.success){
        showToast(res.message);
        $('#blog_commentsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
        // $("#blog_commentsTable").load(location.href + " #blog_commentsTable");
        window.location.reload();
      } else showToast(res.message,'error');
    },'json').fail(()=>showToast('Server error','error'));
  });

  // --- Delete blog_comments ---
  $(document).on('click','.deleteBtn', function(){
    let id=$(this).data('id');
    let html=`
      <p>Are you sure you want to delete this blog_comments?</p>
      <div class="flex justify-end gap-3 mt-4">
        <button id="confirmDeleteBtn" data-id="${id}" class="bg-red-500 text-white px-6 py-2 rounded">Delete</button>
        <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Cancel</button>
      </div>`;
    openModal('Confirm Delete',html);
  });

  $(document).on('click','#confirmDeleteBtn', function(){
    let id=$(this).data('id');
    $.post('<?= $path?>app/crud/api/blog_comments.php',{action:'delete',id}, function(res){
      if(res.success){
        showToast(res.message);
        $('#blog_commentsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
        // $("#blog_commentsTable").load(location.href + " #blog_commentsTable");
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
        // Bulk Copy: fetch each blog_comments data
        $.post('<?= $path?>app/crud/api/blog_comments.php',{action:'bulk_view', ids: ids}, function(res){
            if(res.success){
                let html = '<form id="bulkCopyForm">';
                res.data.forEach(blog_comments => {
                    html += `
                    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow mb-4">
                        <h3 class="font-bold text-lg mb-2 text-green-500">Copy blog_comments</h3>
                        <input type="hidden" name="ids[]" value="${blog_comments.id}">
                        <div class="grid grid-cols-2 gap-4">
                        
                            <div>
                                <label for="blog_comment_id">blog_comment_id</label>
                                <input type="text" id="blog_comment_id" name="blog_comment_id[${blog_comments.id}]" value="${blog_comments.blog_comment_id}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="blog">blog</label>
                                <input type="text" id="blog" name="blog[${blog_comments.id}]" value="${blog_comments.blog}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="firstname">firstname</label>
                                <input type="text" id="firstname" name="firstname[${blog_comments.id}]" value="${blog_comments.firstname}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="lastname">lastname</label>
                                <input type="text" id="lastname" name="lastname[${blog_comments.id}]" value="${blog_comments.lastname}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="Email">Email</label>
                                <input type="text" id="Email" name="Email[${blog_comments.id}]" value="${blog_comments.Email}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="message">message</label>
                                <input type="text" id="message" name="message[${blog_comments.id}]" value="${blog_comments.message}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="createdDate">createdDate</label>
                                <input type="text" id="createdDate" name="createdDate[${blog_comments.id}]" value="${blog_comments.createdDate}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="Status">Status</label>
                                <input type="text" id="Status" name="Status[${blog_comments.id}]" value="${blog_comments.Status}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="created_at">created_at</label>
                                <input type="text" id="created_at" name="created_at[${blog_comments.id}]" value="${blog_comments.created_at}" class="w-full p-2 border rounded mb-2">
                            </div> 
                        </div>
                    </div>
                    `;
                });

                html += `<div class="flex justify-end gap-3 mt-4">
                            <button id="bulkCopySaveBtn" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">Save as New</button>
                            <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Cancel</button>
                         </div></form>`;

                openModal('Bulk Copy blog_comments', html);
            } else showToast(res.message,'error');
        },'json').fail(()=>showToast('Server error','error'));
    }



    else if(action === 'delete'){
        let html = `<p>Are you sure you want to delete selected blog_comments?</p>
                    <div class="flex justify-end gap-3 mt-4">
                      <button id="bulkDeleteBtn" class="bg-red-500 text-white px-6 py-2 rounded">Delete</button>
                      <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Cancel</button>
                    </div>`;
        openModal('Confirm Bulk Delete', html);
    }


      else if(action === 'view'){
          $.post('<?= $path?>app/crud/api/blog_comments.php',{action:'bulk_view', ids: ids}, function(res){
              if(res.success){
                  let html = '';
                  res.data.forEach(blog_comments => {
                      html += `<div class="bg-white dark:bg-gray-800 p-4 rounded shadow mb-4">
                                  
                                        <p>blog_comment_id: ${blog_comments.blog_comment_id}</p>
                                    
                                        <p>blog: ${blog_comments.blog}</p>
                                    
                                        <p>firstname: ${blog_comments.firstname}</p>
                                    
                                        <p>lastname: ${blog_comments.lastname}</p>
                                    
                                        <p>Email: ${blog_comments.Email}</p>
                                    
                                        <p>message: ${blog_comments.message}</p>
                                    
                                        <p>createdDate: ${blog_comments.createdDate}</p>
                                    
                                        <p>Status: ${blog_comments.Status}</p>
                                    
                                        <p>created_at: ${blog_comments.created_at}</p>
                                    
                                </div>`;
                  });
                  html += `<div class="flex justify-end mt-4">
                              <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Close</button>
                          </div>`;
                  openModal('View blog_comments', html);
              }
          },'json');
      }

      else if(action === 'export'){
          let idsParam = ids.join(',');
          window.location.href = `<?= $path?>app/crud/api/blog_comments.php?action=bulk_export&ids=${idsParam}`;
      }


    else if(action === 'edit'){
        // Bulk Edit: fetch each blog_comments's data
        $.post('<?= $path?>app/crud/api/blog_comments.php',{action:'bulk_view', ids: ids}, function(res){
            if(res.success){
                // Create forms for each blog_comments
                let html = '';
                res.data.forEach(blog_comments => {
                    html += `
                        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow mb-4">
                            <h3 class="font-bold text-lg mb-2 text-red-500">Edit blog_comments</h3>
                            <input type="hidden" name="ids[]" value="${blog_comments.id}">
                            <div class="grid grid-cols-2 gap-4">

                                
                                        <div>
                                            <label for="lastname" class="block text-gray-700 dark:text-gray-200">lastname</label>
                                            <input type="text" id="lastname" name="lastname[${blog_comments.id}]" value="${blog_comments.lastname}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="Email" class="block text-gray-700 dark:text-gray-200">Email</label>
                                            <input type="text" id="Email" name="Email[${blog_comments.id}]" value="${blog_comments.Email}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="message" class="block text-gray-700 dark:text-gray-200">message</label>
                                            <input type="text" id="message" name="message[${blog_comments.id}]" value="${blog_comments.message}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="createdDate" class="block text-gray-700 dark:text-gray-200">createdDate</label>
                                            <input type="text" id="createdDate" name="createdDate[${blog_comments.id}]" value="${blog_comments.createdDate}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="Status" class="block text-gray-700 dark:text-gray-200">Status</label>
                                            <input type="text" id="Status" name="Status[${blog_comments.id}]" value="${blog_comments.Status}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="created_at" class="block text-gray-700 dark:text-gray-200">created_at</label>
                                            <input type="text" id="created_at" name="created_at[${blog_comments.id}]" value="${blog_comments.created_at}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
        
                            </div>
                        </div>
                    `;
                });

                html += `<div class="flex justify-end gap-3 mt-4">
                            <button id="bulkSaveBtn" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">Save</button>
                            <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Cancel</button>
                         </div>`;

                openModal('Bulk Edit blog_comments', html);
            } else showToast(res.message,'error');
        },'json').fail(()=>showToast('Server error','error'));
    } else {
        alert('Bulk action not implemented yet.');
    }
});



$(document).on('click','#bulkDeleteBtn', function(){
    let ids = $('.rowCheckbox:checked').map(function(){ return $(this).data('id'); }).get();
    $.post('<?= $path?>app/crud/api/blog_comments.php',{action:'bulk_delete',ids:ids}, function(res){
        if(res.success){
            showToast(res.message);
            $('#blog_commentsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
            // $("#blog_commentsTable").load(location.href + " #blog_commentsTable");
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

// Collect all blog_comments fields
const excluded = [
    'blog_comment_id',
    
    'blog',
    
    'firstname',
    
    'lastname',
    
    'Email',
    
    'message',
    
    'createdDate',
    
    'Status',
    
    'created_at',
    ];

$('#modalContent').find('input, select').each(function() {
    if (!excluded.some(key => this.name.includes(key))) {
        formData[this.name] = $(this).val();
    }
});

    $.post('<?= $path?>app/crud/api/blog_comments.php', formData, function(res){
        if(res.success){
            showToast(res.message);
            $('#blog_commentsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
            // $("#blog_commentsTable").load(location.href + " #blog_commentsTable");
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

    // Collect all blog_comments fields
    const excluded = [
    'blog_comment_id',
    
    'blog',
    
    'firstname',
    
    'lastname',
    
    'Email',
    
    'message',
    
    'createdDate',
    
    'Status',
    
    'created_at',
    ];

    $('#modalContent').find('input, select').each(function() {
        if (!excluded.some(key => this.name.includes(key))) {
            formData[this.name] = $(this).val();
        }
    });


    formData['action'] = 'bulk_edit';

    $.post('<?= $path?>app/crud/api/blog_comments.php', formData, function(res){
        if(res.success){
            showToast(res.message);
            $('#blog_commentsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
            // $("#blog_commentsTable").load(location.href + " #blog_commentsTable");
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
    url: '<?= $path?>app/crud/api/blog_comments.php',
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
  let table = new DataTable('#blog_commentsTable', {
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
      $('#blog_commentsTable').addClass('stripe hover text-gray-200 bg-gray-900');
    } else {
      $('#blog_commentsTable').removeClass('text-gray-200 bg-gray-900');
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
