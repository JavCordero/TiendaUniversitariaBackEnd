# Proyecto Tópico avanzados de Ingeniería de Software: Tienda Universitaria - API


## Introducción 💡

### Quiénes Somos
Somos el equipo Bijuu Developers, compuesto por los siguientes integrantes:

- Javiera Cordero (Líder del proyecto y full Stack).
- Juan Barnett (Líder de requerimientos y full Stack).
- Nicolás Cofré (Líder de SQA y full Stack).
- Luciano Larama (Líder de programación y full Stack).
- Yeison Olivares (Líder de documentación y full Stack).

### Contexto
El equipo está formado por estudiantes de la Universidad Católica del Norte, Antofagasta Chile. Con el fin de lograr el trabajo requerido para la asignatura de *Proyecto Tópico avanzados de Ingeniería de Software*.

## Proyecto 📜
Este proyecto consta en la creación de un control de inventario para la Tienda Universitaria de la Universidad Católica del Norte. El repositorio actual corresponde a la aplicación por lado del cliente. El proyecto consta de 2 partes.

- La primera corresponde a la aplicación que incluye el frontend y backend (WebApp).
- La segunda corresponde a la API que se encargará de manegar los datos y Requests (WebApi).

Este repositorio actualmente almacena la aplicación web. 
Para la WebApp, dirigirse al siguiente repositorio: https://github.com/JavCordero/TiendaUniversitariaFrontEnd

## Tecnologías 🛠️

* [Laravel 8.64](https://laravel.com/docs/8.x)
* [PHP 7.3.7](https://www.php.net/)
* [MySQL](https://www.mysql.com/)

### Dependencias utilizadas:

* fruitcake/laravel-cors: ^2.0
* guzzlehttp/guzzle: ^7.0.1
* laravel/passport: ^10.1
* laravel/sanctum: ^2.11
* laravel/tinker: ^2.5
* facade/ignition: ^2.5
* fakerphp/faker: ^1.9.1
* laravel/sail: ^1.0.1
* mockery/mockery: ^1.4.4
* nunomaduro/collision: ^5.10
* phpunit/phpunit: ^9.5


## Instalación 🔧
### Pre-requisitos:
* PHP 7.3+
* Gestor de paquetes: [composer](https://getcomposer.org/)

> Se debe crear un repositorio local el cual almacenará la información adyacente en el repositorio actual.

### Paso a paso  de instalación

1. Abrir el terminal.
2. Crear el directorio al cual se clonará el proyecto.
3. Iniciar el repositorio mediante:
```
git init
```
4. Crear conexión con el repositorio:
```
git remote add origin https://github.com/JavCordero/TiendaUniversitariaBackEnd
```
5. Finalmente, hacer pull al master:
```
git pull origin main
```

### Instrucciones:
Debes renombrar el archivo .env.example a .env con los datos correspondientes a la base de datos.

Desde la terminal en la carpeta raíz del proyecto, usa los siguientes comandos:

1. Debes instalar las dependecias utilizadas en el proyecto.
	```sh
	composer install
	```
1. Debes generar una clave aleatoria para el proyecto.
	```sh
	php artisan key:generate
	```
1. Debes instalar las dependecias utilizadas en el proyecto.
	```sh
	composer install
	```
1. Debes generar las tablas necesarias del proyecto a la base de datos.
	```sh
	php artisan migrate
	```
1. Si deseas poblar la base de datos.
	```sh
	php artisan migrate:fresh --seed
  
## Uso 🔧
Desde la terminal en la carpeta raíz del proyecto, usa los siguientes comandos:

* Para levantar el servidor: `http://127.0.0.1:8000`
	```sh
	php artisan migrate
	```
* Para ejecutar las pruebas unitarias y de integración:
	```sh
	php artisan test
	```

## Convenciones 📋
### Commits
Los commits deben incluir un mensaje descriptivo de los cambios realizados
La estructura de los mensajes es la siguiente:
```
- <type>(<scope>):<subject>
```
#### type: el tipo de cambio, este pueden ser
- feat	  : adición nueva 	
- fix 	  : bug fixes (arreglo de errores)
- docs	  : cambios en la documentación
- Style	  : cambio de estilo que no afectan en la funcionalidad (formato, espaciados, etc).
- Refactor: cambio que no arregla ni agrega una funcionalidad.
- Test	  : agregar pruebas faltantes o bien corregir existentes
- Chore	  : cambios en librerías, build y herramientas auxiliares.
- perf	  : cambio que mejora el rendimiento del programa.

#### scope: Opcional, específica el lugar en donde se realiza el cambio en el commit (clase, módulo, etc).
#### subject: Descripción corta de que trata el cambio emitido.

### Documentación 

#### Para C#
```
///<summary>
///</summary>
* Hay que documentar las clases y métodos que se utilizan.
* Comentarios útiles sobre algo en específico dentro del código.
```

### Programación
* Clases/Modelos: PascalCase
* Métodos: PascalCase
* Variables: camelCase
* Parámetros para Métodos: camelCase
* Json: spinal-case

### Comentarios
Para cualquier duda o consulta sobre el proyecto, no dude en contactarse con el Líder del proyecto, Javiera Cordero, mediante el siguiente correo electrónico: javiera.cordero@alumnos.ucn.cl 

Se despide atentamente, el equipo Bijuu Developers.
