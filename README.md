# Aplicación web de graficación de atractores caóticos de GrIISoft

Este repositorio contiene el código fuente del proyecto web de la Aplicación web de graficación de atractores caóticos de GrIISoft. A continuación, se detallan los pasos necesarios para instalar, configurar, ejecutar y detener la aplicación utilizando Docker y Laravel Sail.

## Instalación

1. Clona el repositorio:

   ```bash
   git clone https://github.com/Thaferzzitha/griisoft-web-project

2. Copia el archivo de ejemplo de variables de entorno:

   ```bash
   cp .env.example .env

3. Asegúrate de tener Docker Desktop ejecutándose (en Windows) o verificar que docker este instalado (Linux) con el siguiente comando.
    ```bash
   docker -v
4. Ejecuta el siguiente comando para instalar las dependencias de PHP utilizando Composer:
   ```bash
   docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
5. Crea un alias para el comando Sail:
   ```bash
   alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
6. Levanta los contenedores de Docker:
   ```bash
   sail up -d
7. Genera una clave de aplicación:
    ```bash
    sail artisan key:generate
8. Instala las migraciones, y pobla la base de datos:
    ```bash
    sail artisan migrate --seed
9. Instala las dependencias de Node.js:
    ```bash
    sail npm install

## Configuración

1. Las variables de ambiente en el archivo .env se modifican al estar en producción, aquí un ejemplo:
    ```bash
    APP_NAME=Griisoft
    APP_ENV=production
    APP_KEY=base64:C04qyLl4wns52wgEOJta4IPrpKqE184AeebJL4PSarc=
    APP_DEBUG=true
    APP_URL=http://url.com
    
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=nombre-de-la-bd-en-produccion
    DB_USERNAME=usuario
    DB_PASSWORD=contraseña

## Ejecución

1. Asegúrate de tener el alias para Sail configurado:
   ```bash
   alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
3. Levanta los contenedores de Docker:
   ```bash
   sail up -d
5. Compila los activos de frontend:
   ```bash
   sail npm run dev

## Detener

1. Para detener los contenedores y la aplicación:
    ```bash
    sail down
