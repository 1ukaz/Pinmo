


<div id="trimestre">
	
	<div class="calendario">
	
		<table cellspacing='0' cellpadding='0'>
				
		<tr>
		<td colspan="7"><?php echo(obtenerNombreMes($mes));?> </td>
		</tr>
		
		<tr>
			<th ><?php echo($DIA1);?></th>
			<th ><?php echo($DIA2);?></th>
			<th ><?php echo($DIA3);?></th>
			<th ><?php echo($DIA4);?></th>
			<th ><?php echo($DIA5);?></th>
			<th ><?php echo($DIA6);?></th>
			<th ><?php echo($DIA7);?></th>
		</tr>

		<?php

		//Variable para llevar la cuenta del dia actual
		$dia_actual = 1;

		//calculo el numero del dia de la semana del primer dia
		$numero_dia = calcula_numero_dia_semana(1,$mes,$ano);
		//echo "Numero del dia de demana del primer: $numero_dia <br>";

		//calculo el �ltimo dia del mes
		$ultimo_dia = ultimoDia ($mes,$ano);


		//escribo la primera fila de la semana
		?>

		<tr>
		<?php
		for ($i = 0;$i<7;$i++){
		
			//si el dia de la semana i es menor que el numero del primer dia de la semana no pongo
			// nada en la celda
			if ( $i < $numero_dia){ ?>
            
				<td>&nbsp;</td><?php
			}
			
			//si el dia de la semana i es mayor que el numero del primer dia lo pongo en la celda
			else{
				
				if(strlen($ano)==2){ $da = "20$ano"; } else{ $da = $ano;} 				
				if(strlen($mes)==1){ $da .= "0$mes"; } else{ $da .= $mes;} 				
				if(strlen($dia_actual)==1){ $da .= "0$dia_actual"; } else{ $da .= $dia_actual;}

				if( array_reservaciones_search( $aReservaciones, $da)){ ?>
					<td class='dia_disponible'><?php echo($dia_actual); ?> </td><?php
				}
				
				else{ ?>
					<td class='dia_reservado' ><?php echo($dia_actual); ?> </td> <?php
				}

				$dia_actual++;
			}
		}
		
		?>
		</tr>

		<?php
		//recorro todos los dem�s d�as hasta el final del mes
		$numero_dia = 0;

		while ($dia_actual <= $ultimo_dia){
		
			//si estamos a principio de la semana escribo el <TR>
			if ($numero_dia == 0){ ?>
				<tr > <?php
			}


			//----------------------------------------------------------------------------
			//esto es para marcar el fondo de los dias ocupados con otro color
			//----------------------------------------------------------------------------

			if(strlen($ano)==2){ $da = "20$ano"; } else{ $da = $ano;} 				
			if(strlen($mes)==1){ $da .= "0$mes"; } else{ $da .= $mes;} 				
			if(strlen($dia_actual)==1){ $da .= "0$dia_actual"; } else{ $da .= $dia_actual;}
			
			
			if( array_reservaciones_search( $aReservaciones, $da)){ ?>
				<td class='dia_disponible' ><?php echo($dia_actual); ?></td> <?php
			}

			else{ ?>
				<td class='dia_reservado'><?php echo($dia_actual); ?></td><?php
			}

			//----------------------------------------------------------------------------
			$dia_actual++;
			$numero_dia++;

			//si es el u�timo de la semana, me pongo al principio de la semana y escribo el </tr>
			if ($numero_dia == 7){
				$numero_dia = 0; ?>
				</tr> <?php
			}

		}

		//compruebo que celdas me faltan por escribir vacias de la �ltima semana del mes
		for ($i=$numero_dia;$i<7;$i++){ ?>
			<td>&nbsp;</td><?php
		}
		
		?>


		</table>
		
	</div>
	

	
	
	<?php 		
	/*********************************************************************************************************************************************************************/
	$mes = sumarFecha($strFechaIni,"1 MONTH","%m");
	$ano = sumarFecha($strFechaIni,"1 MONTH","%Y");
	?>
	
	<div class="calendario">
	
		<table cellspacing='0' cellpadding='0'>
				
		<tr>
		<td colspan="7"><?php echo(obtenerNombreMes($mes));?> </td>
		</tr>		
		
	
		<tr>
			<th ><?php echo($DIA1);?></th>
			<th ><?php echo($DIA2);?></th>
			<th ><?php echo($DIA3);?></th>
			<th ><?php echo($DIA4);?></th>
			<th ><?php echo($DIA5);?></th>
			<th ><?php echo($DIA6);?></th>
			<th ><?php echo($DIA7);?></th>
		</tr>
	
		<?php
	
		//Variable para llevar la cuenta del dia actual
		$dia_actual = 1;
	
		//calculo el numero del dia de la semana del primer dia
		$numero_dia = calcula_numero_dia_semana(1,$mes,$ano);
		//echo "Numero del dia de demana del primer: $numero_dia <br>";
	
		//calculo el �ltimo dia del mes
		$ultimo_dia = ultimoDia ($mes,$ano);
	
	
		//escribo la primera fila de la semana
		?>
	
		<tr>
		<?php
		for ($i = 0;$i<7;$i++){
		
			//si el dia de la semana i es menor que el numero del primer dia de la semana no pongo
			// nada en la celda
			if ( $i < $numero_dia){ ?>
	        
				<td>&nbsp;</td><?php
			}
			
			//si el dia de la semana i es mayor que el numero del primer dia lo pongo en la celda
			else{
				
				if(strlen($ano)==2){ $da = "20$ano"; } else{ $da = $ano;} 				
				if(strlen($mes)==1){ $da .= "0$mes"; } else{ $da .= $mes;} 				
				if(strlen($dia_actual)==1){ $da .= "0$dia_actual"; } else{ $da .= $dia_actual;}
	
				if( array_reservaciones_search( $aReservaciones, $da)){ ?>
					<td class='dia_disponible'><?php echo($dia_actual); ?> </td><?php
				}
				
				else{ ?>
					<td class='dia_reservado' ><?php echo($dia_actual); ?> </td> <?php
				}
	
				$dia_actual++;
			}
		}
		
		?>
		</tr>
	
		<?php
		//recorro todos los dem�s d�as hasta el final del mes
		$numero_dia = 0;
	
		while ($dia_actual <= $ultimo_dia){
		
			//si estamos a principio de la semana escribo el <TR>
			if ($numero_dia == 0){ ?>
				<tr > <?php
			}
	
	
			//----------------------------------------------------------------------------
			//esto es para marcar el fondo de los dias ocupados con otro color
			//----------------------------------------------------------------------------
	
			if(strlen($ano)==2){ $da = "20$ano"; } else{ $da = $ano;} 				
			if(strlen($mes)==1){ $da .= "0$mes"; } else{ $da .= $mes;} 				
			if(strlen($dia_actual)==1){ $da .= "0$dia_actual"; } else{ $da .= $dia_actual;}
			
			
			if( array_reservaciones_search( $aReservaciones, $da)){ ?>
				<td class='dia_disponible' ><?php echo($dia_actual); ?></td> <?php
			}
	
			else{ ?>
				<td class='dia_reservado'><?php echo($dia_actual); ?></td><?php
			}
	
			//----------------------------------------------------------------------------
			$dia_actual++;
			$numero_dia++;
	
			//si es el u�timo de la semana, me pongo al principio de la semana y escribo el </tr>
			if ($numero_dia == 7){
				$numero_dia = 0; ?>
				</tr> <?php
			}
	
		}
	
		//compruebo que celdas me faltan por escribir vacias de la �ltima semana del mes
		for ($i=$numero_dia;$i<7;$i++){ ?>
			<td>&nbsp;</td><?php
		}
		
		?>
	
	
		</table>
	</div>
	
		
	
			
	<?php 
	/*********************************************************************************************************************************************************************/
	$mes = sumarFecha($strFechaIni,"2 MONTH","%m");
	$ano = sumarFecha($strFechaIni,"2 MONTH","%Y");
	?>
	
	<div class="calendario">
	
		<table cellspacing='0' cellpadding='0'>
		
		<tr>
		<td colspan="7"><?php echo(obtenerNombreMes($mes));?> </td>
		</tr>	
		<tr>
			<th ><?php echo($DIA1);?></th>
			<th ><?php echo($DIA2);?></th>
			<th ><?php echo($DIA3);?></th>
			<th ><?php echo($DIA4);?></th>
			<th ><?php echo($DIA5);?></th>
			<th ><?php echo($DIA6);?></th>
			<th ><?php echo($DIA7);?></th>
		</tr>
	
		<?php
	
		//Variable para llevar la cuenta del dia actual
		$dia_actual = 1;
	
		//calculo el numero del dia de la semana del primer dia
		$numero_dia = calcula_numero_dia_semana(1,$mes,$ano);
		//echo "Numero del dia de demana del primer: $numero_dia <br>";
	
		//calculo el �ltimo dia del mes
		$ultimo_dia = ultimoDia ($mes,$ano);
	
	
		//escribo la primera fila de la semana
		?>
	
		<tr>
		<?php
		for ($i = 0;$i<7;$i++){
		
			//si el dia de la semana i es menor que el numero del primer dia de la semana no pongo
			// nada en la celda
			if ( $i < $numero_dia){ ?>
	        
				<td>&nbsp;</td><?php
			}
			
			//si el dia de la semana i es mayor que el numero del primer dia lo pongo en la celda
			else{
				
				if(strlen($ano)==2){ $da = "20$ano"; } else{ $da = $ano;} 				
				if(strlen($mes)==1){ $da .= "0$mes"; } else{ $da .= $mes;} 				
				if(strlen($dia_actual)==1){ $da .= "0$dia_actual"; } else{ $da .= $dia_actual;}
	
				if( array_reservaciones_search( $aReservaciones, $da)){ ?>
					<td class='dia_disponible'><?php echo($dia_actual); ?> </td><?php
				}
				
				else{ ?>
					<td class='dia_reservado' ><?php echo($dia_actual); ?> </td> <?php
				}
	
				$dia_actual++;
			}
		}
		
		?>
		</tr>
	
		<?php
		//recorro todos los dem�s d�as hasta el final del mes
		$numero_dia = 0;
	
		while ($dia_actual <= $ultimo_dia){
		
			//si estamos a principio de la semana escribo el <TR>
			if ($numero_dia == 0){ ?>
				<tr > 
				<?php
			}
	
	
			//----------------------------------------------------------------------------
			//esto es para marcar el fondo de los dias ocupados con otro color
			//----------------------------------------------------------------------------
	
			if(strlen($ano)==2){ $da = "20$ano"; } else{ $da = $ano;} 				
			if(strlen($mes)==1){ $da .= "0$mes"; } else{ $da .= $mes;} 				
			if(strlen($dia_actual)==1){ $da .= "0$dia_actual"; } else{ $da .= $dia_actual;}
			
			
			
			if( array_reservaciones_search( $aReservaciones, $da)){ ?>
				<td class='dia_disponible' ><?php echo($dia_actual); ?></td> <?php
			}
	
			else{ ?>
				<td class='dia_reservado'><?php echo($dia_actual); ?></td><?php
			}
	
			//----------------------------------------------------------------------------
			$dia_actual++;
			$numero_dia++;
	
			//si es el u�timo de la semana, me pongo al principio de la semana y escribo el </tr>
			if ($numero_dia == 7){
				$numero_dia = 0; ?>
				</tr> <?php
			}
	
		}
	
		//compruebo que celdas me faltan por escribir vacias de la �ltima semana del mes
		for ($i=$numero_dia;$i<7;$i++){ ?>
			<td>&nbsp;</td><?php
		}
		
		?>
	
	
		</table>
	</div>
			
</div>