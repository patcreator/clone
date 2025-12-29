    <!-- BEGIN: Expanded 26-card Masonry Dashboard Section -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- ✅ Load Chart.js first -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // // const axios = require('axios').default;
  // axios.post('/user', {
  //   firstName: 'Fred',
  //   lastName: 'Flintstone'
  // })
  // .then(function (response) {
  //   console.log(response);
  // })
  // .catch(function (error) {
  //   console.log(error);
  // });
</script>
<style>
  /* Pinterest-style masonry columns */
  .masonry {
    column-count: 4;
    column-gap: 1rem;
  }
  @media (max-width: 1280px) { .masonry { column-count: 3; } }
  @media (max-width: 1024px) { .masonry { column-count: 2; } }
  @media (max-width: 640px)  { .masonry { column-count: 1; } }
  .masonry-item {
    break-inside: avoid;
    margin-bottom: 1rem;
  }
  /* Card maximize styles helper */
  .card-maximized {
    position: fixed !important;
    inset: 2rem !important;
    z-index: 9999 !important;
    width: auto !important;
    height: auto !important;
    margin: 0 !important;
    overflow: auto !important;
    border-radius: 0.5rem !important;
  }
</style>


<section class="p-6 py-0 rounded-xl" >


<div id="toast-simple" class="flex justify-between items-center w-full mb-5 p-4 space-x-4 rtl:space-x-reverse text-gray-500 bg-white divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow-sm dark:text-gray-400 dark:divide-gray-700 dark:bg-gray-800" role="alert">
    <h1 class="text-2xl font-bold mb-4">
      <span class="text-xs"><div class="card-content" id="live-time">Loading...</div></span>
      <span>Welcome to <?= $site_name ?></span></h1>
    <div class="flex justify-between items-center">
      <i class="fa fa-location-dot mx-2 mr-0"></i>
      <div id="detected-country" class="text-gray-700 dark:text-gray-300 ps-4 text-sm font-normal">Detecting country...</div>
    </div>
</div>

  
  <div id="dashboard" class="masonry">
    <!-- 1. Searches -->
    <div class="masonry-item">
      <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg" data-url-content="/data/searches">
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
          <h2 class="font-semibold text-lg text-gray-700 dark:text-gray-300"><i class="fa-solid fa-magnifying-glass me-1 text-red-500"></i> Contents</h2>
          <div class="flex items-center space-x-2 text-gray-500">
            <button class="collapse-btn" title="Collapse"><i class="fa-solid fa-angle-down"></i></button>
            
            <button class="max-btn" title="Maximize"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
          </div>
        </div>
        <div class="card-content p-4" x-data="{
    search: '',
    open: false,
    items: [],
    get filteredItems() {
        return this.items.filter(i => i.toLowerCase().startsWith(this.search.toLowerCase()));
    },
    init() {
        fetch('<?= $path ?>@api/api', {method: 'POST',body: new URLSearchParams({ action: 'get_tables' })}).then(res => res.json()).then(res => {this.items = res.data;});
    }
}">
    <input
        x-model="search"
        @input="open = search.trim().length > 0"
        type="text"
        placeholder="Search table..."
        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 dark:bg-slate-800 dark:border-slate-700 focus:outline-none"
    >

        <div class="h-[13rem] overflow-y-auto">
            <template x-for="item in filteredItems" :key="item">
                <p class="grid grid-cols-12 gap-2 mt-3">
                  <a
                    x-html="`<i class='fa fa-external-link me-2'></i>${item}`"
                    :href="`<?= $path ?>s/content?e=${item}`"
                    class="block w-full col-span-8 px-4 py-2  text-sm font-medium text-gray-900 hover:shadow-md bg-white border border-gray-200 rounded-lg cursor-pointer dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                ></a>
                <a target="_blank"
                    :href="`<?= $path ?>s/Create?e=${item}`"
                    class="block w-full px-4 flex items-center justify-center hover:bg-gray-200 col-span-2 py-2  text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg cursor-pointer dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                >
                  <i class="uil-plus"></i>
                </a>
                <a target="_blank"

                    :href="`<?= $path ?>s/Report?get=${item}`"
                    class="block w-full px-4 flex items-center justify-center hover:bg-gray-200 py-2 col-span-2  text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg cursor-pointer dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                >
                  <i class="uil-file-alt"></i>
                </a>
                
                </p>
            </template>
        </div>
</div>
      </div>
    </div>

<!-- 2. Settings -->
<div class="masonry-item" x-data="settingsCard()" x-init="loadSettings">
  <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
      <h2 class="dark:text-slate-300 font-semibold text-lg text-gray-700">
        <i class="fa-solid fa-gear me-1 text-red-500"></i> Settings
      </h2>
      <div class="flex items-center space-x-2 text-gray-500">
        <button type="button" class="collapse-btn" title="Collapse">
          <i class="fa-solid fa-angle-down"></i>
        </button>
        <button type="button" class="max-btn" title="Maximize">
          <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
        </button>
      </div>
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

      <a href="<?= $path?>settings" @click.prevent="openFullPage"
         class="mt-3 inline-block text-pink-600 text-sm">
         Open full settings page
      </a>
    </div>

    <div x-show="loading" class="p-4 text-center text-gray-500">
      <i class="fa-solid fa-spinner fa-spin"></i> Loading settings...
    </div>
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

    <!-- 3. Helpful Links -->
    <div class="masonry-item">
      <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg" >
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
          <h2 class="font-semibold text-lg text-gray-700 dark:text-gray-300"><i class="fa-solid fa-link me-1 text-red-500"></i> Helpful Links</h2>
          <div class="flex items-center space-x-2 text-gray-500">
            <button class="collapse-btn"><i class="fa-solid fa-angle-down"></i></button>
            
            <button class="max-btn"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
          </div>
        </div>
        <div class="card-content p-4">
          <ul class="list-disc ml-5 space-y-1">
            <li><a href="@support/documentation" class="text-pink-600">Documentation</a></li>
            <li><a href="@support/api-reference" class="text-pink-600">API Reference</a></li>
            <li><a href="@support/developer-forum" class="text-pink-600">Developer Forum</a></li>
            <li><a href="@support/support-center" class="text-pink-600">Support Center</a></li>
          </ul>
        </div>
      </div>
    </div>








<!-- 5. 10 New Registered Users (Table) -->
<div class="masonry-item" x-data="newUsersCard()" x-init="loadUsers">
  <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
      <h2 class="dark:text-slate-300 font-semibold text-lg text-gray-700">
        <i class="fa-solid fa-table-list me-1 text-red-500"></i> 10 New Registered Users
      </h2>
      <div class="flex items-center space-x-2 text-gray-500">
        <button type="button" class="collapse-btn" title="Collapse">
          <i class="fa-solid fa-angle-down"></i>
        </button>
        <button type="button" class="max-btn" title="Maximize">
          <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
        </button>
      </div>
    </div>

    <!-- Table Content -->
    <div class="card-content p-4 overflow-x-auto" x-show="!loading">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-700 font-medium">
          <tr>
            <th class="p-2">#</th>
            <th class="p-2">Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">View</th>
          </tr>
        </thead>
        <tbody>
          <template x-for="(user, index) in users" :key="index">
            <tr class="border-b hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-900 transition">
              <!-- Row number instead of actual ID -->
              <td class="p-2" x-text="index + 1"></td>
              <td class="p-2" x-text="user.username"></td>
              <td class="p-2" x-text="user.email"></td>
              <td class="p-2">
                <!-- Use actual ID in the link but not visible -->
                <a :href="`<?=$path?>@users/${user.username}`" class="text-pink-600 hover:underline">View</a>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>

    <div x-show="loading" class="p-4 text-center text-gray-500">
      <i class="fa-solid fa-spinner fa-spin"></i> Loading users...
    </div>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('newUsersCard', () => ({
    users: [],
    loading: true,

    async loadUsers() {
      this.loading = true;
      try {
        const res = await fetch('<?=$path?>@api/api', {
          method: 'POST',
          body: new URLSearchParams({ action: 'get_new_users' })
        });
        const json = await res.json();

        if (json.success) {
          this.users = json.data;
        } else {
          console.error(json.error);
        }
      } catch (err) {
        console.error('Error loading users:', err);
      } finally {
        this.loading = false;
      }
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








    <!-- 8. Active Users (chart placeholder) -->
    <div class="masonry-item">
      <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg" data-url-content="/data/active-users">
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
          <h2 class="font-semibold text-lg text-gray-700 dark:text-gray-300"><i class="fa-solid fa-chart-line me-1 text-red-500"></i> Users status</h2>
          <div class="flex items-center space-x-2 text-gray-500">
            <button class="collapse-btn"><i class="fa-solid fa-angle-down"></i></button>
            
            <button class="max-btn"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
          </div>
        </div>




<div 
  class="card-content p-4" 
  x-data="userStatusChart" 
  x-init="init()"
>
  <div class="h-[13rem] flex justify-center">
    <canvas id="userStatusChartCanvas"></canvas>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('userStatusChart', () => ({
    chart: null,
    data: {},

    async init() {
      try {
        const res = await fetch('<?=$path?>@api/api', {
          method: 'POST',
          body: new URLSearchParams({ action: 'get_active_users' })
        });
        const json = await res.json();

        if (json.success) {
          this.data = json.data;
          this.renderChart();
        } else {
          showMessage(json.error,'success');
          console.error(json.error);
        }
      } catch (err) {
        showMessage(err,'error');
        console.error('Fetch error:', err);
      }
    },

    renderChart() {
      const ctx = document.getElementById('userStatusChartCanvas').getContext('2d');
      const labels = Object.keys(this.data);
      const values = Object.values(this.data);

      // ✅ Destroy old chart if it exists
      if (this.chart) {
        this.chart.destroy();
      }

      // ✅ Create new chart instance safely
      this.chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: labels.map(s => s.replace('-', ' ')),
          datasets: [{
            label: 'User Status',
            data: values,
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom'
            }
          }
        }
      });
    }
  }));
});
</script>

      </div>
    </div>

    <!-- 9. Visitor Stats (hour/day/week/month/year) -->
    <div class="masonry-item">
      <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg" data-url-content="/data/visitors">
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
          <h2 class="font-semibold text-lg text-gray-700 dark:text-gray-300"><i class="fa-solid fa-compass me-1 text-red-500"></i> Visitors</h2>
          <div class="flex items-center space-x-2 text-gray-500">
            <button class="collapse-btn"><i class="fa-solid fa-angle-down"></i></button>
            
            <button class="max-btn"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
          </div>
        </div>
<div class="card-content p-4" 
     x-data="visitorStats" 
     x-init="init('day')">

  <div class="flex gap-2 flex-wrap">
    <template x-for="p in ['hour', 'day', 'week', 'month', 'year']">
      <button 
        class="px-3 py-1 rounded transition"
        :class="period === p ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
        @click="init(p)">
        <span x-text="p.charAt(0).toUpperCase() + p.slice(1)"></span>
      </button>
    </template>
  </div>

  <div class="mt-3">
    <canvas id="visitorsChartCanvas" height="120"></canvas>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  setInterval(() => this.init(this.period), 60000);
  Alpine.data('visitorStats', () => ({
    chart: null,
    data: [],
    period: 'day',

    async init(p = 'day') {
      this.period = p;

      const res = await fetch('<?=$path?>@api/api', {
        method: 'POST',
        body: new URLSearchParams({ action: 'get_visitor_stats', period: this.period })
      });

      const json = await res.json();
      if (json.success) {
        this.data = json.data;
        this.renderChart();
      } else {
        console.error(json.error);
      }
    },

    renderChart() {
      const ctx = document.getElementById('visitorsChartCanvas').getContext('2d');

      const labels = this.data.map(i => i.label);
      const values = this.data.map(i => i.visits);

      // ✅ destroy old chart if exists
      if (this.chart) this.chart.destroy();

      this.chart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels,
          datasets: [{
            label: 'Visitors (' + this.period + ')',
            data: values,
            borderWidth: 2,
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: { beginAtZero: true }
          },
          plugins: {
            legend: { position: 'bottom' },
            title: { display: true, text: 'Website Visitors by ' + this.period }
          }
        }
      });
    }
  }));
});
</script>

      </div>
    </div>


    <!-- 12. Open Tools -->
    <div class="masonry-item">
      <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg" data-url-content="/data/tools">
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
          <h2 class="font-semibold text-lg text-gray-700 dark:text-gray-300"><i class="fa-solid fa-screwdriver-wrench me-1 text-red-500"></i> Open Tools</h2>
          <div class="flex items-center space-x-2 text-gray-500">
            <button class="collapse-btn"><i class="fa-solid fa-angle-down"></i></button>
            
            <button class="max-btn"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
          </div>
        </div>
        <div class="card-content p-4">
          <div class="grid grid-cols-2 gap-2">
            <a target="_blank" href="<?= $path ?>query" class="py-2 px-3 border dark:border-slate-800 dark:bg-slate-700 dark:hover:bg-slate-700/50 rounded rounded-2xl">Query runner</a>
            <a target="_blank" href="<?= $path ?>cmd" class="py-2 px-3 border dark:border-slate-800 dark:bg-slate-700 dark:hover:bg-slate-700/50 rounded rounded-2xl">CMD</a>
            <a target="_blank" href="<?= $path ?>explore" class="py-2 px-3 border dark:border-slate-800 dark:bg-slate-700 dark:hover:bg-slate-700/50 rounded rounded-2xl">EXPLORE</a>
            <a target="_blank" href="<?= $path ?>filemanager" class="py-2 px-3 border dark:border-slate-800 dark:bg-slate-700 dark:hover:bg-slate-700/50 rounded rounded-2xl">File manager</a>
            <a target="_blank" href="<?= $path ?>editor" class="py-2 px-3 border dark:border-slate-800 dark:bg-slate-700 dark:hover:bg-slate-700/50 rounded rounded-2xl">EDITOR</a>
          </div>
        </div>
      </div>
    </div>

<!-- 13. 5 Latest Newsletters -->
<div class="masonry-item">
  <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
      <h2 class="dark:text-slate-300 font-semibold text text-gray-700">
        <i class="fa-solid fa-newspaper me-1 text-red-500"></i>10 new Newsletter Emails
      </h2>
      <div class="flex items-center space-x-2 text-gray-500">
        <button class="collapse-btn"><i class="fa-solid fa-angle-down"></i></button>
        <button class="max-btn"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
      </div>
    </div>

    <div class="card-content p-4" x-data="newsletterEmails" x-init="init()">

      <!-- List of emails -->
      <div class="space-y-2">
        <template x-for="subscriber in data" :key="subscriber.email">
          <a :href="`mailto:${subscriber.email}`" class="font-medium flex flex-col text-gray-700 dark:text-gray-200 justify-start items-start bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 hover:dark:bg-red-700/10 p-2 rounded-md">
            <span x-text="subscriber.email"></span>
            <small class="text-gray-400">(
              <span x-text="timeAgo(subscriber.created_at)"></span>
            )</small>
            <span class="ml-2 px-2 py-0.5  rounded-full text-xs"
                  :class="subscriber.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 dark:bg-red-950 text-gray-700 dark:text-gray-200'">
              <span x-text="subscriber.status.charAt(0).toUpperCase() + subscriber.status.slice(1)"></span>
            </span>
          </a>
        </template>
      </div>

      <!-- Pie Chart: Active vs Not-Active -->
      <div class="mt-3 border border-dashed dark:border-gray-500 rounded-lg py-6 text-center text-gray-400">
        <canvas id="newsletterChartCanvas" height="200"></canvas>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('newsletterEmails', () => ({
    data: [],
    chart: null,

    async init() {
      try {
        const res = await fetch('@api/api', {
          method: 'POST',
          body: new URLSearchParams({ action: 'get_latest_newsletters' })
        });
        const json = await res.json();

        if(json.success) {
          this.data = json.data;
          this.renderChart();
        } else {
          console.error(json.error);
        }
      } catch(err) {
        console.error('Fetch error:', err);
      }
    },

    // Convert date to “time ago”
    timeAgo(date) {
      const now = new Date();
      const past = new Date(date);
      const diff = Math.floor((now - past) / 1000); // seconds

      if(diff < 60) return `${diff}s ago`;
      if(diff < 3600) return `${Math.floor(diff/60)}m ago`;
      if(diff < 86400) return `${Math.floor(diff/3600)}h ago`;
      return `${Math.floor(diff/86400)}d ago`;
    },

    renderChart() {
      // Count active vs not-active
      const statusCounts = this.data.reduce((acc, cur) => {
        acc[cur.status] = (acc[cur.status] || 0) + 1;
        return acc;
      }, {});

      const ctx = document.getElementById('newsletterChartCanvas').getContext('2d');

      if(this.chart) this.chart.destroy();

      this.chart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['Active', 'Not-Active'],
          datasets: [{
            data: [statusCounts['active'] || 0, statusCounts['not-active'] || 0],
            backgroundColor: ['#10B981', '#9CA3AF'], // green and gray
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { position: 'bottom' },
            title: { display: true, text: 'Active vs Not-Active Subscribers' }
          }
        }
      });
    }
  }));
});
</script>






    <!-- 14. Compose Message -->
    <div class="masonry-item">
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











<!-- blog -->
<div class="masonry-item" 
     x-data="blogContentTabs" 
     x-init="loadData('latest')">
  <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden hover:shadow-lg">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
      <h2 class="dark:text-slate-300 font-semibold text-lg text-gray-700">
        <i class="fa-solid fa-book-open me-1 text-red-500"></i> View Content
      </h2>
      <div class="flex items-center space-x-2 text-gray-500">
        <button class="collapse-btn"><i class="fa-solid fa-angle-down"></i></button>
        <button class="max-btn"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
      </div>
    </div>

    <div class="card-content p-4">
      <!-- Tabs -->
      <div class="flex flex-wrap gap-2 mb-4">
        <template x-for="(tab, key) in tabs" :key="key">
          <button @click="loadData(key)"
                  class="px-3 py-1 rounded text-sm font-medium transition"
                  :class="currentTab === key ? 'bg-red-500 text-white' : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-900 hover:bg-gray-700'">
            <span x-text="tab"></span>
          </button>
        </template>
      </div>

      <!-- Dynamic Content -->
      <div class="border border-dashed dark:border-gray-500 rounded-lg p-4 h-[14rem] overflow-y-auto">
        <template x-if="currentTab === 'latest'">
          <ul>
            <template x-for="b in data" :key="b.Blog_id">
              <li class="mb-2 border-b dark:border-gray-600 pb-1">
                <div class="font-medium text-gray-800 dark:text-slate-300" x-text="b.Title"></div>
                <div class="text-xs text-gray-500 dark:text-slate-100" x-text="b.Publish_Date"></div>
              </li>
            </template>
          </ul>
        </template>

        <template x-if="currentTab === 'categories'">
          <canvas id="categoriesChart" class="dark:text-slate-300"></canvas>
        </template>

        <template x-if="currentTab === 'comments'">
          <ul>
            <template x-for="c in data" :key="c.createdDate">
              <li class="mb-2 border-b dark:border-slate-600 pb-1">
                <div class="font-medium text-gray-800 dark:text-slate-300" x-text="c.firstname + ' ' + c.lastname"></div>
                <div class="text-xs text-gray-500 dark:text-slate-100" x-text="c.message"></div>
              </li>
            </template>
          </ul>
        </template>

        <template x-if="currentTab === 'authors'">
          <canvas id="authorsChart"></canvas>
        </template>

<template x-if="currentTab === 'settings'">
  <form @submit.prevent="updateSettings" class="text-sm text-gray-700 space-y-3">
    <template x-for="(value, key, index) in data" :key="key">
      <template x-if="key !== 'blog_page_id'">
        <div x-data="{ uid: `input-${key}-${index}-${Math.random().toString(36).substr(2, 6)}` }">
          <label 
            class="block font-medium text-gray-600 dark:text-gray-300 capitalize mb-2"
            :for="uid"
            x-text="key.replaceAll('_', ' ')">
          </label>

          <input 
            type="text"
            class="w-full border rounded px-2 py-1 text-gray-700 focus:ring-1 focus:ring-red-400 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-400 focus:outline-none"
            :id="uid"
            x-model="data[key]">
        </div>
      </template>
    </template>

    <button 
      type="submit"
      class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
      Save Settings
    </button>
  </form>
</template>


      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener('alpine:init', () => {

  Alpine.data('blogContentTabs', () => ({
    tabs: {
      latest: 'Latest 5 Blogs',
      categories: 'Categories',
      comments: 'Latest Comments',
      authors: 'Top Authors',
      settings: 'Blog Page Settings'
    },
    updateSettings: async function() {
  const formData = new URLSearchParams();
  formData.append('action', 'update_blog_settings');
  
  // append all settings except ID
  for (const [key, value] of Object.entries(this.data)) {
    if (key !== 'blog_page_id') formData.append(key, value);
  }

  const res = await fetch('<?=$path?>@api/api', {
    method: 'POST',
    body: formData
  });
  const json = await res.json();

  if (json.success) {
    showMessage('Blog settings updated successfully!','success');
  } else {
    showMessage(json.error);
  }
},
    currentTab: 'latest',
    data: [],
    chart: null,

    async loadData(tab) {
      this.currentTab = tab;
      this.data = [];
      if (this.chart) this.chart.destroy();

      const map = {
        latest: 'get_latest_blogs',
        categories: 'get_categories',
        comments: 'get_latest_comments',
        authors: 'get_top_authors',
        settings: 'get_blog_page_settings'
      };

      const res = await fetch('<?=$path?>@api/api', {
        method: 'POST',
        body: new URLSearchParams({ action: map[tab] })
      });
      const json = await res.json();

      if (json.success) {
        this.data = json.data;
        if (tab === 'categories') this.renderChart('categoriesChart', this.data, 'category', 'total', 'bar');
        if (tab === 'authors') this.renderChart('authorsChart', this.data, 'author', 'posts', 'pie');
      } else {
        console.error(json.error);
      }
    },

    renderChart(id, data, labelKey, valueKey, type = 'bar') {
      const ctx = document.getElementById(id).getContext('2d');
      const labels = data.map(d => d[labelKey]);
      const values = data.map(d => d[valueKey]);

      this.chart = new Chart(ctx, {
        type,
        data: {
          labels,
          datasets: [{
            label: labelKey,
            data: values,
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { position: 'bottom' } },
          scales: type === 'bar' ? { y: { beginAtZero: true } } : {}
        }
      });
    }
  }));

});
</script>











<!-- 18. Reports (list options) -->
<div class="masonry-item" x-data="reportsCard()" x-init="loadTables">
  <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
      <h2 class="dark:text-slate-300 font-semibold text-lg text-gray-700">
        <i class="fa-solid fa-file-lines me-1 text-red-500"></i> Reports
      </h2>
      <div class="flex items-center space-x-2 text-gray-500">
        <button type="button" class="collapse-btn" title="Collapse">
          <i class="fa-solid fa-angle-down"></i>
        </button>
        <button type="button" class="max-btn" title="Maximize">
          <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
        </button>
      </div>
    </div>

    <div class="card-content p-4" x-show="!loading">
      <div class="text-sm text-gray-600 dark:text-gray-100 mb-3">Generate reports by selecting a table:</div>

      <!-- Search Bar -->
      <div class="relative mb-3">
        <input type="text"
               x-model="search"
               placeholder="Search tables..."
               class="w-full border rounded px-3 py-2 text-sm focus:ring-1 focus:ring-red-400 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">
        <i class="fa-solid fa-magnifying-glass absolute right-3 top-3 text-gray-400"></i>
      </div>

      <!-- Tables List -->
      <div class="max-h-60 overflow-y-auto">
        <template x-if="filteredTables.length">
          <ul class=" ">
            <template x-for="table in filteredTables" :key="table">
              <li class="flex justify-between items-center py-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition  border-none rounded-2xl px-3">
                <span class="font-medium text-gray-700 dark:text-gray-200" x-text="table"></span>
                <a :href="`${baseReportUrl}?get=${encodeURIComponent(table)}`"
                   class="text-sm hover:bg-gray-100 hover:border rounded-2xl px-3 py-1 rounded transition">
                   <i class="fa fa-external-link"></i>
                </a>
              </li>
            </template>
          </ul>
        </template>

        <template x-if="!filteredTables.length && !loading">
          <div class="text-gray-500 text-sm text-center py-4">No tables found.</div>
        </template>
      </div>

      <!-- Footer -->
      <div class="mt-4 text-center">
        <button @click="openBuilder"
                class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800 transition">
           All Reports
        </button>
      </div>
    </div>

    <!-- Loading -->
    <div x-show="loading" class="p-4 text-center text-gray-500">
      <i class="fa-solid fa-spinner fa-spin"></i> Loading tables...
    </div>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('reportsCard', () => ({
    tables: [],
    search: '',
    loading: true,
    baseReportUrl: '<?=$path?>report',

    async loadTables() {
      this.loading = true;
      try {
        const res = await fetch('<?=$path?>@api/api', {
          method: 'POST',
          body: new URLSearchParams({ action: 'get_tables' })
        });
        const json = await res.json();

        if (json.success) {
          // Expecting: { success: true, data: [ "users", "orders", ... ] }
          this.tables = json.data;
        } else {
          console.error(json.error);
        }
      } catch (err) {
        console.error('Error loading tables:', err);
      } finally {
        this.loading = false;
      }
    },

    get filteredTables() {
      if (!this.search.trim()) return this.tables;
      return this.tables.filter(t =>
        t.toLowerCase().includes(this.search.toLowerCase())
      );
    },

    openBuilder() {
      window.location.href = this.baseReportUrl;
    }
  }));
});
</script>






<div class="masonry-item" x-data="countsCard()" x-init="loadCounts()">
  <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
      <h2 class="dark:text-slate-300 font-semibold text-lg text-gray-700">
        <i class="fa-solid fa-hashtag me-1 text-red-500"></i> Counts & Metrics
      </h2>
      <div class="flex items-center space-x-2 text-gray-500">
        <button class="collapse-btn"><i class="fa-solid fa-angle-down"></i></button>
        <button class="max-btn"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
      </div>
    </div>

    <div class="card-content p-4">
      <div class="flex gap-3 overflow-x-auto flex-row">
        <template x-for="(tableData, table) in counts" :key="table">
          <div class="bg-gray-100 dark:bg-gray-900 p-3 rounded min-w-32 text-center">
            <div class="text-sm capitalize" x-text="table.replace('_', ' ')"></div>
            <div class="text-xl font-bold" x-text="tableData.total"></div>
          </div>
        </template>
      </div>

      <div class="mt-4 border border-dashed dark:border-gray-500 rounded-lg py-6 px-2 text-gray-400 overflow-x-auto">
  <div class="min-w-[700px] h-[400px]">
    <canvas id="countsChart" class="w-full h-full"></canvas>
  </div>
</div>

    </div>
  </div>
</div>

<!-- Add Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('countsCard', () => ({
    counts: {},
    chart: null,
    async loadCounts() {
      try {
        const res = await fetch('<?=$path?>@api/api', {
          method: 'POST',
          body: new URLSearchParams({ action: 'get_table_weekly_counts' })
        });
        const json = await res.json();

        if (json.success) {
          this.counts = json.data;
          this.renderChart(json.data);
        } else {
          console.error(json.error);
        }
      } catch (err) {
        console.error('Error loading counts:', err);
      }
    },

    renderChart(data) {
      const ctx = document.getElementById('countsChart').getContext('2d');
      const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

      const datasets = Object.keys(data).map((table, i) => ({
        label: table,
        data: [
          data[table].mon,
          data[table].tue,
          data[table].wed,
          data[table].thu,
          data[table].fri,
          data[table].sat,
          data[table].sun
        ],
        fill: false,
        borderWidth: 2,
        tension: 0.3
      }));

      if (this.chart) this.chart.destroy();

      this.chart = new Chart(ctx, {
        type: 'line',
        data: { labels, datasets },
        options: {
          responsive: true,
          plugins: {
            legend: { position: 'bottom' },
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { precision: 0 }
            }
          }
        }
      });
    }
  }));
});
</script>












<!-- 21. New Feedback -->
<div class="masonry-item" x-data="feedbackCard()" x-init="loadFeedback()">
  <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
      <h2 class="dark:text-slate-300 font-semibold text-lg text-gray-700">
        <i class="fa-solid fa-comment-dots me-1 text-red-500"></i> New Feedback
      </h2>
      <div class="flex items-center space-x-2 text-gray-500">
        <button class="collapse-btn" title="Collapse">
          <i class="fa-solid fa-angle-down"></i>
        </button>
        <button class="max-btn" title="Maximize">
          <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
        </button>
      </div>
    </div>

    <div class="card-content p-4" x-show="!loading">
      <div class="space-y-3">
        <template x-for="(item, index) in feedback" :key="index">
          <div class="p-3 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 hover:bg-slate-800 rounded shadow-sm  transition">
            <b x-text="item.name"></b> — 
            <span x-text="item.subject"></span>
          </div>
        </template>

        <template x-if="feedback.length === 0">
          <div class="text-gray-500 text-center py-4">No feedback found.</div>
        </template>
      </div>

      <div class="mt-3">
        <a href="<?=$path?>feedback" class="text-pink-600 hover:underline text-sm">
          Open feedback manager
        </a>
      </div>
    </div>

    <div x-show="loading" class="p-4 text-center text-gray-500">
      <i class="fa-solid fa-spinner fa-spin"></i> Loading feedback...
    </div>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('feedbackCard', () => ({
    feedback: [],
    loading: true,

    async loadFeedback() {
      this.loading = true;
      try {
        const res = await fetch('<?=$path?>@api/api', {
          method: 'POST',
          body: new URLSearchParams({ action: 'get_new_feedback' })
        });
        const json = await res.json();

        if (json.success) {
          this.feedback = json.data;
        } else {
          console.error(json.error);
        }
      } catch (err) {
        console.error('Error loading feedback:', err);
      } finally {
        this.loading = false;
      }
    }
  }));
});
</script>






    <!-- 25. Logout -->
    <div class="masonry-item">
      <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg">
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
          <h2 class="font-semibold text-lg text-gray-700 dark:text-gray-300"><i class="fa-solid fa-right-from-bracket me-1 text-red-500"></i> Logout</h2>
          <div class="flex items-center space-x-2 text-gray-500">
            <button class="collapse-btn"><i class="fa-solid fa-angle-down"></i></button>
            
            <button class="max-btn"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
          </div>
        </div>
        <div class="card-content p-4">
          <p class="text-sm text-gray-600 dark:text-gray-300">Click the button below to sign out of the admin dashboard.</p>
          <div class="mt-3"><a href="<?= $path?>logout" id="logout-btn" class="bg-gray-700 hover:bg-red-700 text-white px-4 py-2 rounded">Logout</a></div>
        </div>
      </div>
    </div>










<!-- 24. Change Your Password -->
<div class="masonry-item">
  <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg" data-url-content="/data/change-password">
    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
      <h2 class="dark:text-slate-300 font-semibold text-lg text-gray-700">
        <i class="fa-solid fa-lock me-1 text-red-500"></i> Change Password
      </h2>
      <div class="flex items-center space-x-2 text-gray-500">
        <button class="collapse-btn"><i class="fa-solid fa-angle-down"></i></button>
        <button class="max-btn"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
      </div>
    </div>

    <div class="card-content p-4 space-y-2" x-data="changePassword">
      <input type="password" x-model="current" placeholder="Current password" class="w-full border rounded px-3 py-2 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">
      <input type="password" x-model="newPass" placeholder="New password" class="w-full border rounded px-3 py-2 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">
      <input type="password" x-model="confirm" placeholder="Confirm new password" class="w-full border rounded px-3 py-2 dark:bg-slate-800 dark:border-slate-700 focus:outline-none">

      <div class="mt-2">
        <button @click="change" class="bg-gray-700 hover:bg-red-700 text-white px-4 py-2 rounded">Change Password</button>
      </div>

      <p x-text="message" :class="{'text-green-600': success, 'text-red-600': !success}" class="mt-2 text-sm"></p>
    </div>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('changePassword', () => ({
    current: '',
    newPass: '',
    confirm: '',
    message: '',
    success: false,

    async change() {
      if (!this.current || !this.newPass || !this.confirm) {
        this.message = 'Please fill in all fields.';
        this.success = false;
        return;
      }

      const res = await fetch('@api/api', {
        method: 'POST',
        body: new URLSearchParams({
          action: 'change_password',
          current: this.current,
          new: this.newPass,
          confirm: this.confirm
        })
      });

      const json = await res.json();
      this.success = json.success;
      this.message = json.success ? json.message : json.error;

      if (json.success) {
        this.current = this.newPass = this.confirm = '';
      }
    }
  }));
});
</script>










    <!-- 26. Control System Users (roles & table access) -->
    <div class="masonry-item">
      <div class="card bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden transition hover:shadow-lg" data-url-content="/data/control-users">
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-slate-700 dark:border-slate-600 ">
          <h2 class="font-semibold text-lg text-gray-700 dark:text-gray-300"><i class="fa-solid fa-shield-halved me-1 text-red-500"></i> Control System Users</h2>
          <div class="flex items-center space-x-2 text-gray-500">
            <button class="collapse-btn"><i class="fa-solid fa-angle-down"></i></button>
            
            <button class="max-btn"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>
          </div>
        </div>

        <div class="card-content p-4">
            <a href="@users" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-400 dark:hover:bg-gray-700  dark:text-black dark:hover:text-white py-2 px-3 rounded-2xl">
              Go to user control page
            </a>
        </div>
      </div>
    </div>

  </div> <!-- end dashboard -->


  <!-- Interactions -->
  <script>
    // Collapse/Expand
    $(document).on('click', '.collapse-btn', function () {
      const content = $(this).closest('.card').find('.card-content');
      content.slideToggle(180);
      $(this).find('i').toggleClass('fa-angle-down fa-angle-up');
    });

    // Reload (simulate) - uses data-url-content attribute
    $(document).on('click', '.reload-btn', function () {
      const card = $(this).closest('.card');
      const url = card.attr('data-url-content') || '[no-url]';
      const content = card.find('.card-content');
      const prev = content.html();
      content.html('<div class="text-center py-6 text-gray-400">🔄 Loading...</div>');
      setTimeout(function () {
        content.html('<div class="text-sm text-gray-600 pb-2">Reloaded (simulated) from <b>' + url + '</b></div>' + prev);
      }, 800);
    });

    // Maximize / restore
    $(document).on('click', '.max-btn', function () {
      const card = $(this).closest('.card');
      const isMax = card.hasClass('card-maximized');
      if (!isMax) {
        card.addClass('card-maximized');
        $('body').addClass('overflow-hidden');
      } else {
        card.removeClass('card-maximized');
        $('body').removeClass('overflow-hidden');
      }
    });

    // Drag & Drop Sorting
    new Sortable(document.getElementById('dashboard'), {
      animation: 200,
      ghostClass: 'opacity-50',
      // allow dragging from anywhere on the card
      handle: '.card',
    });

    // Live Time updater (YYYY-MM-DD HH:MM:SS)
    function updateTime() {
      const now = new Date();
      const y = now.getFullYear();
      const m = String(now.getMonth() + 1).padStart(2, '0');
      const d = String(now.getDate()).padStart(2, '0');
      const hh = String(now.getHours()).padStart(2, '0');
      const mm = String(now.getMinutes()).padStart(2, '0');
      const ss = String(now.getSeconds()).padStart(2, '0');
      const formatted = y + '-' + m + '-' + d + ' ' + hh + ':' + mm + ':' + ss;
      $('#live-time').text(formatted);
    }
    setInterval(updateTime, 1000);
    updateTime();

    // Dark Mode toggle (simple page-level toggle)
    // $('#dark-toggle').on('change', function () {
    //   const enabled = $(this).is(':checked');
    //   if (enabled) {
    //     $('html').addClass('dark');
    //     $('body').addClass('bg-gray-900 text-gray-100');
    //   } else {
    //     $('html').removeClass('dark');
    //     $('body').removeClass('bg-gray-900 text-gray-100');
    //   }
    // });



    // Attempt to detect country via a simple geo-IP endpoint (best-effort; will fail without network)
    (function detectCountry() {
      const el = document.getElementById('detected-country');
      // Best-effort: try using a public endpoint; if blocked or not allowed, fallback.
      fetch('https://ipapi.co/json/')
        .then(res => res.json())
        .then(data => {
          if (data && data.country_name) {
            el.textContent = data.country_name;
          } else {
            el.textContent = 'Unknown';
          }
        }).catch(function () {
          // fallback: basic Intl API (not reliably country)
          try {
            const locale = Intl.DateTimeFormat().resolvedOptions().locale || 'en';
            el.textContent = 'Locale: ' + locale + ' (external geo lookup unavailable)';
          } catch (e) {
            el.textContent = 'Country detection unavailable';
          }
        });
    })();

    // Small accessibility: allow Enter to trigger "Add Task" or "Create" in example fields (simulated)
    $(document).on('keypress', 'input', function (e) {
      if (e.which === 13) {
        e.preventDefault();
        $(this).closest('.card').find('button').first().trigger('click');
      }
    });

  </script>

</section>
<!-- END:  Masonry Dashboard Section -->