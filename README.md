# Tian Mail Laravel

Guia basica para instalar, ejecutar y desplegar este proyecto (Laravel 12).

## Requisitos minimos

- PHP `>= 8.2` (recomendado: `8.3.x`)
- Composer `>= 2.x`
- Base de datos MySQL/MariaDB
- Node.js `16.x` (solo si vas a compilar frontend)
- pnpm `8.x` (solo si vas a compilar frontend)

## Instalacion local

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configura tu `.env` con credenciales de BD y correo.

Ejemplo minimo importante:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_db
DB_USERNAME=tu_user
DB_PASSWORD=tu_pass

FILESYSTEM_DISK=local
VITE_API_BASE_URL="${APP_URL}"
```

Luego ejecuta:

```bash
php artisan migrate
php artisan serve
```

## Frontend (Vue 3 + Vite + Tailwind)

```bash
pnpm install
pnpm run dev
pnpm run build
```

`pnpm run dev` levanta el servidor de Vite y recompila automaticamente.

Archivos generados principales:

- `public/build/manifest.json`
- `public/build/assets/*`

## Despliegue rapido (subiendo assets compilados)

Si en produccion subes assets ya compilados, NO necesitas Node/pnpm en el servidor.

### Paso 1: compilar en local o CI

```bash
pnpm install
pnpm run build
```

### Paso 2: subir al servidor

Sube el codigo y asegurate de incluir:

- `public/build/manifest.json`
- `public/build/assets/*`

### Paso 3: instalar dependencias PHP en servidor

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
```

Si usas colas o cache adicional, agrega los comandos correspondientes (`queue:work`, `horizon`, `cache:clear`, etc.).

## Verificacion rapida

```bash
php artisan --version
php artisan route:list
php artisan test
```

Si los comandos responden sin errores, la aplicacion esta levantando correctamente.
