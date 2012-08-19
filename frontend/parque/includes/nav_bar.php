<form id="formNav" method="POST" action="index.php"  name="formNav">
<input type="hidden" name="modulo" id="modulo" value="<?php echo $modulo; ?>"></input>
<input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>"></input>
<input type="hidden" name="idioma" id="idioma" value="<?php echo $idioma; ?>"></input>
<input type="hidden" name="propiedad" id="propiedad" value="<?php echo $propiedad; ?>"></input>
<input type="hidden" name="categoria" id="categoria" value="<?php echo $categoria; ?>"></input>
</form>

<div id="menu">

    <ul > 
        <li onclick="javascript: cambiarNodo(1);" >  <?php echo ( ETIQUETA_BOTON_INICIO); ?> </li>
        <li onclick="javascript: cambiarNodo(2);" >  <?php echo ( ETIQUETA_BOTON_PROPIEDADES); ?> </li>   
        <li onclick="javascript: cambiarNodo(5);" >  <?php echo ( ETIQUETA_BOTON_SOBRE_NOSOTROS); ?> </li>
        <li onclick="javascript: cambiarNodo(6);" >  <?php echo ( ETIQUETA_BOTON_HISTORIA); ?> </li>
        <li onclick="javascript: cambiarNodo(3);" >  <?php echo ( ETIQUETA_BOTON_SERVICIOS); ?> </li>
        <li onclick="javascript: cambiarNodo(4);" >  <?php echo ( ETIQUETA_BOTON_CONTACTO); ?> </li>  	
    </ul>
    
</div>


