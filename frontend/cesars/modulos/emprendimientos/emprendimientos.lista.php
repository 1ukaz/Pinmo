<?php

//recupero propiedades
$aPropiedades =& $oSistema->getPropiedades($oFiltro);

//array de items de propiedad. ej luminoso.
$aItems =& $oSistema->getItems($idioma);

//para alternar los colores de las filas de la tabla 
$i=0;

?>

<h2>Nuevos Emprendimientos</h2>

<div id="resultados" >
                    
	<div id="resultados_lista">	

	<?php
	
	foreach ($aPropiedades as $oPropiedad){	?>
	
		<div class="<?php echo ($i%2 ==0)? 'fila': 'fila_sombreada';?>" >
		
			<div class="columna" id="columna_thumbnail">
			
				<div class="thumbnail">
					
					<?php
					$oImagen = $oPropiedad->loadPrimeraImagenFromDB($oPropiedad->getIdPropiedad());
					
					if($oImagen != null){
						
						$archivo =  htmlentities($oImagen->getArchivo());?>
						
						<img class="thumbnail" src="modulos/emprendimientos/loadImage.php?idp=<?php echo($oImagen->getIdPropiedad()); ?>&id=<?php echo($oImagen->getIdImagen()); ?>"></img> 
				
					<?php }	
					else{ ?>
						
							<img class="thumbnail" src="modulos/emprendimientos/sinimagen.png"></img> 
				
					<?php }	?>
														
				</div>
				
			</div>
			
			<div class="columna" id="columna_descripcion" >	
			
				<?php if($oPropiedad->getCodigoReferencia()){?>
				
				<div>Ref: <?php echo( $oPropiedad->getCodigoReferencia()); ?></div>
				
				<?php }?>
				
				
				<div><?php 
					//imprimo calle
					echo( $oPropiedad->getCalle());
					//altura de calle sin precision
					echo (is_numeric($oPropiedad->getNumero()))? " al ".(intval(($oPropiedad->getNumero()/100))*100) : "";
	
					?>
				</div>	
						
				<div><?php 
				
				$calle1 = $oPropiedad->getEntreCalle1();
				$calle2 = $oPropiedad->getEntreCalle2();
				
				if($calle1!='' && $calle2!=''){
					echo (ETIQUETA_ENTRE." $calle1 ".ETIQUETA_Y." $calle2"); 
				}
				elseif($calle1!='' || $calle2!=''){
					echo (ETIQUETA_Y." $calle1 $calle2 "); 	
				}			
				?>			
				</div>
				
				<div><?php 
				
				if($oPropiedad->getAliasLocalidad()!=''){
					echo ($oPropiedad->getAliasLocalidad());
				}
				else{
					echo( $oSistema->getLocalidad( $oPropiedad->getIdLocalidad() ) ); 
				}
				
				?></div>
							
				
			</div>	
			
			<div class="columna" id="columna_intro_emp">
				<?php echo( $oPropiedad->getObservaciones()); ?>
			</div>
			
			
			<div class="columna" id="columna_iconos">
								
			<?php				
	        
			//items que estan activos de cada propiedad
			$aItemsActivos =& $oPropiedad->getItems($oPropiedad->getIdPropiedad());
						
			
	        //para cada item disponible en el sistema
	        foreach ($aItems as $key => $descripcionItem){
	                    
	            //recorro los items 
	            foreach ($aItemsActivos as $itemActivo){
	        
	                //veo si esta activado para la propiedad actual
	                if( $key == $itemActivo->getIdItem() ){
	                    
	                    $oItem = Item::loadItemFromDB($key,$idioma);
	                    
	                    //veo si tiene imagen definida	
	                    if($oItem->getImagen()!=''){
	                        //echo ("<img src='imagenes/itemspropiedad/".$oItem->getImagen()."' title='".$oItem->getDescripcion()."' alt='".$oItem->getDescripcion()."'></img>");
							echo ("<img src=\"imagenes/itemspropiedad/".$oItem->getImagen()."\" onmouseover=\"return overlib('<br /><center>".ucwords($oItem->getDescripcion())."</center><br />', WIDTH, 180);\" onmouseout=\"return nd();\"></img>");
	                    }  
	                }
	            }
	        } 
	        ?>
	 	
	        </div>			
	            
	        
	        
	        <div class="columna" id="columna_acciones">
	        <ul>
	        <li onclick="javascript: procesarAccion('<?php echo ($modulo); ?>','ver_detalle','<?php echo ($oPropiedad->getIdPropiedad()); ?>');" ><?php echo (ETIQUETA_VER_DETALLE); ?> </li>
	        </ul>
	        </div>
					
			
		</div>
		
        
        
		<?php
		
		
		$i++;
		
	} ?>
	
	
	</div>
	</div>