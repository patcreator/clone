
                <?php
  $stmt = $pdo->query("SELECT * FROM website_visitors");
  $website_visitors = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

foreach ($website_visitors as $item) {
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
$columns = ['id','ip_address','user_agent','referrer_url','landing_page','visit_time','session_id','country','browser','device_type','created_at','updated_at','status'];
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

    <h1 class="text-2xl font-bold mb-6 px-8">website_visitors</h1>

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

    <!-- Add website_visitors Button -->
    <a href="#" id="addwebsite_visitorsBtn" class="mb-4 ml-8 px-4 py-2 bg-red-500 text-white rounded inline-block">
      Add website_visitors
    </a>

    <!-- Reusable Modal -->
    <div id="website_visitorsModal" class="fixed inset-0 w-full h-full bg-white dark:bg-gray-900 dark:text-white z-50 overflow-y-auto hidden">
      <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
        <button class="modal-close flex items-center text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white">
          <i class="fa fa-arrow-left mr-2"></i> Back
        </button>
        <h2 class="text-xl font-bold" id="modalTitle">website_visitors</h2>
        <span></span>
      </div>
      <div class="p-6 max-w-2xl mx-auto" id="modalContent"></div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto px-8 pt-2">
      <div id="tab-tables">
        <table id="website_visitorsTable" class="display min-w-full bg-white rounded shadow w-full text-sm text-left text-gray-700 dark:text-gray-200">
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
            <?php $i=1; foreach($website_visitors as $item): ?>
            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 hover:dark:bg-gray-700">
                <td class="border-0 px-4 py-4 dark:bg-gray-800"><input type="checkbox" class="rowCheckbox" data-id="<?= $item['id'] ?>"></td>

                <td class="border-0 px-4 py-4 dark:bg-gray-800"><?= $i++ ?></td>

                 
                    <td class="editable" data-id="<?= $item['id'] ?>" data-field="id" data-values=''><?= htmlspecialchars($item['id']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['id'] ?>" data-field="ip_address" data-values=''><?= htmlspecialchars($item['ip_address']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['id'] ?>" data-field="user_agent" data-values=''><?= htmlspecialchars($item['user_agent']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['id'] ?>" data-field="referrer_url" data-values=''><?= htmlspecialchars($item['referrer_url']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['id'] ?>" data-field="landing_page" data-values=''><?= htmlspecialchars($item['landing_page']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['id'] ?>" data-field="visit_time" data-values=''><?= htmlspecialchars($item['visit_time']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['id'] ?>" data-field="session_id" data-values=''><?= htmlspecialchars($item['session_id']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['id'] ?>" data-field="country" data-values=''><?= htmlspecialchars($item['country']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['id'] ?>" data-field="browser" data-values=''><?= htmlspecialchars($item['browser']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['id'] ?>" data-field="device_type" data-values=''><?= htmlspecialchars($item['device_type']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['id'] ?>" data-field="created_at" data-values=''><?= htmlspecialchars($item['created_at']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['id'] ?>" data-field="updated_at" data-values=''><?= htmlspecialchars($item['updated_at']) ?></td>
                    
                    <td class="editable" data-id="<?= $item['id'] ?>" data-field="status" data-values=''><?= htmlspecialchars($item['status']) ?></td>
                    
                <td class="border-0 px-4 py-4 dark:bg-gray-800">
                  <button class="viewBtn p-2 hover:bg-gray-100 rounded-full" data-id="<?= $item['id'] ?>" title="view"><i class="mdi-24px mdi mdi-eye"></i></button>
                  <button class="editBtn p-2 hover:bg-gray-100 rounded-full" data-id="<?= $item['id'] ?>" title="edit"><i class="mdi-24px mdi mdi-pen"></i></button>
                  <button class="deleteBtn p-2 hover:bg-gray-100 rounded-full" data-id="<?= $item['id'] ?>" title="delete"><i class="mdi-24px mdi mdi-delete"></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <!-- Charts Tab -->
<div id="tab-charts" class="hidden px-8 pt-4">
  <canvas id="website_visitorsChart" height="120"></canvas>
</div>

<!-- Grid Tab -->
<div id="tab-grid" class="hidden px-8 pt-4">
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <?php foreach($website_visitors as $key => $item): ?>
      <div
        class="group bg-white dark:bg-gray-800 rounded-lg shadow-md border border-transparent hover:border-red-500 focus-within:border-red-500 active:border-red-700 transition duration-300 overflow-hidden"
        x-data="{ open: false }"
      >
        <img
          src="<?= htmlspecialchars($item['image'] ?? 'https://placehold.co/600x400') ?>"
          alt="website_visitors Image"
          class="w-full h-40 object-cover"
        />
        <div class="p-4">
          <h3 class="text-lg font-bold text-red-600"><?= htmlspecialchars($item['id']) ?></h3>
          <p class="text-gray-600 dark:text-gray-300"><?= htmlspecialchars($item['ip_address']??'') ?></p>
          <p class="text-sm text-gray-500 dark:text-gray-400">Status: <?= htmlspecialchars($item['user_agent']??'') ?></p>

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

              <p><?= htmlspecialchars($item['referrer_url'] ?? '') ?></p> <p><?= htmlspecialchars($item['landing_page'] ?? '') ?></p> <p><?= htmlspecialchars($item['visit_time'] ?? '') ?></p> <p><?= htmlspecialchars($item['session_id'] ?? '') ?></p> <p><?= htmlspecialchars($item['country'] ?? '') ?></p> <p><?= htmlspecialchars($item['browser'] ?? '') ?></p> <p><?= htmlspecialchars($item['device_type'] ?? '') ?></p> <p><?= htmlspecialchars($item['created_at'] ?? '') ?></p> <p><?= htmlspecialchars($item['updated_at'] ?? '') ?></p> <p><?= htmlspecialchars($item['status'] ?? '') ?></p> 
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
    <?php foreach($website_visitors as $item): ?>
       
<li class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:border-red-500 transition">
        <div class="flex items-center space-x-4">
          <!-- Optional website_visitors Image -->
          <img
            src="<?= htmlspecialchars($item['image'] ?? 'https://placehold.co/600x400') ?>"
            alt="Image"
            class="w-10 h-10 rounded-full object-cover"
          />

          <!-- website_visitors Info -->
          <div>
            <p class="font-semibold text-red-600 dark:text-red-400"><?= htmlspecialchars($item['id']) ?></p>
            <p class="font-semibold text-red-600 dark:text-red-400"><?= htmlspecialchars($item['ip_address']) ?></p>
            <p class="font-semibold text-red-600 dark:text-red-400"><?= htmlspecialchars($item['user_agent']) ?></p>







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
                <p><?= htmlspecialchars($item['referrer_url'] ?? '') ?></p> <p><?= htmlspecialchars($item['landing_page'] ?? '') ?></p> <p><?= htmlspecialchars($item['visit_time'] ?? '') ?></p> <p><?= htmlspecialchars($item['session_id'] ?? '') ?></p> <p><?= htmlspecialchars($item['country'] ?? '') ?></p> <p><?= htmlspecialchars($item['browser'] ?? '') ?></p> <p><?= htmlspecialchars($item['device_type'] ?? '') ?></p> <p><?= htmlspecialchars($item['created_at'] ?? '') ?></p> <p><?= htmlspecialchars($item['updated_at'] ?? '') ?></p> <p><?= htmlspecialchars($item['status'] ?? '') ?></p> 
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
    <table id="website_visitorsTable" class="min-w-full bg-white rounded shadow text-sm">
      <thead>
        <tr>
          <th class="bg-gray-100 px-4 py-2 text-left dark:bg-gray-800">#</th>
         <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">id</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">ip_address</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">user_agent</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">referrer_url</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">landing_page</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">visit_time</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">session_id</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">country</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">browser</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">device_type</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">created_at</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">updated_at</th> <th class="border-0 bg-gray-100 px-4 py-4 dark:bg-gray-800 text-left bg-gray-100 dark:bg-gray-800 font-semibold text-gray-700 dark:text-gray-300 px-4 py-3">status</th> 
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; foreach($website_visitors as $item): ?>
        <tr class="border-t">
          <td class="px-4 py-2 dark:bg-gray-800"><?= $i++ ?></td>
            <td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['referrer_url']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['landing_page']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['visit_time']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['session_id']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['country']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['browser']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['device_type']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['created_at']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['updated_at']) ?></td><td class="px-4 py-2 dark:bg-gray-800"><?= htmlspecialchars($item['status']) ?></td>
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
  <a href="<?= $path?>app/crud/api/website_visitors.php?action=export" class="bg-green-500 text-white px-4 py-2 rounded">Export All website_visitors</a>
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
const ctx = document.getElementById('website_visitorsChart');
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
        label: 'website_visitors Created',
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
          title: { display: true, text: 'website_visitors Count' }
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

      $.post('<?= $path?>app/crud/api/website_visitors.php', {
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
    $('#website_visitorsModal').hide().removeClass('hidden').fadeIn(200);
  }

  $(document).on('click','.modal-close', function(){
    $('#website_visitorsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
  });

  // --- Add website_visitors ---
  $('#addwebsite_visitorsBtn').click(function(e){
    e.preventDefault();
      
    $.post('<?= $path?>app/crud/forms/create/website_visitors.php', function(res){
      if(res){
        openModal('Add website_visitors',res);
      } else showToast(res.message,'error');
    }).fail(()=>showToast('Server error:Register Data failed ','error'));

  });

  // --- Submit Add ---
  $(document).on('submit','#addwebsite_visitorsForm', function(e){
    e.preventDefault();
    $.post('<?= $path?>app/crud/api/website_visitors.php', $(this).serialize()+'&action=insert', function(res){
      if(res.success){
        showToast(res.message);
        $('#website_visitorsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
        // $("#website_visitorsTable").load(location.href + " #website_visitorsTable");
        window.location.reload();
      } else { showToast(res.message,'error'); }
    },'json').fail(()=>showToast('Server error','error'));
  });

  // --- Save & Continue ---
  $(document).on('click','#saveContinueBtn', function(){
    $.post('<?= $path?>app/crud/api/website_visitors.php', $('#addwebsite_visitorsForm').serialize()+'&action=insert', function(res){
      if(res.success){
        showToast(res.message);
        // $("#website_visitorsTable").load(location.href + " #website_visitorsTable");
        window.location.reload();
        $('#addwebsite_visitorsForm')[0].reset();
      } else {showToast(res.message,'error');}
    },'json').fail(()=>showToast('Server error','error'));
  });

// --- View website_visitors ---
$(document).on('click','.viewBtn', function(){
    let id=$(this).data('id');
    $.post('<?= $path?>app/crud/api/website_visitors.php',{action:'view',id}, function(res){
      if(res.success){
        let html = `
          <div class="bg-red-500 text-white p-4 rounded mb-4 text-lg font-semibold">
            website_visitors
          </div>
          <div class="grid grid-cols-2 gap-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
            <p class="text-gray-700 dark:text-gray-200"><strong>id:</strong> ${res.data.id}</p> <p class="text-gray-700 dark:text-gray-200"><strong>ip_address:</strong> ${res.data.ip_address}</p> <p class="text-gray-700 dark:text-gray-200"><strong>user_agent:</strong> ${res.data.user_agent}</p> <p class="text-gray-700 dark:text-gray-200"><strong>referrer_url:</strong> ${res.data.referrer_url}</p> <p class="text-gray-700 dark:text-gray-200"><strong>landing_page:</strong> ${res.data.landing_page}</p> <p class="text-gray-700 dark:text-gray-200"><strong>visit_time:</strong> ${res.data.visit_time}</p> <p class="text-gray-700 dark:text-gray-200"><strong>session_id:</strong> ${res.data.session_id}</p> <p class="text-gray-700 dark:text-gray-200"><strong>country:</strong> ${res.data.country}</p> <p class="text-gray-700 dark:text-gray-200"><strong>browser:</strong> ${res.data.browser}</p> <p class="text-gray-700 dark:text-gray-200"><strong>device_type:</strong> ${res.data.device_type}</p> <p class="text-gray-700 dark:text-gray-200"><strong>created_at:</strong> ${res.data.created_at}</p> <p class="text-gray-700 dark:text-gray-200"><strong>updated_at:</strong> ${res.data.updated_at}</p> <p class="text-gray-700 dark:text-gray-200"><strong>status:</strong> ${res.data.status}</p> 
          <div class="flex justify-end mt-4">
            <button type="button" class="modal-close bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">Close</button>
          </div>
        `;
        openModal('View website_visitors', html);
      } else showToast(res.message,'error');
    },'json').fail(()=>showToast('Server error','error'));
});


  // --- Edit website_visitors ---
  $(document).on('click','.editBtn', function(){
    let id=$(this).data('id');
    $.get('<?= $path?>app/crud/forms/update/website_visitors.php', {id}, function(res){
        openModal('Edit website_visitors',res);
    }).fail(()=>showToast('Server error','error'));
  });


  // --- Submit Edit ---
  $(document).on('submit','#editwebsite_visitorsForm', function(e){
    e.preventDefault();
    $.post('<?= $path?>app/crud/api/website_visitors.php', $(this).serialize()+'&action=edit', function(res){
      if(res.success){
        showToast(res.message);
        $('#website_visitorsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
        // $("#website_visitorsTable").load(location.href + " #website_visitorsTable");
        window.location.reload();
      } else showToast(res.message,'error');
    },'json').fail(()=>showToast('Server error','error'));
  });

  // --- Delete website_visitors ---
  $(document).on('click','.deleteBtn', function(){
    let id=$(this).data('id');
    let html=`
      <p>Are you sure you want to delete this website_visitors?</p>
      <div class="flex justify-end gap-3 mt-4">
        <button id="confirmDeleteBtn" data-id="${id}" class="bg-red-500 text-white px-6 py-2 rounded">Delete</button>
        <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Cancel</button>
      </div>`;
    openModal('Confirm Delete',html);
  });

  $(document).on('click','#confirmDeleteBtn', function(){
    let id=$(this).data('id');
    $.post('<?= $path?>app/crud/api/website_visitors.php',{action:'delete',id}, function(res){
      if(res.success){
        showToast(res.message);
        $('#website_visitorsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
        // $("#website_visitorsTable").load(location.href + " #website_visitorsTable");
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
        // Bulk Copy: fetch each website_visitors data
        $.post('<?= $path?>app/crud/api/website_visitors.php',{action:'bulk_view', ids: ids}, function(res){
            if(res.success){
                let html = '<form id="bulkCopyForm">';
                res.data.forEach(website_visitors => {
                    html += `
                    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow mb-4">
                        <h3 class="font-bold text-lg mb-2 text-green-500">Copy website_visitors</h3>
                        <input type="hidden" name="ids[]" value="${website_visitors.id}">
                        <div class="grid grid-cols-2 gap-4">
                        
                            <div>
                                <label for="id">id</label>
                                <input type="text" id="id" name="id[${website_visitors.id}]" value="${website_visitors.id}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="ip_address">ip_address</label>
                                <input type="text" id="ip_address" name="ip_address[${website_visitors.id}]" value="${website_visitors.ip_address}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="user_agent">user_agent</label>
                                <input type="text" id="user_agent" name="user_agent[${website_visitors.id}]" value="${website_visitors.user_agent}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="referrer_url">referrer_url</label>
                                <input type="text" id="referrer_url" name="referrer_url[${website_visitors.id}]" value="${website_visitors.referrer_url}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="landing_page">landing_page</label>
                                <input type="text" id="landing_page" name="landing_page[${website_visitors.id}]" value="${website_visitors.landing_page}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="visit_time">visit_time</label>
                                <input type="text" id="visit_time" name="visit_time[${website_visitors.id}]" value="${website_visitors.visit_time}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="session_id">session_id</label>
                                <input type="text" id="session_id" name="session_id[${website_visitors.id}]" value="${website_visitors.session_id}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="country">country</label>
                                <input type="text" id="country" name="country[${website_visitors.id}]" value="${website_visitors.country}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="browser">browser</label>
                                <input type="text" id="browser" name="browser[${website_visitors.id}]" value="${website_visitors.browser}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="device_type">device_type</label>
                                <input type="text" id="device_type" name="device_type[${website_visitors.id}]" value="${website_visitors.device_type}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="created_at">created_at</label>
                                <input type="text" id="created_at" name="created_at[${website_visitors.id}]" value="${website_visitors.created_at}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="updated_at">updated_at</label>
                                <input type="text" id="updated_at" name="updated_at[${website_visitors.id}]" value="${website_visitors.updated_at}" class="w-full p-2 border rounded mb-2">
                            </div> 
                            <div>
                                <label for="status">status</label>
                                <input type="text" id="status" name="status[${website_visitors.id}]" value="${website_visitors.status}" class="w-full p-2 border rounded mb-2">
                            </div> 
                        </div>
                    </div>
                    `;
                });

                html += `<div class="flex justify-end gap-3 mt-4">
                            <button id="bulkCopySaveBtn" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">Save as New</button>
                            <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Cancel</button>
                         </div></form>`;

                openModal('Bulk Copy website_visitors', html);
            } else showToast(res.message,'error');
        },'json').fail(()=>showToast('Server error','error'));
    }



    else if(action === 'delete'){
        let html = `<p>Are you sure you want to delete selected website_visitors?</p>
                    <div class="flex justify-end gap-3 mt-4">
                      <button id="bulkDeleteBtn" class="bg-red-500 text-white px-6 py-2 rounded">Delete</button>
                      <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Cancel</button>
                    </div>`;
        openModal('Confirm Bulk Delete', html);
    }


      else if(action === 'view'){
          $.post('<?= $path?>app/crud/api/website_visitors.php',{action:'bulk_view', ids: ids}, function(res){
              if(res.success){
                  let html = '';
                  res.data.forEach(website_visitors => {
                      html += `<div class="bg-white dark:bg-gray-800 p-4 rounded shadow mb-4">
                                  
                                        <p>id: ${website_visitors.id}</p>
                                    
                                        <p>ip_address: ${website_visitors.ip_address}</p>
                                    
                                        <p>user_agent: ${website_visitors.user_agent}</p>
                                    
                                        <p>referrer_url: ${website_visitors.referrer_url}</p>
                                    
                                        <p>landing_page: ${website_visitors.landing_page}</p>
                                    
                                        <p>visit_time: ${website_visitors.visit_time}</p>
                                    
                                        <p>session_id: ${website_visitors.session_id}</p>
                                    
                                        <p>country: ${website_visitors.country}</p>
                                    
                                        <p>browser: ${website_visitors.browser}</p>
                                    
                                        <p>device_type: ${website_visitors.device_type}</p>
                                    
                                        <p>created_at: ${website_visitors.created_at}</p>
                                    
                                        <p>updated_at: ${website_visitors.updated_at}</p>
                                    
                                        <p>status: ${website_visitors.status}</p>
                                    
                                </div>`;
                  });
                  html += `<div class="flex justify-end mt-4">
                              <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Close</button>
                          </div>`;
                  openModal('View website_visitors', html);
              }
          },'json');
      }

      else if(action === 'export'){
          let idsParam = ids.join(',');
          window.location.href = `<?= $path?>app/crud/api/website_visitors.php?action=bulk_export&ids=${idsParam}`;
      }


    else if(action === 'edit'){
        // Bulk Edit: fetch each website_visitors's data
        $.post('<?= $path?>app/crud/api/website_visitors.php',{action:'bulk_view', ids: ids}, function(res){
            if(res.success){
                // Create forms for each website_visitors
                let html = '';
                res.data.forEach(website_visitors => {
                    html += `
                        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow mb-4">
                            <h3 class="font-bold text-lg mb-2 text-red-500">Edit website_visitors</h3>
                            <input type="hidden" name="ids[]" value="${website_visitors.id}">
                            <div class="grid grid-cols-2 gap-4">

                                
                                        <div>
                                            <label for="referrer_url" class="block text-gray-700 dark:text-gray-200">referrer_url</label>
                                            <input type="text" id="referrer_url" name="referrer_url[${website_visitors.id}]" value="${website_visitors.referrer_url}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="landing_page" class="block text-gray-700 dark:text-gray-200">landing_page</label>
                                            <input type="text" id="landing_page" name="landing_page[${website_visitors.id}]" value="${website_visitors.landing_page}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="visit_time" class="block text-gray-700 dark:text-gray-200">visit_time</label>
                                            <input type="text" id="visit_time" name="visit_time[${website_visitors.id}]" value="${website_visitors.visit_time}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="session_id" class="block text-gray-700 dark:text-gray-200">session_id</label>
                                            <input type="text" id="session_id" name="session_id[${website_visitors.id}]" value="${website_visitors.session_id}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="country" class="block text-gray-700 dark:text-gray-200">country</label>
                                            <input type="text" id="country" name="country[${website_visitors.id}]" value="${website_visitors.country}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="browser" class="block text-gray-700 dark:text-gray-200">browser</label>
                                            <input type="text" id="browser" name="browser[${website_visitors.id}]" value="${website_visitors.browser}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="device_type" class="block text-gray-700 dark:text-gray-200">device_type</label>
                                            <input type="text" id="device_type" name="device_type[${website_visitors.id}]" value="${website_visitors.device_type}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="created_at" class="block text-gray-700 dark:text-gray-200">created_at</label>
                                            <input type="text" id="created_at" name="created_at[${website_visitors.id}]" value="${website_visitors.created_at}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="updated_at" class="block text-gray-700 dark:text-gray-200">updated_at</label>
                                            <input type="text" id="updated_at" name="updated_at[${website_visitors.id}]" value="${website_visitors.updated_at}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
                                        <div>
                                            <label for="status" class="block text-gray-700 dark:text-gray-200">status</label>
                                            <input type="text" id="status" name="status[${website_visitors.id}]" value="${website_visitors.status}" class="w-full p-2 border rounded mb-2" required>
                                        </div> 
                                    
        
                            </div>
                        </div>
                    `;
                });

                html += `<div class="flex justify-end gap-3 mt-4">
                            <button id="bulkSaveBtn" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">Save</button>
                            <button type="button" class="modal-close bg-gray-500 text-white px-6 py-2 rounded">Cancel</button>
                         </div>`;

                openModal('Bulk Edit website_visitors', html);
            } else showToast(res.message,'error');
        },'json').fail(()=>showToast('Server error','error'));
    } else {
        alert('Bulk action not implemented yet.');
    }
});



$(document).on('click','#bulkDeleteBtn', function(){
    let ids = $('.rowCheckbox:checked').map(function(){ return $(this).data('id'); }).get();
    $.post('<?= $path?>app/crud/api/website_visitors.php',{action:'bulk_delete',ids:ids}, function(res){
        if(res.success){
            showToast(res.message);
            $('#website_visitorsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
            // $("#website_visitorsTable").load(location.href + " #website_visitorsTable");
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

// Collect all website_visitors fields
const excluded = [
    'id',
    
    'ip_address',
    
    'user_agent',
    
    'referrer_url',
    
    'landing_page',
    
    'visit_time',
    
    'session_id',
    
    'country',
    
    'browser',
    
    'device_type',
    
    'created_at',
    
    'updated_at',
    
    'status',
    ];

$('#modalContent').find('input, select').each(function() {
    if (!excluded.some(key => this.name.includes(key))) {
        formData[this.name] = $(this).val();
    }
});

    $.post('<?= $path?>app/crud/api/website_visitors.php', formData, function(res){
        if(res.success){
            showToast(res.message);
            $('#website_visitorsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
            // $("#website_visitorsTable").load(location.href + " #website_visitorsTable");
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

    // Collect all website_visitors fields
    const excluded = [
    'id',
    
    'ip_address',
    
    'user_agent',
    
    'referrer_url',
    
    'landing_page',
    
    'visit_time',
    
    'session_id',
    
    'country',
    
    'browser',
    
    'device_type',
    
    'created_at',
    
    'updated_at',
    
    'status',
    ];

    $('#modalContent').find('input, select').each(function() {
        if (!excluded.some(key => this.name.includes(key))) {
            formData[this.name] = $(this).val();
        }
    });


    formData['action'] = 'bulk_edit';

    $.post('<?= $path?>app/crud/api/website_visitors.php', formData, function(res){
        if(res.success){
            showToast(res.message);
            $('#website_visitorsModal').fadeOut(200,function(){ $(this).addClass('hidden'); });
            // $("#website_visitorsTable").load(location.href + " #website_visitorsTable");
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
    url: '<?= $path?>app/crud/api/website_visitors.php',
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
  let table = new DataTable('#website_visitorsTable', {
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
      $('#website_visitorsTable').addClass('stripe hover text-gray-200 bg-gray-900');
    } else {
      $('#website_visitorsTable').removeClass('text-gray-200 bg-gray-900');
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
