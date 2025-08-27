<?php
require __DIR__ . '/../config/db.php';
$id = intval($_GET['id'] ?? 0);
$venta = $conn->query('SELECT * FROM ventas WHERE id=' . $id)->fetch_assoc();

if (!$venta) {
    echo "Venta no encontrada.";
    exit;
}

$det = $conn->query('SELECT dv.*, p.nombre FROM detalle_ventas dv LEFT JOIN productos p ON p.id=dv.producto_id WHERE dv.venta_id=' . $id);
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Venta registrada</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-slate-50 min-h-screen">

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mx-auto px-4 py-6">
  <div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-3">Venta registrada - ID <?php echo $venta['id']; ?></h2>
    <p class="mb-4 text-slate-600">Cliente: <?php echo htmlspecialchars($venta['cliente']); ?> | Fecha: <?php echo $venta['fecha']; ?></p>

    <div class="overflow-x-auto">
      <table class="table table-striped table-bordered">
        <thead class="table-light">
          <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio unit.</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php while($r=$det->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($r['nombre']); ?></td>
            <td><?php echo $r['cantidad']; ?></td>
            <td>$ <?php echo number_format($r['precio_unitario'],2); ?></td>
            <td>$ <?php echo number_format($r['precio_unitario']*$r['cantidad'],2); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="3" class="text-end">Total:</th>
            <th>$ <?php echo number_format($venta['total'],2); ?></th>
          </tr>
        </tfoot>
      </table>
    </div>

    <a class="btn btn-primary mt-4" href="<?php echo dirname(dirname($_SERVER['PHP_SELF'])); ?>/index.php">
      <i class="bi bi-house-door"></i> Ir al panel
    </a>
  </div>
</div>

</body>
</html>
