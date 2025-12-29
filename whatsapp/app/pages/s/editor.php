
  <!-- Flowbite -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

  <!-- Ace Editor -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.3/ace.js"></script>

  <style>
    section { background-color: #0f172a; }
    #editor { height: 75vh; width: 100%; border-radius: .5rem; }
    .folder, .file { cursor: pointer; }
  </style>
<section class="section dark:bg-gray-800 dark:text-white p-4 mx-9 rounded-3xl -mt-15">

  <!-- Navbar -->
  <nav class="bg-gray-800 border-b border-gray-700 px-4 py-3 flex justify-between items-center rounded-lg">
    <h1 class="text-xl font-semibold text-white">📘 File Code Editor</h1>
    <div class="flex items-center space-x-2">
    <button id="openFileBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Open</button>
      <input id="filepathInput" type="text" placeholder="Enter file path..." class="text-gray-900 rounded-lg p-2 w-64 border border-gray-600">
      <button id="loadBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg">Load</button>

      <select id="modeSelect" class="text-gray-900 text-sm rounded-lg p-2.5 border border-gray-600">
        <option value="html">HTML</option>
        <option value="css">CSS</option>
        <option value="php">PHP</option>
        <option value="javascript">JavaScript</option>
        <option value="json">JSON</option>
        <option value="sql">SQL</option>
        <option value="text">Text</option>
      </select>

      <button id="saveBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Save</button>
    </div>
  </nav>

  <!-- Layout -->
  <div class="grid grid-cols-12 gap-4 mt-4">
    <!-- Sidebar -->
    <aside class="col-span-3 bg-gray-900 rounded-lg p-3 overflow-y-auto max-h-[75vh]" id="sidebar">
      <h2 class="text-lg font-semibold mb-3">📂 Project Files</h2>
      <div id="fileTree" class="text-sm"></div>
    </aside>

    <!-- Editor + Preview -->
    <section class="col-span-9 space-y-3">
      <div id="editor" class="shadow-lg"></div>
    </section>
  </div>















  <!-- JS -->
  <script>
    const editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/html");
    editor.setFontSize(14);
    editor.setValue("");

    const modeSelect = document.getElementById("modeSelect");
    modeSelect.addEventListener("change", () => {
      editor.session.setMode("ace/mode/" + modeSelect.value);
    });

    // Fetch file tree from PHP
    fetch("<?= $path ?>@api/api?action=filetree")
      .then(res => res.json())
      .then(data => renderTree(data, document.getElementById("fileTree")));

    function renderTree(items, container) {
      items.forEach(item => {
        const el = document.createElement("div");
        el.className = "pl-3";

        if (item.type === "folder") {
          const folder = document.createElement("div");
          folder.textContent = "📁 " + item.name;
          folder.classList.add("folder", "hover:text-blue-400", "font-semibold");
          const childrenContainer = document.createElement("div");
          childrenContainer.style.display = "none";
          folder.addEventListener("click", () => {
            childrenContainer.style.display = 
              childrenContainer.style.display === "none" ? "block" : "none";
          });
          el.appendChild(folder);
          el.appendChild(childrenContainer);
          renderTree(item.children, childrenContainer);
        } else {
          const file = document.createElement("div");
          file.textContent = "📄 " + item.name;
          file.classList.add("file", "hover:text-green-400");
          file.addEventListener("click", () => loadFile(item.path));
          el.appendChild(file);
        }

        container.appendChild(el);
      });
    }

    // Load file content
    function loadFile(path) {
      fetch("<?= $path?>@api/api?action=loadfile&file=" + path)
        .then(res => res.text())
        .then(text => {
          editor.setValue(text);
          const ext = path.split('.').pop().toLowerCase();
          const map = {
            html: "html", htm: "html", php: "php", css: "css",
            js: "javascript", json: "json", sql: "sql", txt: "text"
          };
          editor.session.setMode("ace/mode/" + (map[ext] || "text"));
          modeSelect.value = map[ext] || "text";
          document.getElementById("filepathInput").value = path;

  
        })
        .catch(() => alert("Failed to load file: " + path));
    }

    // Manual load
    document.getElementById("loadBtn").addEventListener("click", () => {
      const path = document.getElementById("filepathInput").value;
      if (path.trim() !== "") loadFile(path.trim());
    });

    // Save as file download
    document.getElementById("saveBtn").addEventListener("click", () => {
      const content = editor.getValue();
      const mode = modeSelect.value;

      $.post("<?= $path?>@api/api",{
        action:'savefile',
        path:$('#filepathInput').val(),
        content:content
      }, function(res){
        showMessage(res.message, res.text);
      }).fail(function(xhr) {
        showMessage("server error 500", 'error');
      }); 
    });

    




// --- Open in new window ---
document.getElementById("openFileBtn").addEventListener("click", () => {
  const path = document.getElementById("filepathInput").value.trim();
  if (!path) {
    alert("No file path selected!");
    return;
  }
  const width = 600, height = 600;
  const left = (window.screen.width / 2) - (width / 2);
  const top = (window.screen.height / 2) - (height / 2);
  window.open(
    path,
    "_blank",
    `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes,status=no`
  );
});

  </script>
</section>