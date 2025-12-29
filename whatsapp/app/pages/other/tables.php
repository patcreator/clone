        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200">
          <h2 class="font-semibold text-lg text-gray-700"><i class="fa-solid fa-magnifying-glass me-1 text-red-500"></i> tables</h2>
          <div class="flex items-center space-x-2 text-gray-500">
            <button class="collapse-btn" title="Collapse"><i class="fa-solid fa-angle-down"></i></button>
            <button class="reload-btn" title="Reload"><i class="fa-solid fa-rotate-right"></i></button>
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
        fetch('<?= $path ?>@api/api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'get_tables' })
        })
        .then(res => res.json())
        .then(res => {
            this.items = res.data;
        });
    }
}">
    <input
        x-model="search"
        @input="open = search.trim().length > 0"
        type="text"
        placeholder="Search table..."
        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
    >

        <div>
            <template x-for="item in filteredItems" :key="item">
                <a
                    x-text="item"
                    :href="`<?= $path ?>s/content?e=${item}`"
                    class="block w-full px-4 py-2 mt-3 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg cursor-pointer dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                ></a>
            </template>
        </div>
</div>