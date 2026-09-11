# Dashboard de Inventario

Dashboard web para una bodega, desarrollado para organizar productos,
stock y valor del inventario en un solo panel.

## Tecnologías

- HTML, CSS y Bootstrap
- JavaScript
- PHP
- MySQL

## Funcionalidades

- Login protegido con sesiones PHP.
- Consulta de productos desde MySQL.
- Registro de nuevos productos mediante formulario.
- Indicadores de productos, unidades, stock bajo y valor del inventario.
- Diseño adaptable para computadora y celular.

## Instalación

1. Crear una base de datos MySQL.
2. Importar `database.sql` desde phpMyAdmin.
3. Copiar `config/database.example.php` como `config/database.php`.
4. Completar las credenciales de la base de datos en `config/database.php`.
5. Subir los archivos a un servidor con PHP y MySQL.

## Acceso de prueba

El usuario inicial se define en `database.sql`. En producción debe cambiarse
la contraseña después de la primera configuración.