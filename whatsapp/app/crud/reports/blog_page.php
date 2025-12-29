
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>blog_page Report</title>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { background: #f9fafb; font-family: system-ui, sans-serif; color: #111; }
section { padding: 2rem; }
table.dataTable { width: 100%; font-size: 0.9rem; }
h1 { font-size: 1.5rem; font-weight: 600; }
button { cursor: pointer; }
.chart-container { background: #fff; border-radius: 12px; padding: 1rem; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<section>
  <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
    <div>
      <h1>blog_page — Report</h1>
      <p style="color:#6b7280;font-size:0.9rem;">Auto-generated data report with charts & CSV export</p>
    </div>
    <button id="exportCSV" style="background:#2563EB;color:#fff;padding:0.5rem 1rem;border:none;border-radius:6px;">Export CSV</button>
  </header>

  <div style="background:#fff;border-radius:12px;padding:1rem;box-shadow:0 1px 3px rgba(0,0,0,0.08);overflow-x:auto;">
    <table id="reportTable" class="display">
      <thead>
      <tr>
        
                <th>blog_page_id</th>
                
                <th>view_search</th>
                
                <th>view_categories</th>
                
                <th>view_recent_blog</th>
                
                <th>recent_blog_number</th>
                
                <th>view_blog_tags</th>
                
                <th>custom_html</th>
                
                <th>img_after_recent_post</th>
                
                <th>img_after_tag</th>
                
                <th>Plain_Text_title</th>
                
                <th>Plain_Text_description</th>
                
                <th>blog_style</th>
                
                <th>pagination_style</th>
                
                <th>show_author</th>
                
                <th>show_author_img</th>
                
                <th>show_single_category</th>
                
                <th>title_limit</th>
                
                <th>description_limit</th>
                
                <th>cta_label</th>
                
                <th>go-to-page</th>
                
                <th>show-date</th>
                
                <th>show-pagination</th>
                
                <th>show-comment</th>
                
                <th>showCategoryIcon</th>
                
                <th>number_of_post</th>
                
                <th>created_at</th>
                
      </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(350px,1fr));gap:1.5rem;margin-top:2rem;">    <div class="chart-container">
      <h3 style="font-size:0.9rem;margin-bottom:0.5rem;">Records Created — last 12 months</h3>
      <canvas id="createdChart" height="120"></canvas>
    </div>    <div class="chart-container">
      <h3 style="font-size:0.9rem;margin-bottom:0.5rem;">Status Distribution</h3>
      <canvas id="statusChart" height="120"></canvas>
    </div>  </div>
</section>

<script>
$(function() {
  let records = [];
  const table = $('#reportTable').DataTable({
    ajax: {
      url: '<?= $path ?>app/crud/reports/api/blog_page.php?ajax=1',
      dataSrc: ''
    },
    columns: [
      
                { data: 'blog_page_id' },
                
                { data: 'view_search' },
                
                { data: 'view_categories' },
                
                { data: 'view_recent_blog' },
                
                { data: 'recent_blog_number' },
                
                { data: 'view_blog_tags' },
                
                { data: 'custom_html' },
                
                { data: 'img_after_recent_post' },
                
                { data: 'img_after_tag' },
                
                { data: 'Plain_Text_title' },
                
                { data: 'Plain_Text_description' },
                
                { data: 'blog_style' },
                
                { data: 'pagination_style' },
                
                { data: 'show_author' },
                
                { data: 'show_author_img' },
                
                { data: 'show_single_category' },
                
                { data: 'title_limit' },
                
                { data: 'description_limit' },
                
                { data: 'cta_label' },
                
                { data: 'go-to-page' },
                
                { data: 'show-date' },
                
                { data: 'show-pagination' },
                
                { data: 'show-comment' },
                
                { data: 'showCategoryIcon' },
                
                { data: 'number_of_post' },
                
                { data: 'created_at' },
                    ]
  });

  table.on('xhr.dt', function(e, settings, json, xhr) {
    records = json;
    updateCharts();
  });

  function updateCharts() {    // --- Status Distribution ---
    const statusCounts = {};
    records.forEach(r => statusCounts[r.status] = (statusCounts[r.status] || 0) + 1);
    if (window.statusChartInstance) window.statusChartInstance.destroy();
    window.statusChartInstance = new Chart($('#statusChart'), {
      type: 'pie',
      data: {
        labels: Object.keys(statusCounts),
        datasets: [{ data: Object.values(statusCounts), backgroundColor: ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6'] }]
      }
    });
    // --- Created Records per Month ---
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
      data: { labels: months, datasets: [{ label: 'Records Created', data: monthlyCounts, borderColor: '#2563EB', fill: false }] }
    });  }

  // --- CSV Export ---
  $('#exportCSV').on('click', function() {
    const data = table.rows({ search: 'applied' }).data().toArray();
    if (!data.length) return;
    const csv = [Object.keys(data[0]).join(',')]
      .concat(data.map(r => Object.values(r).join(',')))
      .join('\n');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = 'blog_page-report.csv'; a.click();
  });
});
</script>

</body>
</html>