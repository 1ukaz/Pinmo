<div id="browser_panel">
    
<div id="buscador">
						
		<form id="formBuscador" method="POST" action="index.php?modulo=<?php echo $modulo; ?>&accion=buscar&idioma=<?php echo $idioma; ?>&propiedad=<?php echo $propiedad; ?>"  name="frmBuscador">
			
        <input type="hidden" name="modulo" id="b_modulo" value="<?php echo $modulo; ?>"></input>
        <input type="hidden" name="accion" id="b_accion" value="<?php echo $accion; ?>"></input>
        <input type="hidden" name="idioma" id="b_idioma" value="<?php echo $idioma; ?>"></input>
        <input type="hidden" name="categoria" id="b_categoria" value="<?php echo $categoria; ?>"></input>
		
	    <!--  <div id="divtitulo" ><?php echo(ETIQUETA_BUSCADOR); ?> </div> -->
        
        
       	<div class="criterio">
	             
			<label for="cboCategoria" ><?php echo(ETIQUETA_CATEGORIAS); ?></label>
            
			<select class="combo" id="cboCategoria" name="cboCategoria"  tabindex="0" >			
			
            	<?php foreach ($aTiposPublicacion as $key => $tipo){
                    $option = "<option value=$key ";
                    $option.= ($key == $categoria)? " selected=selected ":"";
                    $option.= ">$tipo</option>";
                    echo ($option);
                } ?>
			
			</select>    
		            
		</div>
 
 
	    <div class="criterio">
	    	
			<label for="cboProvincia" ><?php echo(ETIQUETA_PROVINCIA); ?></label>
			<select name="cboProvincia" class="combo" id="cboProvincia" tabindex="2" onchange="javascript: buscar('<?php echo ($modulo); ?>','buscar','');" >
			<option value="T" <?php echo ($provincia =='T')? "selected=selected" :""; ?> > <?php echo(ETIQUETA_TODAS); ?></option>
			
			<?php 
			
			foreach ($aProvincias as $key => $strProvincia){?>
				
				<option value="<?php echo( $key ); ?>"  <?php echo( "$key" == "$provincia" )? "selected=selected" : "";?> ><?php echo($strProvincia); ?>	</option>
				
			<?php } ?>	
			</select>
			
		</div>

	    <div class="criterio">
			<label for="cboLocalidad" ><?php echo(ETIQUETA_LOCALIDAD); ?></label>
			<select name="cboLocalidad" class="combo" id="cboLocalidad" tabindex="3" >
			<option value="T" selected="selected"  ><?php echo(ETIQUETA_TODAS); ?></option>
			<?php
			if($provincia!="T"){
				
				foreach ($aLocalidades as $key=> $strLocalidad){
					$option = "<option value=$key ";
					$option.= ($key==$localidad)? "selected=selected >" : ">";
					$option.=  $strLocalidad ."</option>";
					echo ($option);
				}
			}
			?>
			</select>
		</div>        
	    
	    <div class="criterio">
	             
			<label for="cboTipo" ><?php echo(ETIQUETA_TIPO); ?></label>
			<select class="combo" id="cboTipoPropiedad" name="cboTipoPropiedad"  tabindex="1" >
			<option value="T" selected><?php echo(ETIQUETA_TODOS); ?></option>
			
			<?php foreach ($aTiposPropiedad as $key => $tipo){
				$option = "<option value=$key ";
				$option.= ($key == $tipoPropiedad)? " selected=selected ":"";
				$option.= ">$tipo</option>";
				echo ($option);
			} ?>
			
			</select>    
		            
		</div>
			
	    <div class="criterio">  				                
	    	<label for="cboAmbientes"><?php echo(ETIQUETA_AMBIENTES); ?></label>
	    	<select name="cboAmbientes" id="cboAmbientes" class="combo">
	    	<option value="T" <?php echo ($ambientes=='T')? "selected=selected":""; ?>> <?php echo(ETIQUETA_TODOS); ?> </option>
	    	<option value="1" <?php echo ($ambientes==1)? "selected=selected":""; ?>> 1 </option>
	    	<option value="2" <?php echo ($ambientes==2)? "selected=selected":""; ?>> 2 </option>
	    	<option value="3" <?php echo ($ambientes==3)? "selected=selected":""; ?>> 3 </option>
	    	<option value="4" <?php echo ($ambientes==4)? "selected=selected":""; ?>> 4 </option>
	    	<option value="5" <?php echo ($ambientes==5)? "selected=selected":""; ?>> <?php echo(ETIQUETA_MAS_DE); ?> 4 </option>
	    	</select>
		</div>
			
		


		    
	    <div class="criterio"> 
				<label for="cboSuperficieTotal"><?php echo(ETIQUETA_SUPERFICIE); ?></label>
				<select name="cboSuperficieTotal" id="cboSuperficieTotal" class="combo">
		    	<option value="T" <?php echo ($superficie=='T')? "selected=selected":""; ?>> <?php echo(ETIQUETA_TODOS); ?> </option>
		    	<option value="1" <?php echo ($superficie==1)? "selected=selected":""; ?>> <?php echo(ETIQUETA_HASTA); ?> 40 <?php echo(ETIQUETA_M2); ?> </option>
		    	<option value="2" <?php echo ($superficie==2)? "selected=selected":""; ?>> <?php echo(ETIQUETA_MAS_DE); ?> 40 <?php echo(ETIQUETA_M2); ?></option>
		    	<option value="3" <?php echo ($superficie==3)? "selected=selected":""; ?>> <?php echo(ETIQUETA_MAS_DE); ?> 60 <?php echo(ETIQUETA_M2); ?></option>
		    	<option value="4" <?php echo ($superficie==4)? "selected=selected":""; ?>> <?php echo(ETIQUETA_MAS_DE); ?> 100 <?php echo(ETIQUETA_M2); ?></option>
		    	</select>
		</div> 
		    
	
			
		<div class="criterio"> 
		       
			<label for="cboAntiguedad"><?php echo(ETIQUETA_ANTIGUEDAD); ?></label>
			<select name="cboAntiguedad" id="cboAntiguedad" class="combo">
		    <option value="T" <?php echo ($antiguedad=='T')? "selected=selected":""; ?>> <?php echo(ETIQUETA_TODOS); ?> </option>
		    <option value="1" <?php echo ($antiguedad==1)? "selected=selected":""; ?>> <?php echo(ETIQUETA_A_ESTRENAR); ?> </option>
		    <option value="2" <?php echo ($antiguedad==2)? "selected=selected":""; ?>> <?php echo(ETIQUETA_HASTA); ?> 10 <?php echo(ETIQUETA_ANOS); ?> </option>
		    <option value="3" <?php echo ($antiguedad==3)? "selected=selected":""; ?>> <?php echo(ETIQUETA_HASTA); ?> 25 <?php echo(ETIQUETA_ANOS); ?> </option>
		    <option value="4" <?php echo ($antiguedad==4)? "selected=selected":""; ?>> <?php echo(ETIQUETA_HASTA); ?> 50 <?php echo(ETIQUETA_ANOS); ?> </option>
		    <option value="5" <?php echo ($antiguedad==5)? "selected=selected":""; ?>> <?php echo(ETIQUETA_MAS_DE); ?> 50 <?php echo(ETIQUETA_ANOS); ?> </option>
		    </select>
		    
		</div>
		
		    
		<div id="divboton">
        
        
            <ul > 
            <li onclick="javascript: buscar('<?php echo($modulo); ?>','buscar');"> <?php echo ( ETIQUETA_BOTON_BUSCAR); ?> </li>        
            </ul>

		</div>			
		</form>	
</div>


<?php if($accion!='buscar'){ ?>

	<div id="destacadas">

		<center><h4> <?php echo(ETIQUETA_TITULO_DESTACADAS); ?> </h4></center>

		<ul>
		
		<li>
		<div >
		<a href="index.php?modulo=2&amp;accion=ver_detalle&amp;idioma=es&amp;propiedad=353">
		<img src="modulos/propiedades/loadImage.php?idp=353&amp;id=3381"> </img></a>
		</div>
		
		<div >
		<strong>Billinghurst al 2200 - Palermo </strong>  Excelente departamento de 4 ambientes en venta.
		</div>
		
		</li>
		
		<li>
		<div >
		<a href="index.php?modulo=2&amp;accion=ver_detalle&amp;idioma=es&amp;propiedad=192">
		<img src="modulos/propiedades/loadImage.php?idp=192&amp;id=1487"> </img></a>
		</div>
		
		<div >
		<strong>Perón 1600 - Balvanera</strong> Departamentos a estrenar en moderno edificio con detalles de categoría. Ideales para uso PROFESIONAL ó EMPRESARIAL.
		</div>
		
		</li>
		
		<li>
		<div >
		<a href="index.php?modulo=2&amp;accion=ver_detalle&amp;idioma=es&amp;propiedad=285">
		<img src="modulos/propiedades/loadImage.php?idp=285&amp;id=2872"> </img></a>
		</div>
		
		<div >	
		<strong>Ituzaingo al 500 - Barracas</strong> Excelente PH reciclado utilizando materiales de alta calidad y muy buen gusto en el diseño. 237 M2.  
		</div>
		
		</li>
			
		</ul>


	</div>
	
<?php } ?>


</div>
