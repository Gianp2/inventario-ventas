<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol']!='admin') { header('Location: login.php'); exit; }
$users = $conn->query('SELECT id,nombre,email,rol,creado_en FROM usuarios ORDER BY creado_en DESC');
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Usuarios</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-slate-50 min-h-screen"><?php include '../includes/header.php'; ?>
<div class="container mx-auto px-4 py-6"><div class="grid grid-cols-1 lg:grid-cols-4 gap-4"><aside class="lg:col-span-1"><?php include '../includes/sidebar.php'; ?></aside><main class="lg:col-span-3 bg-white p-4 rounded shadow">
<h4>Usuarios</h4>
<table class="table"><thead><tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Creado</th></tr></thead><tbody>
<?php while($u=$users->fetch_assoc()): ?>
<tr><td><?php echo htmlspecialchars($u['nombre']); ?></td><td><?php echo htmlspecialchars($u['email']); ?></td><td><?php echo $u['rol']; ?></td><td><?php echo $u['creado_en']; ?></td></tr>
<?php endwhile; ?>
</tbody></table>
</main></div></div></body></html>
