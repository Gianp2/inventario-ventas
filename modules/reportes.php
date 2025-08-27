<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Reportes</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-slate-50 min-h-screen"><?php include '../includes/header.php'; ?>
<div class="container mx-auto px-4 py-6"><div class="grid grid-cols-1 lg:grid-cols-4 gap-4"><aside class="lg:col-span-1"><?php include '../includes/sidebar.php'; ?></aside><main class="lg:col-span-3 bg-white p-4 rounded shadow">
<h4 class="mb-3">Reportes</h4>
<canvas id="salesChart" height="120"></canvas>
</main></div></div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
fetch('api_sales_last7.php').then(r=>r.json()).then(data=>{
  const ctx = document.getElementById('salesChart').getContext('2d');
  new Chart(ctx,{type:'line',data:{labels:data.labels,datasets:[{label:'Ventas',data:data.totals, borderColor:'rgba(59,130,246,1)', tension:0.3, pointRadius:4}]}, options:{responsive:true}});
});
</script>
</body></html>
