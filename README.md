# Papiro

Papiro es una tienda online de papelería desarrollada como proyecto final.  
El sistema permite consultar productos, registrarse, gestionar direcciones, usar un carrito de compra, realizar pedidos y administrar el catálogo y los pedidos desde un panel interno.

---

## Resumen del proyecto

El proyecto está dividido en dos partes principales:

- **Backend** desarrollado con **Laravel**, encargado de la lógica de negocio, acceso a base de datos, autenticación, gestión de pedidos y generación de facturas en PDF.
- **Frontend** desarrollado con **Angular**, centrado en la experiencia de usuario y en la interacción con la API.

El backend expone una **API REST** que permite trabajar con clientes, direcciones, carrito, pedidos, administración de productos, gestión de usuarios y generación de facturas.

---

## Tecnologías utilizadas

### Backend
- Laravel
- PHP
- MySQL
- Docker
- Laravel Sanctum
- DomPDF

### Frontend
- Angular
- TypeScript
- HTML
- CSS / Tailwind CSS

---

## Funcionalidades principales

### Zona pública
- Listado de productos
- Detalle de producto
- Listado de categorías
- Filtros y búsqueda

### Zona cliente
- Registro e inicio de sesión
- Gestión de direcciones
- Gestión de carrito
- Creación de pedidos
- Consulta de pedidos
- Descarga de factura en PDF

### Zona administración
- Gestión de productos
- Gestión de pedidos
- Cambio de estado de pedidos
- Gestión de usuarios
- Creación de administradores por parte del superadministrador

---

## Instalación y puesta en marcha

### 1. Clonar el repositorio

```bash
git clone <URL_DEL_REPOSITORIO>
cd papiro-back
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Configurar el archivo .env
Crear una copia del archivo de ejemplo:

```bash
cp .env.example .env
```
Después, configurar los datos de la base de datos.
Ejemplo:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=papiro
DB_USERNAME=papiro_user
DB_PASSWORD=papiro_pass
```
También se recomienda:

```bash
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
APP_TIMEZONE=Europe/Madrid
```

### 4. Levantar la base de datos con Docker

```bash
docker compose up -d
```

### 5. Generar clave de aplicación
```bash
php artisan key:generate
```

### 6. Ejecutar migraciones y seeders
```bash
php artisan migrate:fresh --seed
```

### 7. Crear enlace simbólico para los archivos públicos
```bash
php artisan storage:link
```

### 8. Iniciar el servidor
```bash
php artisan serve
```

El backend quedará disponible en:

```bash
http://127.0.0.1:8000
```

# Usuarios de prueba
## Cliente
Email: lucia@email.com
Contraseña: 12345678

## Administrador
Email: admin@papiro.com
Contraseña: admin1234

### Superadministrador
Email: superadmin@papiro.com
Contraseña: super1234

# Endpoints principales
## Catálogo público
`GET /api/productos`
`GET /api/productos/{id}`
`GET /api/categorias`
`GET /api/categorias/{id}`

## Autenticación
`POST /api/register`
`POST /api/login`
`POST /api/logout`
`GET /api/me`

## Direcciones
`GET /api/direcciones`
`POST /api/direcciones/{id}`
`GET /api/direcciones/{id}`
`PUT /api/direcciones/{id}`
`DELETE /api/direcciones/{id}`
`PATCH /api/direcciones/{id}/principal`

## Carrito
`GET /api/carrito`
`POST /api/carrito/items`
`PUT /api/carrito/items/{id}`
`DELETE /api/carrito/items/{id}`
`DELETE /api/carrito/vaciar`

## Pedidos
`POST /api/pedidos`
`GET /api/pedidos`
`GET /api/pedidos/{id}`

## Administración
`GET /api/admin/productos`
`POST /api/admin/productos`
`POST /api/admin/productos/{id}`
`DELETE /api/admin/productos/{id}`
`GET /api/admin/pedidos`
`PATCH /api/admin/pedidos/{id}/estado`
`GET /api/admin/usuarios`
`PATCH /api/admin/usuarios/{id}/estado`
`POST /api/admin/usuarios/admin`

# Estructura del proyecto

```
app/
 ├── Http/
 │   ├── Controllers/Api
 │   ├── Middleware
 │   └── Requests
 ├── Models

database/
 ├── migrations
 └── seeders

resources/
 └── views/pdf

routes/
 ├── api.php
 └── web.php
```
#Facturas PDF

Cuando un pedido cambia a un estado de validación definido por la lógica de negocio, se genera automáticamente una factura en PDF.
La factura:

- se guarda en base de datos
- genera un número de factura único
- almacena la ruta del archivo PDF
- puede exponerse al frontend para su descarga

Los archivos PDF se almacenan en:
```bash
storage/app/public/facturas
```
Y son accesibles públicamente a través de:
```bash
/storage/facturas/...
```

# Estado del proyecto
El backend se encuentra funcionalmente completado, incluyendo:

- autenticación
- catálogo
- direcciones
- carrito
- pedidos
- administración
- generación de facturas PDF

Las mejoras futuras pueden centrarse en:

- paneles de estadísticas
- mejoras visuales del frontend
- optimización de filtros y búsquedas
- nuevas funcionalidades para el perfil del usuario
