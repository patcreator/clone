<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>user_activity Report</title>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
:root {
  --bg-color: #f9fafb;
  --text-color: #111;
  --card-bg: #fff;
  --table-bg: #fff;
  --table-header-bg: #f3f4f6;
  --button-bg: #2563EB;
  --button-color: #fff;
  --subtext-color: #6b7280;
}

@media (prefers-color-scheme: dark) {
  :root {
    --bg-color: #1f2937;
    --text-color: #f9fafb;
    --card-bg: #111827;
    --table-bg: #1f2937;
    --table-header-bg: #374151;
    --button-bg: #3B82F6;
    --button-color: #fff;
    --subtext-color: #9ca3af;
  }
}

body { background: var(--bg-color); font-family: system-ui, sans-serif; color: var(--text-color); }
section { padding: 2rem; }
table.dataTable { width: 100%; font-size: 0.9rem; background: var(--table-bg); color: var(--text-color); }
table.dataTable thead { background: var(--table-header-bg); color: var(--text-color); }
h1 { font-size: 1.5rem; font-weight: 600; }
button { cursor: pointer; background: var(--button-bg); color: var(--button-color); padding:0.5rem 1rem; border:none; border-radius:6px; }
button:hover { opacity: 0.9; }
.chart-container { background: var(--card-bg); border-radius: 12px; padding: 1rem; box-shadow: 0 1px 4px rgba(0,0,0,0.1); color: var(--text-color); }
.dataTables_wrapper .dataTables_paginate .paginate_button { color: var(--text-color) !important; }
.dataTables_wrapper .dataTables_filter input { background: var(--table-bg); color: var(--text-color); border: 1px solid #ccc; }
.dataTables_wrapper .dataTables_length select { background: var(--table-bg); color: var(--text-color); border: 1px solid #ccc; }
</style>
</head>
<body>

<section>
  <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
    <div>
      <h1>user_activity — Report</h1>
      <p style="color:var(--subtext-color);font-size:0.9rem;">Auto-generated data report with charts & CSV export</p>
    </div>
    <button id="exportCSV">Export CSV</button>
  </header>

  <div>
    <table id="reportTable" class="display">
      <thead>
      <tr>
        <th>id</th><th>user_id</th><th>activity_type</th><th>description</th><th>ip_address</th><th>created_at</th><th>updated_at</th><th>status</th>
      </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(350px,1fr));gap:1.5rem;margin-top:2rem;">
    <div class="chart-container">
      <h3 style="font-size:0.9rem;margin-bottom:0.5rem;">Records Created — last 12 months</h3>
      <canvas id="createdChart" height="120"></canvas>
    </div>
    <div class="chart-container">
      <h3 style="font-size:0.9rem;margin-bottom:0.5rem;">Status Distribution</h3>
      <canvas id="statusChart" height="120"></canvas>
    </div>
  </div>
</section>

<script>
$(function() {
  let records = [];
  const table = $('#reportTable').DataTable({
    ajax: {
      url: '<?= $path ?>app/crud/reports/api/user_activity.php?ajax=1',
      dataSrc: ''
    },
    columns: [
      
            { data: 'id' },
        
            { data: 'user_id' },
        
            { data: 'activity_type' },
        
            { data: 'description' },
        
            { data: 'ip_address' },
        
            { data: 'created_at' },
        
            { data: 'updated_at' },
        
            { data: 'status' },
        
    ],
    autoWidth: false,
    responsive: true
  });

  table.on('xhr.dt', function(e, settings, json) {
    records = json;
    updateCharts();
  });

  function updateCharts() {
    // Status Distribution
    const statusCounts = {};
    records.forEach(r => statusCounts[r.Status] = (statusCounts[r.Status] || 0) + 1);

    if (window.statusChartInstance) window.statusChartInstance.destroy();
    window.statusChartInstance = new Chart($('#statusChart'), {
      type: 'pie',
      data: {
        labels: Object.keys(statusCounts),
        datasets: [{
          data: Object.values(statusCounts),
          backgroundColor: ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6']
        }]
      },
      options: { plugins: { legend: { labels: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-color') } } } }
    });

    // Created Records per Month
    const months = [...Array(12).keys()].map(i => {
      const d = new Date(); d.setMonth(d.getMonth() - i);
      return d.toLocaleString('default', { month: 'short', year: 'numeric' });
    }).reverse();

    const monthlyCounts = months.map(m => records.filter(r => {
      const date = new Date(r.created_at);
      const label = date.toLocaleString('default', { month: 'short', year: 'numeric' });
      return label === m;
    }).length);

    if (window.createdChartInstance) window.createdChartInstance.destroy();
    window.createdChartInstance = new Chart($('#createdChart'), {
      type: 'line',
      data: { labels: months, datasets: [{ label: 'Records Created', data: monthlyCounts, borderColor: '#2563EB', backgroundColor: 'transparent' }] },
      options: {
        plugins: { legend: { labels: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-color') } } },
        scales: {
          x: { ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-color') } },
          y: { ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-color') } }
        }
      }
    });
  }

  // CSV Export
  $('#exportCSV').on('click', function() {
    const data = table.rows({ search: 'applied' }).data().toArray();
    if (!data.length) return;
    const csv = [Object.keys(data[0]).join(',')]
      .concat(data.map(r => Object.values(r).join(',')))
      .join('
');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = 'user_activity-report.csv'; a.click();
  });
});
</script>

</body>
</html>
