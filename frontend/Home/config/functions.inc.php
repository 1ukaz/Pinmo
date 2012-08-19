<?php

function parseUrlFriendlyString( $string ) {
	return str_replace(array(" ","á","é","í","ó","ú","ñ"), array("-","a","e","i","o","u","ni"), ereg_replace("[\,\(\)]", "", strtolower($string)));
}

/**********************************************************************************/
/* FUNCION UTILIZADA POR EL APPLET DE JAVA PARA DETECTAR EL NAVEGADOR DEL CLIENTE */
/* DEL LADO DEL SERVIDOR, PARA PODER DETERMINAR EL TIPO DE OBJETO DE LA APP JAVA  */
/**********************************************************************************/

function getBrowserType($user_agent) {

	$navegadores = array('Opera' => 'Opera','Mozilla Firefox'=> '(Firebird)|(Firefox)','Galeon' => 'Galeon','Mozilla'=>'Gecko','MyIE'=>'MyIE','Lynx' => 'Lynx',
						 'Netscape' => '(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)','Konqueror'=>'Konqueror',
						 'Internet Explorer 7' => '(MSIE 7\.[0-9]+)','Internet Explorer 6' => '(MSIE 6\.[0-9]+)','Internet Explorer 5' => '(MSIE 5\.[0-9]+)',
						 'Internet Explorer 4' => '(MSIE 4\.[0-9]+)');

	foreach($navegadores as $navegador => $pattern){
		if (stristr($pattern, $user_agent))
			return $navegador;
    }

	return 'Desconocido';
}

/**************************************************************************/
/*		       FUNCION QUE COMPARA DOS FECHAS Y DEVUELVE:				  */
/*           0 -> Fechas Iguales || 1 -> fechaUno < fechaDos              */
/*     -1 -> fechaUno > fechaDos || 5 -> Alguna fecha posterior a Hoy     */
/**************************************************************************/

function compareDates( $fechaUno, $fechaDos ) {
	if (strpos($fechaUno, "/") !== false && strpos($fechaDos, "/") !== false) {
		list($d1, $m1, $a1) = explode("/",$fechaUno);
		list($d2, $m2, $a2) = explode("/",$fechaDos);		
	}
	elseif (strpos($fechaUno, "-") !== false && strpos($fechaDos, "-") !== false) {
		list($d1, $m1, $a1) = explode("-",$fechaUno);
		list($d2, $m2, $a2) = explode("-",$fechaDos);				
	}
	$fechaTempUno = mktime(0, 0, 0, $m1, $d1, $a1);
	$fechaTempDos = mktime(0, 0, 0, $m2, $d2, $a2);
	$fechaTempHoy = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
	/*
	if ($fechaTempUno < $fechaTempHoy || $fechaTempDos < $fechaTempHoy) 
		return 5;
	*/
	if ($fechaTempUno == $fechaTempDos) 
		return 0;
	if ($fechaTempUno < $fechaTempDos) 
		return 1;
	else 
		return -1;
}

/**************************************************************************/
/*      FUNCION QUE CONVIERTE UN TIMESTAMP A FECHA Y HORA EN ESPAÑOL      */
/**************************************************************************/

function convertirTimestamp( $fecha ) {
    if ($fecha != "") return substr($fecha, 8, 2)."/".substr($fecha, 5, 2)."/".substr($fecha, 0, 4)." a las: ".substr($fecha, 11, 8)." hs";
}

/**************************************************************************/
/* FUNCION QUE CONVIERTE UN TIMESTAMP A FECHA Y HORA EN LETRAS EN ESPAÑOL */
/**************************************************************************/

function convertirTimestampLetras( $tmstmp ) {
    if ($tmstmp != '') {
	$ano = substr($tmstmp, 0, 4);
	$mes = substr($tmstmp, 5, 2);
	$dia = substr($tmstmp, 8, 2);
	$hora = substr($tmstmp, 11, 8);
	$fecha = mktime(0, 0, 0, $mes, $dia, $ano);
	$i = getdate($fecha);
	$dia_cast = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");	
	$mes_cast = array( 1 => "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$tmstmp_conv = $dia_cast[$i['wday']]." ".$i['mday']." de ".$mes_cast[$i['mon']]." de ".$i['year']. " a las: ".$hora." hs.";
	return $tmstmp_conv;
    }
}

/**************************************************************************/
/* FUNCION QUE CONVIERTE UN TIMESTAMP A FECHA EN LETRAS EN ESPAÑOL        */
/**************************************************************************/

function convertirFechaLetras( $fecha ) {
    if ($fecha != '') {
		$ano = substr($fecha, 0, 4);
		$mes = substr($fecha, 5, 2);
		$dia = substr($fecha, 8, 2);
		$fecha_swap = mktime(0, 0, 0, $mes, $dia, $ano);
		$i = getdate($fecha_swap);
		$dia_cast = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");	
		$mes_cast = array( 1 => "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		$fecha_conv = $dia_cast[$i['wday']]." ".$i['mday']." de ".$mes_cast[$i['mon']]." de ".$i['year'];
		return $fecha_conv;
    }
}

/*************************************************************************/
/*        FUNCION QUE CONVIERTE LA FECHA PARA SER GRABADA EN LA BD       */
/*************************************************************************/

function convertirFechaSql( $fecha, $flag='' ) {
	if ($fecha != 'Ingrese Fecha' && $fecha != '') {
		$fechan = explode ("/", $fecha);
		$fechah = $fechan[2]."-".$fechan[1]."-".$fechan[0];
		return $fechah;
	}
}

/*************************************************************************/
/*  FUNCION QUE CONVIERTE LA FECHA DE LA BD PARA MOSTRARLA EN ESPAÑOL    */
/*************************************************************************/

function convertirFechaMostrar( $fecha ) {
	if ($fecha) {
		$fechan = explode("-", $fecha);
		$fechah = $fechan[2]."/".$fechan[1]."/".$fechan[0];
		return $fechah;
	}
}

/*************************************************************************/
/*          FUNCION QUE CAMBIA EN LA FECHA A MOSTRAR: / POR -            */
/*************************************************************************/

function quitarSlashesFecha( $fecha ) {
	if ($fecha) return str_replace("/", "-", $fecha);
}

/*************************************************************************/
/*	FUNCION QUE CALCULA LA CANTIDAD DE DIAS ENTRE UNA FECHA Y HOY    */
/*************************************************************************/

function getDaysLeft( $fechaPasada, $isTimeStamp = 0 ) {
	if ($isTimeStamp)
		$numDays = floor(abs(strtotime(date("Y-m-d H:i:s")) - strtotime($fechaPasada))/86400);
	else
		$numDays = floor(abs(strtotime(date("Y-m-d")) - strtotime($fechaPasada))/86400);
	$fechaTemp = str_replace("-", "", $fechaPasada);
	if ($fechaTemp < date("Ymd"))
		$numDays = $numDays - ($numDays*2);
	return $numDays;
}

/*************************************************************************/
/*		  FUNCION QUE PARSEA UN TEXTO QUITANDO ACENTOS Y ENTERS          */
/*************************************************************************/

function parseText( $texto ) {
	$textoParseado = trim($texto);
	//$textoParseado = utf8_decode($texto);
	$textoParseado = str_replace("ñ", "&ntilde;", $texto);
	$textoParseado = str_replace("á", "&aacute;", $textoParseado);
	$textoParseado = str_replace("é", "&eacute;", $textoParseado);
	$textoParseado = str_replace("í", "&iacute;", $textoParseado);
	$textoParseado = str_replace("ó", "&oacute;", $textoParseado);
	$textoParseado = str_replace("ú", "&uacute;", $textoParseado);

	return ( str_replace("\r\n", "<br/>", str_replace('"', '&quot;', str_replace("\r", "<br/>", str_replace("\n", "<br/>", $textoParseado)))) );
}

/*************************************************************************/
/*          FUNCION QUE OBTIENE EL ORDEN DEL LISTADO                     */
/*************************************************************************/

function sacarOrden( $orden ) {
	switch( $orden ) {
		case 1:
			return "P.destacada DESC, P.idPropiedad ASC, P.idProvincia, P.idLocalidad, P.calle";
		break;
		case 2:
			return "PP.monto DESC, P.destacada";
		break;
		case 3:
			return "PP.monto ASC, P.destacada";
		break;
		case 4:
			return "P.fechaAlta DESC, P.destacada DESC, P.idPropiedad ASC";
		default:
			return "P.destacada DESC, P.idPropiedad ASC, P.idProvincia, P.idLocalidad, P.calle";
		break;
	}
}

/********************************************************************************/
/*		FUNCION PARA ORDENAR UN ARRAY DE OBJETOS POR UN ATRIBUTO A DEFINIR		*/
/*			  EN EL EJEMPLO SE UTILIZA EL METODO getTime() COMO GUIA			*/
/********************************************************************************/

function sortObject($data) {
	for ($i = count($data) - 1; $i >= 0; $i--) {
		$swapped = false;
		for ($j = 0; $j < $i; $j++) {
			if ( $data[$j]->getTime() < $data[$j + 1]->getTime() ) {
				$tmp = $data[$j];
                $data[$j] = $data[$j + 1];
                $data[$j + 1] = $tmp;
                $swapped = true;
			}
		}
		if (!$swapped) {
			return $data;
		}
	}
}

?>