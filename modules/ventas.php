<?php
session_start();
require __DIR__ . '/../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit; 
}
$msg='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $conn->real_escape_string($_POST['cliente']);
    $items = json_decode($_POST['items'], true);
    $total = 0.0;

    foreach ($items as $it) {
        $res = $conn->query('SELECT precio,stock FROM productos WHERE id=' . intval($it['producto_id']));
        if ($row = $res->fetch_assoc()) {
            $total += floatval($row['precio']) * intval($it['cantidad']);
        }
    }

    $stmt = $conn->prepare('INSERT INTO ventas (usuario_id,cliente,total) VALUES (?,?,?)');
    $uid = $_SESSION['user_id'];
    $stmt->bind_param('isd',$uid,$cliente,$total);
    $stmt->execute();
    $venta_id = $stmt->insert_id;

    foreach ($items as $it) {
        $pid = intval($it['producto_id']);
        $cant = intval($it['cantidad']);
        $res = $conn->query('SELECT precio,stock FROM productos WHERE id=' . $pid);
        if ($row = $res->fetch_assoc()) {
            $precio = floatval($row['precio']);
            $conn->query("INSERT INTO detalle_ventas (venta_id,producto_id,cantidad,precio_unitario) VALUES ($venta_id,$pid,$cant,$precio)");
            $conn->query('UPDATE productos SET stock = stock - ' . $cant . ' WHERE id=' . $pid);
        }
    }

    header('Location: ' . dirname($_SERVER['PHP_SELF']) . '/venta_exito.php?id=' . $venta_id);
    exit;
}

$productos = $conn->query('SELECT id,nombre,precio,stock FROM productos WHERE stock>0');
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Registrar Venta</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-slate-50 min-h-screen">

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mx-auto px-4 py-6">
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <aside class="lg:col-span-1"><?php include __DIR__ . '/../includes/sidebar.php'; ?></aside>
    <main class="lg:col-span-3 bg-white p-6 rounded-xl shadow space-y-4">
      <h2 class="text-2xl font-bold text-slate-800 mb-4">Registrar Venta</h2>

      <form id="saleForm" method="post">
        <div class="mb-3">
          <label class="block text-sm font-medium text-slate-600">Cliente</label>
          <input name="cliente" class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <h3 class="text-lg font-semibold mb-2">Productos</h3>
        <div id="items" class="space-y-2">
          <div class="flex gap-2 items-center">
            <select class="form-select product-select flex-1">
              <?php while($p=$productos->fetch_assoc()): ?>
                <option value="<?php echo $p['id']; ?>" data-precio="<?php echo $p['precio']; ?>">
                  <?php echo htmlspecialchars($p['nombre']); ?> (stock: <?php echo $p['stock']; ?>)
                </option>
              <?php endwhile; ?>
            </select>
            <input type="number" class="form-control qty w-24" value="1" min="1" style="max-inline-size:100px;">
            <button type="button" class="btn btn-danger btn-sm remove"><i class="bi bi-dash"></i></button>
          </div>
        </div>

        <button type="button" id="addItem" class="btn btn-secondary btn-sm mt-2"><i class="bi bi-plus"></i> Agregar otro</button>

        <div class="mt-4">
          <strong>Total: $<span id="totalVal">0.00</span></strong>
        </div>

        <input type="hidden" name="items" id="itemsInput">
        <button type="submit" class="btn btn-primary mt-4">Registrar Venta</button>
      </form>
    </main>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
function recalc() {
  let total = 0;
  $('#items .flex').each(function() {
    const sel = $(this).find('select');
    const qty = parseInt($(this).find('.qty').val()) || 0;
    const price = parseFloat(sel.find('option:selected').data('precio')) || 0;
    total += price * qty;
  });
  $('#totalVal').text(total.toFixed(2));
}

$(document).on('click', '.remove', function() {
  if ($('#items .flex').length > 1) $(this).closest('.flex').remove();
  recalc();
});

$('#addItem').click(function() {
  let html = $('#items .flex:first').clone();
  html.find('.qty').val(1);
  $('#items').append(html);
  recalc();
});

$(document).on('change', '.product-select, .qty', recalc);
recalc();

$('#saleForm').submit(function(e) {
  e.preventDefault();
  let items = [];
  $('#items .flex').each(function() {
    items.push({
      producto_id: $(this).find('select').val(),
      cantidad: $(this).find('.qty').val()
    });
  });
  $('#itemsInput').val(JSON.stringify(items));
  this.submit();
});
</script>
</body>
</html>
