<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$msg = '';

// Eliminar producto
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM productos WHERE id=$id");
    header("Location: productos.php"); exit;
}

// Actualizar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']=='edit') {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $desc = $conn->real_escape_string($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $cat = $conn->real_escape_string($_POST['categoria']);
    $imagen = null;

    if (!empty($_FILES['imagen']['name'])) {
        $updir = __DIR__ . '/../assets/img/uploads/';
        if (!is_dir($updir)) mkdir($updir, 0755, true);
        $filename = time() . '_' . basename($_FILES['imagen']['name']);
        $target = $updir . $filename;
        if(move_uploaded_file($_FILES['imagen']['tmp_name'], $target)){
            $imagen = 'assets/img/uploads/' . $filename;
            $imagen = $conn->real_escape_string($imagen);
            $conn->query("UPDATE productos SET imagen='$imagen' WHERE id=$id");
        }
    }

    $conn->query("UPDATE productos SET nombre='$nombre', descripcion='$desc', precio=$precio, stock=$stock, categoria='$cat' WHERE id=$id");
    header("Location: productos.php"); exit;
}

// Crear producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']=='add') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $desc = $conn->real_escape_string($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $cat = $conn->real_escape_string($_POST['categoria']);
    $imagen = null;

    if (!empty($_FILES['imagen']['name'])) {
        $updir = __DIR__ . '/../assets/img/uploads/';
        if (!is_dir($updir)) mkdir($updir, 0755, true);
        $filename = time() . '_' . basename($_FILES['imagen']['name']);
        $target = $updir . $filename;
        if(move_uploaded_file($_FILES['imagen']['tmp_name'], $target)){
            $imagen = 'assets/img/uploads/' . $filename;
            $imagen = $conn->real_escape_string($imagen);
        }
    }

    $query = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria".($imagen?', imagen':'').")
              VALUES ('$nombre','$desc',$precio,$stock,'$cat'".($imagen?",'$imagen'":'').")";
    if($conn->query($query)){
        header('Location: productos.php'); exit;
    } else {
        $msg = "Error al guardar producto: " . $conn->error;
    }
}

// Obtener productos
$products = $conn->query('SELECT * FROM productos ORDER BY creado_en DESC');
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Productos</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen">

<?php include '../includes/header.php'; ?>

<div class="container mx-auto px-4 py-6">
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
    <aside class="lg:col-span-1">
      <?php include '../includes/sidebar.php'; ?>
    </aside>
    <main class="lg:col-span-3">

      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Productos</h2>
        <button id="toggleForm" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
          Agregar producto
        </button>
      </div>

      <?php if($msg): ?>
        <div class="bg-red-100 text-red-700 px-3 py-2 rounded mb-4"><?php echo $msg; ?></div>
      <?php endif; ?>

      <!-- Formulario oculto con Tailwind -->
      <div id="addForm" class="hidden mb-4">
        <div class="bg-white p-4 rounded shadow">
          <form method="post" enctype="multipart/form-data" class="space-y-3">
            <input type="hidden" name="action" value="add">
            <div>
              <label class="block text-sm font-medium mb-1">Nombre</label>
              <input name="nombre" class="w-full border rounded px-2 py-1" required>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Descripción</label>
              <textarea name="descripcion" class="w-full border rounded px-2 py-1"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
              <div>
                <label class="block text-sm font-medium mb-1">Precio</label>
                <input type="number" step="0.01" name="precio" class="w-full border rounded px-2 py-1" required>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Stock</label>
                <input type="number" name="stock" class="w-full border rounded px-2 py-1" required>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Categoría</label>
                <input name="categoria" class="w-full border rounded px-2 py-1">
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Imagen</label>
              <input type="file" name="imagen" class="w-full">
            </div>
            <div>
              <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Guardar</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Grid de productos -->
      <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
        <?php while($p = $products->fetch_assoc()): ?>
          <div class="bg-white rounded shadow p-4 flex flex-col items-center text-center">
            <?php if($p['imagen']): ?>
              <img src="../<?php echo $p['imagen']; ?>" class="w-32 h-32 object-cover rounded mb-3">
            <?php else: ?>
              <div class="w-32 h-32 bg-gray-200 flex items-center justify-center rounded mb-3 text-gray-500">Sin imagen</div>
            <?php endif; ?>
            <h3 class="font-semibold"><?php echo htmlspecialchars($p['nombre']); ?></h3>
            <p class="text-sm text-gray-500 mb-2"><?php echo htmlspecialchars($p['descripcion']); ?></p>
            <p class="font-bold text-blue-600">$ <?php echo number_format($p['precio'],2); ?></p>
            <p class="text-sm text-gray-600">Stock: <?php echo $p['stock']; ?> | Cat: <?php echo htmlspecialchars($p['categoria']); ?></p>
            <div class="mt-3 flex gap-2">
              <button onclick="openEditModal(<?php echo $p['id']; ?>,'<?php echo htmlspecialchars($p['nombre']); ?>','<?php echo htmlspecialchars($p['descripcion']); ?>','<?php echo $p['precio']; ?>','<?php echo $p['stock']; ?>','<?php echo htmlspecialchars($p['categoria']); ?>')" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Editar</button>
              <a href="productos.php?delete=<?php echo $p['id']; ?>" onclick="return confirm('¿Seguro que quieres eliminar este producto?')" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>

    </main>
  </div>
</div>

<!-- Modal edición -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
  <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
    <h3 class="text-lg font-semibold mb-3">Editar producto</h3>
    <form method="post" enctype="multipart/form-data" class="space-y-2">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" id="edit_id">
      <div>
        <label class="block text-sm">Nombre</label>
        <input name="nombre" id="edit_nombre" class="w-full border rounded px-2 py-1">
      </div>
      <div>
        <label class="block text-sm">Descripción</label>
        <textarea name="descripcion" id="edit_descripcion" class="w-full border rounded px-2 py-1"></textarea>
      </div>
      <div class="grid grid-cols-3 gap-2">
        <div>
          <label class="block text-sm">Precio</label>
          <input type="number" step="0.01" name="precio" id="edit_precio" class="w-full border rounded px-2 py-1">
        </div>
        <div>
          <label class="block text-sm">Stock</label>
          <input type="number" name="stock" id="edit_stock" class="w-full border rounded px-2 py-1">
        </div>
        <div>
          <label class="block text-sm">Categoría</label>
          <input name="categoria" id="edit_categoria" class="w-full border rounded px-2 py-1">
        </div>
      </div>
      <div>
        <label class="block text-sm">Imagen</label>
        <input type="file" name="imagen" class="w-full">
      </div>
      <div class="flex justify-end gap-2 mt-3">
        <button type="button" onclick="closeEditModal()" class="bg-gray-400 px-3 py-1 rounded">Cancelar</button>
        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Guardar</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.getElementById("toggleForm").addEventListener("click", () => {
    document.getElementById("addForm").classList.toggle("hidden");
  });

  function openEditModal(id,nombre,desc,precio,stock,categoria){
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_descripcion').value = desc;
    document.getElementById('edit_precio').value = precio;
    document.getElementById('edit_stock').value = stock;
    document.getElementById('edit_categoria').value = categoria;
    document.getElementById('editModal').classList.remove('hidden');
  }
  function closeEditModal(){
    document.getElementById('editModal').classList.add('hidden');
  }
</script>

</body>
</html>
