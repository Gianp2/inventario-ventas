# 📦 Sistema de Inventario y Ventas

Aplicación web para la gestión de productos, inventario y ventas, desarrollada en **PHP**, **MySQL** y **Bootstrap + TailwindCSS**.

---

## 🚀 Características

- 🔐 Sistema de login y autenticación de usuarios  
- 📊 Panel de administración con productos, stock y precios  
- ➕ Agregar productos con imagen  
- ✏️ Editar y eliminar productos  
- ❌ Eliminar productos fácilmente  
- 🖼️ Visualización de productos con diseño moderno  
- 🎠 Carrusel de imágenes para destacar productos o promociones  
- 📂 Estructura modular (separación por `modules/`, `includes/`, `config/`)

---

## ⚙️ Requisitos

- XAMPP (PHP + Apache + MySQL)  
- Composer (opcional, si usás librerías adicionales)  
- Git para clonar el repositorio  

---


## 🎠 Carrusel de imágenes

html
<div id="carouselProductos" class="carousel slide mb-5" data-bs-ride="carousel">
  <div class="carousel-inner rounded shadow">
    <div class="carousel-item active">
      <img src="imagen1.png" class="d-block w-100" alt="Producto 1">
    </div>
    <div class="carousel-item">
      <img src="imagen2.png" class="d-block w-100" alt="Producto 2">
    </div>
    <div class="carousel-item">
      <img src="imagen3.png" class="d-block w-100" alt="Producto 3">
    </div>
    <div class="carousel-item">
      < booking src="imagen4.png" class="d-block w-100" alt="Producto 4">
    </div>
    <div class="carousel-item">
      <img src="imagen5.png" class="d-block w-100" alt="Producto 5">
    </div>
    <div class="carousel-item">
      <img src="imagen6.png" class="d-block w-100" alt="Producto 6">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselProductos" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselProductos" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>
