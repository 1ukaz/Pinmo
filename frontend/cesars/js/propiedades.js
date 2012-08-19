function switchCapa( capa ){

	var strCapa ="";

	//oculto las 4 capas
	for(i=1; i<5; i++){
		strCapa = "capaPaso"+i;
		ocultarElemento(strCapa);

	}

	//muestro la capa seleccionada
	strCapa = "capaPaso" + capa;
	mostrarElemento( strCapa );

}