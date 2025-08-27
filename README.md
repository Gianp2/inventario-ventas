# Sistema Inventario - XAMPP (versión mejorada)

## Qué cambié en esta versión
- Interfaz modernizada y responsiva usando **Tailwind CDN** y **Bootstrap CDN**.
- Librerías reales cargadas vía CDN: **Tailwind**, **Bootstrap 5**, **jQuery 3.7**, **Chart.js 4**.
- Mejor estructura visual: dashboard con tarjetas, tablas con imágenes, formularios limpios, sidebar responsivo.
- Se mantuvo la funcionalidad mínima: login, CRUD básico de productos (agregar), registro de ventas, reportes, usuarios.
- Archivos en `vendor/` dejan de ser necesarios (se usan CDNs).

## Instalación rápida
1. Copiar la carpeta `inventario-ventas` a `C:\xampp\htdocs\` (o la ruta equivalente en tu sistema).
2. Iniciar Apache y MySQL desde XAMPP.
3. Abrir en el navegador: `http://localhost/inventario-ventas`
4. El sistema creará las tablas necesarias automáticamente en la base de datos `sistema_inventario` si no existen.
5. Usuario por defecto:
   - Email: admin@demo.com
   - Contraseña: admin123

## Siguientes mejoras posibles
- Incluir archivos locales de Bootstrap/Chart.js si necesitás un paquete offline (puedo agregarlos al ZIP).
- Agregar edición/eliminación de productos, gestión de clientes/proveedores, generación de PDF con FPDF/TCPDF.
- Mejorar autenticación (CSRF tokens, validaciones) y sanitización adicional.

Si querés que también incluya las librerías **localmente en el ZIP** (sin depender de CDN), lo hago en la próxima versión y te lo subo listo para usar sin internet.
