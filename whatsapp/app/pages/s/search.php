<?php
$structureFile = 'app/system/api/structure.json';
$tables = [];

if (file_exists($structureFile)) {
    $json = json_decode(file_get_contents($structureFile), true);
    $tables = isset($json['tables']) ? $json['tables'] : array_keys($json);
}
?>
<title>Search content</title>
<div class="bg-white mx-9 rounded-3xl dark:bg-gray-800 text-gray-800 dark:text-gray-100 p-6 mt-9 shadow-lg">

<div class="max-w-5xl mx-auto ">
  <h1 class="text-2xl font-bold mb-6">Dynamic Table Search</h1>

  <!-- Search Form -->
  <div class="flex flex-wrap gap-3 mb-6">
    <select id="ds_table" class="px-4 py-2 rounded border bg-white dark:bg-gray-800 dark:border-gray-700">
      <option value="">-- Select Table --</option>
      <?php foreach ($tables as $t): ?>
        <option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars(ucfirst($t)) ?></option>
      <?php endforeach; ?>
    </select>

    <select id="ds_column" class="px-4 py-2 rounded border bg-white dark:bg-gray-800 dark:border-gray-700">
      <option value="">-- Select Column --</option>
    </select>

    <input type="text" id="ds_search" placeholder="Type or use voice..." 
           class="flex-grow px-4 py-2 border rounded dark:bg-gray-800 dark:border-gray-700">

    <button id="ds_search_btn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Search</button>
    <button id="vs_start" class="bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200 rounded-full"><i class="mdi mdi-microphone"></i></button>
  </div>

  <!-- Suggestions -->
  <ul id="ds_suggestions" class="mb-4 border rounded bg-white dark:bg-gray-800 max-h-60 overflow-y-auto hidden"></ul>

  <!-- Main Content Area -->
  <div id="ds_main_content" class="bg-white dark:bg-gray-800 rounded p-4 shadow max-h-[300px] overflow-y-auto overflow-x-auto"></div>
</div>

<script>
// --- Update columns when table changes ---
$('#ds_table').on('change', function() {
  const table = $(this).val();
  $('#ds_column').empty().append('<option value="">-- Select Column --</option>');

  if (!table) return;

  // Load structure.json for selected table columns
  $.get('<?= $path.$structureFile ?>', function(data) {
    const columns = data[table]?.columns || [];
    columns.forEach(col => {
      $('#ds_column').append(`<option value="${col.name}">${col.name}</option>`);
    });
  });
});

// --- Live search suggestions ---
let suggestionTimeout = null;
$('#ds_search').on('input', function() {
  clearTimeout(suggestionTimeout);
  const table = $('#ds_table').val();
  const column = $('#ds_column').val();
  const query = $(this).val();
  if (!table || !column || !query) {
    $('#ds_suggestions').hide();
    return;
  }

  suggestionTimeout = setTimeout(() => {
    $.get('<?= $path ?>@cog/search_in_table', { ajax: 1, table: table, column: column, q: query }, function(data) {
      const ul = $('#ds_suggestions');
      ul.empty();
      if (data.length === 0) {
        ul.hide();
        return;
      }
      data.forEach(row => {
        const display = row[column] ?? '';
        const li = $('<li></li>').text(display)
                     .addClass('px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer')
                     .on('click', function() {
                        searchData();
                     });
        ul.append(li);
      });
      ul.removeClass('hidden').show();
    });
  }, 300);
});

// --- Search button click ---
$('#ds_search_btn').on('click', function() {
  searchData();
});
function searchData(){
    const table = $('#ds_table').val();
  const column = $('#ds_column').val();
  const query = $('#ds_search').val();
  if (!table || !column || !query) return;

  $.get('<?= $path ?>@cog/search_in_table', 
    { ajax: 1, table: table, column: column, q: query }, 
    function(data) {
      $('#ds_suggestions').hide();
      const content = $('#ds_main_content');
      content.empty();

      if (!data || data.length === 0) {
        content.text('No results found.');
        return;
      }

      // Create table element
      const tableElem = $('<table class="min-w-full border border-gray-300 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100"></table>');
      const thead = $('<thead class="bg-gray-100 dark:bg-gray-800"></thead>');
      const tbody = $('<tbody></tbody>');

      // Get column names from first result
      const columns = Object.keys(data[0]);

      // Build table header
      let headerRow = $('<tr></tr>');
      columns.forEach(col => {
        headerRow.append(`<th class="border px-2 py-1 text-left font-semibold">${col}</th>`);
      });
      // Add 'Action' column
      headerRow.append('<th class="border px-2 py-1 text-left font-semibold">Action</th>');
      thead.append(headerRow);
      tableElem.append(thead);

      // Build table body rows
      data.forEach(row => {
        let tr = $('<tr class="even:bg-gray-50 dark:even:bg-gray-800"></tr>');
        columns.forEach(col => {
          tr.append(`<td class="border px-2 py-1">${row[col] ?? ''}</td>`);
        });

        // Action link column
        const actionLink = `<a href="<?= $path ?>s/content?e=${encodeURIComponent(table)}&col=${encodeURIComponent(column)}" class="text-red-600 hover:underline">Open link</a>`;
        tr.append(`<td class="border px-2 py-1">${actionLink}</td>`);

        tbody.append(tr);
      });

      tableElem.append(tbody);
      content.append(tableElem);
    }
  );
}






// --- Enhanced Voice Search ---
let recognition;
if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
  const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
  recognition = new SpeechRecognition();
  recognition.lang = 'en-US';
  recognition.interimResults = false;  // Only final results
  recognition.maxAlternatives = 1;
  recognition.continuous = true;       // Keep recognition alive

  $('#vs_start').on('click', function() {
    if (!recognition) return alert('Voice recognition not supported');
    recognition.start();
  });

  // --- Mapping symbols ---
  const symbolMap = {
    'tilde': '~', 'exclamation': '!', 'at': '@', 'hash': '#', 'dollar': '$',
    'percent': '%', 'caret': '^', 'and': '&', 'star': '*', 'paren open': '(',
    'paren close': ')', 'underscore': '_', 'plus': '+', 'backtick': '`',
    'dash': '-', 'equal': '=', 'brace open': '{', 'brace close': '}',
    'bracket open': '[', 'bracket close': ']', 'colon': ':', 'quote': '"',
    'pipe': '|', 'semicolon': ';', 'apostrophe': "'", 'less': '<', 'greater': '>',
    'comma': ',', 'dot': '.', 'slash': '/', 'backslash': '\'
  };

  // --- Mapping basic numbers ---
  const numbersMap = {
    'zero': 0, 'one': 1, 'two': 2, 'three': 3, 'four': 4, 'five': 5,
    'six': 6, 'seven': 7, 'eight': 8, 'nine': 9, 'ten': 10,
    'eleven': 11, 'twelve': 12, 'thirteen': 13, 'fourteen': 14,
    'fifteen': 15, 'sixteen': 16, 'seventeen': 17, 'eighteen': 18,
    'nineteen': 19, 'twenty': 20, 'thirty': 30, 'forty': 40, 'fifty': 50,
    'sixty': 60, 'seventy': 70, 'eighty': 80, 'ninety': 90, 'hundred': 100,
    'thousand': 1000, 'million': 1000000, 'billion': 1000000000
  };

  // --- Function to convert spoken numbers to digits ---
  function parseSpokenNumber(words) {
    let total = 0, current = 0;
    words.forEach(word => {
      const num = numbersMap[word];
      if (num >= 1000) { // thousand, million, billion
        current *= num;
        total += current;
        current = 0;
      } else if (num === 100) {
        current *= 100;
      } else if (num !== undefined) {
        current += num;
      } else {
        // If not a number, push previous total + current as string and reset
        if (current) {
          total += current;
          current = 0;
        }
      }
    });
    total += current;
    return total || null;
  }

  recognition.onresult = function(event) {
    let transcript = event.results[event.results.length - 1][0].transcript.trim().toLowerCase();

    // Replace symbol words with symbols
    Object.keys(symbolMap).forEach(word => {
      const regex = new RegExp(`\b${word}\b`, 'gi');
      transcript = transcript.replace(regex, symbolMap[word]);
    });

    // Split transcript into words for number parsing
    const words = transcript.split(/\s+/);
    let processedWords = [];
    let buffer = [];

    words.forEach(word => {
      if (numbersMap[word] !== undefined) {
        buffer.push(word);
      } else {
        if (buffer.length) {
          const num = parseSpokenNumber(buffer);
          if (num !== null) processedWords.push(num);
          buffer = [];
        }
        processedWords.push(word);
      }
    });

    if (buffer.length) {
      const num = parseSpokenNumber(buffer);
      if (num !== null) processedWords.push(num);
      buffer = [];
    }

    transcript = processedWords.join(' ');

    // --- Check for "press enter" command ---
    if (/press enter/i.test(transcript)) {
      searchData(); // Trigger search
      transcript = transcript.replace(/press enter/i, '').trim();
    }

    $('#ds_search').val(transcript).trigger('input');
  };

  recognition.onerror = function(event) {
    console.error('Voice recognition error:', event.error);
  };

  recognition.onend = function() {
    // Automatically restart recognition to keep it alive
    recognition.start();
  };
}



</script>

</section>