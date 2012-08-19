<?php

function dayIsBooked( $aDay, $aMonth, $aYear, $vectorReservas ) {
	$aDay = str_pad($aDay, 2, "0", STR_PAD_LEFT);
	$aMonth = str_pad($aMonth, 2, "0", STR_PAD_LEFT);
	
	$aDate = $aYear.$aMonth.$aDay;
	for ($j = 0; $j < count($vectorReservas); $j++)
			if (($aDate >= $vectorReservas[$j]["desde"]) && ($aDate <= $vectorReservas[$j]["hasta"])) {
				return true;
	}		
}

function printCalendarOnScreen( $aMonthName, $aYear, $aMonth, $aTimestamp, $aRsArray ) {
    $monthstart = date("w", $aTimestamp);
    $lastday = date("d", mktime (0, 0, 0, $aMonth + 1, 0, $aYear));
	$startdate = -$monthstart;

	$HTMLCad = "";
	$HTMLCad = "<table class=\"calendar\" border=\"1\" cellpadding=\"3\" cellspacing=\"0\"><tr><td colspan=\"7\" class=\"monthName\">".$aMonthName." ".$aYear."</td></tr><tr><td class=\"weekDays\">Do</td><td class=\"weekDays\">Lu</td><td class=\"weekDays\">Ma</td><td class=\"weekDays\">Mi</td><td class=\"weekDays\">Ju</td><td class=\"weekDays\">Vi</td><td class=\"weekDays\">Sa</td></tr>";							
		//Averiguar cuántas filas se necesitan o ponerle a todos los meses 6 para que tengan la misma altura xD
        $numrows = 6; //ceil (((date("t",mktime (0, 0, 0, $aMonth + 1, 0, $aYear)) + $monthstart) / 7));
                        
        //Crear el número apropiado de filas
        for ($k = 1; $k <= $numrows; $k++) {
        	$HTMLCad .= '<tr>';
            //Utilizar 7 columnas (para 7 días)...
            for ($i = 0; $i < 7; $i++) {
            	$startdate++;
                if (($startdate <= 0) || ($startdate > $lastday)) {
                	//Si tenemos un día en blanco en el calendario.
                    $HTMLCad .= '<td class="empty">&nbsp;</td>';
				}
				else {
					if (dayIsBooked( $startdate, $aMonth, $aYear, $aRsArray )) {
                    	$HTMLCad .= "<td class=\"booked\" onmouseover=\"return overlib('<center>Dia Reservado</center>', LEFT, WIDTH, 100);\" onmouseout=\"return nd();\">".$startdate."</td>";
                    }
                    else {
                    	$HTMLCad .= "<td class=\"notBooked\"onmouseover=\"return overlib('<center>Dia Libre</center>', WIDTH, 100);\" onmouseout=\"return nd();\">".$startdate."</td>";
                    }
                }
			} // End for $i
            $HTMLCad .= '</tr>';
		} // End for $k	
		$HTMLCad .= '</table>';
				
		print($HTMLCad);
}

function getBookedDatesFromDB( $fromMonth, $toMonth, $fromYear, $toYear, $idProp ) {
	$fromTimestamp = mktime(0, 0, 0, $fromMonth, 1, $fromYear);
	$toTimestamp = mktime (0, 0, 0, $toMonth, 1, $toYear);
	$prevFirstDay = $fromYear.str_pad($fromMonth, 2, "0", STR_PAD_LEFT)."01";
	$nextLastDay = $toYear.str_pad($toMonth, 2, "0", STR_PAD_LEFT).date("t", $toTimestamp);

	$sql  = "SELECT fechaDesde, fechaHasta FROM Reservaciones WHERE idPropiedad = ".$idProp;
	$sql .= " AND (fechaDesde BETWEEN '".$prevFirstDay."' AND '".$nextLastDay."' OR fechaHasta BETWEEN '".$prevFirstDay."' AND '".$nextLastDay."')";
	//echo $sql;

	$query = mysql_query($sql);
	
	if (!$query) {
		echo "<div style=\"width:100%; margin-top:150px; float:left;\"><div class=\"ui-state-error ui-corner-all\" style=\"margin-top:20px; margin-bottom:15px; padding:1em .3em 0 1.5em;\"> 
          		<p><span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\"></span> 
          		<strong>Error!</strong> No se pudieron recuperar las Reservas </p></div></div>";
		die();
	}
	
	if (mysql_num_rows($query) > 0) {
		$datesArray = array();
		while ($res = mysql_fetch_assoc($query)) {
			$datesArray[] = array( "desde" => str_replace("-", "", $res["fechaDesde"]), "hasta" => str_replace("-", "", $res["fechaHasta"]));		
		}
	
		return $datesArray;
	}
	
}

?>