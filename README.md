#Prueba de programación en PHP

##Descripción

El edificio de Aigües de Barcelona quiere mejorar la eficiencia energética de sus
ascensores. Para ello, te han encargado que desarrolles un simulador que genere un
informe de su uso a lo largo del día.

El edificio tiene 4 plantas (3 + planta baja) y 3 ascensores.
Aigües de Barcelona nos ha proporcionado las secuencias de uso (peticiones) de sus
ascensores:

1. Cada 5 minutos de 09:00h a 11:00h llaman al ascensor desde la planta baja
   para ir a la planta 2
2. Cada 5 minutos de 09:00h a 11:00h llaman al ascensor desde la planta baja
   para ir a la planta 3
3. Cada 10 minutos de 09:00h a 10:00h llaman al ascensor desde la planta baja
   para a la planta 1
4. Cada 20 minutos de 11:00h a 18:20h llaman al ascensor desde la planta baja
   para ir a las plantas 1, 2 y 3
5. Cada 4 minutos de 14:00h a 15:00h llaman al ascensor desde las plantas 1, 2 y
   3 para ir a la planta baja
6. Cada 7 minutos de 15:00h a 16:00h llaman al ascensor desde las plantas 2 y 3
   para ir a la planta baja
7. Cada 7 minutos de 15:00h a 16:00h llaman al ascensor desde la planta baja
   para ir a las plantas 1 y 3
8. Cada 3 minutos de 18:00h a 20:00h llaman al ascensor desde las plantas 1, 2 y
   3 para ir a la planta baja
   
   ###Parte 1
   
   La aplicación debe procesar las secuencias indicadas y generar como resultado un
   informe que muestre para cada minuto desde las 09:00h hasta las 20:00h:
   
   + La planta en la que se encuentra cada ascensor en ese momento.
   + El número total de plantas recorridas por cada ascensor hasta ese punto.
 
   A efectos de cómputo, asumiremos que cada movimiento completo (origen →
   llamada, llamada → destino) se realiza de forma instantánea (tiene una duración
   despreciable), y que los ascensores funcionan de manera similar a cómo lo harían
   otros ascensores corrientes. Si te surgen dudas de cómo se comportan en
   determinadas situaciones, escoge un camino y explícalo en las asunciones que hagas.Se pide implementar el simulador usando un paradigma de programación orientada
   a objetos, con PHP sin utilizar ningún framework. La configuración (número de
   plantas, ascensores y secuencias) debe poder cambiar. Si el proyecto es exitoso, se
   utilizará en otros edificios de la compañía.
   
   ###Parte 2
   
   Aigües de Barcelona quiere ver los resultados del simulador en una web. Para ello,
   deberás integrar el simulador desarrollado en un framework PHP (Laravel a poder
   ser, sino Symphony) que genere una página web con los mismos resultados que en el
   apartado 1.
   Nota: no se valorará la parte visual sino el correcto uso del framework, por lo tanto la
   maquetación HTML/CSS puede ser muy simple.
   Valoraciones y consideraciones.
   
   
   + Se valorará el tiempo empleado en completar la prueba
   + Que funcione correctamente y no contenga errores
   + Que el código sea fácil de leer y esté bien comentado

=========================================================================

#Code test

# Technologies #

+ Docker
+ CentOS
+ Nginx
+ PHP-fpm 7.4
+ Symfony 4.4
+ Composer
 
# How to run #

Dependencies:

  * [Docker](https://www.docker.com)
  * [Composer](https://getcomposer.org/)

Simply `cd` to elevators project and run `make start`. This will initialise and start all the containers, then leave them running in the background.
Also will install dependencies trough Composer and give permissions to cache & logs directories (sudo will be needed).

This generate 3 docker images:
+ nginx
+ phpdockerio/php74-fpm
+ elevators_php-fpm

And 2 running containers:
+ elevators-webserver
+ elevators-php-fpm

## Webserver ##

+ [localhost:8080](http://localhost:8080)

# How to clean installation from your system #

Executing `make stop` will stop & delete project containers. Also **vendor** directory will be removed.

### Other Docker useful commands

Remove specific project images:

`docker images` will show you a list of images created on your system by docker.

To remove them execute `docker rmi IMAGE_ID IMAGE_ID ...` where IMAGE_ID is the IMAGE ID displayed on list.

Delete all containers at once:
 
 + `docker ps -a -q | xargs docker rm -vf`
   
Delete all images at once:

 + `docker images -q | xargs docker rmi`
 
 # NOTES #
 
 All code lives under `src/`.
 
 Please, I will appreciate a lot any advice that you can give to me. It will help me to improve :)
 
 And many thanks for your time and attention. I apologize if I made loss your time in any manner.
 
 Have a great day!!