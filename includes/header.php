<?php
// Calcular prefijo relativo según si el script actual está dentro de /modules
$__in_modules = (strpos($_SERVER['PHP_SELF'], '/modules/') !== false);
$__prefix = $__in_modules ? '../' : '';
?>
<!-- Header: uses Tailwind + Bootstrap CDN for layout -->
<nav class="bg-slate-800 text-white">
  <div class="container mx-auto px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <a href="<?php echo $__prefix; ?>index.php" class="text-xl font-semibold">Inventario</a>
    </div>
    <div class="flex items-center gap-3">
      <?php if(isset($_SESSION['user_name'])): ?>
        <span class="hidden sm:inline text-slate-200"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <a href="<?php echo $__prefix; ?>logout.php" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">Cerrar sesión</a>
      <?php else: ?>
        <a href="<?php echo $__prefix; ?>modules/login.php" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">Ingresar</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
