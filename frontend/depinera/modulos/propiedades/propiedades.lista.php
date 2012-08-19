<?php 

//recupero propiedades
$aPropiedades =& $oSistema->getPropiedades($oFiltro);

//array de items de propiedad. ej luminoso.
$aItems =& $oSistema->getItems($idioma);

//para alternar los colores de las filas de la tabla 
$i=0;

?>

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
						
						<img class="thumbnail" src="modulos/propiedades/loadImage.php?idp=<?php echo($oImagen->getIdPropiedad()); ?>&id=<?php echo($oImagen->getIdImagen()); ?>"></img> 
				
					<?php }	
					else{ ?>
						
							<img class="thumbnail" src="images/none.png" alt="Sin Imagen" title="Sin Imagen"></img> 
				
					<?php }	?>
														
				</div>
				
			</div>
			
			<div class="columna" id="columna_descripcion" >	
			
				<?php if($oPropiedad->getCodigoReferencia()){?>
				
				<div>Ref: <?php echo( $oPropiedad->getCodigoReferencia()); ?></div>
				
				<?php }?>
				
				
				<div>
				
					<?php 
					
					//imprimo calle
					echo "<strong> ".ETIQUETA_CALLE." </strong>";
					echo ( $oPropiedad->getCalle());
					echo (" ".$oPropiedad->getNumeroRedondeado() );
	
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
				echo "<strong> ".ETIQUETA_LOCALIDAD." </strong>"; 

				if($oPropiedad->getAliasLocalidad()!=''){
					echo ($oPropiedad->getAliasLocalidad());
				}
				else{
					echo( $oSistema->getLocalidad( $oPropiedad->getIdLocalidad() ) ); 
				}				
				
				?></div>
							
				
			</div>	
			
			<div class="columna" id="columna_iconos">
								
			<?php				
	        
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
	                        
							echo ("<img src=\"images/itemspropiedad/".$oItem->getImagen()."\" onmouseover=\"return overlib('<br /><center>".ucwords($oItem->getDescripcion())."</center><br />', WIDTH, 180);\" onmouseout=\"return nd();\"></img>");

	                    }  
	                }
	            }
	        } 
	        ?>
	 	
	        </div>			
	            
	        <div class="columna" id="columna_precio">
	        	
	            <?php
	            
	            
	            $aPublicacion = $oPropiedad->getPublicacion();
	            
	            //esto oculta la columna de acciones
	            $mostrar_acciones = false;
				$tagsEstado = 0;
	            
	            foreach ($aPublicacion as $oPublicacion){
	            	
	            	//para cada tipo de publicacion seleccionado veo que hago de acuerdo al tipo
	            	switch ($oPublicacion->getIdEstadoPublicacion()){

	            		case ESTADO_PUBLICACION_SUSPENDIDO:
	            			if ($tagsEstado == 0) echo "<div class='etiqueta_estado_propiedad'>".ETIQUETA_SUSPENDIDO."</div>  ";
							$tagsEstado++;
	            		break;
	            			            		
	            		case ESTADO_PUBLICACION_ALQUILADO:
	            			if ($tagsEstado == 0) echo "<div class='etiqueta_estado_propiedad'>".ETIQUETA_ALQUILADO."</div>  ";
							$tagsEstado++;
	            		break;
	            		
	            		case ESTADO_PUBLICACION_RESERVADO:
	            			if ($tagsEstado == 0) echo "<div class='etiqueta_estado_propiedad'>".ETIQUETA_RESERVADO."</div> ";
							$tagsEstado++;
	            		break;
	            		
	            		case ESTADO_PUBLICACION_VENDIDO:
	            			if ($tagsEstado == 0) echo "<div class='etiqueta_estado_propiedad'>".ETIQUETA_VENDIDO."</div> ";
							$tagsEstado++;
	            		break;
	            		
	            		case ESTADO_PUBLICACION_PUBLICADO:

	            			$mostrar_acciones = true;
	            		            	
	            			//si esta publicada con monto y se puede publicar
		            		if(is_numeric($oPublicacion->getMonto() )){
		            			
		            			//si el monto es mayor a 100000 poner consultar
		            			if($oPublicacion->getMonto() >= 100000 ){
		            				echo "<strong>". $aTiposPublicacion[$oPublicacion->getIdTipoPublicacion()] . ": </strong>".ETIQUETA_CONSULTAR." <br>";
		            				
		            			}
		            			//si el monto es mayor a cero lo muestro
		            			elseif ( $oPublicacion->getMonto() > 0){
		            				echo "<strong>". $aTiposPublicacion[$oPublicacion->getIdTipoPublicacion()] . ": </strong> ".$aTiposMoneda[$oPublicacion->getIdTipoMoneda()]['simbolo']. " ". $oPublicacion->getMonto() . "<br>";
		            			}
		            			//en otro caso pongo consultar
		            			else{
		            				echo "<strong>". $aTiposPublicacion[$oPublicacion->getIdTipoPublicacion()] . ": </strong>".ETIQUETA_CONSULTAR."<br>";
		            			}
		            		}
		            				           		
		            		
		            		//si esta publicada sin monto
		            		else{
		            			echo "<strong>". $aTiposPublicacion[$oPublicacion->getIdTipoPublicacion()] . ": </strong>".ETIQUETA_CONSULTAR."<br>";
		            		}
		            		
		            	break;
	            	}		
	            }
	                        
	            ?>
	            
	         
	        </div>
	        
	        <div class="columna" id="columna_acciones">

	        <?php if($mostrar_acciones){ ?>
	                
	        	<ul>
        		
	        		<a href="index.php?modulo=<?php echo $modulo; ?>&accion=ver_detalle&idioma=<?php echo $idioma; ?>&propiedad=<?php echo $oPropiedad->getIdPropiedad(); ?>" style="text-decoration:none; color:#FFFFFF;" > <li><?php echo (ETIQUETA_VER_DETALLE); ?></li> </a>
	        	</ul>
	        	
	        <?php } ?>	
	        </div>
					
			
		</div>
		
        
        
		<?php
				
		
		$i++;
		
	} ?>
	
	
	</div>
	</div>
