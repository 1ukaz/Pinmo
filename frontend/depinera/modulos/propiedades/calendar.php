<?php
	include_once("../../../../old_classes/Sistema.class.php");
	require("../../includes/cal_functions.inc.php");

	//echo "<div align='left'><pre><font size='2'>"; print_r($_GET); echo "</font></pre></div>";
	
	$oSistema = new Sistema();

	if ((!$_GET['m']) && (!$_GET['y'])) {
		$actualMonth = date("n");
		$year = date("Y");
	} else {
		$actualMonth = $_GET['m'];
		$year = $_GET['y'];
	}
	
	if (!$_GET['p'])
		$idPropiedad = 1;
	else
		$idPropiedad = $_GET['p'];
		
	$arrayMonths = array(1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	
	if ($actualMonth >= 1 && $actualMonth <= 3) {
		$actualMonth = 2;
		$prevMonth = $actualMonth - 1;
		$nextMonth = $actualMonth + 1;
		$actualQuarter = "Primer Trimenstre ".$year;
		$prevQuarter = 11;
		$prevYear = $year - 1;
		$nextQuarter = 5;
		$nextYear = $year;
	}
	elseif ($actualMonth >= 4 && $actualMonth <= 6) {
		$actualMonth = 5;
		$prevMonth = $actualMonth - 1;
		$nextMonth = $actualMonth + 1;
		$actualQuarter = "Segundo Trimestre ".$year;
		$prevQuarter = 2;
		$prevYear = $year;
		$nextQuarter = 8;
		$nextYear = $year;
	}
	elseif ($actualMonth >= 7 && $actualMonth <= 9) {
		$actualMonth = 8;
		$prevMonth = $actualMonth - 1;
		$nextMonth = $actualMonth + 1;
		$actualQuarter = "Tercer Trimestre ".$year;
		$prevQuarter = 5;
		$prevYear = $year;
		$nextQuarter = 11;
		$nextYear = $year;
	}
	elseif ($actualMonth >= 10 && $actualMonth <= 12) {
		$actualMonth = 11;
		$prevMonth = $actualMonth - 1;
		$nextMonth = $actualMonth + 1;
		$actualQuarter = "Cuarto Trimestre ".$year;
		$prevQuarter = 8;
		$prevYear = $year;
		$nextQuarter = 2;
		$nextYear = $year + 1;
	}

	// Calcular el mes anterior
	$prev_Timestamp = mktime (0, 0, 0, $prevMonth, 1, $year);
	$prevMonthName = $arrayMonths[$prevMonth];

	// Calcular el mes actual
	$actual_Timestamp = mktime (0, 0, 0, $actualMonth, 1, $year);
	$actualMonthName = $arrayMonths[$actualMonth];

	// Calcular el mes siguiente
	$next_Timestamp = mktime (0, 0, 0, $nextMonth, 1, $year);
	$nextMonthName = $arrayMonths[$nextMonth];
	
	$contFechas = array();
	$contFechas = getBookedDatesFromDB( $prevMonth, $nextMonth, $year, $year, $idPropiedad );
	
	//echo "<div align='left'>Fechas:<pre><font size='2'>"; print_r($contFechas); echo "</font></pre></div>";
	
	?>
	<div id="CalendarWrapper"><?php //echo $actualQuarter; ?>
		<div id="MonthsWrapper">    
			<div id="CalendarFirst">
            	<?php printCalendarOnScreen( $prevMonthName, $year, $prevMonth, $prev_Timestamp, $contFechas ); ?>
			</div><!-- Fin CalendarFirst -->
			<div id="CalendarSecond">
            	<?php printCalendarOnScreen( $actualMonthName, $year, $actualMonth, $actual_Timestamp, $contFechas ); ?>
			</div><!-- Fin CalendarSecond -->
			<div id="CalendarThird">
            	<?php printCalendarOnScreen( $nextMonthName, $year, $nextMonth, $next_Timestamp, $contFechas ); ?>
			</div><!-- Fin CalendarThird -->			
		</div><!-- Fin MonthsWrapper -->
        <div id="CalendarControl">
          <a class="cal" onclick="loadCalendar('./modulos/propiedades/calendar.php?m=<?php echo($prevQuarter); ?>&y=<?php echo($prevYear); ?>&p=<?php echo $idPropiedad; ?>'); return nd();" onmouseover="return overlib('<center>Ver los tres Meses Anteriores</center>', LEFT, WIDTH, 180);" onmouseout="return nd();">Anteriores</a>
            &nbsp;&nbsp;||&nbsp;&nbsp;
          <a class="cal" onclick="loadCalendar('./modulos/propiedades/calendar.php?m=<?php echo($nextQuarter); ?>&y=<?php echo($nextYear); ?>&p=<?php echo $idPropiedad; ?>'); return nd();" onmouseover="return overlib('<center>Ver los tres Meses Siguientes</center>', WIDTH, 180);" onmouseout="return nd();">Siguientes</a>
        </div><!-- Fin CalendarControl -->
        <div id="CalendarRefs">
        	<div class="refLabel">Referencias:</div>
        	<div class="booked ref">&nbsp;</div>
            <div class="refLabel">>> Reservado</div>
        	<div class="notBooked ref">&nbsp;</div>
            <div class="refLabel">>> Libre</div>
        </div><!-- Fin CalendarRefs -->
	</div><!-- Fin de CalendarWrapper -->