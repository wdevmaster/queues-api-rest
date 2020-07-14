# Queues API Rest

[TOC]

> **Comando de prueba:** 
`php artisan http:post https://atomic.incfile.com/fakepost`
Este comando solo ejecuta una sola solicitud a la url
para hacer varias solicitudes hay que agregar las opcion `queue=`
`php artisan http:post --queue=5 https://atomic.incfile.com/fakepost`

## Requisitos del servidor
- PHP >= 7.2.5
- BCMath PHP Extension
- Fileinfo PHP extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Instalacion
1.  Clonar el repositorio
```bash
git clone repo
```
2.  Luego aceder a la carpeta del proyecto 
```bash
cd pathTo/project
```
3.  Ejecutar los siguientes comandos:
```bash
cp .env.example .env
```
> **Nota:** 
> Si usaras un entorno local ve a la seccion de [Lamp][Lamp]

### Configuracion ENV

#### Base de datos entorno local
En el archivo `.env` configura la base de datos en las siguientes lineas:
```bash
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
Es necesario ejecutar el comando `php artisan migrate` para que todo funcione sin problemas.


Configurar las colas de trabajo en el archivo `.env`:
```bash
QUEUE_CONNECTION=database
```

### Permisos
Si a instalado el proyecto en la carpeta `www` de su servidor es necesario que le de permisos a la carpeta `storage/` y a la `bootstrap/cache` ejecutando en el siquiente comando en la bash
```bash
sudo chmod 777 -R storage/ bootstrap/cache/
```

### Configuracion

#### Lamp
Para configurar el proyecto en un LAMP o otro entornno local solo debes tener [composer](https://getcomposer.org/download/ "composer") installado y ejecutar los siguientes comandos:
```bash
composer install 
php artisan key:generate
```
Luego se debe configurar  un vHost si estan en un entorno local o en produccion se debe el domion apuntar a la carpeta `public/` dentro del proyecto.

##### Apache
Para ello puede usar el siguiente codigo para un ***.htaccess*** si usas [apache](https://httpd.apache.org/ "apache") en la carpeta raiz ( **/** ) del proyecto:
```bash
<IfModule mod_rewrite.c>
	RewriteEngine on
	RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```
##### Nginx
Si usas [nginx](https://www.nginx.com/ "nginx") solo deberas  cambiar la configuracion del archivo */etc/nginx/conf.d/default.conf*  por el siguiente codigo:
```bash
server {
    listen 80;
    index index.php index.html;
    root /var/www/public;  #Aqui cambias la ruta por donde este el proyecto

    location / {
        try_files $uri /index.php?$args;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
```
[docker]: https://pandao.github.io/editor.md/en.html#Docker "docker"
[Lamp]: https://pandao.github.io/editor.md/en.html#Lamp "Lamp"