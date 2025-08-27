<?php
session_start();
require 'config/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: modules/login.php'); exit; }
$user_name = $_SESSION['user_name'];

// Estadísticas
$tp = $conn->query("SELECT COUNT(*) as total_products FROM productos")->fetch_assoc()['total_products'];
$ts = $conn->query("SELECT IFNULL(SUM(total),0) as total_sales FROM ventas WHERE DATE(fecha)=CURDATE()")->fetch_assoc()['total_sales'];
$ls = $conn->query("SELECT COUNT(*) as low_stock FROM productos WHERE stock <= 5")->fetch_assoc()['low_stock'];
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard - Inventario</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="bg-slate-50 min-h-screen">

<?php include 'includes/header.php'; ?>

<main class="container mx-auto px-4 py-6">
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Sidebar -->
    <aside class="lg:col-span-1">
      <?php include 'includes/sidebar.php'; ?>
    </aside>

    <!-- Main -->
    <section class="lg:col-span-3 space-y-6">
      <div class="text-3xl font-bold text-slate-800 mb-4">Bienvenido, <?php echo htmlspecialchars($user_name); ?></div>

      <!-- Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
          <div class="bg-blue-100 text-blue-600 p-3 rounded-full text-2xl"><i class="bi bi-box"></i></div>
          <div>
            <h5 class="text-sm text-slate-500">Total productos</h5>
            <div class="text-2xl font-bold"><?php echo $tp; ?></div>
          </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
          <div class="bg-green-100 text-green-600 p-3 rounded-full text-2xl"><i class="bi bi-currency-dollar"></i></div>
          <div>
            <h5 class="text-sm text-slate-500">Ventas hoy</h5>
            <div class="text-2xl font-bold">$ <?php echo number_format($ts,2); ?></div>
          </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
          <div class="bg-red-100 text-red-600 p-3 rounded-full text-2xl"><i class="bi bi-exclamation-triangle"></i></div>
          <div>
            <h5 class="text-sm text-slate-500">Stock bajo (&le;5)</h5>
            <div class="text-2xl font-bold"><?php echo $ls; ?></div>
          </div>
        </div>
      </div>

      <!-- Ventas últimos 7 días -->
      <div class="bg-white p-5 rounded-xl shadow">
        <h5 class="mb-4 text-slate-700 font-semibold">Ventas últimos 7 días</h5>
        <div class="w-full h-80">
          <canvas id="salesChart"></canvas>
        </div>
      </div>

      <!-- Productos más vendidos y últimas ventas -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-xl shadow">
          <h5 class="mb-3 font-semibold text-slate-700">Productos más vendidos</h5>
          <ul class="list-disc pl-5 text-slate-600">
            <?php
              $res = $conn->query("SELECT p.nombre, SUM(d.cantidad) as total_sold
                                   FROM detalle_ventas d
                                   JOIN productos p ON p.id=d.producto_id
                                   GROUP BY p.id
                                   ORDER BY total_sold DESC
                                   LIMIT 5");
              while($row = $res->fetch_assoc()) echo "<li>{$row['nombre']} ({$row['total_sold']})</li>";
            ?>
          </ul>
        </div>
        <div class="bg-white p-5 rounded-xl shadow">
          <h5 class="mb-3 font-semibold text-slate-700">Últimas ventas</h5>
          <ul class="list-disc pl-5 text-slate-600">
            <?php
              $res = $conn->query("SELECT cliente,total,fecha FROM ventas ORDER BY fecha DESC LIMIT 5");
              while($row = $res->fetch_assoc()) echo "<li>{$row['cliente']} - $".number_format($row['total'],2)." - {$row['fecha']}</li>";
            ?>
          </ul>
        </div>
      </div>

    </section>
  </div>
</main>

<script>
fetch('modules/api_sales_last7.php')
.then(res => res.json())
.then(data => {
  const ctx = document.getElementById('salesChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: data.labels,
      datasets: [{
        label: 'Ventas',
        data: data.totals,
        backgroundColor: 'rgba(59,130,246,0.8)',
        hoverBackgroundColor: 'rgba(37,99,235,0.9)',
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      scales: { y: { beginAtZero: true } },
      plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } }
    }
  });
});
</script>

</body>
</html>
