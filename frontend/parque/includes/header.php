

<div id="header_left">
	<img src="imagenes/logo.png" />
    
</div>

<div id="header_right">
   
	<div id="barra_idioma">
		<?php echo(ETIQUETA_SELECTOR_IDIOMA);?>

        <img src="imagenes/bandera-<?php echo($idioma)?>.png" alt="flag" title="flag">
    
        <select class="combo" name="cboIdioma" id="cboIdioma" onchange="javascript: cambiarIdioma(this);" >
    
        <?php 
        /*cargo los idiomas definidos en la base de datos*/
        $aIdiomas = $oSistema->getIdiomas();
    
        foreach($aIdiomas as $keyIdioma=> $vIdioma){ ?>
    
            <option value="<?php echo($keyIdioma);?>" <?php echo($idioma==$keyIdioma)? "selected":""; ?>  > <?php echo ($vIdioma); ?> </option>
    
        <?php } ?>	
    
        </select>
	
	</div>
        
</div>