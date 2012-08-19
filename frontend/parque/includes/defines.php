<?php

/*direccion a la que se envian las consultas desde la web*/
define("CONTACTO_EMAIL", "info@parque-propiedades.com.ar");

/*asunto de las consultas enviadas desde la web*/
define("CONTACTO_ASUNTO", "Consulta recibida desde la página web");



define ("USER_APP_PATH",$_SERVER['SERVER_NAME']);
define ("ADMIN_APP_PATH","http://localhost/pinmo.com.ar");

define("PATH_ALMACEN_IMAGENES","modulos/propiedades/imagenes");
define("PATH_MODULO_PROPIEDADES","modulos/propiedades");


//valores por defecto al iniciar la web
define("MODULO_POR_DEFECTO", 1);
define("ACCION_POR_DEFECTO", "");
define("PROPIEDAD_POR_DEFECTO", "");
define("IDIOMA_POR_DEFECTO", "es"); //espa�ol

define("ID_INMOBILIARIA_POR_DEFECTO",1); //www.pinmo.com.ar


/*Tipo de publicacion de la propiedad - categoria de publicacion*/
define("TIPO_PUBLICACION_VENTA",1);
define("TIPO_PUBLICACION_ALQUILER",2);
define("TIPO_PUBLICACION_TEMPORARIO",3);

//usadas en ABM de propiedades
if(!defined("ID_TIPO_PROPIEDAD_DEFAULT")) define("ID_TIPO_PROPIEDAD_DEFAULT",1);
if(!defined("ID_LOCALIDAD_POR_DEFECTO")) define("ID_LOCALIDAD_POR_DEFECTO",135);
if(!defined("ID_PROVINCIA_POR_DEFECTO")) define("ID_PROVINCIA_POR_DEFECTO",0);



/*estado de publicacion de la propiedad*/
define("ESTADO_PUBLICACION_NO_PUBLICADO",7);
define("ESTADO_PUBLICACION_PUBLICADO",1);
define("ESTADO_PUBLICACION_VENDIDO",2);
define("ESTADO_PUBLICACION_ALQUILADO",3);
define("ESTADO_PUBLICACION_RESERVADO",4);
define("ESTADO_PUBLICACION_ARCHIVADO",5);
define("ESTADO_PUBLICACION_SUSPENDIDO",6);

 


/*Estados de exclusividad de una publicacion*/
define("ESTADO_EXCLUSIVIDAD_PUBLICA",1);
define("ESTADO_EXCLUSIVIDAD_PRIVADA",2);
define("ESTADO_EXCLUSIVIDAD_EXCLUSIVA",3);


?>
