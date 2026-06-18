# Sistema Web CRM - UNIMAQ S.A.C.

Este proyecto es un Sistema de Gestión de Clientes, Cotizaciones y Ventas (CRM) desarrollado en Laravel 10 para la empresa UNIMAQ S.A.C., automatizando sus procesos comerciales y reemplazando las hojas de cálculo tradicionales.

## Tecnologías Utilizadas
- **Backend:** Laravel 10 (PHP 8.2)
- **Frontend:** Bootstrap 5, Blade Templates, JavaScript (Vanilla)
- **Base de Datos:** MySQL
- **Exportaciones:** Maatwebsite Excel (XLSX), Barryvdh DomPDF (PDF)
- **Autenticación:** Laravel UI

## Requisitos Previos
- PHP 8.2 o superior
- Composer
- MySQL (o MariaDB equivalente)
- Node.js y NPM (opcional, para compilar assets localmente si fuera necesario)

## Instrucciones de Instalación

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/tu-usuario/unimaq-crm.git
   cd unimaq-crm
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Configurar las variables de entorno:**
   - Copiar el archivo de ejemplo:
     ```bash
     cp .env.example .env
     ```
   - Configurar la conexión a la base de datos en `.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=unimaq_crm
     DB_USERNAME=root
     DB_PASSWORD=
     ```

4. **Generar la clave de aplicación:**
   ```bash
   php artisan key:generate
   ```

5. **Ejecutar migraciones y seeders:**
   (Esto creará las tablas base e insertará los roles y los usuarios de prueba).
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Ejecutar el servidor local:**
   ```bash
   php artisan serve
   ```

## Usuarios de Prueba (Seeders)
Por defecto, tras ejecutar los seeders, se crean los siguientes accesos:

- **Administrador:**
  - Email: `admin@unimaq.com`
  - Clave: `password123`
- **Vendedor:**
  - Email: `vendedor@unimaq.com`
  - Clave: `password123`
- **Gerente:**
  - Email: `gerente@unimaq.com`
  - Clave: `password123`

## Pruebas Automatizadas
Para ejecutar los casos de prueba obligatorios, utilice:
```bash
php artisan test
```

## Estructura de Roles y Permisos
- **Administrador:** Acceso total a todos los módulos, incluyendo la gestión de Usuarios y Auditoría.
- **Vendedor:** Acceso a Clientes, Cotizaciones, Ventas y Reportes. No tiene acceso a Usuarios ni Auditoría.
- **Gerente:** Acceso de solo-lectura vía el Módulo de Reportes. No puede crear, editar ni eliminar registros en los módulos transaccionales.

## Módulos del Sistema
- **Clientes:** Gestión del portafolio con soft-deletes y validación de duplicidad (RUC/DNI).
- **Cotizaciones:** Generación Master-Detail de proformas con cálculos matemáticos automáticos y correlativos.
- **Ventas:** Registro de ventas (directas o derivadas de cotizaciones) y su estado de pago.
- **Reportes:** Dashboard general y capacidad de exportación a Excel (.xlsx) y PDF.
- **Usuarios & Auditoría:** Administración del acceso y log estricto de todas las acciones que modifican el estado de la base de datos (CREATE, UPDATE, DELETE).
