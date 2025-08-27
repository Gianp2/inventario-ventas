<?php
$__in_modules = (strpos($_SERVER['PHP_SELF'], '/modules/') !== false);
$__prefix = $__in_modules ? '../' : '';
$current = basename($_SERVER['PHP_SELF']);
function is_active($name){ global $current; return $current === $name ? 'bg-blue-100 font-semibold' : ''; }
?>
<nav class="bg-white rounded-xl shadow p-4 h-full sticky top-4">
  <ul class="space-y-2">
    <li>
      <a href="<?php echo $__prefix; ?>index.php" class="flex items-center gap-3 p-2 rounded <?php echo is_active('index.php'); ?>">
        <i class="bi bi-speedometer2 text-lg"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li>
      <a href="<?php echo $__prefix; ?>modules/productos.php" class="flex items-center gap-3 p-2 rounded <?php echo is_active('productos.php'); ?>">
        <i class="bi bi-box-seam text-lg"></i>
        <span>Productos</span>
      </a>
    </li>
    <li>
      <a href="<?php echo $__prefix; ?>modules/ventas.php" class="flex items-center gap-3 p-2 rounded <?php echo is_active('ventas.php'); ?>">
        <i class="bi bi-cash-coin text-lg"></i>
        <span>Ventas</span>
      </a>
    </li>
    <li>
      <a href="<?php echo $__prefix; ?>modules/reportes.php" class="flex items-center gap-3 p-2 rounded <?php echo is_active('reportes.php'); ?>">
        <i class="bi bi-graph-up text-lg"></i>
        <span>Reportes</span>
      </a>
    </li>
    <li>
      <a href="<?php echo $__prefix; ?>modules/usuarios.php" class="flex items-center gap-3 p-2 rounded <?php echo is_active('usuarios.php'); ?>">
        <i class="bi bi-people text-lg"></i>
        <span>Usuarios</span>
      </a>
    </li>
  </ul>
</nav>
