<?php
//print_r($_POST);

//parametros
$accion = (isset($_POST['accion']))? $_POST['accion']: "ver_detalles";
$propiedad =  (isset($_POST['propiedad']))? $_POST['propiedad']: "";

//guardo el log de la visita a la propiedad
$ip = $_SERVER['REMOTE_ADDR'];
$oLog = new LogVisitaPropiedad($propiedad,$ip);
$oLog->saveToDB();



//recupera datos de propiedad
$oPropiedad = new Propiedad($propiedad);

//recupero arrays del sistema
$aItems =& $oSistema->getItems();
$aTiposPropiedad =& $oSistema->getTiposPropiedad();
$aItemsActivos =& $oPropiedad->getItems($propiedad);
$aProvincias =& $oSistema->getProvincias();

// Recupero las publicaciones asociadas a la Propiedad para poder determinar si es Alquiler Temporario y mostrar el Calendario
$aPublicaciones = ($oPropiedad->getPublicacion($propiedad));

//print_r($aPublicaciones);

//datos de localizacion
$strProvincia = is_numeric($oPropiedad->getIdProvincia())? $aProvincias[$oPropiedad->getIdProvincia()]:'';
$strLocalidad = is_numeric($oPropiedad->getIdLocalidad())? Localidad::recuperarNombreLocalidad($oPropiedad->getIdLocalidad()):"";
$alturaRedondeada = is_numeric($oPropiedad->getNumero())? intval(($oPropiedad->getNumero()/100))*100:'';


//valido si estan cargados los datos de ubicacion para mostrar mapa - 24  caba 25 es gba
if($oPropiedad->getCalle()!='' && $alturaRedondeada!='' && $strLocalidad!=''){ 
	$mostrarMapa = true;
}
else{
	$mostrarMapa = false;
}


//$mostrarCalendario = false;


for ($j = 0; $j < count($aPublicaciones); $j++) {
	if ( ($aPublicaciones[$j]->getIdTipoPublicacion() == 3) && ($aPublicaciones[$j]->getIdEstadoPublicacion() == 1) )
		$mostrarCalendario = true;
}



?>


<?php

//valido si estan cargados los datos de ubicacion para mostrar mapa - 24  caba 25 es gba
if($mostrarMapa){ ?>
	
	<script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAActgCiU5gcQrgzL7TWarPmhQjEkSzhoyC8NV_c9gXnqbheLcvxBQX2frY3I7RyMbHp6rSQr9-NaauHw"  type="text/javascript"></script>

	<script type="text/javascript">
	
	var geocoder;
	var map;
	
	var address = "<?php 

	echo( $oPropiedad->getCalle()." $alturaRedondeada, $strLocalidad , $strProvincia" ); 
	?>
	";
	
	
	// On page load, call this function
	
	function load()
	{
	  // Create new map object
	  map = new GMap2(document.getElementById("mapa"));
	  
	  //map.setMapType(G_SATELLITE_MAP);
		map.setMapType(G_NORMAL_MAP);
	
	
	  // Create new geocoding object
	  geocoder = new GClientGeocoder();
	
	  // Retrieve location information, pass it to addToMap()
	  geocoder.getLocations(address, addToMap);
	}
	
	
	
	// This function adds the point to the map
	
	function addToMap(response)
	{
	  // Retrieve the object
	  place = response.Placemark[0];
	
	  // Retrieve the latitude and longitude
	  point = new GLatLng(place.Point.coordinates[1], place.Point.coordinates[0]);
	
	  // Center the map on this point
	  map.setCenter(point, 15);
	
	  // Create a marker
	  marker = new GMarker(point);
	
	  // Add the marker to map
	  map.addOverlay(marker);
	 
	
	  // Add address information to marker
	  marker.openInfoWindowHtml(address);
	}
	
 	$(document).ready(function(){
    	$("#frmConsulta").validate({
   			submitHandler: function(form) {
						$.ajax({
							type: 'POST',
							url: './modulos/emprendimientos/formSubmit.php',
							data: $('#'+form.id).serialize(),
							success: function(data) {
								$('#response').html(data);
							},
							error: function(e) {
								if (e.description == null) {
    								alert("Excepción: " + e.message);  
  								} else {
    								alert("Excepción: " + e.description);
  								}
							}
						})
   			}
		});
		
  	});

	</script>
	
<?php } ?>	


<div id="contenedor_detalle">

	<div id="detalle_titulo"> <?php
		
		echo($oPropiedad->getCalle());
		
		if( is_numeric($alturaRedondeada) ){
			echo (" al $alturaRedondeada");
		}
	?>
	</div>
    	
	
	<div id="detalle_descripcion"> 
		<h4><?php echo (ETIQUETA_INFO_GRAL); ?></h4>
	
		<ul>
		<?php if($oPropiedad->getCodigoReferencia()!=''){?>
		<li><?php echo("<strong>".ETIQUETA_DETALLE_CODIGO."</strong>". $oPropiedad->getCodigoReferencia()); ?></li>
		<?php }?>
		
		<?php if($oPropiedad->getCalle()!=''){?>
		<li><?php echo("<strong>".ETIQUETA_DETALLE_DOMICILIO."</strong>".$oPropiedad->getCalle()." al $alturaRedondeada" ); ?></li>
		<?php }?>
		
		<?php if($strLocalidad!=''){?>
		<li><?php echo("<strong>".ETIQUETA_DETALLE_LOCALIDAD."</strong> $strLocalidad"); ?></li>
		<?php }?>
	
		<?php if($strProvincia!=''){?>
		<li><?php echo("<strong>".ETIQUETA_DETALLE_PROVINCIA."</strong>$strProvincia"); ?></li>
		<?php }?>
		
		<?php if($oPropiedad->getIdTipoPropiedad()!=''){?>
		<li><?php echo("<strong>".ETIQUETA_DETALLE_TIPO_PROPIEDAD."</strong>".$aTiposPropiedad [$oPropiedad->getIdTipoPropiedad()]); ?></li>
		<?php }?>
		
		<?php if($oPropiedad->getPiso()!=''){
	
			//si el piso es cero cambiar por PB
			$nPiso = ($oPropiedad->getPiso() ==0)? ETIQUETA_PLANTA_BAJA : $oPropiedad->getPiso();
		?>
		
		<li><?php echo("<strong>".ETIQUETA_DETALLE_PISO."</strong>".$nPiso ); ?></li>
		<?php } ?>
	
		<?php if( $oPropiedad->getEntreCalle1() !='' && $oPropiedad->getEntreCalle2()!=''){
			echo ("<li>". "<strong>".ETIQUETA_DETALLE_ENTRE_CALLES."</strong>". $oPropiedad->getEntreCalle1(). ETIQUETA_DETALLE_Y. $oPropiedad->getEntreCalle2()."</li>");
		} ?>
		
		<?php if( is_numeric($oPropiedad->getAmbientes())){
			if($oPropiedad->getAmbientes()>0){
				echo ("<li>"."<strong>".ETIQUETA_DETALLE_AMBIENTES."</strong>".$oPropiedad->getAmbientes()."</li>");
			}
		} ?>
		
		<?php if( is_numeric($oPropiedad->getAntiguedad())){
			if($oPropiedad->getAntiguedad()>0){
				echo ("<li>"."<strong>".ETIQUETA_DETALLE_ANTIGUEDAD."</strong>".$oPropiedad->getAntiguedad()." ".ETIQUETA_DETALLE_ANIOS."</li>");
			}
			else {
				echo ("<li>"."<strong>".ETIQUETA_DETALLE_A_ESTRENAR."</strong>"."</li>");
			}
		} ?>
		
		
		<?php if( is_numeric($oPropiedad->getSuperficieCubierta())){
			if($oPropiedad->getSuperficieCubierta()>0){
				echo ("<li>"."<strong>".ETIQUETA_DETALLE_SUPERFICIE_CUB."</strong>".$oPropiedad->getSuperficieCubierta().ETIQUETA_DETALLE_M2."</li>");
			}
		} ?>	
	
		<?php if( is_numeric($oPropiedad->getSuperficieTotal())){
			if($oPropiedad->getSuperficieTotal()>0){
				echo ("<li><strong>".ETIQUETA_DETALLE_SUPERFICIE_TOT."</strong>".$oPropiedad->getSuperficieTotal().ETIQUETA_DETALLE_M2."</li>");
			}
		} ?>	
		</ul>	
		
		<?php 
		
		//Items de la propiedad
		if(count($aItemsActivos)>0){ ?>
	
			<h4><?php echo (ETIQUETA_CARACTERISTICAS); ?></h4>
		
			<ul>
			<?php foreach ($aItemsActivos as $oItem){ ?>
		
				<li><?php echo($aItems[$oItem->getIdItem()]); ?></li>
			
			<?php } ?>
			</ul>
		<?php } ?>
	
	
		<?php 
	
		//Medidas de las habitaciones
	
		if(count($aHabitacionesActivas)>0){	?>
	
			<h4><?php echo (ETIQUETA_MEDIDAS); ?></h4>
		
			<ul>
			
			<?php foreach ($aHabitacionesActivas as $oHabitacion){?>
		
			<li><?php echo($aTiposHabitacion[$oHabitacion->getIdTipoHabitacion()].": ".$oHabitacion->getAncho()." x ".$oHabitacion->getLargo()." ".ETIQUETA_DETALLE_METROS); ?> </li>
			
			<?php }?>
			</ul>
			
		<?php } ?>
				
	</div>
	
	<div id="galeria_emprendimientos">
		
		<?php
				
		$aTiposHabitacion =& $oSistema->getTiposHabitacion();
		
		//$aImagenes =& Propiedad::loadImagenesFromDB($propiedad);

		$i=0;

		$aUnidades = $oSistema->getUnidadesFromEmprendimiento($propiedad);

		if(count($aUnidades)){?>
		
			<h4><?php echo (ETIQUETA_DETALLE_UNIDADES); ?></h4>
		
			<?php
			
			//creo los elementos html para mostrar las imagenes ya cargadas a la propiedad
			foreach ($aUnidades as $oUnidad){ ?>
	
				<div class="unidad_emprendimiento" >
				
				<?php		
				$oImagen = Propiedad::loadPrimeraImagenFromDB($oUnidad->getIdPropiedad());
						
				$strLabelFoto = (is_numeric($oImagen->getDescripcion()) && isset($aTiposHabitacion[$oImagen->getDescripcion()]))? $aTiposHabitacion[$oImagen->getDescripcion()] : "";
				?>
				<h3><?php echo $oUnidad->getAmbientes()." ".( ($oUnidad->getAmbientes() > 1)? ETIQUETA_AMBIENTES: ETIQUETA_AMBIENTE); ?> </h3>
				
				<a href="http://cesars.pinmo.com.ar/index.php?modulo=2&accion=ver_detalle&propiedad=<?php echo($oUnidad->getIdPropiedad())?>&idioma=<?php echo $idioma;?>"  title="<?php echo ($strLabelFoto); ?>" alt="<?php echo ($strLabelFoto); ?>" >
				
				<?php if($oImagen != null){	?>
				<img id="<?php echo("thb".$oImagen->getIdImagen()); ?>"
					title="<?php echo($oImagen->getDescripcion()); ?>" 
					alt="<?php echo($oImagen->getDescripcion()); ?>"
					class="thumbnail"
					src="modulos/emprendimientos/loadImage.php?idp=<?php echo($oImagen->getIdPropiedad()); ?>&id=<?php echo($oImagen->getIdImagen()); ?>&s=t"  >
				</img>
				<?php } else{ ?>
					<img id="<?php echo("thb$i"); ?>"
					title="Sin Imagen" 
					alt="Sin Imagen"
					class="thumbnail"
					src="modulos/emprendimientos/loadImage.php?idp=<?php echo($oImagen->getIdPropiedad()); ?>&id=&s=t"  >
				</img>				
				<?php } ?>
				</a>
				
				</div>
				
			<?php
			$i++;
			}
		}
		?>

	</div>	
    
	<div id="detalle_obs" >
	<?php 
			
	//Observaciones
	$strObs =  $oPropiedad->getObservaciones();
		
	if(trim($strObs)!=''){?>
	
	
    	
		
            <h4><?php echo (ETIQUETA_OBSERVACIONES); ?></h4>
            <p> <?php echo $strObs ; ?>	</p>
		
        
	<?php } ?>
	</div>

	<?php if($mostrarMapa){ ?>
	
		
		<div id="detalle_mapa">
		<h4><?php echo (ETIQUETA_MAPA); ?></h4>		
		<div id="mapa"> 
		
		</div>
		
		
		<!--
		<p> <strong> <?php echo(ETIQUETA_ZOOM_MAPA); ?> </strong></p>
		-->
		
		</div>
		<script type="text/javascript">
			load();
		</script>

	<?php } ?>


	
	<div id="consulta">
	
		<h4> <?php echo (ETIQUETA_ENVIAR_CONSULTA); ?> </h4>
		
		<div id="response">
		
		<form id="frmConsulta" name="frmConsulta" method="POST" action="">
			 
			<input type="hidden" name="txtLang" id="txtLang" value="<?php echo($idioma);?>" >
			<input type="hidden" name="txtProp" id="txtProp" value="<?php echo($oPropiedad->getCalle()." ".$oPropiedad->getNumero()."-$strLocalidad-$strProvincia");?>" >
			<input type="hidden" name="txtID" id="txtID" value="<?php echo($propiedad); ?>" >
		
			<div>
			<label for="txtConsulta"> <?php echo (ETIQUETA_CONSULTA_CONSULTA); ?></label>
			<textarea rows="3" id="txtConsulta" name="txtConsulta" class="required" title="Debe Ingresar una Consulta"> </textarea>
			</div>
							
			<div>
			<label for="txtNombre"><?php echo (ETIQUETA_CONSULTA_NOMBRE); ?></label>
			<input  type="text" id="txtNombre" name="txtNombre" maxlength="50" class="required" title="Debe Ingresar su Nombre"></input>
			</div>
			
			<div>
			<label for="txtEmail"><?php echo (ETIQUETA_CONSULTA_EMAIL); ?></label>
			<input  type="text" id="txtEmail" name="txtEmail"  maxlength="50" class="required email" title="Debe Ingeresar un e-mail valido"></input>
			</div>
			
			<div style="padding-top:25px;">
				<p><input type="submit" id="btnEnviarConsulta" value="Enviar"  /> </input></p>
			</div>

		
		</form>
		</div>
	</div>
		
</div>

