# Tian Mail Laravel

Guia basica para instalar, ejecutar y desplegar este proyecto (Laravel 9).

## Requisitos minimos

- PHP `>= 8.0.2` (recomendado: `8.3.x`)
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
```

Luego ejecuta:

```bash
php artisan migrate
php artisan serve
```

## Frontend (solo para compilar assets)

Este proyecto usa Laravel Mix (Webpack).

```bash
pnpm install
pnpm run dev
pnpm run prod
```

Archivos generados principales:

- `public/js/app.js`
- `public/js/app.js.LICENSE.txt`
- `public/css/app.css`
- `public/mix-manifest.json`

## Despliegue rapido (subiendo assets compilados)

Si en produccion subes assets ya compilados, NO necesitas Node/pnpm en el servidor.

### Paso 1: compilar en local o CI

```bash
pnpm install
pnpm run prod
```

### Paso 2: subir al servidor

Sube el codigo y asegurate de incluir:

- `public/js/app.js`
- `public/js/app.js.LICENSE.txt`
- `public/css/app.css`
- `public/mix-manifest.json`

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
```

Si ambos comandos responden, la aplicacion esta levantando correctamente.
