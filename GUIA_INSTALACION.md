# Guía de Instalación y Despliegue - CRM UNIMAQ S.A.C.

Este documento describe los requisitos y los pasos exactos para instalar, configurar y desplegar el **Sistema Web de Gestión de Clientes y Ventas (CRM)** de UNIMAQ S.A.C. en un entorno local (desarrollo) o servidor.

---

## 1. Requisitos Previos del Sistema

Asegúrese de que el entorno o servidor cumpla con las siguientes características técnicas antes de iniciar la instalación:

- **Servidor Web:** Apache o Nginx (XAMPP/Laragon recomendado para desarrollo en Windows).
- **PHP:** Versión 8.1 o superior.
- **Extensiones de PHP requeridas:**
  - `pdo_mysql` (Conexión a base de datos)
  - `mbstring` (Manejo de cadenas)
  - `openssl` (Seguridad)
  - `gd` (Procesamiento de imágenes y generación de Excel)
  - `zip` (Compresión de archivos para reportes Excel)
- **Base de Datos:** MySQL 5.7+ o MariaDB 10.3+.
- **Herramientas de Consola:**
  - [Composer](https://getcomposer.org/) (Gestor de dependencias de PHP).

> **Aviso Importante para Exportar a Excel:** Debe verificar en su archivo `php.ini` (ej. `C:\xampp\php\php.ini`) que las líneas `extension=gd` y `extension=zip` no tengan un punto y coma (`;`) al inicio. Si lo tienen, bórrelo, guarde el archivo y reinicie Apache.

---

## 2. Preparación del Entorno

### Paso 2.1: Obtener el Código Fuente
Copie la carpeta del proyecto `unimaq-crm` a la ruta pública de su servidor local (por ejemplo, `C:\xampp\htdocs\unimaq-crm`).

### Paso 2.2: Instalar Dependencias de PHP
Abra una terminal (Símbolo del sistema, PowerShell o Git Bash), navegue hasta la raíz del proyecto y ejecute:
```bash
cd C:\xampp\htdocs\unimaq-crm
composer install
```
*Este comando descargará todas las librerías necesarias de Laravel en la carpeta `vendor/`.*

---

## 3. Configuración del Sistema

### Paso 3.1: Archivo de Entorno (.env)
1. En la raíz del proyecto, busque el archivo llamado `.env.example`.
2. Duplique este archivo y renómbrelo como `.env`.
3. Abra el nuevo archivo `.env` con un editor de texto e ingrese los datos de su conexión a la base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=unimaq_crm_db
DB_USERNAME=root
DB_PASSWORD=
```
*(Cambie `unimaq_crm_db`, `root` y la contraseña según la configuración de su MySQL).*

### Paso 3.2: Generar Clave de Aplicación
Ejecute el siguiente comando para encriptar las sesiones y contraseñas del sistema:
```bash
php artisan key:generate
```

---

## 4. Base de Datos (Migraciones y Seeders)

Asegúrese de haber creado la base de datos vacía (`unimaq_crm_db`) en su gestor (phpMyAdmin o MySQL Workbench). Luego, ejecute:

```bash
php artisan migrate:fresh --seed
```

**¿Qué hace este comando?**
- `migrate:fresh`: Destruye (si existen) y crea todas las tablas estructurales de la base de datos desde cero.
- `--seed`: Inserta los datos iniciales necesarios (Roles base y la cuenta del Administrador principal).

**Credenciales generadas por defecto:**
- **Email:** `admin@unimaq.pe`
- **Contraseña:** `password`

---

## 5. Puesta en Marcha

Si está utilizando XAMPP, asegúrese de que Apache y MySQL estén en verde (iniciados).
Para iniciar el servidor de desarrollo de Laravel, ejecute:

```bash
php artisan serve
```

El terminal le devolverá una URL local, por defecto:
👉 `http://127.0.0.1:8000`

Abra esa dirección en su navegador Google Chrome, Firefox o Edge y verá la pantalla de inicio de sesión del CRM UNIMAQ.

---

## 6. Resolución de Problemas Comunes (Troubleshooting)

1. **Error 500 al iniciar sesión o cargar la página:**
   - Asegúrese de haber renombrado el `.env` y ejecutado `php artisan key:generate`.
2. **Error de Clase no encontrada (Maatwebsite\Excel):**
   - Faltan dependencias. Ejecute `composer install`.
3. **Error al exportar reportes Excel (ZipArchive not found o GD missing):**
   - Edite el archivo `php.ini`, active las extensiones `zip` y `gd`, guarde y reinicie Apache.
4. **Base de datos no conectada:**
   - Verifique que MySQL esté corriendo en XAMPP y que el nombre de la base de datos coincida con el del archivo `.env`.

***
*Fin del documento.*
