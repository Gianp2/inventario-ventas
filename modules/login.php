<?php
session_start();
require '../config/db.php';

// Auto-setup: crear admin por defecto si no existen usuarios
$check = $conn->query("SELECT COUNT(*) as c FROM usuarios")->fetch_assoc();
if (isset($check['c']) && intval($check['c']) === 0) {
    $nombre = 'Administrador';
    $email_seed = 'admin@demo.com';
    $pass_seed = password_hash('admin123', PASSWORD_BCRYPT);
    $rol = 'admin';
    $stmtSeed = $conn->prepare("INSERT INTO usuarios (nombre,email,password,rol) VALUES (?,?,?,?)");
    $stmtSeed->bind_param('ssss', $nombre, $email_seed, $pass_seed, $rol);
    $stmtSeed->execute();
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';
    $stmt = $conn->prepare('SELECT id,nombre,password,rol FROM usuarios WHERE email=? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id']=$row['id'];
            $_SESSION['user_name']=$row['nombre'];
            $_SESSION['user_rol']=$row['rol'];
            header('Location: ../index.php');
            exit;
        } else { $err='Credenciales inválidas'; }
    } else { $err='Credenciales inválidas'; }
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login - Inventario</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-50 flex items-center">
  <div class="container mx-auto px-4">
    <div class="max-w-md mx-auto">
      <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-3">Iniciar sesión</h2>
        <?php if($err): ?><div class="alert alert-danger"><?php echo $err; ?></div><?php endif; ?>
        <form method="post" class="space-y-3">
          <div>
            <label class="block text-sm">Email</label>
            <input name="email" required class="w-full border rounded px-3 py-2">
          </div>
          <div>
            <label class="block text-sm">Contraseña</label>
            <input type="password" name="password" required class="w-full border rounded px-3 py-2">
          </div>
          <div class="flex justify-between items-center">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Ingresar</button>
            <small class="text-slate-500">Usuario: <strong>admin@demo.com</strong> / <strong>admin123</strong></small>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
