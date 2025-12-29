<div class="grid md:grid-cols-2 gap-6">
      <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<!-- 2. Settings -->
<div  x-data="settingsCard()" x-init="loadSettings" class="md:mx-9">
  <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg ">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
      <h2 class="dark:text-slate-300 font-semibold text-lg text-gray-700">
        <i class="fa-solid fa-gear me-1 text-red-500"></i> Settings
      </h2>
    </div>

    <div class="card-content p-4" x-show="!loading">
      <template x-for="(value, key) in settings" :key="key">
        <div class="flex justify-between items-center py-1 border-b border-gray-100 dark:border-gray-700 group">
          <span class="text-gray-700 dark:text-gray-300 font-medium" x-text="formatLabel(key)"></span>

          <!-- Inline Editing -->
          <div class="flex items-center space-x-1">
            <template x-if="editingKey !== key">
              <span class="text-gray-600 dark:text-gray-100 group-hover:text-pink-600 cursor-pointer"
                    x-text="value"
                    @click="startEdit(key, value)">
              </span>
            </template>

            <template x-if="editingKey === key">
              <input type="text"
                     x-model="editValue"
                     @keydown.enter.prevent="saveEdit(key)"
                     @blur="saveEdit(key)"
                     class="border px-2 py-1 text-sm rounded w-40 focus:ring-1 focus:ring-red-400 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">
            </template>

            <button x-show="editingKey !== key"
                    class="text-gray-400 hover:text-gray-600 transition"
                    @click="startEdit(key, value)">
              <i class="fa-solid fa-pen-to-square text-xs"></i>
            </button>
          </div>
        </div>
      </template>
    </div>

    <div x-show="loading" class="p-4 text-center text-gray-500">
      <i class="fa-solid fa-spinner fa-spin"></i> Loading settings...
    </div>
  </div>







    <!-- 14. Compose Message -->
    <div class="masonry-item my-4">
      <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg" data-url-content="/data/compose">
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
          <h2 class="font-semibold text-lg text-gray-700 dark:text-gray-300"><i class="fa-solid fa-envelope me-1 text-red-500"></i> Compose Message</h2>
          <div class="flex items-center space-x-2 text-gray-500">
            <button class="collapse-btn"><i class="fa-solid fa-angle-down"></i></button>
            
            <button class="max-btn"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
          </div>
        </div>
        <div class="card-content p-4 space-y-2">
          <input type="text" placeholder="To:" class="w-full border rounded-lg px-3 py-2 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">
          <input type="text" placeholder="Subject:" class="w-full border rounded-lg px-3 py-2 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">
          <textarea rows="4" placeholder="Write message..." class="w-full border rounded-lg px-3 py-2 dark:bg-slate-800 dark:border-slate-700 focus:outline-none"></textarea>

         <script>
function handleMessage(action) {
  const recipient = document.querySelector('input[placeholder="To:"]').value.trim();
  const subject = document.querySelector('input[placeholder="Subject:"]').value.trim();
  const message = document.querySelector('textarea').value.trim();
  const status = action === 'send' ? 'sent' : 'draft';

  fetch('@api/api', {
    method: 'POST',
    body: new URLSearchParams({
      action: 'save_message',
      recipient,
      subject,
      message,
      status
    })
  })
  .then(res => res.json())
  .then(json => {
    if (json.success) {
     showMessage(`Message ${status === 'sent' ? 'sent' : 'saved as draft'} successfully!`,'success');
      $("input, textarea").val('');
    } else {
     showMessage('Error: ' + json.error,'error');
    }
  })
  .catch(err => console.error('Fetch error:', err));
}
</script>

<!-- Buttons (modify existing buttons) -->
<div class="flex gap-2">
  <button class="bg-gray-700 text-white px-4 py-2 rounded-lg" onclick="handleMessage('send')">Send</button>
  <button class="px-4 py-2 border rounded-lg" onclick="handleMessage('draft')">Save Draft</button>
</div>

        </div>
      </div>
    </div>




<!-- 15. Create Tasks -->
<div class="masonry-item" x-data="tasksCard()" x-init="loadTasks()">
  <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
      <h2 class="dark:text-slate-300 font-semibold text-lg text-gray-700">
        <i class="fa-solid fa-list-check me-1 text-red-500"></i> Create Tasks
      </h2>
      <div class="flex items-center space-x-2 text-gray-500">
        <button class="collapse-btn" title="Collapse"><i class="fa-solid fa-angle-down"></i></button>
        <button class="max-btn" title="Maximize"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
      </div>
    </div>

    <!-- Card content -->
    <div class="card-content p-4">
      <input type="text" placeholder="Task title" x-model="newTask" class="w-full border rounded-lg px-3 py-2 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">
      <div class="mt-2 flex gap-2">
        <button @click="addTask" class="bg-gray-700 text-white px-4 py-2 rounded-lg">Add Task</button>
        <button @click="loadTasks" class="px-4 py-2 border rounded-lg">Refresh</button>
      </div>

      <div class="mt-4">
        <template x-for="(task, index) in tasks" :key="index">
          <div class="flex items-center justify-between bg-gray-50 hover:bg-gray-100 dark:bg-slate-700 dark:hover:bg-slate-900  p-2 rounded mb-2 border dark:border-slate-600  transition">
            <div class="flex items-center gap-2">
              <input type="checkbox" x-model="task.completed" @change="toggleComplete(task)" class="cursor-pointer">
              <span x-text="task.task" :class="task.completed ? 'line-through text-gray-400' : ''"></span>
            </div>
            <button @click="deleteTask(task.id)" class="text-red-500 hover:text-red-700">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </template>

        <template x-if="tasks.length === 0">
          <div class="text-gray-500 text-center py-4">No tasks found.</div>
        </template>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('tasksCard', () => ({
    tasks: [],
    newTask: '',
    async loadTasks() {
      try {
        const res = await fetch('<?=$path?>@api/api', {
          method: 'POST',
          body: new URLSearchParams({ action: 'get_tasks' })
        });
        const json = await res.json();
        if (json.success) this.tasks = json.data;
      } catch (err) {
        console.error('Error loading tasks:', err);
      }
    },
    async addTask() {
      if (!this.newTask.trim()) return showMessage('Task title required','success');
      try {
        const res = await fetch('<?=$path?>@api/api', {
          method: 'POST',
          body: new URLSearchParams({ action: 'add_task', task: this.newTask })
        });
        const json = await res.json();
        if (json.success) {
          this.tasks.unshift({ task: this.newTask, completed: 0 });
          this.newTask = '';
        }
      } catch (err) {
        console.error('Error adding task:', err);
      }
    },
    async toggleComplete(task) {
      try {
        await fetch('<?=$path?>@api/api', {
          method: 'POST',
          body: new URLSearchParams({ action: 'toggle_task', id: task.id, completed: task.completed ? 1 : 0 })
        });
      } catch (err) {
        console.error('Error updating task:', err);
      }
    },
    async deleteTask(id) {
      if (!confirm('Delete this task?')) return;
      try {
        const res = await fetch('<?=$path?>@api/api', {
          method: 'POST',
          body: new URLSearchParams({ action: 'delete_task', id })
        });
        const json = await res.json();
        if (json.success) {
          this.tasks = this.tasks.filter(t => t.id !== id);
        }
      } catch (err) {
        console.error('Error deleting task:', err);
      }
    }
  }));
});
</script>





</div>
<div>
    <button title="Dark Mode" class="flex darkModeToggle items-center w-full px-3 py-2 my-3  rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
          <i title="Dark Mode" data-lucide="moon" id="darkModeIcon" class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-300"></i>

          <span><span id="darkModeText">Dark Mode</span></span>
        </button>
        <!-- 7. Change Table Icons -->
<div class="masonry-item" x-data="iconManager" x-init="loadIcons()">
  <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
      <h2 class="dark:text-slate-300 font-semibold text-lg text-gray-700">
        <i class="fa-solid fa-icons me-1 text-red-500"></i> Change Table Icons
      </h2>
      <div class="flex items-center space-x-2 text-gray-500">
        <button class="collapse-btn"><i class="fa-solid fa-angle-down"></i></button>
        <button class="max-btn"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
      </div>
    </div>

    <div class="card-content p-4">
      <!-- Grid of tables -->
      <div class="grid grid-cols-2 gap-3">
        <template x-for="[table, icon] of Object.entries(icons)" :key="table">
          <div class="flex items-center gap-2 cursor-pointer hover:text-red-500"
               @click="promptIconChange(table)">
            <i :class="icon"></i> <span x-text="capitalize(table)"></span>
          </div>
        </template>
      </div>

      <div class="mt-3 text-sm text-gray-500" x-text="message || 'Click an item to change its icon.'"></div>
    </div>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('iconManager', () => ({
    icons: {},
    message: '',

    async loadIcons() {
      const res = await fetch('@api/api', {
        method: 'POST',
        body: new URLSearchParams({ action: 'get_icons' })
      });
      const json = await res.json();
      if (json.success) {
        this.icons = json.data;
      } else {
        this.message = json.error;
      }
    },

    async promptIconChange(table) {
      const newIcon = prompt(`Enter new icon class for "${table}" (e.g. fa-solid fa-user, mdi mdi-account, uil uil-user):`, this.icons[table]);
      if (!newIcon) return;

      const res = await fetch('@api/api', {
        method: 'POST',
        body: new URLSearchParams({
          action: 'update_icon',
          table,
          icon: newIcon
        })
      });

      const json = await res.json();
      if (json.success) {
        this.icons[table] = newIcon;
        this.message = `Icon for "${table}" updated successfully!`;
      } else {
        this.message = json.error;
      }
    },

    capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    }
  }));
});
</script>








<!-- 6. Add New User -->
<div class="masonry-item">
  <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
      <h2 class="dark:text-slate-300 font-semibold text-lg text-gray-700">
        <i class="fa-solid fa-circle-plus me-1 text-red-500"></i> Add New User
      </h2>
      <div class="flex items-center space-x-2 text-gray-500">
        <button class="collapse-btn" title="Collapse"><i class="fa-solid fa-angle-down"></i></button>
        <button class="max-btn" title="Maximize"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
      </div>
    </div>

    <div class="card-content p-4" x-data="addUserCard()" x-init="init()">
      <p class="text-sm text-gray-600 dark:text-gray-100">Fill out the form to add a new user:</p>

      <form @submit.prevent="addUser" class="mt-3 space-y-3">
        <div>
          <label class="block text-gray-700 dark:text-gray-200 text-sm mb-1">Username</label>
          <input type="text" x-model="username" required
                 class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">
        </div>
        <div>
          <label class="block text-gray-700 dark:text-gray-200 text-sm mb-1">Email</label>
          <input type="email" x-model="email" required
                 class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">
        </div>
        <div>
          <label class="block text-gray-700 dark:text-gray-200 text-sm mb-1">Password</label>
          <input type="password" x-model="password" required
                 class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">
        </div>

        <button type="submit"
                class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-red-800 transition">
          Create New User
        </button>
      </form>

      <div x-show="loading" class="mt-3 text-gray-500 text-sm">
        <i class="fa-solid fa-spinner fa-spin"></i> Adding user...
      </div>

      <div x-show="successMessage" class="mt-3 text-green-600 text-sm" x-text="successMessage"></div>
      <div x-show="errorMessage" class="mt-3 text-red-600 text-sm" x-text="errorMessage"></div>
    </div>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('addUserCard', () => ({
    username: '',
    email: '',
    password: '',
    loading: false,
    successMessage: '',
    errorMessage: '',

    init() {
      // Any initialization if needed
    },

    async addUser() {
      this.loading = true;
      this.successMessage = '';
      this.errorMessage = '';

      try {
        const formData = new URLSearchParams();
        formData.append('action', 'add_user');
        formData.append('username', this.username);
        formData.append('email', this.email);
        formData.append('password', this.password);

        const res = await fetch('<?=$path?>@api/api', {
          method: 'POST',
          body: formData
        });

        const json = await res.json();

        if (json.success) {
          this.successMessage = 'User added successfully!';
          this.username = '';
          this.email = '';
          this.password = '';
        } else {
          this.errorMessage = json.error || 'Error adding user';
        }
      } catch (err) {
        this.errorMessage = 'Network error';
      } finally {
        this.loading = false;
      }
    }
  }));
});
</script>








<!-- 16. Add Content -->
<div class="masonry-item">
  <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg" data-url-content="/data/add-content">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
      <h2 class="dark:text-slate-300 font-semibold text-lg text-gray-700">
        <i class="fa-solid fa-pen-to-square me-1 text-red-500"></i> Add Content
      </h2>
      <div class="flex items-center space-x-2 text-gray-500">
        <button class="collapse-btn"><i class="fa-solid fa-angle-down"></i></button>
        <button class="max-btn"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
      </div>
    </div>

    <div class="card-content p-4 space-y-2" x-data="addContent">
      <!-- Title -->
      <input type="text" x-model="title" placeholder="Title" class="w-full border rounded px-3 py-2 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">

      <!-- Path + Extension + Import -->
      <div class="flex gap-2 items-center">
        <input type="text" x-model="path" placeholder="Path (e.g. /docs/readme.md)" class="flex-1 border rounded px-3 py-2 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">
        <select x-model="extension" class="border rounded px-2 py-2 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">
          <option value="md">.md</option>
          <option value="txt">.txt</option>
          <option value="html">.html</option>
          <option value="js">.js</option>
          <option value="php">.php</option>
        </select>
        <input type="file" x-ref="file" class="hidden" @change="importFile">
      </div>
        <button @click="$refs.file.click()" class="bg-gray-100  hover:bg-gray-200 dark:bg-gray-700  dark:hover:bg-gray-600 w-full p-2 rounded" title="Import file">
          IMPORT FILE <i class="fa-solid fa-file-import text-white bg-gray-500 dark:text-black dark:bg-gray-100 p-1 rounded"></i>
        </button>

      <!-- Body -->
      <textarea x-model="body" rows="6" placeholder="Body..." class="w-full border rounded px-3 py-2 dark:bg-slate-800 dark:border-slate-700 focus:outline-none"></textarea>

      <!-- Actions -->
      <div class="flex gap-2">
        <button @click="save('publish')" class="bg-gray-700 text-white px-4 py-2 rounded">Publish</button>
        <button @click="save('draft')" class="px-4 py-2 border rounded">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('addContent', () => ({
    title: '',
    path: '',
    extension: 'md',
    body: '',

    // 🗂️ Import file content
    importFile(event) {
      const file = event.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        this.body = e.target.result;
        this.path = file.name; // auto-fill path with file name
        const ext = file.name.split('.').pop();
        this.extension = ext || 'txt';
      };
      reader.readAsText(file);
    },

    // 💾 Save content to backend
    async save(status = 'draft') {
      if (!this.title.trim()) {
        showMessage('Please enter a title.', 'error');
        return;
      }

      const res = await fetch('@api/api', {
        method: 'POST',
        body: new URLSearchParams({
          action: 'save_content',
          title: this.title,
          path: this.path,
          extension: this.extension,
          body: this.body,
          status
        })
      });

      const json = await res.json();
      if (json.success) {
        showMessage('Content saved successfully as ' + status,'success');
        this.title = this.path = this.body = '';
      } else {
        showMessage('Error: ' + json.error,'error');
      }
    }
  }));
});
</script>













</div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('settingsCard', () => ({
    settings: {},
    loading: true,
    editingKey: null,
    editValue: '',

    async loadSettings() {
      this.loading = true;
      try {
        const res = await fetch('<?=$path?>@api/api', {
          method: 'POST',
          body: new URLSearchParams({ action: 'get_settings' })
        });
        const json = await res.json();

        if (json.success) {
          this.settings = json.data;
        } else {
          console.error(json.error);
        }
      } catch (err) {
        console.error('Error loading settings:', err);
      } finally {
        this.loading = false;
      }
    },

    startEdit(key, value) {
      this.editingKey = key;
      this.editValue = value;
      this.$nextTick(() => {
        const input = document.querySelector('input[x-model="editValue"]');
        if (input) input.focus();
      });
    },

    async saveEdit(key) {
      if (this.editValue === this.settings[key]) {
        this.editingKey = null;
        return;
      }

      try {
        const formData = new URLSearchParams();
        formData.append('action', 'update_settings');
        formData.append(key, this.editValue);

        const res = await fetch('<?=$path?>@api/api', {
          method: 'POST',
          body: formData
        });

        const json = await res.json();
        if (json.success) {
          this.settings[key] = this.editValue;
          showMessage('Setting updated!', 'success');
        } else {
          showMessage(json.error || 'Error updating setting', 'error');
        }
      } catch (err) {
        console.error('Error saving setting:', err);
        showMessage('Network error', 'error');
      } finally {
        this.editingKey = null;
      }
    },

    formatLabel(key) {
      return key.replaceAll('_', ' ').replace(/\b\w/g, c => c.toUpperCase());
    },

    openFullPage() {
      window.location.href = '<?=$path?>settings';
    }
  }));
});
</script>