
<div class="mx-6 min-h-screen flex">
  <!-- Sidebar -->
  <aside class="w-64 bg-white dark:bg-slate-800 rounded-3xl shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4">Help Topics</h2>
    <ul id="topic-list" class="space-y-2 mb-4"></ul>
    <button
  id="addTopicBtn"
  data-modal-target="topic-modal"
  data-modal-toggle="topic-modal"
  class="w-full px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
  Add Topic
</button>
<div
  id="topic-modal"
  tabindex="-1"
  aria-hidden="true"
  class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
>
  <div class="relative p-4 w-full max-w-2xl max-h-full">
    <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-md">
      <!-- header -->
      <div class="flex items-center justify-between p-4 md:p-5 border-b dark:border-gray-600 border-gray-200">
        <h2 id="modalTitle" class="text-xl font-bold mb-4">Add Topic</h2>
        <button
          type="button"
          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          data-modal-hide="topic-modal"
        >
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <!-- body (your form) -->
      <div class="p-4 md:p-5 space-y-4">
        <input type="hidden" id="topicId">
        <input type="text" id="topicTitle" placeholder="Title" class="w-full border dark:bg-gray-800 dark:border-gray-700 p-2 mb-2 rounded">
        <textarea id="topicContent" placeholder="Content" class="w-full border dark:bg-gray-800 dark:border-gray-700 p-2 mb-2 rounded h-40"></textarea>
      </div>
      <!-- footer -->
      <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
        <button id="saveTopic" class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-500 dark:hover:bg-red-600">
          Save
        </button>
        <button data-modal-hide="topic-modal" type="button" class="ms-3 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
          Cancel
        </button>
      </div>
    </div>
  </div>
</div>

  </aside>

  <!-- Content -->
  <main class="flex-1 p-8">
    <div id="content-area" class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-md">
      <h1 class="text-3xl font-bold mb-4">Welcome to the Help Page</h1>
      <p>Select a topic from the sidebar to see detailed instructions.</p>
    </div>
  </main>

  <!-- Modal -->
<div id="topicModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 mx-auto mt-20 p-6 rounded-lg w-1/2 relative">
      <h2 class="text-xl font-bold mb-4" id="modalTitle">Add Topic</h2>
      <input type="hidden" id="topicId">
      <input type="text" id="topicTitle" placeholder="Title" class="w-full border dark:bg-gray-800 dark:border-gray-700 p-2 mb-2 rounded">
      <textarea id="topicContent" placeholder="Content" class="w-full border dark:bg-gray-800 dark:border-gray-700 p-2 mb-2 rounded h-40"></textarea>
      <div class="flex justify-end gap-2">
        <button id="saveTopic" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Save</button>
        <button id="closeModal" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
      </div>
    </div>
  </div>
<script>
function loadTopics() {
  $.get('@cog/help_data', {action:'list'}, function(data) {
    const list = $('#topic-list').empty();
    data.forEach(t => {
      list.append(`
        <li class="flex justify-between items-center">
          <button class="text-left text-blue-600 hover:underline flex-1 topic-btn" data-id="${t.id}" data-title="${t.title}" data-content="${t.content}" data-updated="${t.updated_at}">${t.title}</button>
          <div class="flex gap-1">
            <button class="edit-btn px-2 py-1 bg-yellow-400 text-white rounded" data-id="${t.id}" data-title="${t.title}" data-content="${t.content}">Edit</button>
            <button class="delete-btn px-2 py-1 bg-red-500 text-white rounded" data-id="${t.id}">Delete</button>
          </div>
        </li>
      `);
    });

    // Topic click
    $('.topic-btn').on('click', function() {
      const title = $(this).data('title');
      const content = $(this).data('content');
      const updated = $(this).data('updated');
      $('#content-area').html(`
        <h2 class="text-2xl font-bold mb-3">${title}</h2>
        <p>${content}</p>
        <p class="text-sm text-gray-500 mt-2">Last updated: ${updated}</p>
      `);
    });

    // Edit click
$('.edit-btn').on('click', function() {
  $('#modalTitle').text('Edit Topic');
  $('#topicId').val($(this).data('id'));
  $('#topicTitle').val($(this).data('title'));
  $('#topicContent').val($(this).data('content'));
  // trigger the modal toggle, e.g.:
  const btn = $('#btnn');
  btn.click();
});




    // Delete click
    $('.delete-btn').on('click', function() {
      if(confirm('Are you sure you want to delete this topic?')) {
        $.post('@cog/help_data?action=delete', {action:'delete', id: $(this).data('id')}, function() {
          loadTopics();
          showMessage('Data deleted', 'success');
        });
      }
    });
  });
}

$('#addTopicBtn').on('click', function() {
  $('#topicModal').fadeIn(); // now works
  $('#modalTitle').text('Add Topic');
  $('#topicId').val('');
  $('#topicTitle').val('');
  $('#topicContent').val('');
});

$('#closeModal').on('click', function() {
  const btn = $('#btnn');
  btn.click();
});









$('#saveTopic').on('click', function() {
  const id = $('#topicId').val();
  const title = $('#topicTitle').val();
  const content = $('#topicContent').val();
  const action = id ? 'update' : 'add';
  $.post('@cog/help_data?action='+action, {action, id, title, content}, function(response) {
    if (response.success) {
      showMessage(response.message, 'success');
      const btn = $('#btnn');
      btn.click();
    }else{
      showMessage(response.message, 'error');
    }
    $('#topicModal').fadeOut();

    loadTopics();
  }).fail(function(xhr){
      showMessage('server error', 'error');
    // alert(JSON.stringify(xhr));
  });
});

// Initial load
loadTopics();
</script>
</div>
<button id="btnn" data-modal-target="topic-modal" data-modal-toggle="topic-modal" style="display:none"></button>