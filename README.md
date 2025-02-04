<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Proyecto API de Gestión de Ventas

Este es un sistema para gestionar productos, clientes y ventas, así como generar reportes de ventas y productos más vendidos. Está construido utilizando Laravel, una estructura PHP moderna, con autenticación basada en Sanctum.

## Dependencias Importantes

- **Laravel 8.x o superior**: Framework PHP utilizado para construir la aplicación.
- **Sanctum**: Paquete para autenticación de API.
- **Swagger**: Para documentación de los endpoints de la API.
- **PHP 7.4 o superior**: Requisito mínimo de versión de PHP.
- **MySQL**: Base de datos utilizada para almacenar la información.

## Instalación

1. Clona este repositorio en tu máquina local:
   ```
   git clone https://github.com/tuusuario/proyecto.git
   ```
2. Accede al directorio del proyecto:
   ```
   cd proyecto
   ```
3. Instala las dependencias de Composer:
   ```
   composer install
   ```
4. Configura el archivo `.env` con los datos de tu base de datos:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nombre_base_datos
   DB_USERNAME=usuario
   DB_PASSWORD=contraseña
   ```
5. Ejecuta las migraciones y seeding de la base de datos:
   ```
   php artisan migrate --seed
   ```

## Funcionalidades

### Autenticación y Seguridad
- **Registro de usuarios** (`POST /register`): Permite crear un nuevo usuario.
- **Inicio de sesión** (`POST /login`): Permite que los usuarios inicien sesión utilizando Sanctum.
- **Cierre de sesión** (`GET /logout`): Permite que los usuarios cierren sesión.

### Gestión de Productos
- **Listado de productos** (`GET /products`): Obtiene todos los productos disponibles.
- **Crear producto** (`POST /products/crear`): Permite crear un nuevo producto.
- **Actualizar producto** (`PUT /products/actualizar/{id}`): Permite actualizar la información de un producto.
- **Buscar producto** (`GET /products/buscar/{id}`): Busca un producto por su ID.
- **Eliminar producto** (`DELETE /products/borrar/{id}`): Permite eliminar un producto.

### Gestión de Ventas
- **Listado de ventas** (`GET /ventas`): Obtiene todas las ventas realizadas.
- **Crear venta** (`POST /ventas/crear`): Permite crear una nueva venta.
- **Detalle de venta** (`GET /ventas/{id}/detalle`): Obtiene el detalle de una venta por ID.
- **Buscar venta** (`GET /ventas/{id}`): Obtiene información de una venta específica.

### Gestión de Clientes
- **Listado de clientes** (`GET /clients`): Obtiene todos los clientes.
- **Crear cliente** (`POST /clients/crear`): Permite crear un nuevo cliente.
- **Actualizar cliente** (`PUT /clients/actualizar/{id}`): Permite actualizar la información de un cliente.
- **Buscar cliente** (`GET /clients/buscar/{id}`): Busca un cliente por ID.
- **Eliminar cliente** (`DELETE /clients/borrar/{id}`): Permite eliminar un cliente.

### Reportes
- **Productos más vendidos** (`GET /reportes/product-top`): Obtiene un reporte de los productos más vendidos.
- **Reporte de ventas** (`GET /reportes/sales-report`): Obtiene un reporte con las ventas realizadas.

## Validaciones

- Los **roles** de usuario se gestionan mediante middleware y restricciones por rutas. Solo los administradores tienen acceso a ciertas funcionalidades como la creación y eliminación de productos o clientes.
- Se utiliza la validación estándar de Laravel para asegurar que todos los datos necesarios estén presentes en las solicitudes.

## Endpoints

1. **Registro**:
   - Método: `POST`
   - Ruta: `/register`
   - Parámetros:
     - `name`: Nombre del usuario.
     - `email`: Correo electrónico.
     - `password`: Contraseña.

2. **Inicio de sesión**:
   - Método: `POST`
   - Ruta: `/login`
   - Parámetros:
     - `email`: Correo electrónico.
     - `password`: Contraseña.

3. **Listado de productos**:
   - Método: `GET`
   - Ruta: `/products`

4. **Crear producto**:
   - Método: `POST`
   - Ruta: `/products/crear`
   - Parámetros:
     - `name`: Nombre del producto.
     - `price`: Precio del producto.
     - `sku`: SKU del producto.

5. **Reporte de productos más vendidos**:
   - Método: `GET`
   - Ruta: `/reportes/product-top`
   - Parámetros opcionales:
     - `start_date`: Fecha de inicio.
     - `end_date`: Fecha de fin.

## Contribuciones

Si deseas contribuir a este proyecto, sigue estos pasos:

1. Realiza un fork del repositorio.
2. Crea una nueva rama (`git checkout -b feature-nueva-funcionalidad`).
3. Realiza los cambios y haz commit (`git commit -am 'Agrega nueva funcionalidad'`).
4. Empuja tus cambios (`git push origin feature-nueva-funcionalidad`).
5. Abre un pull request.

