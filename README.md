# StockCenter

"StockCenter" es un soft básico en PHP para llevar stock de productos, es un proyecto viejo que quiero compartir con posibilidad de que cualquiera pueda modificarlo a su gusto.

<br>

## Frameworks
* Bootstrap v4.2.1 - https://getbootstrap.com/
* Font Awesome v5.5.0 - https://fontawesome.com
* Animate.css v3.7.0 - https://daneden.me/animate
* Particles.js v2.0.0 - https://vincentgarreau.com
* FancyBox v3.5.7 - https://fancyapps.com/fancybox/

<br>

## Contenido
El soft incluye las siguientes funciones:
* Agregar o eliminar usuarios.
* Usuarios con diferentes permisos.
* Agergar/modiificar o eliminar distintos productos (se pueden subir fotos).
* Generar factura "X" [^1].
* Listado de facturas generadas.
* Posibilidad de agregar diferentes clientes.
* Registro de todo lo que pasa en el sistema, desde login hasta cuando se modificó un producto.

<br>

## Permisos de usuario
* <ins>Admin</ins>: Agregar o eliminar usuarios y acceso a los registros del sistema. (incluye permisos de Empleado y Manager).
* <ins>Empleado</ins>: puede generar presupuestos y ver el listado de presupuestos generados (incluye permisos de Manager).
* <ins>Manager</ins>: Agregar/modificar o eliminar stock.

<br>

## Instalación
* Clonar el repositorio en un servidor con Apache y PHP 7 al menos.
* Importar "stock_center.sql" a la base de datos.
* Modificar los datos del archivo "/scripts/PHP/settings.php" para los que correspondan en su caso (base de datos y google recaptcha, la clave de encriptación es a su gusto).
* Abrir el archivo "createFirstUser.php", poner el nombre y contraseña del usuario admin que desee y ejecutarlo para que se cree un usuario "ADMIN" en la base de datos.
* Elminar el archivo "createFirstUser.php".

<br>

[^1]: El sistema **no** está conectado a AFIP ni tampoco ofrece algún tipo de conexión, por ende las facturas "X" realizadas <ins>no tienen ningún tipo de validez fiscal</ins>.
