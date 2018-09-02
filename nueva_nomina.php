<?php 
session_start();
header("Content-Type: text/html; charset=iso-8859-1");
require("include/init.php");
require("fpdf17/fpdf.php");
include("fechas.php");
require("validacionAjax.php");
require("keyholder.php");
if ($_SESSION['decla_mensaje']  != "ok")
  {
  echo "<center><br /><br />" . $_SESSION['decla_mensaje'];
  exit();
  }
echo "<center>" . $_SESSION['crm_cliente_breadcrumb_switch'] . "</center>";
?>
<link rel="stylesheet" type="text/css" href="estilos/fancy.css" />
<?php
$IDUsuario = $_SESSION['crm_coinfo_id'];
$IDEmpresa = $_SESSION['crm_cliente_id'];
$sql_codigo="Select * From cartera_empresas Where IDEmpresa = '$IDEmpresa' ";
$result_codigo= mysql_query($sql_codigo);
$row_codigo = mysql_fetch_assoc($result_codigo);
require ("QR_Lib/BarcodeQR.php"); 

$token = explode("@", $_GET['token']);
$empleado = $token[2];
$Folio = $token[5];

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
	/*echo "<pre>";
	print_r($_POST);
	echo "</pre>";*/
	$idempleado = $_POST['idempleado'];
	$ID_Empresa = $_POST['ID_Empresa'];
	$IDUsuario = $_POST['IDUsuario'];
	$parametros = $_POST['parametros'];
	$Encontrados = $_POST['Encontrados_folios'];
	$Certificado = $_POST['Certificado'];
	$Numero_Certificado= $_POST['NumCer'];
	$NombreLlave= $_POST['Nombrellave'];
	$Clave_Certificado= $_POST['ClaveCertificado'];
	$Nombre = $_POST['empleado'];
	$RFC = $_POST['RFC'];
	$RFCL = $_POST['RFCL'];
	$NombreL = $_POST['NombreL'];+
	$EstadoL = $_POST['EstadoL'];
	$CiudadL = $_POST['CiudadL'];
	$CalleL = $_POST['CalleL'];
	$ColoniaL = $_POST['ColoniaL'];
	$CPL = $_POST['CPL'];
	$NumeroInteriorL = $_POST['InteriorL'];
	$NumeroExteriorL = $_POST['ExteriorL'];
	$CURP = $_POST['curp'];
	$NSS = $_POST['nss'];
	$Puesto = $_POST['puesto'];
	$Contrato = $_POST['contrato'];
	$FechaInicial = $_POST['fecha_inicial'];
	$Registro_patronal = $_POST['RegistroP'];
	$Departamento = $_POST['Departamento'];
	$Salario_diario = $_POST['SalarioDiario'];
	$Periodicidad = $_POST['PeriodoPago'];
	$TipoContrato = $_POST['TipoContrato'];
	$TipoJornada = $_POST['TipoJornada'];
	$Numero_empleado = $_POST['Num_empleado'];
	$FechaFin = $_POST['fecha_final'];
	$MPAGO = $_POST['MPAGO'];
	$FPAGO = $_POST['FPAGO'];
	$CondicionesPago = $_POST['Condiciones'];
	$CitaPago = $_POST['Cita'];
	$comprobante = $_POST['comprobante'];
	$Lugar = $_POST['Lugar'];
	$Regimen = $_POST['TipoRegimen'];
	$Regimen_empresa = $_POST['Regimen_empresa'];
	$Serie= $_POST['serie'];
	$Folio= $_POST['folios'];
	$Percepcionesx= $_POST['PercepcionesD'];
	$Periodo_Inicial= $_POST['txtPeriodo_Inicial'];
	$Periodo_Final1= $_POST['fecha_calcula'];
	$Periodo_Final2= $_POST['fecha_libre'];
	$Clabe= $_POST['CLABE'];
	$Banco= $_POST['Banco'];
	$Deduccionesx= $_POST['DeduccionesD'];
	$Percepciones_total= $_POST['Percepciones_total'];
	$Deducciones_total= $_POST['Deducciones_total'];
	$OtrosPagos_total= $_POST['OtrosPagos_total'];
	$Total= $_POST['TOTAL'];
	$Comprados= $_POST['Comprados'];
	$Usados= $_POST['Usados'];
	$FolioNomina= $_POST['Folio_Nomina'];
	$Switch= $_POST['switch'];
	$Horas_extra= $_POST['Horas_Extra'];
	$Incapacidades= $_POST['Incapacidades'];
	$FechaPago= $_POST['txtFechaPago'];
	$Dias_pagados = $_POST['txtDiasPagados'];
	$SubContrataciones = $_POST['SubContrataciones_hidden'];
	$OtrosPagos = $_POST['OtrosPagos_hidden'];
	$Extraordinaria = $_POST['extraordinaria'];
	$TipoRelacion = $_POST['TipoRelacion'];
	$UUIDRelacionado = $_POST['UUID'];
	$Confirmacion = $_POST['Confirmacion'];
	
	$Switch_Incapacidades= $_POST['switch_incapacidades_hidden'];
	$Switch_Subcontratacion= $_POST['switch_subcontratacion_hidden'];
	$Switch_Otros= $_POST['switch_otros_hidden'];
	
	echo "<br />Percepcionesx: " . $Percepcionesx;
	
	function valorEnLetras($x) 
	{ 
		global $Moneda;
		if ($x<0) { $signo = "menos ";} 
		else      { $signo = "";} 
		$x = abs ($x); 
		$C1 = $x; 
		$G6 = floor($x/(1000000));  // 7 y mas 
		$E7 = floor($x/(100000)); 
		$G7 = $E7-$G6*10;   // 6 
		$E8 = floor($x/1000); 
		$G8 = $E8-$E7*100;   // 5 y 4 
		$E9 = floor($x/100); 
		$G9 = $E9-$E8*10;  //  3 
		$E10 = floor($x); 
		$G10 = $E10-$E9*100;  // 2 y 1 
		$G11 = round(($x-$E10)*100,0);  // Decimales 
		////////////////////// 
		$H6 = unidades($G6); 
		if($G7==1 AND $G8==0) { $H7 = "Cien "; } 
		else {    $H7 = decenas($G7); } 
		
		$H8 = unidades($G8); 
		
		if($G9==1 AND $G10==0) { $H9 = "Cien "; } 
		else {    $H9 = decenas($G9); } 


		
		$H10 = unidades($G10); 
		
		if($G11 < 10) { $H11 = "0".$G11; } 
		else { $H11 = $G11; } 
		
		if($Moneda == "MXN")
		{
			$Tipo_imp_moneda = "Pesos ";
			$Mon_dec = "/100 M.N. "; 
		}
		if($Moneda == "USD")
		{
			$Tipo_imp_moneda = "Dolares ";
			$Mon_dec = "/100 USD. "; 
		}
		if($Moneda == "EUR")
		{
			$Tipo_imp_moneda = "Euros ";
			$Mon_dec = "/100 EUR. "; 
		}
		if($Moneda == "JPY")
		{
			$Tipo_imp_moneda = "Yenes ";
			$Mon_dec = "/100 JPY. "; 
		}
		///////////////////////////// 
		if($G6==0) { $I6=" "; } 
		elseif($G6==1) { $I6="Millón "; } 
		else { $I6="Millones "; } 
		
		if ($G8==0 AND $G7==0) { $I8=" "; } 
		else { $I8="Mil "; } 
		
		$I10 = $Tipo_imp_moneda; 
		$I11 = $Mon_dec; 
		$C3 = $signo.$H6.$I6.$H7.$I7.$H8.$I8.$H9.$I9.$H10.$I10.$H11.$I11; 
		
		return $C3; //Retornar el resultado 
	} 

	function unidades($u) 
	{ 
		if ($u==0)  {$ru = " ";} 
		elseif ($u==1)  {$ru = "Un ";} 
		elseif ($u==2)  {$ru = "Dos ";} 
		elseif ($u==3)  {$ru = "Tres ";} 
		elseif ($u==4)  {$ru = "Cuatro ";} 
		elseif ($u==5)  {$ru = "Cinco ";} 
		elseif ($u==6)  {$ru = "Seis ";} 
		elseif ($u==7)  {$ru = "Siete ";} 
		elseif ($u==8)  {$ru = "Ocho ";} 
		elseif ($u==9)  {$ru = "Nueve ";} 
		elseif ($u==10) {$ru = "Diez ";} 
		
		elseif ($u==11) {$ru = "Once ";} 
		elseif ($u==12) {$ru = "Doce ";} 
		elseif ($u==13) {$ru = "Trece ";} 
		elseif ($u==14) {$ru = "Catorce ";} 
		elseif ($u==15) {$ru = "Quince ";} 
		elseif ($u==16) {$ru = "Dieciseis ";} 
		elseif ($u==17) {$ru = "Decisiete ";} 
		elseif ($u==18) {$ru = "Dieciocho ";} 
		elseif ($u==19) {$ru = "Diecinueve ";} 
		elseif ($u==20) {$ru = "Veinte ";} 
		
		elseif ($u==21) {$ru = "Veintiun ";} 
		elseif ($u==22) {$ru = "Veintidos ";} 
		elseif ($u==23) {$ru = "Veintitres ";} 
		elseif ($u==24) {$ru = "Veinticuatro ";} 
		elseif ($u==25) {$ru = "Veinticinco ";} 
		elseif ($u==26) {$ru = "Veintiseis ";} 
		elseif ($u==27) {$ru = "Veintisiente ";} 
		elseif ($u==28) {$ru = "Veintiocho ";} 
		elseif ($u==29) {$ru = "Veintinueve ";} 
		elseif ($u==30) {$ru = "Treinta ";} 
		
		elseif ($u==31) {$ru = "Treinta y un ";} 
		elseif ($u==32) {$ru = "Treinta y dos ";} 
		elseif ($u==33) {$ru = "Treinta y tres ";} 
		elseif ($u==34) {$ru = "Treinta y cuatro ";} 
		elseif ($u==35) {$ru = "Treinta y cinco ";} 
		elseif ($u==36) {$ru = "Treinta y seis ";} 
		elseif ($u==37) {$ru = "Treinta y siete ";} 
		elseif ($u==38) {$ru = "Treinta y ocho ";} 
		elseif ($u==39) {$ru = "Treinta y nueve ";} 
		elseif ($u==40) {$ru = "Cuarenta ";} 
		
		elseif ($u==41) {$ru = "Cuarenta y un ";} 
		elseif ($u==42) {$ru = "Cuarenta y dos ";} 
		elseif ($u==43) {$ru = "Cuarenta y tres ";} 
		elseif ($u==44) {$ru = "Cuarenta y cuatro ";} 
		elseif ($u==45) {$ru = "Cuarenta y cinco ";} 
		elseif ($u==46) {$ru = "Cuarenta y seis ";} 
		elseif ($u==47) {$ru = "Cuarenta y siete ";} 
		elseif ($u==48) {$ru = "Cuarenta y ocho ";} 
		elseif ($u==49) {$ru = "Cuarenta y nueve ";} 
		elseif ($u==50) {$ru = "Cincuenta ";} 
		
		elseif ($u==51) {$ru = "Cincuenta y un ";} 
		elseif ($u==52) {$ru = "Cincuenta y dos ";} 
		elseif ($u==53) {$ru = "Cincuenta y tres ";} 
		elseif ($u==54) {$ru = "Cincuenta y cuatro ";} 
		elseif ($u==55) {$ru = "Cincuenta y cinco ";} 
		elseif ($u==56) {$ru = "Cincuenta y seis ";} 
		elseif ($u==57) {$ru = "Cincuenta y siete ";} 
		elseif ($u==58) {$ru = "Cincuenta y ocho ";} 
		elseif ($u==59) {$ru = "Cincuenta y nueve ";} 
		elseif ($u==60) {$ru = "Sesenta ";} 
		
		elseif ($u==61) {$ru = "Sesenta y un ";} 
		elseif ($u==62) {$ru = "Sesenta y dos ";} 
		elseif ($u==63) {$ru = "Sesenta y tres ";} 
		elseif ($u==64) {$ru = "Sesenta y cuatro ";} 
		elseif ($u==65) {$ru = "Sesenta y cinco ";} 
		elseif ($u==66) {$ru = "Sesenta y seis ";} 
		elseif ($u==67) {$ru = "Sesenta y siete ";} 
		elseif ($u==68) {$ru = "Sesenta y ocho ";} 
		elseif ($u==69) {$ru = "Sesenta y nueve ";} 
		elseif ($u==70) {$ru = "Setenta ";} 
		
		elseif ($u==71) {$ru = "Setenta y un ";} 
		elseif ($u==72) {$ru = "Setenta y dos ";} 


		elseif ($u==73) {$ru = "Setenta y tres ";} 
		elseif ($u==74) {$ru = "Setenta y cuatro ";} 
		elseif ($u==75) {$ru = "Setenta y cinco ";} 
		elseif ($u==76) {$ru = "Setenta y seis ";} 
		elseif ($u==77) {$ru = "Setenta y siete ";} 
		elseif ($u==78) {$ru = "Setenta y ocho ";} 
		elseif ($u==79) {$ru = "Setenta y nueve ";} 
		elseif ($u==80) {$ru = "Ochenta ";} 
		
		elseif ($u==81) {$ru = "Ochenta y un ";} 
		elseif ($u==82) {$ru = "Ochenta y dos ";} 
		elseif ($u==83) {$ru = "Ochenta y tres ";} 
		elseif ($u==84) {$ru = "Ochenta y cuatro ";} 
		elseif ($u==85) {$ru = "Ochenta y cinco ";} 
		elseif ($u==86) {$ru = "Ochenta y seis ";} 
		elseif ($u==87) {$ru = "Ochenta y siete ";} 
		elseif ($u==88) {$ru = "Ochenta y ocho ";} 
		elseif ($u==89) {$ru = "Ochenta y nueve ";} 
		elseif ($u==90) {$ru = "Noventa ";} 
		
		elseif ($u==91) {$ru = "Noventa y un ";} 
		elseif ($u==92) {$ru = "Noventa y dos ";} 
		elseif ($u==93) {$ru = "Noventa y tres ";} 
		elseif ($u==94) {$ru = "Noventa y cuatro ";} 
		elseif ($u==95) {$ru = "Noventa y cinco ";} 
		elseif ($u==96) {$ru = "Noventa y seis ";} 
		elseif ($u==97) {$ru = "Noventa y siete ";} 
		elseif ($u==98) {$ru = "Noventa y ocho ";} 
		else            {$ru = "Noventa y nueve ";} 
		return $ru; //Retornar el resultado 
	} 

	function decenas($d) 
	{ 
		if ($d==0)  {$rd = "";} 
		elseif ($d==1)  {$rd = "Ciento ";} 
		elseif ($d==2)  {$rd = "Doscientos ";} 
		elseif ($d==3)  {$rd = "Trescientos ";} 
		elseif ($d==4)  {$rd = "Cuatrocientos ";} 
		elseif ($d==5)  {$rd = "Quinientos ";} 
		elseif ($d==6)  {$rd = "Seiscientos ";} 
		elseif ($d==7)  {$rd = "Setecientos ";} 
		elseif ($d==8)  {$rd = "Ochocientos ";} 
		else            {$rd = "Novecientos ";} 
		return $rd; //Retornar el resultado 
	} 

	$Numero_letra = valorEnLetras($Total, "2");
     
	$Explode_rfcL = explode("-",$RFCL); 
	$Explode_rfc = explode("-",$RFC);
       
	$RFC_Final =  $Explode_rfc[0].$Explode_rfc[1].$Explode_rfc[2];
	$RFCL_Final =  $Explode_rfcL[0].$Explode_rfcL[1].$Explode_rfcL[2];
      
    if($FPAGO == "")
       $FPAGO = "No Identificado"; 
        
    if($MPAGO == "")
       $MPAGO = "NA"; 
    
    if($CondicionesPago == "")
       $CondicionesPago = "No Identificado"; 
    
    if($CitaPago == "")
       $CitaPago = "No Identificado"; 
    
    if($Lugar == "")
       $Lugar = "No Identificado"; 
    
    $Certificado = preg_replace('[\s+]',"", $Certificado); //Eliminamos los espacios del certificado
	function normaliza ($cadena) //Funcion para eliminar caracteres incorrectos
    { 
		$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ 
		ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ?? '; 
		$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuy 
		bsaaaaaaaceeeeiiiidnoooooouuuyybyRr '; 
	   
		$cadena = strtr($cadena,($originales), $modificadas); 
		return $cadena;
    } 
    
	function limpia_espacios ($cadena_limpia) //Funcion para eliminar caracteres incorrectos
	{ 
		$explode_limpia = explode(" ",$cadena_limpia); 
		$contador_limpia = count($explode_limpia);
		
		for($i=0;$i<$contador_limpia;$i++)
		{
			if($explode_limpia[$i]!= "")
			{
				$cadena_limpia_final.= $explode_limpia[$i]." "; 
			}
		}
		
		$cadena_limpia_final = trim($cadena_limpia_final);
		return $cadena_limpia_final;
	}
    
	//Eliminamos los espacios al principio y al final de las cadenas
    $Nombre= limpia_espacios($Nombre);
    $MPAGO_Final = trim($MPAGO);
    $FPAGO_Final = trim($FPAGO);
    $Regimen_empresa_sin = trim($Regimen_empresa);
    $Regimen_sin = trim($Regimen);
    $CondicionesPago_Final = trim($CondicionesPago);
    $CitaPago_Final = trim($CitaPago);
    $Lugar_Final = trim($Lugar);
    $NombreL = limpia_espacios ($NombreL);
    $EstadoL = limpia_espacios ($EstadoL);
    $CiudadL = limpia_espacios ($CiudadL);
    $CalleL = limpia_espacios ($CalleL);
    $ColoniaL = limpia_espacios ($ColoniaL);
    $CPL = limpia_espacios ($CPL);
    $NumeroInteriorL = limpia_espacios ($NumeroInteriorL);
    $NumeroExteriorL = limpia_espacios ($NumeroExteriorL);
    $Registro_patronal = limpia_espacios ($Registro_patronal);
    $Serie_Final = trim($Serie);
	$NombreL_Final = trim($NombreL);
	$Nombre_Final = trim($Nombre);
	$EstadoL_Final = trim($EstadoL);
	$CiudadL_Final = trim($CiudadL);
	$CalleL_Final = trim($CalleL);
	$ColoniaL_Final = trim($ColoniaL);
	$CPL_Final = trim($CPL);
	$NumeroInteriorL_Final = trim($NumeroInteriorL);
	$NumeroExteriorL_Final = trim($NumeroExteriorL);
	$Registro_patronal_Final = trim($Registro_patronal);
	$UUIDRelacionado_Final = trim($UUIDRelacionado);
	$Confirmacion_Final = trim($Confirmacion);
	$fecha = date("Y-m-d");
	$hora = date("H:i:s"); 
         
	if($Periodicidad == "99")
	{
		$Extra = "1";
		$Periodo_Final = $Periodo_Final2;
		if ($Periodo_Inicial != $Periodo_Final)
			$Dias_funcion = dates_range($Periodo_Inicial,$Periodo_Final);
		else	  
			$Dias_pagados = 1;
    }
    else
    {
		$Periodo_Final = $Periodo_Final1;
		// $Dias_pagados = 14;
        $Extra = "0";  
    }
	
	$sql_status = "SELECT FechaBaja FROM empleado_cancelacion WHERE IDEmpleado = '$idempleado'";
	$consulta_status = mysql_query($sql_status);
	if (mysql_num_rows($consulta_status) > 0)
	{
		echo "<style>
				body
				{
					font-family:Verdana, Geneva, sans-serif;
				}
				.titulo
				{
					background-color:#48A399;
					font-weight:bold;
					text-decoration-color: white;
				}
			  </style>";
		$renglon_status = mysql_fetch_assoc($consulta_status);
		$sql_empleado = "SELECT * FROM cartera_empleados_empresa WHERE IDEmpleado = '$idempleado'";
		$consulta_empleado = mysql_query($sql_empleado);
		$renglon_empleado = mysql_fetch_assoc($consulta_empleado);


		$sql_valida_fecha = "SELECT nc.FechaInicialPago, nc.FechaFinalPago, ec.FechaBaja, cee.FechaInicioLaboral
							   FROM nominacion_clientes as nc, empleado_cancelacion as ec , cartera_empleados_empresa as cee
							   WHERE nc.IDEmpleado = ec.IDEmpleado 
							   AND ec.IDEmpleado = cee.IDEmpleado
							   AND nc.TipoNomina = 'Base'
							   AND nc.IDEmpleado = '$idempleado'";
		$consulta_valida_fecha = mysql_query($sql_valida_fecha);
		//echo "sql_fechas: " . $sql_valida_fecha . " - " . mysql_error();
		
		if (mysql_num_rows($consulta_valida_fecha) == 0)
		{
			$renglon_valida_fechas['PeriodoFinal'] = $_POST['fecha_calcula'];
			$renglon_valida_fechas['FechaInicioLaboral'] = $_POST['fecha_inicio_laboral'];
		}
		else
		{
			$renglon_valida_fechas = mysql_fetch_assoc($consulta_valida_fecha);
		}

		if ($renglon_valida_fechas['FechaFinalPago'] < $renglon_valida_fechas['FechaInicioLaboral'])
		{
			echo "<br /><br />
				  <table border='1' bordercolor='#FFF' cellpadding='7' cellspacing='0' width='90%' align='center' bgcolor='#EAEAEA'>
					<tr>
					  <td align='right' class='titulo' width='35%'>Empleado</td>
					  <td  width='65%'>" . $renglon_empleado['Nombre']. "</td>
					</tr>
					<tr>
					  <td align='right' class='titulo'>Fecha de Baja</td>
					  <td bgcolor='#EAEAEA'>" . dameFecha($renglon_valida_fechas['FechaInicioLaboral']). "</td>
					</tr>
				  <table>";
			echo "<br /><table border='1' cellpadding='10' cellspacing='0' width='90%' align='center' bordercolor='#FF0000' >
					<tr>
					  <td colspan='2' bgcolor='#FFAAAA'align='center'>
						<font color='#CC0000'><b>La fecha final no puede ser anterior <br />a la fecha de inicio de relación laboral<br /></b></font>
					  </td>
					</tr>
					<tr>
					  <td align='center'>
					    <br />
						<input type='button' value='Cerrar' onclick='cerrar();' style='cursor:pointer'>
						&emsp;
						<input type='button' name='btnIntentar' value='Volver a Intentar' onclick='volver_intentar();' style='cursor:pointer'>
						<br />&emsp;
					  </td>
					</tr>
				  </table>";
			echo "<script>
					function volver_intentar() { window.location= 'nomina_base.php?token=$parametros'; }
					function cerrar() { parent.jQuery.fancybox.close(); }
				  </script>";
			exit();
		}
		
	  
	}
	
	$queryx = "Insert Into nominacion_clientes (IDEmpresa, IDEmpleado, FolioNomina, FormaPago, MetodoPago, CondicionesPago, LugarExpedicion,Fecha, HoraCaptura,IDCreador,FechaInicialPago, FechaFinalPago, NumeroDiasPagados,TotalPercepciones, TotalDeducciones,TotalOtrosPagos,Total,TotalPagado,TotalSaldo, TipoNomina,FechaPago,Extraordinario,TipoPeriodo)
                                                Values ('$IDEmpresa','$idempleado','$FolioFactura','$FPAGO_Final','$MPAGO_Final','$CondicionesPago_Final', '$Lugar_Final','$fecha','$hora','$IDUsuario','$Periodo_Inicial','$Periodo_Final', '$Dias_pagados','$Percepciones_total', '$Deducciones_total', 'OtrosPagos_total' ,'$Total','0' ,'0','PreNomina', '$FechaPago','1','$Periodicidad')";
	$resultx = mysql_query($queryx);
	//echo "<br />Queryx: " .$queryx ." - ". mysql_error();
	$IDINSERT = mysql_insert_id();  
	
	if ($UUIDRelacionado_Final != "")
	{
		$sql_cfdi = "INSERT INTO nominacion_clientes_cfdi (FolioSistema, TipoRelacion, UUIDRelacionado, Confirmacion)
													VALUES ('$IDINSERT','$TipoRelacion','$UUIDRelacionado_Final','$Confirmacion_Final')";
		$insercion_cfdi = mysql_query($sql_cfdi);
		echo "cfdi: " . $sql_cfdi . " - " . mysql_error();
	}
	else
	  if ($Confirmacion_Final != "")
	  {
		  $sql_confirmacion = "INSERT INTO nominacion_clientes_cfdi (FolioSistema, Confirmacion)
													VALUES ('$IDINSERT','$Confirmacion_Final')";
		  $insercion_confirmacion = mysql_query($sql_confirmacion);
		  echo "cfdi: " . $sql_confirmacion . " - " . mysql_error();
	  }
	
	$Valorfac_p = explode("--",$Percepcionesx); 
	$iteraciones1_p = count($Valorfac_p); 
	$Acumulado_Excento_p = 0;
	$Acumulado_Gravado_p =0;
	$j=1;
	
	echo "<pre>";
	print_r($Valorfac_p);
	echo "</pre>";
   
	while($j<$iteraciones1_p) 
	{
		$Agrupador_p = $Valorfac_p[$j]; //Seleccionamos el valor de medida que tiene como value su id          
		$j++; //i vale 4
		$Numero_p = $Valorfac_p[$j];
		$j++;
		$Concepto_p = $Valorfac_p[$j];
		$j++;
		$Gravado_p = $Valorfac_p[$j];
		$j++;	
		$Excento_p = $Valorfac_p[$j];
		$j++;	
		$IDConcepto_cartera = $Valorfac_p[$j];
		$j++;	
		$j++;	
		$TipoD = $Valorfac_p[$j];
		$j++;	
		$HorasD = $Valorfac_p[$j];
		$j++;	
		$DiasD = $Valorfac_p[$j];
		$j++;	
		$GravadoD = $Valorfac_p[$j];
		$j++;	
		$ExcentoD = $Valorfac_p[$j];
		$j++;	
		$ImporteD = $Valorfac_p[$j];
		$j++;	
		$TipoT = $Valorfac_p[$j];
		$j++;	
		$HorasT = $Valorfac_p[$j];
		$j++;	
		$DiasT = $Valorfac_p[$j];
		$j++;	
		$GravadoT = $Valorfac_p[$j];
		$j++;	
		$ExcentoT = $Valorfac_p[$j];
		$j++;	
		$ImporteT = $Valorfac_p[$j];
		$j++;	
		$TipoS = $Valorfac_p[$j];
		$j++;	
		$HorasS = $Valorfac_p[$j];
		$j++;	
		$DiasS = $Valorfac_p[$j];
		$j++;
		$GravadoS = $Valorfac_p[$j];
		$j++;	
		$ExcentoS = $Valorfac_p[$j];
		$j++;	
		$ImporteS = $Valorfac_p[$j];
		$j++;
		///////Empiezan valores de indemnizacion
		$TotalPagado = $Valorfac_p[$j];
		$j++;
		$AniosServicio = $Valorfac_p[$j];
		$j++;
		$UltimoSueldo = $Valorfac_p[$j];
		$j++;
		$IngresoAcumulableIndem = $Valorfac_p[$j];
		$j++;
		$IngresoNoAcumulableIndem = $Valorfac_p[$j];
		$j++;
		//////// Empiezan valores de pension
		$TotalExhibicion = $Valorfac_p[$j];
		$j++;
		$TotalParcialidad = $Valorfac_p[$j];
		$j++;
		$MontoDiario = $Valorfac_p[$j];
		$j++;
		$IngresoAcumulablePension = $Valorfac_p[$j];
		$j++;
		$IngresoNoAcumulablePension = $Valorfac_p[$j];
		$j++;
                                         
		if ($Gravado_p == "")
			$ImporteGravadoP = 0;
		else
			$ImporteGravadoP = $Gravado_p;

		if ($Excento_p == "")
			$ImporteExcentoP = 0;
		else
			$ImporteExcentoP = $Excento_p;
                                         
		$Acumulado_Excento_p = $Acumulado_Excento_p + $Excento_p; 
		$Acumulado_Gravado_p = $Acumulado_Gravado_p + $Gravado_p;  
		
		$query2 = "Insert Into nominacion_clientes_percepciones (FolioSistema, TipoPercepcion, Clave, Concepto, ImporteGravado, ImporteExcento, IDConceptoCartera)
		Values ('$IDINSERT', '$Agrupador_p', '$Numero_p', '$Concepto_p', round($ImporteGravadoP,2),round($ImporteExcentoP,2),'$IDConcepto_cartera')";
		$result2 = mysql_query($query2);
		echo "<br /><br />query2: " .$query2 ." - ". mysql_error();
		
		$ID_insert_percepcion = mysql_insert_id();
		echo mysql_error();
		
		if($Agrupador_p == "019") // Horas extra
		{
			if($HorasD != "" && $DiasD != "" && $ImporteD != "")
			{
				$query_dobles = "Insert Into nominacion_clientes_horasextras_detalle (FolioPercepcion, TipoHora, Horas, Dias, Importe, Gravado, Excento)
				Values ('$ID_insert_percepcion', '$TipoD', '$HorasD', '$DiasD', '$ImporteD','$GravadoD','$ExcentoD')";
				$result_dobles=mysql_query($query_dobles);  
			}
			if($HorasT != "" && $DiasT != "" && $ImporteT != "")
			{
				$query_triples = "Insert Into nominacion_clientes_horasextras_detalle (FolioPercepcion, TipoHora, Horas, Dias, Importe, Gravado, Excento)
				Values ('$ID_insert_percepcion', '$TipoT', '$HorasT', '$DiasT', '$ImporteT','$GravadoT','$ExcentoT')";
				$result_triples=mysql_query($query_triples);  
			}
			if($HorasS != "" && $DiasS != "" && $ImporteS != "")
			{
				$query_simples = "Insert Into nominacion_clientes_horasextras_detalle (FolioPercepcion, TipoHora, Horas, Dias, Importe, Gravado, Excento)
				Values ('$ID_insert_percepcion', '$TipoS', '$HorasS', '$DiasS', '$ImporteS','$GravadoS','$ExcentoS')";
				$result_simples=mysql_query($query_simples);  
			}
		}
		if(($Agrupador_p == "022") || ($Agrupador_p == "023") || ($Agrupador_p == "025")) /// Indemnización
		{
			$query_indemnizacion = "Insert Into nominacion_clientes_indemnizacion (FolioSistema, TotalPagado, NumAnios, UltimoSueldo, IngresoAcumulable, IngresoNoAcumulable)
				Values ('$ID_insert_percepcion', '$TotalPagado', '$AniosServicio', '$UltimoSueldo', '$IngresoAcumulableIndem','$IngresoNoAcumulableIndem')";
			$result_indemnizacion = mysql_query($query_indemnizacion);  
			echo "<br />Indemnizacion: " . $query_indemnizacion ." - ". mysql_error();
		}
		if(($Agrupador_p == "039") || ($Agrupador_p == "044")) /// Pensiones
		{
			$query_pension = "Insert Into nominacion_clientes_pensiones (FolioSistema, TotalUnaExhibicion, TotalParcialidad, MontoDiario, IngresoAcumulable, IngresoNoAcumulable)
				Values ('$ID_insert_percepcion', '$TotalExhibicion', '$TotalParcialidad', '$MontoDiario', '$IngresoAcumulablePension','$IngresoNoAcumulablePension')";
			$result_pension = mysql_query($query_pension);  
			echo "<br />Pension: " . $query_pension ." - ". mysql_error();
		}
	}  //CIERRA EL WHILE DE LAS PERCEPCIONES
                             
	$Valorfac = explode("--",$Deduccionesx); 
	$iteraciones1 = count($Valorfac); 
	$Acumulado_Excento_d = 0;
	$Acumulado_Gravado_d =0;
	$i=1;
	$ISR_Nomina = 0;
	while($i<$iteraciones1) 
	{
		$Agrupador_d = $Valorfac[$i]; //Seleccionamos el valor de medida que tiene como value su id          
		$i++; //i vale 2
		$Numero_d = $Valorfac[$i];
		$i++;
		$Concepto_d = $Valorfac[$i];
		$i++;
		$Gravado_d = $Valorfac[$i];
		$i++;	
		$Excento_d = $Valorfac[$i];
		$i++;
		$IDConcepto_cartera_d = $Valorfac[$i];
		$i++;
		$ImporteFinal_d = $Valorfac[$i];
		$i++;
		///////Empiezan valores de indemnizacion
		$DiasIncapacidad = $Valorfac[$i];
		$i++;
		$TipoIncapacidad = $Valorfac[$i];
		$i++;
		$Descuento = $Valorfac[$i];
		$i++;
		
		$sql_ISR="Select * From cartera_nomina_movimientos_empresa Where TipoDeduccion = '$Concepto_d' AND TipoNomina = 'd'";
		$result_ISR= mysql_query($sql_ISR) or die(mysql_error());         //Buscamos el nombre de medida con el id que tenemos en el explode
		$row_ISR = mysql_fetch_assoc($result_ISR);
		$row_ISR['Descripcion'];  //Nombre del concepto 
                                      
		if($row_ISR['Descripcion'] == "ISR")
		{
			$ISR_Nomina = $ISR_Nomina + $Excento_d;
			$Acumulado_Excento_d = $Acumulado_Excento_d + $Excento_d; 
		}
		else
		{
			$Acumulado_Excento_d = $Acumulado_Excento_d + $Excento_d; 
			$Acumulado_descuento = $Acumulado_descuento + $Excento_d; 
			$Acumulado_Gravado_d = $Acumulado_Gravado_d + $Gravado_d;   
		}

		if ($Gravado_d == "")
			$ImporteGravadoD = 0;
		else
			$ImporteGravadoD = $Gravado_d;
		
		if ($Excento_d == "")
			$ImporteExcentoD = 0;
		else
			$ImporteExcentoD = $Excento_d;
                                           
		$query2 = "Insert Into nominacion_clientes_deducciones (FolioSistema, TipoDeduccion, Clave, Concepto, ImporteGravado, ImporteExcento, IDConceptoCartera)
                    Values ('$IDINSERT', '$Agrupador_d', '$Numero_d', '$Concepto_d', round($ImporteGravadoD,2), round($ImporteExcentoD,2),'$IDConcepto_cartera_d')";
		$result2=mysql_query($query2);
		$ID_insert_deduccion = mysql_insert_id();
		
		if($Agrupador_d == "006") /// Incapacidad
		{
			$query_incapacidad = "Insert Into nominacion_clientes_incapacidades (FolioSistema, TipoIncapacidad, DiasIncapacidad, Descuento)
										Values ('$ID_insert_deduccion', '$TipoIncapacidad', '$DiasIncapacidad', '$Descuento')";
			$result_incapacidad = mysql_query($query_incapacidad);  
			//echo "<br />Incapacidad: " . $query_incapacidad ." - ". mysql_error();
		}
                                        
	}  //CIERRA EL WHILE DE LAS DEDUCCIONES
                                    
	$Valor_incapacidades = explode("--",$Incapacidades); 
	$iteraciones_incapacidades = count($Valor_incapacidades); 
	$k=1;
	
	while($k<$iteraciones_incapacidades) 
	{
		$Dias = $Valor_incapacidades[$k]; //Seleccionamos el valor de medida que tiene como value su id          
		$k++; //i vale 4
		$Tipo_Incapacidad = $Valor_incapacidades[$k];
		$k++;
		$Descuento = $Valor_incapacidades[$k];
		$k++;
		
		$query_incapacidad = "Insert Into nominacion_clientes_incapacidades (FolioSistema, TipoIncapacidad, DiasIncapacidad, Descuento)
		Values ('$IDINSERT', '$Tipo_Incapacidad', '$Dias', '$Descuento')";
		$result_incapacidad=mysql_query($query_incapacidad);
		//echo $query_borrar_incapacidades;
		echo mysql_error();
	}  
		
	$Valor_Contrataciones = explode("--",$SubContrataciones); 
	$iteraciones_contra = count($Valor_Contrataciones); 
	$h=1;
	while($h<$iteraciones_contra) 
	{
		$RFCLaboral = $Valor_Contrataciones[$h]; //Seleccionamos el valor de medida que tiene como value su id          
		$h++; //i vale 4
		$Porcentaje = $Valor_Contrataciones[$h];
		$h++;
		
		$query_subcontratos_insert = "Insert Into nominacion_clientes_subcontratos (FolioSistema, RFCLaboral, PorcentajeTiempo)
		Values ('$IDINSERT', '$RFCLaboral', '$Porcentaje')";
		$result_subcontratos_insert=mysql_query($query_subcontratos_insert);
		echo mysql_error();
	} 
                                    
	$Valor_Otros = explode("--",$OtrosPagos); 
	$iteraciones_otros = count($Valor_Otros); 
	$o=1;
	
	while($o<$iteraciones_otros) 
	{
		$TipoPago = $Valor_Otros[$o]; //Seleccionamos el valor de medida que tiene como value su id          
		$o++; //i vale 4
		$Clave = $Valor_Otros[$o];
		$o++;
		$Concepto = $Valor_Otros[$o];
		$o++;
		$Importe = $Valor_Otros[$o];
		$o++;
		$Subsidio = $Valor_Otros[$o];
		$o++;
		$SaldoFavor = $Valor_Otros[$o];
		$o++;
		$Anio = $Valor_Otros[$o];
		$o++;
		$Remanente = $Valor_Otros[$o];
		$o++;
		$IDConcepto_cartera_otros = $Valor_Otros[$o];
		$o++;
	
		if ($Importe == "")
			$ImporteOtros = 0;
		else
			$ImporteOtros = $Importe; 
		
		$qry_i="SELECT * from cartera_nomina_movimientos_empresa Where FolioInutil = '$IDConcepto_cartera_otros' ";
		$result_i=mysql_query($qry_i);
		$row_concepto = mysql_fetch_assoc($result_i);
		
		$query_otros_insert = "Insert Into nominacion_clientes_otrospagos (FolioSistema, TipoOtroPago, Clave, Concepto, Importe, SubsidioCausado, SaldoFavor, Anio, Remanente, IDConceptoCartera)
		Values ('$IDINSERT', '$TipoPago', '$Clave', '".$row_concepto['Descripcion']."', round($ImporteOtros,2), '$Subsidio', '$SaldoFavor', '$Anio', '$Remanente','$IDConcepto_cartera_otros')";
		$result_otros_insert=mysql_query($query_otros_insert);
		echo mysql_error();
	}  
	
	echo"
    <script language='javascript'>
        parent.jQuery.fancybox.close();
    </script>
    ";        
  
	$empleado = $idempleado;

}//// CIERRA EL POST

$query2 = "Select * From cartera_empleados_empresa Where IDEmpleado = '$empleado'";
$result2=mysql_query($query2);
$row2 = mysql_fetch_assoc($result2);
$nueva = 0;

$IDSerie = $row2['IDSerie'];
if ($IDSerie > 0)
{
	$sql_serie = "SELECT * FROM nominacion_clientes_folios WHERE IDSerie = '".$IDSerie."'";
	$consulta_serie = mysql_query($sql_serie);
	$renglon_serie = mysql_fetch_assoc($consulta_serie);
}
else
{
	$renglon_serie['TipoRegimen'] = $row2['TipoRegimen'];
	$renglon_serie['RegistroPatronal'] = $row2['RegistroPatronal'];
	$renglon_serie['RiesgoPuesto'] = $row2['RiesgoPuesto'];
	$renglon_serie['PeriodoPago'] = $row2['PeriodicidadPago'];
	$renglon_serie['IDDepartamento'] = $row2['IDDepartamento'];
	$renglon_serie['EntidadFed'] = $row2['EntidadFed'];
}

$queryInfo="Select * From facturacion_foliosSAT Where IDEmpresa='$IDEmpresa'"; 
$resultInfo= mysql_query($queryInfo) or die(mysql_error());
$Encontrados_folios = mysql_num_rows($resultInfo);
$rowInfo = mysql_fetch_assoc($resultInfo);

$Percepciones_explode = "";
$Horas_explode = "";
$Detalle_horas_explode = "";
$Indemnizacion_explode = "";
$Pensiones_explode = "";
$Deducciones_explode = "";
$OtrosPagos_explode = "";
$Subcontratos_explode = "";

$Encontrados_percepciones = 0;
$Encontrados_horas = 0;
$Encontrados_detalle_horas = 0;
$Encontrados_indemnizacion = 0;
$Encontrados_pensiones = 0;
$Encontrados_deducciones = 0;
$Encontrados_otros_pagos = 0;
$Encontrados_subcontratos = 0;
$Encontrados_detalle_horas_final = "";
$Encontrados_pensiones_final = "";
$Encontrados_indemnizaciones_final = "";

?>

<style>
.letra_fondo
{
	background-color: #d2d2d2;
	font-size:12px;
	border:2;
	border-style:inset;
}
.letra
{
	font-size:12px;
}
.letra2
{
	font-size:14px;
}
</style>

<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

<script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar.js?random=20060118"></script>
<script>
 $.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '<Ant',
 nextText: 'Sig>',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
$(function () {
$("#txtPeriodo_Inicial").datepicker({ dateFormat: "yy-mm-dd" });
});
$(function () {
$("#txtPeriodo_Final").datepicker({ dateFormat: "yy-mm-dd" });
});
$(function () {
$("#txtFechaPago").datepicker({ dateFormat: "yy-mm-dd" });
});
</script>  

<link rel="stylesheet" href="bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
        
<form method="POST" action="nueva_nomina.php" name="forma"> 
 <input type="hidden"  name="IDUsuario"  value="<?php echo $IDUsuario?>"/>
 <input type="hidden" name="parametros" value="<?php echo $_GET[token];?>" />
 <input type="hidden"  name="renglon" value="0" size="6"/>
 <input type="hidden"  name="DeduccionesD"  size="6"/>
 <input type="hidden"  name="PercepcionesD"  size="6"/>
 <input type="hidden"  name="Incapacidades"  size="6"/>
 <input type="hidden"  name="Horas_Extra"  size="6"/>
 <input type="hidden"  name="conceptoee"  size="6"/>
 <input type="hidden"  name="switch"  value="<?php echo $switch?>" size="6"/>
 <input type="hidden"  name="Folio_Nomina"  value="<?php echo $row_base['FolioSistema']?>" size="6"/>
 <input type="hidden"  name="ID_Empresa"  value="<?php echo $token2?>" size="6"/>
 <input type="hidden"  name="fecha_inicial"  value="<?php echo $row2['FechaInicioLaboral']?>" size="6"/>
 <input type="hidden"  name="fecha_final"  value="<?php echo $row2['FechaFinLaboral']?>" size="6"/>
 <input type="hidden"  name="NombreL" value="<?php echo $row['RazonSocial'] ?>" size="6"/>
 <input type="hidden"  name="RFCL" value="<?php echo $row['RFC'] ?>" size="6"/>
 <input type="hidden"  name="CalleL" value="<?php echo $row['Calle'] ?>" size="6"/>
 <input type="hidden"  name="CiudadL" value="<?php echo $row['Ciudad'] ?>" size="6"/>
 <input type="hidden"  name="EstadoL" value="<?php echo $row['Estado'] ?>"  size="6"/>
 <input type="hidden"  name="ColoniaL" value="<?php echo $row['Colonia'] ?>" size="6"/>
 <input type="hidden"  name="CPL" value="<?php echo $row['CP'] ?>" size="6"/>
 <input type="hidden"  name="InteriorL" value="<?php echo $row['Interior'] ?>" size="6"/>
 <input type="hidden"  name="ExteriorL" value="<?php echo $row['Numero'] ?>" size="6"/>
 <input type="hidden"  name="paisl" value="<?php echo $row['Pais'] ?>" size="6"/>
 <input type="hidden"  name="Encontrados_folios"  value="<?php echo $Encontrados_folios?>" size="6"/>
 <input type="hidden"  name="Certificado" value="<?php echo $rowInfo['Certificado'] ?>"  size="6"/>
 <input type="hidden"  name="NumCer" value="<?php echo $rowInfo['NumCertificado'] ?>"  size="6"/>
 <input type="hidden"  name="Nombrellave" value="<?php echo $rowInfo['NombreLlave'] ?>"  size="6"/>
 <input type="hidden"  name="ClaveCertificado" value="<?php echo $rowInfo['ClaveCertificado'] ?>"  size="6"/>
 <input type="hidden"  name="RegistroP"  value="<?php echo $row2['RegistroPatronal']; ?>" size="6"/>
 <input type="hidden"  name="Departamento"  value="<?php echo $row2['Departamento']; ?>" size="6"/>
 <input type="hidden"  name="CLABE"  value="<?php echo $row2['CLABE']; ?>" size="6"/>
 <input type="hidden"  name="Banco"  value="<?php echo $row2['Banco']; ?>" size="6"/>
 <input type="hidden"  name="SalarioDiario"  value="<?php echo $row2['SalarioDiario']; ?>" size="6"/>
 <input type="hidden"  name="SalarioIntegrado"  value="<?php echo $row2['SalarioIntegrado']; ?>" size="6"/>
 <input type="hidden"  name="TipoRegimen"  value="<?php echo $row2['TipoRegimen']; ?>" size="6"/>
 <input type="hidden"  name="Periodicidad"  value="<?php echo $row2['PeriodicidadPago']; ?>" size="6"/>
 <input type="hidden"  name="TipoContrato"  value="<?php echo $row2['TipoContrato']; ?>" size="6"/>
 <input type="hidden"  name="TipoJornada"  value="<?php echo $row2['TipoJornada']; ?>" size="6"/>
 <input type="hidden"  name="Num_empleado"  value="<?php echo $row2['NumeroEmpleado']; ?>" size="6"/>
 <input type="hidden"  name="SubContrataciones_hidden"  value="" size="6"/>
 <input type="hidden"  name="OtrosPagos_hidden"  value="" size="6"/>
 <input type="hidden"  name="txtContadorPensiones" id="txtContadorPensiones"  value="<?php echo $Encontrados_pensiones ?>"/>
 <input type="hidden"  name="txtContadorIndemnizaciones" id="txtContadorIndemnizaciones"  value="<?php echo $Encontrados_indemnizacion ?>"/> 
  <tr>
    <td align="center" colspan="3">
      <table border="0" cellpadding="4" cellspacing="0" width="100%"  bgcolor="#FFFFFF">
		<tr>
		  <td colspan="3">
		    <?php 
            $query9 = "Select * From facturacion_clientes_folios Where IDEmpresa = '$token2'";
            $result9=mysql_query($query9);
            $row9 = mysql_fetch_assoc($result9);
            ?>
              <table cellpadding="3" cellspacing="0" width="100%" bgcolor="#FFFFFF" border="0">
                <tr id="tr_mensaje" style="display:none">
                  <td colspan="7" align='center'>
                    <table width="50%" cellpadding="12">
                      <tr>
                        <td bgcolor='#FFAAAA'  >
                          <div id="mensaje_errores" align="center" ></div>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td align="center">
                    <table width='100%' cellpadding="2" border="0" class="letra">
                      <tr>
                        <td align="center" colspan="7" bgcolor="7fc1ba">
                          <FONT FACE="arial" SIZE=4 COLOR=white>Información Empleado</FONT>
                        </td>  
                      </tr>
                      <tr>
                        <td align="right" >
						  <b>Contrato:</b>
                          <input type="hidden"  value="<?php echo $row2['TipoContrato']; ?>"  name="contrato"  size="26" readonly/>
                        </td>
                        <td colspan="3">
                          <?php
                          $sqlcontrato="Select * From nominacion_clientes_contratos Where Clave = '$row2[TipoContrato]'";
	                      $resultcontrato= mysql_query($sqlcontrato);
                          $row_contrato = mysql_fetch_assoc($resultcontrato);
                          echo $row_contrato['Descripcion']; 
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td width='10%' align="right">
                         <b>Nombre: </b>
                         <input type="hidden"  value="<?php echo $row2['Nombre']; ?>" name="empleado"  size="66" readonly/>
                        </td>
                        <td width='40%'>
                          <?php echo $row2['Nombre']; ?>  
                        </td>
                        <td align="right" width='10%'>
                          <b>CURP:</b>
                          <input type="hidden" value="<?php echo $row2['CURP']; ?>"  name="curp"  size="33" readonly/>
                        </td>
                        <td width='30%'>
                          <?php echo $row2['CURP']; ?> 
                        </td>
                      </tr>
                      <tr>
                        <td align="right" >
                          <b>RFC:</b>
                          <input type="hidden"  name="RFC" value="<?php echo $row2['RFC']; ?>" size="15" readonly/>  
                          <input type="hidden"  name="idempleado" id="idempleado" value="<?php echo $empleado; ?>" size="15" readonly/>
                        </td> 
                        <td>
                          <?php echo $row2['RFC']; ?> 
                        </td>
                        <td align="right" >
                          <b>NSS:</b>
                          <input type="hidden" value="<?php echo $row2['NSS']; ?>"  name="nss"  size="25" readonly/>
                        </td>
                        <td>
                          <?php echo $row2['NSS']; ?> 
                        </td>
                      </tr>
					  <tr>
                        <td align="right" >
                          <b>Puesto:</b>
                          <input type="hidden"  value="<?php echo $row2['Puesto']; ?>"  name="puesto"  size="26" readonly/>
                        </td>
                        <td>
                          <?php echo $row2['Puesto']; ?> 
                        </td>
                        <td align="right" >
                          <b>Departamento:</b>
                          <input type="hidden"  value="<?php echo $renglon_serie['IDDepartamento']; ?>"  name="contrato"  size="26" readonly/>
                        </td>
                        <td>
                          <?php
                          $sqldepartamento="Select * From nominacion_clientes_departamentos Where IDDepartamento = '".$renglon_serie['IDDepartamento']."'";
	                      $resultdepartamento= mysql_query($sqldepartamento);
                          $row_departamento = mysql_fetch_assoc($resultdepartamento);
                          echo $row_departamento['Departamento']; 
                          ?>
                        </td>
					  </tr>
					  <tr>
                        <td align="right" nowrap>
						  <b>Fecha Inicio:</b>
                          <input type="hidden"  value="<?php echo $row2['FechaInicioLaboral']; ?>"  name="fecha_inicio_laboral" id="fecha_inicio_laboral"  size="26" readonly/>
                        </td>
                        <td>
						  <?php echo dameFechaCompleta($row2['FechaInicioLaboral']); ?> 
                        </td>
                        <td align="right" >
						  <b>Jornada:</b>
						  <input type="hidden"  value="<?php echo $row2['TipoJornada']; ?>"  name="contrato"  size="26" readonly/>
                        </td>
                        <td>
						  <?php
                          $sqljornada="Select * From nominacion_clientes_jornadas Where Clave = '$row2[TipoJornada]'";
                          $resultjornada= mysql_query($sqljornada);
                          $row_jornada = mysql_fetch_assoc($resultjornada);
                          echo $row_jornada['Descripcion']; 
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td align="right">
                          <b>Tipo de nómina:</b>
                        </td>
                        <td>
                          <select name="extraordinaria" id="extraordinaria" style="width:60%" onchange="actualiza_periodo(this.value);">
                            <option value="-">Selecciona una Opción</option>
                            <option value="-">----------------------------------</option>
                            <option value="0">Ordinaria</option>
                            <option value="1">Extraordinaria</option> 
                          </select> 
                        </td>
                        <td align="right">
                          <b>Salario Diario:</b>
                        </td>
                        <td>
                          <?php echo $row2['SalarioDiario']; ?>
                        </td>
                      </tr>
                      <tr>
                        <td align="right">
                          <b>Periodo:</b>
                        </td>
                        <td>
                          <div id="periodo">
                          <select name="PeriodoPago" id="periodo_pago" style="width:60%" onchange="limpia_fechas()">
                            <option value="">Seleccione una Opcion</option>  
                            <option value="">----------------------</option> 
                          </select>
                          </div>
                        </td>
                        <td align="right" >
                          <b>S.D.I.:</b>
                          <input type="hidden"  value="<?php echo $row2['SalarioIntegrado']; ?>"  name="sdi" id="sdi" size="26" readonly/>
                        </td>
                        <td>
                          <?php echo $row2['SalarioIntegrado']; ?> 
                        </td>
                      </tr>
                      <tr>
                        <td align="right">
                          <?php
                          $sqlRegimen="Select * From decla_regimen_trabajador Where Clave = '".$renglon_serie['TipoRegimen']."'";
	                      $resultRegimen= mysql_query($sqlRegimen);
                          $rowR = mysql_fetch_assoc($resultRegimen);
                                                                 
                          $sqlRegimen2="Select * From decla_regimenes Where IDRegimen = '".$row['IDRegimen']."'";
	                      $resultRegimen2= mysql_query($sqlRegimen2);
                          $rowR2 = mysql_fetch_assoc($resultRegimen2);        
                          ?>
                          <input type="hidden"  name="Regimen_empresa"  value="<?php echo $rowR2['TipoRegimen'];?>" size="6"/>
                          <b>Regimen:</b>
                        </td>
                        <td>
                          <?php echo $rowR['Descripcion'];?>
                        </td>
                        <td></td>
                       <td align="right">
                         <img src="images/flechas-abajo-nomina.png" title="Mostrar Adicionales" id="adicional_muestra" border="0" onclick="mostrar_adicionales()" width="8%" style="cursor:pointer" />
                       </td>
                    </tr>
                    <tr>
                        <td colspan="4" align="center">
                		<!--------------------------------------------FLECHITAS------------------------------------------------->
						  <table width="100%" id="adicional" style='display:none' cellspacing="0" cellpadding="0" align="center" border="1" bordercolor="7fc1ba">
							<tr>
                 			  <td valign="center">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td>
                  				<table style="width: 100%" border="0" cellpadding="5" cellspacing="0" align="center">
                                  <tr>
                                    <td bgcolor="7fc1ba" align="center" width="100%"><FONT COLOR=white>CFDI Relacionados</FONT></td>
                                  </tr>
                                  <tr>
                                    <td align="center"> 
                                      Tipo Relación: 
                                      <select id="TipoRelacion" name="TipoRelacion" onchange="boton_cfdi(this.value)" style="width: 45%">
                                        <option value="">Seleccione</option>
                                        <option value="">------------------------------------</option>
                                        <?php
										$sql_tipo_relacion="Select * From SAT_c_TipoRelacion WHERE c_TipoRelacion = '04'";
										$result_tipo_relacion= mysql_query($sql_tipo_relacion);
										while($row_tipo_relacion = mysql_fetch_assoc($result_tipo_relacion))
										{
										?>
											<option value="<?php echo $row_tipo_relacion['c_TipoRelacion']; ?>" selected="selected"><?php echo $row_tipo_relacion['c_TipoRelacion']." - ".$row_tipo_relacion['Descripcion']; ?></option>
										<?php } ?>
										</select>
                                    </td>
                                  </tr>
                                  <tr>
						    		<td align="center">
								  	  <table id="CFDI_relacionados" style="width:60%;" border="0" cellpadding="3" style="display:none">
                                		<tr>
                                          <td align="center" width="90%">
                                            <b>UUID:</b>
                                            <input type='text' autocomplete='off' onclick='(this.select())' value='<?php echo $renglon_cfdi['UUIDRelacionado']; ?>' id='UUID' name="UUID" style="width:82%" maxlength="36"/>
                                            <div id='myModal' class='modal fade'  tabindex='-1' role='dialog' aria-labelledby='basicModal' aria-hidden='true'>
                                              <div class='modal-dialog modal-lg'>
                                                <div class='modal-content'>
                                                  <div class='modal-header'>
                                                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                    <h4 class='modal-title'>Nóminas</h4>
                                                  </div>
                                                  <div class='modal-body'>
                                                    <div id='catalogo_uuid'></div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                          </td>
                                          <td align="center" width="10%">
                                            <img src='images/lupa.png' width='24' style='cursor:pointer' title='Buscar UUID' id='anadir' data-target='#myModal' data-toggle='modal' onclick='abre_ventana(" + contLin_cfdi + ")'/>
                                          </td>

                                        </tr>
								  	  </table>
									</td>
						   	  	  </tr>
								</table>
                          	  </td>
                        	</tr>
                        	<tr>
                  		  	  <td colspan="3">
                            	<table style="width:25%" align="center" cellpadding="5" cellspacing="0">
                              	  <tr>
                                	<td bgcolor="7fc1ba" align="center" width="100%"><FONT COLOR=white>Confirmación</FONT></td>
                              	  </tr>
                              	  <tr>
                                	<td >
                                  	  <input type="text"  onkeypress="return SoloNumeros(event)" autocomplete="new-password" name="Confirmacion" id="Confirmacion" value="<?php echo $renglon_cfdi['Confirmacion']; ?>" style="width:100%" maxlength="5"  /> 
                               	    </td>
                              	  </tr>
                                </table>
                                </td></tr></table>
                          	  </td>
                            </tr>
                          </table>
                		<!--------------------------------------------------------------------------------------------->
                        </td>
                        <td></td>
                      </tr>
					  <tr>
                        <td colspan='6'>
                          <hr size='2' color='7fc1ba'>
                        </td>
                      </tr>
					  <tr>
                        <td colspan="7" align="center">
						  <table border="0" width="100%" class="letra" cellspacing="0" cellpadding="5">
                            <tr>
                              <td width="20%">
                                <b>Comprobante</b><br>
							    <input type="text" value="Nómina" readonly style="width:80%;color:black;background:lightgray;" />
                                <input type="hidden" name="comprobante" value="nomina" />
                              </td>
                              <td width="20%" id="td_periodo_inicial">
                                <b> Periodo Inicial:</b><br />
                                <input type="text" id="txtPeriodo_Inicial" value="<?php echo $row_base['FechaInicialPago']; ?>" size="14" name="txtPeriodo_Inicial" readonly>
                              </td>
                              <td width="20%" id="td_fecha_calcula">
                                <b> Periodo Final: </b><br />
                                <input type="text"  value="click para calcular fecha final" size="16" style='color:gray;' id="fecha_calcula" onclick="calcula_fecha_final()" name="fecha_calcula" readonly >
                                <input type="hidden" value="" size="16" style='color:gray;' id="txtPeriodo_Final" onchange="valida_fechas();" name="fecha_libre" readonly>
                              </td> 
                              <td width="20%" id="td_fechapago" >
                                <b> Fecha Pago: </b><br />
                                <input type="text" id="txtFechaPago" value="<?php echo $row_base['FechaPago']; ?>" size="14" name="txtFechaPago" readonly>
                              </td> 
                              <td width="20%">
                                <b>  Dias Pagados: </b><br />
                                <input type="text" id="txtDiasPagados" value="<?php echo $row_base['NumeroDiasPagados']; ?>" size="14" name="txtDiasPagados" >
                                <img src="images/calc.jpg" />
                              </td> 
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td colspan='6'>
                          <hr size='2' color='7fc1ba'>
                        </td>
                      </tr>
					  <tr>
                        <td colspan="7" align="center">
						  <table width="100%" class="letra" cellpadding="2" cellspacing="3" border="1" bordercolor="#FFFFFF">
                            <tr>
                              <td align="center" nowrap width="18%">
                                <b>Forma de Pago</b>
                              </td>
                              <td align="center" nowrap width="33%">
                               <b>Metodo de Pago</b>
                              </td>
                              <td align="center" nowrap width="14%">
                                <b>Lugar Expedicion</b>
                              </td>
                              <td align="center" nowrap width="18%">
                               <b>Uso CFDI</b>
                              </td>
			  		        </tr>
                      	    <tr>                             
                              <td align="center" id="td_fpago" class="letra_fondo">
                                <?php 
							    $sql_forma = "SELECT * FROM SAT_c_FormaPago Where c_FormaPago = '99'";
							    $consulta_forma = mysql_query($sql_forma);
							  	$renglon_forma = mysql_fetch_assoc($consulta_forma);
								echo $renglon_forma['c_FormaPago'] . " - " . $renglon_forma['Descripcion'];
					            ?>
                              <input type="hidden" id="FPAGO" name="FPAGO" value="<?php $renglon_forma['c_FormaPago']; ?>" />
                              </td>
                              <td id="td_mpago" align="center" class="letra_fondo">
                                <?php 
							    $sql_metodo = "SELECT * FROM SAT_c_MetodoPago Where c_MetodoPago = 'PUE'";
							    $consulta_metodo = mysql_query($sql_metodo);
							    $renglon_metodo = mysql_fetch_assoc($consulta_metodo);
							    echo $renglon_metodo['Descripcion'];
					            ?>
                                <input type="hidden" id="MPAGO" name="MPAGO" value="<?php $renglon_metodo['c_MetodoPago']; ?>" />
							  </td>
							  <td align="center" class="letra_fondo">
                                <?php echo $row_codigo['CP']; ?>
                                <input type="hidden"  value="<?php echo $row_codigo['CP']?>" name="Lugar"  size="10" readonly/>
                              </td>
							  <td  align="center" class="letra_fondo">
                            	<?php 
								$sql_uso = "SELECT * FROM SAT_c_UsoCFDI Where c_UsoCFDI = 'P01'";
								$consulta_uso = mysql_query($sql_uso);
								$renglon_uso = mysql_fetch_assoc($consulta_uso);
								echo $renglon_uso['c_UsoCFDI'] . " - " . $renglon_uso['Descripcion'];
								?>
                              <input type="hidden" id="dcUsoCFDI" name="dcUsoCFDI" value="<?php $renglon_uso['c_UsoCFDI']; ?>" />
							  </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
           		  </td>
        	    </tr>
           	    <tr>
         		  <td>
            	    <table border="0" width="100%">
             		  <tr>
                        <td>
                          <table width="100%" align="center" colspan="9" >
                            <tr>
                              <td align="center" colspan="7" bgcolor="7fc1ba">
                                <FONT FACE="arial" SIZE=4 COLOR=white>Percepciones</FONT>
                              </td>  
                            </tr>
                            <tr>
                              <td colspan="3" align="center" >
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" id="tabla">
                                  <tr bgcolor="F1F1F1">
                                    <td align="center" width="10%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Agr.SAT</b></FONT></td>
                                    <td align="center" width="10%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Num.</b></FONT></td>
                                    <td align="center" width="34%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Concepto</b></FONT></td>
                                    <td align="center" width="12%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Gravado</b></FONT></td>
                                    <td align="center" width="12%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Excento</b></FONT></td>
                                    <td align="center" width="12%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Total</b></FONT></td>
                                    <td align="center" width="10%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Opciones</b></FONT></td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                            <tr>
                             <td><hr size='2' color='7fc1ba'></td>
                           </tr>
                          </table>
                        </td>
              		  </tr>
              		  <tr>
					    <td align="left" id="anadir">
                          <input type="button" value="Agregar Percepcion" onclick="agregar_percepcion(document.forma.renglon,'','',0,0,0)" style="cursor:pointer">
                        </td>
					  </tr>
            	    </table>
             	  </td>
                </tr>
			    <tr>
				  <td>
				    <table border="0" width="100%">
					  <tr>
					    <td>
                          <table width="100%" align="center" colspan="9">
						    <tr>
                              <td align="center" colspan="7" bgcolor="7fc1ba">
                                <FONT FACE="arial" SIZE=4 COLOR=white>Deducciones</FONT>
                              </td>  
                            </tr>
                            <tr>
							  <td colspan="3" align="center" >
                                <table width="100%" cellpadding="0" cellspacing="0"  id="tabla2">
                                  <tr bgcolor="F1F1F1">
                                    <td  align="center" width="10%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Agr.SAT</b></FONT></td>
                                    <td  align="center" width="10%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Num.</b></FONT></td>
                                    <td  align="center" width="34%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Concepto</b></FONT></td>
                                    <td  align="center" width="12%"><FONT FACE="arial" SIZE=3 COLOR=black><b></b></FONT></td>
                                    <td  align="center" width="12%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Importe</b></FONT></td>
                                    <td  align="center" width="12%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Total</b></FONT></td>
                                    <td  align="center" width="10%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Opciones</b></FONT></td>
                                  </tr>
                                </table>
							  </td>
						    </tr>
                   			<tr>
                              <td><hr size='2' color='7fc1ba'></td>
                            </tr>
						  </table>
					    </td>
					  </tr>
					  <tr>
                        <td align="left" id="anadir">
                          <input type="button" value="Agregar Deduccion" onclick="agregar_deducciones(document.forma.renglon,'','',0,0,0)" style="cursor:pointer">
                        </td>
              	      </tr>
					  <!----Aqui estaba incapacidad----->
    				</table>
            	  </td>
          	    </tr>
         	    <tr style="display:none">
           	      <td>
                    <input type='checkbox' name='otros_pagos_check' id='otros_pagos_check' onclick="muestra_otrospagos(this)">Otros Pagos
                    <input type='hidden' id='valor_check' value="0">
                  </td>
        	    </tr>
        <tr id="tr_otrospagos" >
          <td>
            <table border="0" width="100%">
              <tr>
                <td>
				  <table width="100%" align="center" colspan="9" >
                    <tr>
					  <td align="center" colspan="7" bgcolor="7fc1ba">
                        <FONT FACE="arial" SIZE=4 COLOR=white>Otros Pagos</FONT>
                      </td>  
                    </tr>
                    <tr>
                      <td colspan="3" align="center" >
                        <table width="100%" cellpadding="0" cellspacing="0" id="tabla_otrospagos">
                          <tr bgcolor="F1F1F1">
                            <td align="center" width="10%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Agr.SAT</b></FONT></td>
                            <td align="center" width="10%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Clave</b></FONT></td>
                            <td align="center" width="34%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Tipo</b></FONT></td>
                            <td align="center" width="12%"></td>
                            <td align="center" width="12%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Importe</b></FONT></td>
                            <td align="center" width="12%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Total</b></FONT></td>
                            <td align="center" width="10%"><FONT FACE="arial" SIZE=3 COLOR=black><b>Opciones</b></FONT></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td><hr size='2' color='7fc1ba'></td>
                    </tr>
                    <tr>
                      <td align="left" id="anadir">
                        <input type="button" value="Agregar Otro Pago" onclick="agregar_otrospagos('','','','0','','','','')" style="cursor:pointer">
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr> 
        <tr style="display:none">
		  <td>
			<input type='checkbox' id="subcontratos_check"  onclick="muestra_subcontratos(this)">SubContratacion
			<input type='hidden' id='valor_subcontratos' value="0">
		  </td>
        </tr>
        <tr>
		  <td align="center" colspan="7" bgcolor="7fc1ba"><FONT FACE="arial" SIZE=4 COLOR=white>SubContrataciones</FONT></td>  
        </tr>
        <tr>
		  <td align="center">
			<table width="100%" id="muestra_subcontrataciones" >
              <tr>
                <td>
				  <table width="50%" align="center" id="subcontrataciones"  colspan="9" >
                    <tr bgcolor="F1F1F1">
                      <td align="center" width="40%"><FONT FACE="arial" SIZE=3 COLOR=black><b>RFC Labora</b></FONT></td>
                      <td align="center" width="40%"><FONT FACE="arial" SIZE=3 COLOR=black><b>% de Tiempo</b></FONT></td>
                      <td align="center" width="10%"><FONT FACE="arial" SIZE=3 COLOR=black><b></b></FONT></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
              <td><hr size='2' color='7fc1ba'></td>
            </tr>
            <tr>
              <td align="left" id="anadir">
                <input type="button" value="Agregar SubContratación" onclick="agregar_subcontratacion()" style="cursor:pointer">
              </td>
            </tr>
			</table>
		  </td>
        </tr>
        <tr>
          <td align="center" colspan="3" bgcolor="FFFFFF">
            <FONT FACE="arial" SIZE=4 COLOR=white></FONT>
          </td>  
        </tr>
		<tr>
		  <td colspan="3" align="right">
			<table width="50%" id="totales" cellpadding="2">
			  <tr>                             
				<td align="center" bgcolor="48a399" COLSPAN="3"><FONT FACE="arial" SIZE=4 COLOR=white>TOTALES</font></td>
              </tr>
              <tr>                             
            	<td align="center" bgcolor="EAEAEA">
                  Percepciones
                </td>
                <td colspan="2" align="right" bgcolor="F1F1F1">
                   $ <input type="text" style='text-align:right;color:black;background:lightgray' id="Percepciones" name="Percepciones_total" value="0" size="10" readonly/>
                </td>
            	<td ></td>
              </tr>
              <tr>                             
            	<td align="center" bgcolor="EAEAEA">
                  Deducciones
                </td>
                <td colspan="2" align="right" bgcolor="F1F1F1"> 
                  $ <input type="text" style='text-align:right;color:black;background:lightgray' id="Deducciones" name="Deducciones_total" value="0" size="10" readonly/>
                </td>
            	<td ></td>
              </tr>
              <tr>                             
            	<td align="center" bgcolor="EAEAEA">
                  Otros Pagos
                </td>
                <td colspan="2" align="right" bgcolor="F1F1F1"> 
                  $ <input type="text" style='text-align:right;color:black;background:lightgray' id="OtrosPagos_total" name="OtrosPagos_total" value="0" size="10" readonly/>
                </td>
            	<td ></td>
              </tr>
              <tr>                             
            	<td align="center" bgcolor="EAEAEA">TOTAL</td><td colspan="2" align="right" bgcolor="F1F1F1"> $ <input type="text" style='text-align:right;color:black;background:lightgray' id="TOTAL" name="TOTAL" value="0" size="10" readonly/></td>
            	<td ></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
		  <td colspan="3"><br /><hr size="1" color="#76bfb9" /></td>
        </tr>
        <tr>
		  <td align="center" colspan="3">
			<input type="button" id="regresar" value="Regresar" onclick="regresar_listado()" style="cursor:pointer"/>
            &emsp;
			<input type="button" id="vista" value="Vista Previa" onclick="vista_previa()" style="cursor:pointer"/>
            &emsp;
            <input type="button" id="procesar" value="Guardar" onclick="Procesar()" style="cursor:pointer"/>
		  </td>
		</tr>
	  </table>
	</td>
  </tr>
</table>
</td>
</tr>
</table>
</form>

<script language="javascript">
   var http = false;
   var http_p = false;
   var http_d = false;
   var tex;

  if(navigator.appName == "Microsoft Internet Explorer") {
      http = new ActiveXObject("Microsoft.XMLHTTP");
      http_p = new ActiveXObject("Microsoft.XMLHTTP");
      http_d = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
      http = new XMLHttpRequest();
       http_p = new XMLHttpRequest();
        http_d = new XMLHttpRequest();
    }
var nav4 = window.Event ? true : false;
var contLin = 0;
var contLin_indemnizacion = 0;
var contLin_pension = 0;
var contLin2 = 0;
var contLin3 = 0;
var contLin4 = 0;
var renglon = 0;
var renglon_pension = 0;
var renglon_indemnizacion = 0;
var renglon2 = 0;
var renglon3 = 0;
var renglon4 = 0;
var visibles = 0;
var conteo_horas = 0;
var conteo_incapacidad = 0;
var conteo_pension = 0;
var conteo_indemnizacion = 0;
var contLin_otrospagos = 0;
var contLin_subcontratacion = 0;

function SoloNumeros(e,id)
{

   var key = nav4 ? e.which : e.keyCode;
   return (key <= 13 || (key >= 48 && key <= 57) || key == 46);
  
}

function numero(e) 
{
 var codigo; 
 codigo = (document.all) ? e.keyCode : e.which; 
 if (codigo > 31 && (codigo < 48 || codigo > 57) ) 
 {
 return false;
 }
 return true;
}

function Decimal(numero) 
{
    var charCode;
    charCode = numero.keyCode;
    status = charCode;

    if (charCode > 47 && charCode < 58)
        return true;
    else
        if (charCode == 46)
            return true;
        else
            return false;
}

function val(e) 
{
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true;
    patron =/[A-Za-z]/;
    te = String.fromCharCode(tecla);
    return patron.test(te);
}



function sumas_importes()
{
	var auxiliar = 0;      
	for(i=0; i<renglon; i++)
	{   
		var txtUnitarios= "Importe" + i;
		var Subtotal = Number(document.getElementById(txtUnitarios).value);
		auxiliar = auxiliar + Number(Subtotal.toFixed(2));
	} 
	document.getElementById('subtotal').value = auxiliar.toFixed(2);
}

function sumas_IVA()
{
	var auxiliar2 = 0;
	for(i=0; i<renglon; i++)
	{   
	  var txtIVASTOTALES = "IVATOTALHidden" + i;
	  var SUMASIVA = Number(document.getElementById(txtIVASTOTALES).value);
	  auxiliar2 = auxiliar2 + Number(SUMASIVA.toFixed(2));
	} 
	document.getElementById('TIVA').value = auxiliar2.toFixed(2);
}

function suma_TOTAL()
{     
	var IVAST = Number(document.getElementById('TIVA').value);
	var Subtotales= Number(document.getElementById('subtotal').value);
	var Descuento= Number(document.getElementById('Descuento').value);
	var Retiva= Number(document.getElementById('RETIVA').value);
	var ISR= Number(document.getElementById('ISR').value);
	var IEPS= Number(document.getElementById('TIEPS').value);
	var ResIvas = IVAST.toFixed(2);
	var ResSub = Subtotales.toFixed(2);
	var ResDescuento = Descuento.toFixed(2);
	var ResRetiva = Retiva.toFixed(2);
	var ResISR = ISR.toFixed(2);
	var ResIEPS = IEPS.toFixed(2);
	
	var resultadoTotal = Number(ResSub) - Number(ResDescuento) + Number(ResIEPS) + Number(ResIvas) - Number(ResRetiva) - Number(ResISR);
	document.getElementById('TOTAL').value = resultadoTotal.toFixed(2);               
}

function ISR_IVA()
{
	var RETPorciento = Number(document.getElementById('RETPorciento').value);
	var ISRPorciento = Number(document.getElementById('ISRPorciento').value);
	var Subtotales= Number(document.getElementById('subtotal').value);
	var Descuento= Number(document.getElementById('Descuento').value);
	var ResSub = Subtotales.toFixed(2);
	var ResDescuento = Descuento.toFixed(2);
	var ResRetiva = RETPorciento.toFixed(2);
	var ResISR = ISRPorciento.toFixed(2);
	var ImporteDescuento = Number(ResSub) - Number(ResDescuento);
	
	var Retiva = ImporteDescuento.toFixed(2) * Number(ResRetiva) / 100;
	var ISR = ImporteDescuento.toFixed(2) * Number(ResISR) / 100;
	
	
	document.getElementById('RETIVA').value = Retiva.toFixed(2);  
	document.getElementById('ISR').value =  ISR.toFixed(2);
	
	suma_TOTAL();
}

var adicionales_ocultos = 1; 
var contLin_cfdi = 0;
function mostrar_adicionales()
{
	var adicional_temporal = adicionales_ocultos;
	if(adicionales_ocultos == 1)
	{
		document.getElementById('adicional').style.display = "table";
		document.getElementById('adicional_muestra').title = "Ocultar Adicionales";
		document.getElementById('adicional_muestra').src = "images/flechas-arriba-nomina.png";
		adicionales_ocultos = 0;
	}
	if(adicionales_ocultos == 0 && adicional_temporal != 1)
	{
		document.getElementById('adicional').style.display = "none";
		document.getElementById('adicional_muestra').title = "Mostrar Adicionales";
		document.getElementById('adicional_muestra').src = "images/flechas-abajo-nomina.png";
		adicionales_ocultos = 1;
	}
} 

function boton_cfdi(valor)
{
	var i = 0;
	if(valor != "")
	{
		document.getElementById("CFDI_relacionados").style.display = "table";
		//agregar_cfdi('');
	}
	else
	{
		document.getElementById("CFDI_relacionados").style.display = "none";
		document.getElementById("UUID").value = "";
	}
}

function actualiza_listado_uuid() 
{    
	var nombre = "http-";
	if(navigator.appName == "Microsoft Internet Explorer") 
		nombre = new ActiveXObject("Microsoft.XMLHTTP");
	else 
		nombre = new XMLHttpRequest();
		
	nombre.abort();
	document.getElementById('catalogo_uuid').innerHTML = "<div align='center'><img src='images/cargando.gif'></div>";
	cadena="listado_nominas_uuid.php?idempleado="+document.getElementById("idempleado").value;
	nombre.open("GET", cadena, true);
	nombre.onreadystatechange=function() 
	{
		if(nombre.readyState == 4) 
		{
			document.getElementById('catalogo_uuid').innerHTML = nombre.responseText;
			actualiza_listado_carga(1,'Fecha desc'); 
		}
	}
	nombre.send(null);
}

function actualiza_listado_carga(pagina, orden) 
{ 
	var nombre = "http--";
	if(navigator.appName == "Microsoft Internet Explorer") 
		nombre = new ActiveXObject("Microsoft.XMLHTTP"); 
	else
		nombre = new XMLHttpRequest();
    
	nombre.abort();
	// document.getElementById('listado').innerHTML = "<div align='center'><img src='images/cargando.gif'></div>";
	document.getElementById("pagina").value = pagina;
	cadena="actualiza_nominas_uuid.php?token=" + 
											"@" + document.getElementById("Folio").value  + 
											"@" + document.getElementById("Status").value  + 
											"@" + document.getElementById("dcMes").value + 
											"@" + document.getElementById("dcAnyo").value + 
											"@" + document.getElementById("idempleado").value + 
											"@" + pagina + 
											"@" + orden + 
											"";
	nombre.open("GET", cadena, true);
	nombre.onreadystatechange=function() 
	{
		if(nombre.readyState == 4) 
		{
			document.getElementById('listado').innerHTML = nombre.responseText;
		}
	}
	nombre.send(null);
}

function abre_ventana()
{
	document.getElementById('myModal').style.display = "block";
	actualiza_listado_uuid() ;
}
function cierra_ventana()
{
	document.getElementById('myModal').style.display = "none"; 
}

function asigna_uuid(valor)
{
	document.getElementById("UUID").value = valor;
	cierra_ventana();
}

function muestra_otrospagos(casilla)
{
	var o= 0;
	if (casilla.checked == true)
	{
	document.getElementById('valor_check').value = "1";
	document.getElementById('tr_otrospagos').style.display = "table-row";
	document.getElementById('switch_otros_hidden').value ="1";
	} 
	else
	{
		document.getElementById('valor_check').value = "0";
		document.getElementById('tr_otrospagos').style.display = "none";
		document.getElementById('switch_otros_hidden').value ="0";
		
		for(o=0; o<contLin_otrospagos; o++)
		{
		  var OtroTipo = "dcOtroTipo" + o;
		  document.getElementById(OtroTipo).value = "";
		  
		  var ClaveOtro = "ClaveOtro" + o;
		  document.getElementById(ClaveOtro).value = "";
		  
		  var ConceptoOtro = "ConceptoOtro" + o;
		  document.getElementById(ConceptoOtro).value = "";
		 
		  var ImporteOtro = "ImporteOtro" + o;
		  document.getElementById(ImporteOtro).value = "0";
		  
		  var SubSidioCausado = "SubsidioCausado" + o;
		  document.getElementById(SubSidioCausado).value = "";
		  
		  var SaldoFavor = "SaldoFavor" + o;
		  document.getElementById(SaldoFavor).value = "";
		  
		  var AnioOtro = "AnioOtro" + o;
		  document.getElementById(AnioOtro).value = "";
		  
		  var Remanente = "Remanente" + o;
		  document.getElementById(Remanente).value = "";
		  suma_otrospagos(o);                          
		}
	}
}
                                      
function agregar_percepcion() 
{
	var tr, td;
	var cadena;
	tr = document.all.tabla.insertRow();
	tr.id = 'celda_p' + contLin;
	tr.setAttribute('bgcolor', 'F1F1F1');
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.setAttribute('colspan', '2');
	td.innerHTML = "<div id='agrupador_percepcion"+contLin+"'>\n\
					 </div>";
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.setAttribute('id', 'td_Concepto_p'+contLin);
	cadena = "<select id='conceptos"+ contLin + "' name='conceptos"+ contLin + "' onchange='seleccion_p(this.value,"+ contLin + ")' style='width: 98%' > <option value=''>Seleccione un Concepto</option><option>-----------------------</option>";
			   <?php
	$cadenaAuxiliar = "";
	$sql2p="Select * From cartera_nomina_movimientos_empresa Where TipoNomina = 'p' AND Descripcion != 'Percepciones' AND Descripcion != 'Deducciones' AND Status = '1' AND TipoDeduccion != '017' AND IDEmpresa = '$IDEmpresa' Order By Descripcion";
	$result2p= mysql_query($sql2p) or die(mysql_error());
	while($row6p = mysql_fetch_assoc($result2p))
	{
		$cadenaAuxiliar = $cadenaAuxiliar . "<option value=".$row6p['FolioInutil'].">" . htmlentities($row6p['Descripcion']) . "</option>";
	}                                                                          
                      ?> 
	cadena = cadena + "<?php echo $cadenaAuxiliar;?>" + "</select>"; 
       
	td.innerHTML = cadena;
      
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.setAttribute('id', 'td_Gravado_p'+contLin);
	td.innerHTML = "  <input type='text' value='0'  id='Gravado" + contLin + "' onclick='(this.select())' name='Gravado" + contLin + "' onblur='calcula_persepciones(" + contLin + ")' style='text-align:right;width:90%;' onkeypress='return SoloNumeros(event,"+ contLin +");' />\n\
	<input type='hidden' value='1'  id='StatusConcepto_p" + contLin + "' />";
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.setAttribute('id', 'td_Excento_p'+contLin);
	td.innerHTML = "  <input type='text' value='0'  id='Excento" + contLin + "' onclick='(this.select())' name='Excento" + contLin + "' onblur='calcula_persepciones(" + contLin + ")' style='text-align:right;width:90%;' onkeypress='return SoloNumeros(event,"+ contLin +");' />";
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.setAttribute('id', 'td_importe_percepcion'+contLin);
	td.innerHTML = "  <input type='text' value='0'  id='Importe" + contLin + "' name='Importe" + contLin + "' style='text-align:right;color:black;background:lightgray;width:90%;'' onkeypress='return SoloNumeros(event)' readonly/>";
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.innerHTML = "<img src='images/delete.png' title='Borrar Concepto' id='borrar" + contLin + "' onclick='borrarConcepto_p("+ contLin +")' style='width:24;cursor:pointer;'>";
	document.getElementById('conceptos' + contLin).focus();   
	detalles_percepcion(contLin);
	agregar_pension(contLin);
	agregar_indemnizacion(contLin);
	seleccion_p("",contLin);
	contLin++;
	renglon++;
}

function detalles_percepcion(posicion)
{
	var tr, td;
	var cadena;
	var cadena2;
	var cadena3;
	var cadena4;
	var cadena5;
	var cadena6;
	var cadena7;
	
	tr = document.all.tabla.insertRow();
	tr.id = 'detalles_percepcion' + posicion;
	td = tr.insertCell();
	td.setAttribute('colspan', '8');
	td.setAttribute('align', 'center');
        
	cadena = "<table border='0' width='50%' id='tabla_horas"+posicion+"' style='display:none' cellpadding='4'>\n\
				<tr bgcolor='a4d3d0' align='center'>\n\
			   	  <td><font color='#FFFFFF'>Tipo</font></td>\n\
				  <td><font color='#FFFFFF'>Horas</font></td>\n\
				  <td><font color='#FFFFFF'>Dias</font></td>\n\
				  <td><font color='#FFFFFF'>Gravado</font></td>\n\
				  <td><font color='#FFFFFF'>Excento</font></td>\n\
				</tr>\n\
				<tr><td>\n\
				    <input type='hidden' name='tipo_horasD"+ posicion + "' id='tipo_horasD"+ posicion + "' value='01'>\n\
					Dobles\n\
				  </td>\n\
				  <td >\n\
					<input type=text value='' onclick='(this.select())' onkeypress='return SoloNumeros(event)' onpaste='return false;' autocomplete='off'  id='HorasD"+ posicion + "' style='text-align:right;width:90%;' name='Horas"+ posicion + "'>\n\
				  </td>\n\
				  <td >\n\
					<input type=text value='' onclick='(this.select())' onkeypress='return numero(event)' onpaste='return false;' autocomplete='off'  id='Dias_horasD"+ posicion + "' style='text-align:right;width:90%;' name='Dias_horas"+ posicion + "'>\n\
				  </td>\n\
				  <td >\n\
					<input type=text value='0' onclick='(this.select())' onkeypress='return SoloNumeros(event)' onpaste='return false;' autocomplete='off'  id='gravado_horasD"+ posicion + "' onblur=\"suma_gravadoexcentohoras("+posicion+",'D')\" style='text-align:right;width:90%;' name='importe_horas"+ posicion + "'>\n\</td>\n\
				  <td >\n\
					<input type=text value='0' onclick='(this.select())' onkeypress='return SoloNumeros(event)' onpaste='return false;' autocomplete='off'  id='excento_horasD"+ posicion + "' onblur=\"suma_gravadoexcentohoras("+posicion+",'D')\" style='text-align:right;width:90%;' name='importe_horas"+ posicion + "'>\n\</td>\n\
				  <td >\n\
					<input type=hidden value='' onclick='(this.select())' onkeypress='return SoloNumeros(event)' onpaste='return false;' autocomplete='off'  id='importe_horasD"+ posicion + "' style='text-align:right;width:90%;' name='importe_horas"+ posicion + "'>\n\</td>\n\
				</tr>\n\
				<tr><td>\n\
					<input type='hidden' name='tipo_horasT"+ posicion + "' id='tipo_horasT"+ posicion + "' value='02'>\n\
					Triples\n\
				  </td>\n\
				  <td >\n\
					<input type=text value='' onclick='(this.select())' onkeypress='return numero(event)' onpaste='return false;' autocomplete='off'  id='HorasT"+ posicion + "' style='text-align:right;width:90%;' name='Horas"+ posicion + "'>\n\
				  </td>\n\
				  <td >\n\
			  	    <input type=text value='' onclick='(this.select())' onkeypress='return numero(event)' onpaste='return false;' autocomplete='off'  id='Dias_horasT"+ posicion + "' style='text-align:right;width:90%;' name='Dias_horas"+ posicion + "'>\n\
				  </td>\n\
				  <td >\n\
					<input type=text value='0' onclick='(this.select())' onkeypress='return SoloNumeros(event)' onpaste='return false;' autocomplete='off'  id='gravado_horasT"+ posicion + "' onblur=\"suma_gravadoexcentohoras("+posicion+",'T')\" style='text-align:right;width:90%;' name='importe_horas"+ posicion + "'>\n\</td>\n\
				  <td >\n\
					<input type=text value='0' onclick='(this.select())' onkeypress='return SoloNumeros(event)' onpaste='return false;' autocomplete='off'  id='excento_horasT"+ posicion + "' onblur=\"suma_gravadoexcentohoras("+posicion+",'T')\" style='text-align:right;width:90%;' name='importe_horas"+ posicion + "'>\n\</td>\n\
				  <td >\n\
					<input type=hidden value='' onclick='(this.select())' onkeypress='return soloNumeros(event,"+ posicion +");' onpaste='return false;' autocomplete='off'  id='importe_horasT"+ posicion + "' style='text-align:right;width:90%;' name='importe_horas"+ posicion + "'>\n\</td>\n\
				</tr>\n\
				<tr><td>\n\
					<input type='hidden' name='tipo_horasS"+ posicion + "' id='tipo_horasS"+ posicion +"' value='03'>\n\
					Simples\n\
			      </td>\n\
			      <td >\n\
				   <input type=text value='' onclick='(this.select())' onkeypress='return numero(event)' onpaste='return false;' autocomplete='off'  id='HorasS"+ posicion + "' style='text-align:right;width:90%;' name='Horas"+ posicion + "'>\n\
			   	  </td>\n\
			   	  <td >\n\
					<input type=text value='' onclick='(this.select())' onkeypress='return numero(event)' onpaste='return false;' autocomplete='off'  id='Dias_horasS"+ posicion + "' style='text-align:right;width:90%;' name='Dias_horas"+ posicion + "'>\n\
				  </td>\n\
				  <td >\n\
					<input type=text value='0' onclick='(this.select())' onkeypress='return SoloNumeros(event)' onpaste='return false;' autocomplete='off'  id='gravado_horasS"+ posicion + "' onblur=\"suma_gravadoexcentohoras("+posicion+",'S')\" style='text-align:right;width:90%;' name='importe_horas"+ posicion + "'>\n\</td>\n\
				  <td >\n\
					<input type=text value='0' onclick='(this.select())' onkeypress='return SoloNumeros(event)' onpaste='return false;' autocomplete='off'  id='excento_horasS"+ posicion + "' onblur=\"suma_gravadoexcentohoras("+posicion+",'S')\" style='text-align:right;width:90%;' name='importe_horas"+ posicion + "'>\n\</td>\n\
				  <td >\n\
					<input type=hidden value='' onclick='(this.select())' onkeypress='return soloNumeros(event,"+ posicion +");' onpaste='return false;' autocomplete='off'  id='importe_horasS"+ posicion + "' style='text-align:right;width:90%;' name='importe_horas"+ posicion + "'>\n\</td>\n\
				</tr>\n\
			  </table>\n\
			  ";
      td.innerHTML = cadena;                        
}

function agregar_pension(posicion) 
{
	var tr, td;
	var cadena;
	tr = document.all.tabla.insertRow();
	tr.id = 'pension' + posicion;
	td = tr.insertCell();
	td.setAttribute('colspan', '9');
	td.setAttribute('align', 'center');
	td.setAttribute('width', '100%');
	cadena = "<table border='0' width='80%' id='tabla_pension"+posicion+"' style='display:none' cellpadding='4'>\n\
				<input type='hidden' name='switch_pension_hidden"+posicion+"' id='switch_pension_hidden"+posicion+"' value='0' />\n\
				<input type='hidden' name='tipo_pension_hidden"+posicion+"' id='tipo_pension_hidden"+posicion+"' value='' />\n\
				<input type='hidden' value='' name='Total_Exhibicion_hidden"+posicion+"' id='Total_Exhibicion_hidden"+posicion+"' />\n\
				<input type='hidden' value='' name='Total_Parcialidad_hidden"+posicion+"' id='Total_Parcialidad_hidden"+posicion+"' />\n\
				<input type='hidden' value='' name='Monto_Diario_hidden"+posicion+"' id='Monto_Diario_hidden"+posicion+"' />\n\
				<input type='hidden' value='' name='Ingreso_Acumulable_p_hidden"+posicion+"' id='Ingreso_Acumulable_p_hidden"+posicion+"' />\n\
				<input type='hidden' value='' name='Ingreso_No_Acumulable_p_hidden"+posicion+"' id='Ingreso_No_Acumulable_p_hidden"+posicion+"' />\n\
				<tr bgcolor='a4d3d0'>\n\
				  <td colspan='5' align='center'>";
		  cadena+="<FONT SIZE=3 COLOR=white><div id='div_titulo_pension"+posicion+"'></div></FONT>\n\
				  </td>\n\
				</tr>\n\
			    <tr bgcolor='F1F1F1'>\n\
			      <td align='center' width='20%'><FONT FACE='arial' SIZE=2><b>Total Exhibición</b></FONT></td>\n\
			      <td align='center' width='20%'><FONT FACE='arial' SIZE=2><b>Total Parcialidad</b></FONT></td>\n\
			   	  <td align='center' width='19%'><FONT FACE='arial' SIZE=2><b>Monto Diario</b></FONT></td>\n\
			      <td align='center' width='20%'><FONT FACE='arial' SIZE=2><b>Ingreso Acumulable</b></FONT></td>\n\
			      <td align='center' width='21%'><FONT FACE='arial' SIZE=2><b>Ingreso No Acumulable</b></FONT></td>\n\
			 	</tr>\n\
				<tr>\n\
				  <td align='center' id='td_pension_total_exhibicion_"+posicion+"'>\n\
		  			<input type='text' onkeypress='return SoloNumeros(event)' value='0' id='total_exhibicion"+posicion+"' name='total_exhibicion"+posicion+"' onclick='(this.select())' style='text-align:right;width:90%;' title='La cantidad es en pesos MXN' />\n\
				  </td>\n\
				  <td align='center' id='td_pension_total_parcialidad_"+posicion+"'>\n\
					<input type='text' onkeypress='return SoloNumeros(event)' value='0' id='total_parcialidad"+posicion+"' name='total_parcialidad"+posicion+"' onclick='(this.select())' style='text-align:right;width:90%;' title='La cantidad es en pesos MXN'/>\n\
				  </td>\n\
				  <td align='center' id='td_pension_MontoDiario_"+posicion+"'>\n\
					<input type='text' value='0' id='MontoDiario"+posicion+"' name='MontoDiario"+posicion+"' onclick='(this.select())' onkeypress='return SoloNumeros(event)' style='text-align:right;width:90%;' title='La cantidad es en pesos MXN'/>\n\
				  </td>\n\
				  <td align='center' id='td_pension_acumulable_p_"+posicion+"'>\n\
					 <input type='text' value='0' id='acumulable_p"+posicion+"' name='acumulable_p"+posicion+"' onclick='(this.select())' onkeypress='return SoloNumeros(event)' style='text-align:right;width:90%;' title='La cantidad es en pesos MXN'/>\n\
				  </td>\n\
				  <td align='center' id='td_pension_noacumulable_p_"+posicion+"'>\n\
					<input type='text' value='0' id='noacumulable_p"+posicion+"' name='noacumulable_p"+posicion+"'  style='text-align:right;width:90%;' onkeypress='return SoloNumeros(event)' title='La cantidad es en pesos MXN'/>\n\
				  </td>\n\
				</tr>\n\
				<tr>\n\
				  <td colspan='5'><hr size='1' color='7fc1ba'></td>\n\
				</tr>\n\
			  </table>\n\
			   ";
    
	td.innerHTML = cadena;  
}

function agregar_indemnizacion(posicion) 
{
	var tr, td;
	var cadena;
	tr = document.all.tabla.insertRow();
	tr.id = 'indemnizacion' + posicion;
	td = tr.insertCell();
	td.setAttribute('colspan', '9');
	td.setAttribute('align', 'center');
	td.setAttribute('width', '100%');
	cadena = "<table border='0' width='80%' id='tabla_indemnizacion"+posicion+"' style='display:none' cellpadding='4'>\n\
				<input type='hidden' name='switch_indemnizacion_hidden"+posicion+"' id='switch_indemnizacion_hidden"+posicion+"' value='0' />\n\
				<input type='hidden' name='Total_Pagado_hidden"+posicion+"' id='Total_Pagado_hidden"+posicion+"'/>\n\
				<input type='hidden' name='Num_Servicio_hidden"+posicion+"' id='Num_Servicio_hidden"+posicion+"'/>\n\
				<input type='hidden' name='Ultimo_Sueldo_hidden"+posicion+"' id='Ultimo_Sueldo_hidden"+posicion+"'/>\n\
				<input type='hidden' name='Ingreso_Acumulable_i_hidden"+posicion+"' id='Ingreso_Acumulable_i_hidden"+posicion+"'/>\n\
				<input type='hidden' name='Ingreso_No_Acumulable_i_hidden"+posicion+"' id='Ingreso_No_Acumulable_i_hidden"+posicion+"'/>\n\
				<tr bgcolor='a4d3d0'>\n\
				  <td colspan='5' align='center'>\n\
				    <FONT SIZE=3 COLOR=white>Indemnización</FONT>\n\
				  </td>\n\
				</tr>\n\
			    <tr bgcolor='F1F1F1'>\n\
			      <td align='center' width='19%'><FONT FACE='arial' SIZE=2><b>Total Pagado</b></FONT></td>\n\
			      <td align='center' width='20%'><FONT FACE='arial' SIZE=2><b>Años Servicio</b></FONT></td>\n\
			   	  <td align='center' width='19%'><FONT FACE='arial' SIZE=2><b>Ultimo Sueldo</b></FONT></td>\n\
			      <td align='center' width='20%'><FONT FACE='arial' SIZE=2><b>Ingreso Acumulable</b></FONT></td>\n\
			      <td align='center' width='22%'><FONT FACE='arial' SIZE=2><b>Ingreso No Acumulable</b></FONT></td>\n\
			 	</tr>\n\
				<tr>\n\
				  <td align='center' id='td_indemnizacion_totalPagado_"+posicion+"'>\n\
					<input type='text' onkeypress='return SoloNumeros(event)' value='0' id='total_pagado"+posicion+"' name='total_pagado"+posicion+"' onclick='(this.select())' style='text-align:right;width='90%;' title='La cantidad es en pesos MXN'/>\n\
				  </td>\n\
				  <td align='center' id='td_indemnizacion_num_anyos_servicio_"+posicion+"'>\n\
				    <input type='text' onkeypress='return SoloNumeros(event)' value='0' id='num_anyos_servicio"+posicion+"' name='num_anyos_servicio"+posicion+"' onclick='(this.select())' style='text-align:right;width:90%;' title='El año debe estar entre el 0 y el 99'/>\n\
				  </td>\n\
				  <td align='center' id ='td_indemnizacion_ultimo_sueldo_"+posicion+"'>\n\
					<input type='text' value='0' id='ultimo_sueldo"+posicion+"' name='ultimo_sueldo"+posicion+"' onclick='(this.select())' onkeypress='return SoloNumeros(event)' style='text-align:right;width:90%;' title='La cantidad es en pesos MXN'/>\n\
				  </td>\n\
				  <td align='center' id ='td_indemnizacion_acumulable_i_"+posicion+"'>\n\
					 <input type='text' value='0' id='acumulable_i"+posicion+"' name='acumulable_i"+posicion+"' onclick='(this.select())' onkeypress='return SoloNumeros(event)' style='text-align:right;width:90%;' title='La cantidad es en pesos MXN' />\n\
				  </td>\n\
				  <td align='center' id ='td_indemnizacion_noacumulable_i_"+posicion+"'>\n\
					<input type='text' value='0' id='noacumulable_i"+posicion+"' name='noacumulable_i"+posicion+"' style='text-align:right;width:90%;' onkeypress='return SoloNumeros(event)' title='La cantidad es en pesos MXN'/>\n\
				  </td>\n\
				</tr>\n\
				<tr>\n\
				  <td colspan='5'><hr size='1' color='7fc1ba'></td>\n\
				</tr>\n\
			  </table>\n\
			   ";   
	td.innerHTML = cadena;
}

function suma_gravadoexcentohoras(id,letra)
{
	var gravado = eval(document.getElementById('gravado_horas'+letra+id).value);
	var excento = eval(document.getElementById("excento_horas"+letra+id).value);
   
	var gravadoS = eval(document.getElementById('gravado_horasS'+id).value);
	var excentoS = eval(document.getElementById("excento_horasS"+id).value);
	var gravadoT = eval(document.getElementById('gravado_horasT'+id).value);
	var excentoT = eval(document.getElementById("excento_horasT"+id).value);
	var gravadoD = eval(document.getElementById('gravado_horasD'+id).value);
	var excentoD = eval(document.getElementById("excento_horasD"+id).value);
	 
	var can_final_importe = gravado + excento;
	var can_final_gravados = gravadoS + gravadoT + gravadoD;
	var can_final_excentos = excentoS + excentoT + excentoD;
	document.getElementById("importe_horas"+letra+id).value = can_final_importe.toFixed(2);   
	document.getElementById("Gravado"+id).value = can_final_gravados.toFixed(2);   
	document.getElementById("Excento"+id).value = can_final_excentos.toFixed(2);   
	calcula_persepciones(id);       
 }
 
function suma_otrospagos(id)
{
	var auxiliar = 0;
	if(document.getElementById("ImporteOtro"+id).value == "")
	{
		document.getElementById("ImporteOtro"+id).value = "0"; 
	}
	//document.getElementById("SubsidioCausado"+id).value = document.getElementById("ImporteOtro"+id).value;
	var o = 0;
	for(o=0; o<contLin_otrospagos; o++)
	{   
		var txtImporte = "ImporteOtro" + o;
		var ImporteOtro = eval(document.getElementById(txtImporte).value);
		auxiliar = auxiliar + ImporteOtro;
	}
	document.getElementById("OtrosPagos_total").value = auxiliar.toFixed(2);   
	suma_per_ded();
 }

function agregar_otrospagos(Tipo_otros,Clave_otros,Concepto_otros,Importe_otros,Subsidio_otros,Saldo_otros,Anio_otros,Remanente_otros,IDConcepto_otros)
{
	var tr, td;
        
	tr = document.all.tabla_otrospagos.insertRow();
	tr.id = 'tr_tipopago' + contLin_otrospagos;
	td = tr.insertCell();
	td.setAttribute('colspan', '8');
	td.setAttribute('align', 'center');
        
	cadena = "<table border='0' width='100%' >\n\
				<tr>\n\
				  <td colspan='2' align='center'>\n\
				    <input type='hidden' name='Switch_OtrosPagos"+contLin_otrospagos+"' id='Switch_OtrosPagos"+contLin_otrospagos+"' value='1' />\n\
					<div id='agrupador_otrospagos"+contLin_otrospagos+"'>\n\
					</div></td>\n\
				  <td align='center' width='34%'>\n\
					<select name='dcOtroTipoCombo' id='dcOtroTipoCombo"+contLin_otrospagos+"' style='width:98%' onchange='seleccion_o(this.value,"+ contLin_otrospagos + ")'>\n\
					  <option value=''>Selecciona una Opción</option>\n\
					  <option value=''>-----------------------------</option>";
			   		<?php
					$cadenaAuxiliar = "";
					$sql2p="Select * From cartera_nomina_movimientos_empresa Where TipoNomina = 'o' AND Descripcion != 'Percepciones' AND Descripcion != 'Deducciones' AND Status = '1' AND Descripcion != 'Otros' AND IDEmpresa = '$IDEmpresa' Order By Descripcion";
					$result2p= mysql_query($sql2p) or die(mysql_error());
					while($row6p = mysql_fetch_assoc($result2p))
					{
						$cadenaAuxiliar = $cadenaAuxiliar . "<option value=".$row6p['FolioInutil'].">" . htmlentities($row6p['Descripcion']) . "</option>";
				    }         
                    ?> 
					cadena = cadena + "<?php echo $cadenaAuxiliar;?>" + "</select></td>"; 
					cadena2 = "<input type=hidden value='' onclick='(this.select())'  onpaste='return false;' autocomplete='off'  id='ConceptoOtro"+contLin_otrospagos+"'  size='40' name='ConceptoOtro'>\n\
				  <td align='center' width='12%'>\n\
				    <input type='hidden'>\n\
                  </td>\n\
				  <td id='td_importe_otrospagos"+contLin_otrospagos+"' align='center' width='12%'>\n\
					<input type=text value='"+Importe_otros+"' onclick='(this.select())' onkeypress='return SoloNumeros(event)' onblur=\"otrospagos(this,'" +contLin_otrospagos+"')\" onpaste='return false;' autocomplete='off'  id='ImporteOtro"+contLin_otrospagos+"' style='text-align:right;width:90%;' name='ImporteOtro"+contLin_otrospagos+"'>\n\
				  </td>\n\
                  <td  align='center' width='12%'>\n\
					<input type=text value='"+Importe_otros+"' onclick='(this.select())' onkeypress='return SoloNumeros(event)' onblur='suma_otrospagos("+contLin_otrospagos+")' onpaste='return false;' autocomplete='off'  id='TotalOtro"+contLin_otrospagos+"' style='text-align:right;color:black;background:lightgray;width:98%;' name='TotalOtro"+contLin_otrospagos+"'>\n\
				  </td>\n\
				  <td align='center' width='10%'>\n\
					<img src='images/delete.png' title='Borrar Concepto'  onclick='elimina_otros("+contLin_otrospagos+")' style='width:24;cursor:pointer;' vspace='3'>\n\
				  </td>\n\
				</tr>\n\
				<tr id='tr_otrospagos_subsidio_"+contLin_otrospagos+"' style='display:none'>\n\
                  <td colspan='7' align='center'>\n\
				    <table border='0' cellpading='6' cellspacing='0' width='25%' align='center'>\n\
					  <tr bgcolor='a4d3d0' >\n\
					    <td align='center'>\n\
                       	  <FONT SIZE=3 COLOR=white>Subsidio Causado</FONT>\n\
                     	</td>\n\
					   </tr>\n\
					   <tr><td align='center' id='td_subsidio_causado_otros"+contLin_otrospagos+"'>\n\
						   <input type=text value='' onclick='(this.select())' onkeypress='return SoloNumeros(event)' onpaste='return false;' autocomplete='off'  id='SubsidioCausado"+contLin_otrospagos+"' style='text-align:right' size='15' name='SubsidioCausado"+contLin_otrospagos+"'>\n\
					    </td>\n\
					  </tr>\n\
					  <tr><td colspan='3'><hr size='1' color='7fc1ba'/></td></tr>\n\
					</table>\n\
                  </tr>\n\
                <tr id='tr_otrospagos_saldo_"+contLin_otrospagos+"' style='display:none'>\n\
                  <td colspan='7' align='center'>\n\
				    <table border='0' cellpading='4' cellspacing='0' width='50%' align='center'>\n\
					  <tr bgcolor='a4d3d0' >\n\
					    <td colspan='3' align='center'>\n\
                       	  <FONT SIZE=3 COLOR=white>Compensacion Saldos a Favor</FONT>\n\
                     	</td>\n\
					   </tr>\n\
					   <tr bgcolor='F1F1F1'>\n\
						 <td align='center'><FONT FACE='arial' SIZE=2><b>Saldo a Favor</b></td>\n\
						 <td align='center'><FONT FACE='arial' SIZE=2><b>Año</b></td>\n\
						 <td align='center'><FONT FACE='arial' SIZE=2><b>Remanente</b></td>\n\
					  </tr>\n\
					  <tr><td align='center'>\n\
						 <input type=text value='' onclick='(this.select())' onkeypress='return SoloNumeros(event)' onpaste='return false;' autocomplete='off'  id='SaldoFavor"+contLin_otrospagos+"' style='text-align:right' size='15' name='SaldoFavor"+contLin_otrospagos+"'>\n\
					   </td><td align='center'>\n\
						 <input type=text value='' onclick='(this.select())' onkeypress='return numero(event)' onpaste='return false;' autocomplete='off'  id='AnioOtro"+contLin_otrospagos+"' style='text-align:right' size='15' name='AnioOtro"+contLin_otrospagos+"'>\n\
					   </td><td align='center'>\n\
						 <input type=text value='' onclick='(this.select())' onkeypress='return SoloNumeros(event)' onpaste='return false;' autocomplete='off'  id='Remanente"+contLin_otrospagos+"' style='text-align:right' size='15' name='Remanente"+contLin_otrospagos+"'>\n\
					   </td>\n\
					  </tr>\n\
					  <tr><td colspan='3'><hr size='1' color='7fc1ba'/></td></tr>\n\
					</table>\n\
                  </tr>\n\
				</table></td></tr>\n\
              </table>";
    
	td.innerHTML = cadena + cadena2; 
	seleccion_o("",contLin_otrospagos);
	contLin_otrospagos++;
}

function otrospagos(valor, contador)
{
	var total = document.getElementById('ImporteOtro' + contador).value;
	document.getElementById('TotalOtro' + contador).value = total;
	suma_otrospagos(contador);
}

function valida_exhibicion()
{
	if(document.getElementById("total_exhibicion").value == "")
	{
         document.getElementById("total_exhibicion").value = "0";  
	}
   if(document.getElementById("total_exhibicion").value != "0")
	{
		document.getElementById("MontoDiario").readOnly = true; 
		document.getElementById("total_parcialidad").readOnly = true; 
		document.getElementById("MontoDiario").value = "0"; 
		document.getElementById("total_parcialidad").value = "0"; 
	}
   else
	{
		document.getElementById("MontoDiario").readOnly = false; 
		document.getElementById("total_parcialidad").readOnly = false;     
	}
}

function valida_monto_parcialidad()
{
    if(document.getElementById("MontoDiario").value == "")
	{
		document.getElementById("MontoDiario").value = "0";  
	}
       
   if(document.getElementById("total_parcialidad").value == "")
	{
		document.getElementById("total_parcialidad").value = "0";  
	}
   
    if(document.getElementById("total_parcialidad").value != "0" && document.getElementById("MontoDiario").value != "0")
	{
		document.getElementById("total_exhibicion").readOnly = true; 
		document.getElementById("total_exhibicion").value = "0"; 
	}
	else
	{
		document.getElementById("total_exhibicion").readOnly = false;   
	}
}

function agregar_subcontratacion() 
{
	var tr, td;
       
	tr = document.all.subcontrataciones.insertRow();
	tr.id = 'tr_subcontratacion'+contLin_subcontratacion;
	// tr.setAttribute('bgcolor', 'F1F1F1');
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.innerHTML = "<input type='text' value='' id='RFCLabora"+ contLin_subcontratacion +"' onclick='(this.select())' size='20' />";
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.innerHTML = "<input type='text' onkeypress='return SoloNumeros(event)' value='0' id='PorcentajeTiempo"+ contLin_subcontratacion +"' onclick='(this.select())' style='text-align:right;width:98%;' />";
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.innerHTML = "<img src='images/delete.png' title='Borrar' id='borrar" + contLin_subcontratacion + "' onclick='borrarSubContratacion("+ contLin_subcontratacion +")' style='width:24;cursor:pointer;'>";
	contLin_subcontratacion++;
}

function borrarSubContratacion(id) 
{
	document.getElementById('tr_subcontratacion' + id).style.display = "none";
	document.getElementById('RFCLabora' + id).value = "";
	document.getElementById('PorcentajeTiempo' + id).value = "";
}

function borrarIncapacidad(id) 
{
	document.getElementById('celda_incapacidad' + id).style.display = "none";
	document.getElementById('dias' + id).value = "";
	document.getElementById('tipo_incapacidad' + id).value = "";
	document.getElementById('descuento' + id).value = "";
}

function agregar_deducciones() 
{
	var tr, td;
	var cadena;
	tr = document.all.tabla2.insertRow();
	tr.id = 'celda_d' + contLin2;
	tr.setAttribute('bgcolor', 'F1F1F1');
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.setAttribute('colspan', '2');
	td.innerHTML = "<div id='agrupador_deduccion"+contLin2+"'>\n\
                          </div>";
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.setAttribute('id', 'td_Concepto_d'+contLin2);
	cadena = "<select id='conceptosx"+ contLin2 + "' name='conceptosx"+ contLin2 + "' onchange='seleccion_d(this.value,"+ contLin2 + ")' style='width: 98%' > <option value=''>Seleccione un Concepto</option><option>-----------------------</option>";
	<?php
	$cadenaAuxiliarx = "";
	$sql2px="Select * From cartera_nomina_movimientos_empresa Where TipoNomina = 'd' AND IDEmpresa = '$IDEmpresa' AND Status = '1' AND Descripcion != 'Percepciones' AND Descripcion != 'Deducciones' Order By Descripcion";
                              
	$result2px= mysql_query($sql2px) or die(mysql_error());
	while($row6px = mysql_fetch_assoc($result2px))
	{
		$cadenaAuxiliarx = $cadenaAuxiliarx . "<option value=".$row6px['FolioInutil'].">" . htmlentities($row6px['Descripcion']) . "</option>";
	}                                                                                                    
	?> 
	cadena = cadena + "<?php echo $cadenaAuxiliarx;?>" + "</select>"; 
	td.innerHTML = cadena;
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.setAttribute('id', 'td_Gravado_d'+contLin2);
	td.innerHTML = " <input type='hidden' value='0'  id='Gravadox" + contLin2 + "' name='Gravadox" + contLin2 + "' onblur='calcula_deducciones(" + contLin2 + ")' style='text-align:right;color:black;background:lightgray;width:90%;' onkeypress='return soloNumeros(event,"+ contLin2 +");' readonly/>\n\
        <input type='hidden' value='1'  id='StatusConcepto_d" + contLin2 + "' />";
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.setAttribute('id', 'td_Excento_d'+contLin2);
	td.innerHTML = "<input type='text' value='0'  id='Excentox" + contLin2 + "' onclick='(this.select())' name='Excentox" + contLin2 + "' onblur='calcula_deducciones(" + contLin2 + ")' style='text-align:right;width:90%;' onkeypress='return soloNumeros(event,"+ contLin2 +");'/>";
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.innerHTML = "<input type='text' onkeypress='return SoloNumeros(event)' id='Importex" + contLin2 + "' name='Importex' value='0' style='text-align:right;color:black;background:lightgray;width:90%;' readonly/>";
	td = tr.insertCell();
	td.setAttribute('align', 'center');
	td.innerHTML = "<img src='images/delete.png' title='Borrar Concepto' id='borrar" + contLin2 + "' onclick='borrarConcepto_d("+ contLin2 +")' style='width:24;cursor:pointer;'>";
	document.getElementById('conceptosx' + contLin2).focus();
	agregar_incapacidad(contLin2);
	seleccion_d("",contLin2);
	contLin2++;
	renglon2++;
}

function agregar_incapacidad(contLin3) 
{
	var tr, td;
	var cadena;
	tr = document.all.tabla2.insertRow();
	tr.id = 'incapacidad' + contLin3;
	td = tr.insertCell();
	td.setAttribute('colspan', '7');
	td.setAttribute('align', 'center');
	//td.setAttribute('width', '100%');
	cadena = "<table id='tabla_incapacidad"+contLin3+"' width='70%' style='display:none' cellpadding='4' cellspacing='0' border='0'>\n\
			  <tr bgcolor='a4d3d0'>\n\
				<td colspan='5' align='center'>\n\
				  <FONT SIZE=3 COLOR=white>Incapacidad</FONT>\n\
				</td>\n\
			  </tr>\n\
			  <tr bgcolor='F1F1F1'>\n\
				<td align='center' width='28%'><FONT FACE='arial' SIZE=2><b>Días</b></FONT></td>\n\
				<td align='center' width='34%'><FONT FACE='arial' SIZE=2><b>Tipo Incapacidad</b></FONT></td>\n\
				<td align='center' width='28%'><FONT FACE='arial' SIZE=2><b>Descuento</b></FONT></td>\n\
			  </tr>\n\
			  <tr>\n\
			  <input type='hidden' name='switch_incapacidad_hidden"+contLin3+"' id='switch_incapacidad_hidden"+contLin3+"' value='0'>\n\
				<td align='center' width='28%' id='td_incapacidad_dias_"+contLin3+"'>\n\
				  <input type=text value='' onclick='(this.select())' onkeypress='return numero(event)' onpaste='return false;' autocomplete='off'  id='dias"+ contLin3 + "'  size='8' name='dias"+ contLin3 + "'>\n\
				</td>\n\
				<td align='center' width='34%' id='td_incapacidad_tipo_"+contLin3+"'>\n\
				  <select name='tipo_incapacidad"+ contLin3 + "' id='tipo_incapacidad"+ contLin3 + "' style='width:98%'>\n\
				   \n\
					<option value=''>Selecciona una Opción</option>\n\
					<option value=''>------------------------------</option>\n\
					<option value='01'>Riesgo de Trabajo</option>\n\
					<option value='02'>Enfermedad en General</option>\n\
					<option value='03'>Maternidad</option>\n\
				   \n\
				  </select> \n\
				</td>\n\
				<td align='center' width='28%' id='td_incapacidad_descuento_"+ contLin3 + "'>\n\
				  <input type='text' value='' onclick='(this.select())' onkeypress='return soloNumeros(event,"+ contLin3 +");' onpaste='return false;' autocomplete='off'  id='descuento"+ contLin3 + "' style='text-align:right' size='8' name='descuento"+ contLin3 + "' onblur='calcula_deducciones(" + contLin2 + ")'>\n\
				</td>\n\
			  </tr>\n\
			  <tr>\n\
			    <td colspan='5'><hr size='1' color='7fc1ba'></td>\n\
			  </tr>\n\
			</table>\n\
			";
	td.innerHTML = cadena;
}

function borrarConcepto_p(id) 
{
	var agrupador = 'agrupador' + id;
	if(document.getElementById(agrupador).value == "039") 
	{
		conteo_pension--;
		if(conteo_pension == 0)
		{
			document.getElementById('pension1').style.display = "none"; 
		}
	}
   
	if(document.getElementById(agrupador).value == "022" || document.getElementById(agrupador).value == "023" || document.getElementById(agrupador).value == "025") 
	{
		conteo_indemnizacion--;
		if(conteo_indemnizacion == 0)
		{
			document.getElementById('indemnizacion1').style.display = "none"; 
		}
	}
               
	document.getElementById('celda_p' + id).style.display = "none";
	document.getElementById('detalles_percepcion' + id).style.display = "none";
	document.getElementById('agrupador' + id).value = '0';
	document.getElementById('numero' + id).value = '0';
	document.getElementById('conceptos' + id).value = '--';
	document.getElementById('Gravado' + id).value = '0';
	document.getElementById('Excento' + id).value = '0';
	document.getElementById('Importe' + id).value =  '0';
	document.getElementById('HorasD' + id).value =  '';
	document.getElementById('Dias_horasD' + id).value =  '';
	document.getElementById('importe_horasD' + id).value =  '';
	document.getElementById('HorasT' + id).value =  '';
	document.getElementById('Dias_horasT' + id).value =  '';
	document.getElementById('importe_horasT' + id).value =  '';
	document.getElementById('HorasS' + id).value =  '';
	document.getElementById('Dias_horasS' + id).value =  '';
	document.getElementById('importe_horasS' + id).value =  '';
	document.getElementById('StatusConcepto_p' + id).value =  '0';
	
	suma_per_ded();
}

function borrarConcepto_d(id) 
{
	var agrupadorx = 'agrupadorx' + id;
	if(document.getElementById(agrupadorx).value == "006")
	{
		conteo_incapacidad--;
		if(conteo_incapacidad == 0)
		{
			document.getElementById('tabla_incapacidad'+id).style.display = "none";
			document.getElementById('switch_incapacidad_hidden'+ id).value ="0";
		}
	}
	document.getElementById('celda_d' + id).style.display = "none";
	document.getElementById('agrupadorx' + id).value = '0';
	document.getElementById('numerox' + id).value = '0';
	document.getElementById('conceptosx' + id).value = '--';
	document.getElementById('Importex' + id).value =  '0';
	document.getElementById('StatusConcepto_d' + id).value =  '0';
	suma_per_ded();
}

function borrarHoras(id) 
{
	var tabla=document.getElementById('tabla4').getElementsByTagName('tr');
	for(var i=0, visibles=0; i<tabla.length; i++) 
	{
		if(tabla[i].style.display!='none') 
		{
			visibles++; 
			// alert();
		}
	}
           
	if(visibles == 0)
	{
		document.getElementById('tipo_horas' + id).value = '';
		document.getElementById('Horas' + id).value = '';
		document.getElementById('importe_horas' + id).value = '0';
		document.getElementById('Dias_horas' + id).value = '';
	}
	else
	{
		document.getElementById('celda_horas' + id).style.display = "none";
		document.getElementById('tipo_horas' + id).value = '';
		document.getElementById('Horas' + id).value = '';
		document.getElementById('importe_horas' + id).value = '0';
		document.getElementById('Dias_horas' + id).value = '';
	}
}

function elimina_otros(id) 
{
	document.getElementById('tr_tipopago' + id).style.display = "none";
	document.getElementById('Switch_OtrosPagos' + id).value =  '0';
	document.getElementById('dcOtroTipo' + id).value = "";
	//document.getElementById('ClaveOtro' + id).value = "";
	document.getElementById('ConceptoOtro' + id).value = "";
	document.getElementById('ImporteOtro' + id).value = "0";
	document.getElementById('TotalOtro' + id).value = "0";
	document.getElementById('SubsidioCausado' + id).value = "0";
	document.getElementById('SaldoFavor' + id).value = "0";
	document.getElementById('AnioOtro' + id).value = "0";
	document.getElementById('Remanente' + id).value = "0";
	suma_otrospagos(id);
}

function calcula_persepciones(Indicador)
{
	var variableGravado = "Gravado" + Indicador;
	var Gravado = Number(document.getElementById(variableGravado).value);
	
	var variableExcento = "Excento" + Indicador;
	var Excento = Number(document.getElementById(variableExcento).value);
	
	var variableImporte = "Importe" + Indicador;
	document.getElementById(variableImporte).value =  Number(Gravado.toFixed(2)) + Number(Excento.toFixed(2));    
	suma_per_ded();
}
 
function calcula_deducciones(Indicador)
{
	var variableGravado = "Gravadox" + Indicador;
	var Gravado = Number(document.getElementById(variableGravado).value);
	
	var variableExcento = "Excentox" + Indicador;
	var Excento = Number(document.getElementById(variableExcento).value);
	
	var variableImporte = "Importex" + Indicador;
	document.getElementById(variableImporte).value =  Number(Gravado.toFixed(2)) + Number(Excento.toFixed(2));   
	
	suma_per_ded();
}
 
function suma_per_ded()
{    
	var auxiliar2 = 0;
	var auxiliar3 = 0;
	var auxiliar_final = 0;
	var auxiliar4 = eval(document.getElementById('OtrosPagos_total').value);
         
	for(i=0; i<renglon; i++)
	{   
		var txtPercepciones = "Importe" + i;
		var Percepcion = eval(document.getElementById(txtPercepciones).value);
		auxiliar2 = auxiliar2 + eval(Percepcion);
	} 
	for(j=0; j<renglon2; j++)
	{   
		var txtDeducciones = "Importex" + j;
		var Deduccion = eval(document.getElementById(txtDeducciones).value);
		auxiliar3 = auxiliar3 + eval(Deduccion);
	} 
	document.getElementById('Percepciones').value = auxiliar2;
	document.getElementById('Deducciones').value = auxiliar3;
	auxiliar_final = auxiliar2 - auxiliar3 + auxiliar4;
	document.getElementById('TOTAL').value = auxiliar_final.toFixed(2);
	// alert( document.getElementById('Deducciones').value);
}
 
function seleccion_o(valor,indice)
{  
	http[indice] = new XMLHttpRequest();
	http[indice].abort();  //fija
	cadena = "actualiza_agrupador_otrospagos.php?valor=" + valor+"&id="+indice;
	http[indice].open("GET", cadena); //FIJO
	http[indice].send(); 
     
	http[indice].onreadystatechange=function()  //FIJO
	{
		if(http[indice].readyState == 4 && http[indice].status == 200) //FIJO
		{
			document.getElementById('agrupador_otrospagos'+indice).innerHTML= http[indice].responseText;
			
			if(document.getElementById('dcOtroTipo'+indice).value == "001")
			{
				document.getElementById('tr_otrospagos_saldo_'+indice).style.display = "table-row";
			}
			else
			{
				document.getElementById('tr_otrospagos_saldo_'+indice).style.display = "none"; 
				document.getElementById('SaldoFavor'+indice).value=""; 
				document.getElementById('AnioOtro'+indice).value=""; 
				document.getElementById('Remanente'+indice).value="";      
			}
			if(document.getElementById('dcOtroTipo'+indice).value == "002")
			{
				document.getElementById('tr_otrospagos_subsidio_'+indice).style.display = "table-row";
			}
			else
			{
				document.getElementById('tr_otrospagos_subsidio_'+indice).style.display = "none"; 
				document.getElementById('SubsidioCausado'+indice).value="";  
			}
		}
	}
}
  
function seleccion_p(valor,indice)
{ 
	http_p[indice] = new XMLHttpRequest();
	http_p[indice].abort();  //fija
	cadena = "actualiza_agrupador_percepcion.php?valor=" + valor+"&id="+indice;
	http_p[indice].open("GET", cadena); //FIJO
	http_p[indice].send(); 
	
	http_p[indice].onreadystatechange=function()  //FIJO
	{
		if(http_p[indice].readyState == 4 && http_p[indice].status == 200) //FIJO
		{
			document.getElementById('agrupador_percepcion'+indice).innerHTML= http_p[indice].responseText;
			if(document.getElementById('agrupador'+indice).value == "019")// Horas extras
			{
				document.getElementById('tabla_horas'+indice).style.display = "table-row"; 
				document.getElementById("Gravado"+indice).readOnly = true;
				document.getElementById("Excento"+indice).readOnly = true;
			}
			else
			{ 
				document.getElementById('tabla_horas'+indice).style.display = "none";
				document.getElementById("Gravado"+indice).readOnly = false;
				document.getElementById("Excento"+indice).readOnly = false;
			}
        
			if((document.getElementById('agrupador'+indice).value == "039") || (document.getElementById('agrupador'+indice).value == "044"))// Jubilaciones, pensiones o haberes de retiro en una sola exhibicion o en parcialidades			
			{
				document.getElementById('tabla_pension'+indice).style.display = "table-row"; 
				conteo_pension++;
				document.getElementById('switch_pension_hidden'+ indice).value ="1";
				document.getElementById('tipo_pension_hidden'+ indice).value = document.getElementById('agrupador'+indice).value;
				if (document.getElementById('tipo_pension_hidden'+indice).value == "039")
				{
					document.getElementById('div_titulo_pension'+indice).innerHTML = "Jubilaciones, pensiones o haberes de retiro en una sola exhibición";
				   	document.getElementById("total_parcialidad"+indice).value = "0";
				   	document.getElementById("MontoDiario"+indice).value = "0";
				   	document.getElementById("total_exhibicion"+indice).readOnly = false;
				   	document.getElementById("total_parcialidad"+indice).readOnly = true;
				   	document.getElementById("MontoDiario"+indice).readOnly = true;
					document.getElementById("total_exhibicion"+indice).style.backgroundColor = "white";
					document.getElementById("total_parcialidad"+indice).style.backgroundColor = "lightgray";
					document.getElementById("MontoDiario"+indice).style.backgroundColor = "lightgray";
			   }
			   else if (document.getElementById('tipo_pension_hidden'+indice).value == "044")
			   {
				  	document.getElementById('div_titulo_pension'+indice).innerHTML = "Jubilaciones, pensiones o haberes de retiro en parcialidades";
					document.getElementById("total_exhibicion"+indice).value = "0";
				   	document.getElementById("total_exhibicion"+indice).readOnly = true;
				   	document.getElementById("total_parcialidad"+indice).readOnly = false;
				   	document.getElementById("MontoDiario"+indice).readOnly = false;
					document.getElementById("total_exhibicion"+indice).style.backgroundColor = "lightgray";
					document.getElementById("total_parcialidad"+indice).style.backgroundColor = "white";
				   	document.getElementById("MontoDiario"+indice).style.backgroundColor = "white";
			   }
			}
			else
			{ 
				if (document.getElementById('switch_pension_hidden'+indice).value == "1")
					conteo_pension--;
				//document.getElementById('pension1').style.display = "none"; 
				document.getElementById('tabla_pension'+indice).style.display = "none"; 
				document.getElementById('switch_pension_hidden'+indice).value ="0";
				document.getElementById('tipo_pension_hidden'+ indice).value = "";
			}
        
			if(document.getElementById('agrupador'+indice).value == "022" || document.getElementById('agrupador'+indice).value == "023" || document.getElementById('agrupador'+indice).value == "025")// Horas extras
			{
				//document.getElementById('indemnizacion1').style.display = "table-row"; 
				document.getElementById('tabla_indemnizacion'+indice).style.display = "table-row"; 
				document.getElementById('switch_indemnizacion_hidden'+indice).value ="1";
				conteo_indemnizacion++;
			}
			else
			{ 
				if (document.getElementById('switch_indemnizacion_hidden'+indice).value == "1")
					conteo_indemnizacion--;
				//document.getElementById('indemnizacion1').style.display = "none"; 
				document.getElementById('tabla_indemnizacion'+indice).style.display = "none";
				document.getElementById('switch_indemnizacion_hidden'+indice).value ="0";
			}
		}
	}
}
 
 
function seleccion_d(valor,indice)
{    
	http_d[indice] = new XMLHttpRequest();
	http_d[indice].abort();  //fija
	cadena = "actualiza_agrupador_deduccion.php?valor=" + valor+"&id="+indice;
	http_d[indice].open("GET", cadena); //FIJO
	http_d[indice].send(); 
	http_d[indice].onreadystatechange=function()  //FIJO
	{
		if(http_d[indice].readyState == 4 && http_d[indice].status == 200) //FIJO
		{
			document.getElementById('agrupador_deduccion'+indice).innerHTML = http_d[indice].responseText;
			
			if(document.getElementById('agrupadorx'+indice).value == "006")// Incapacidad
			{
				document.getElementById('tabla_incapacidad'+indice).style.display = "table"; 
				conteo_incapacidad++;
				document.getElementById('switch_incapacidad_hidden'+ indice).value ="1";
			}
			else
			{ 
				if (document.getElementById('switch_incapacidad_hidden'+indice).value == "1")
					conteo_incapacidad--;
				document.getElementById('tabla_incapacidad'+indice).style.display = "none";
				document.getElementById('switch_incapacidad_hidden'+indice).value ="0";
			}
		}
 	}
}
 
function Procesar()
{
	document.forma.Incapacidades.value = "";
    document.forma.PercepcionesD.value = "";
    document.forma.DeduccionesD.value = "";
    document.forma.OtrosPagos_hidden.value = "";
    document.forma.SubContrataciones_hidden.value = "";
   
    var periodo =  document.getElementById("periodo_pago").value;
	var fecha_inicio_laboral = document.forma.fecha_inicio_laboral.value;
	var fecha_inicial = document.forma.txtPeriodo_Inicial.value;
    var fecha_final = document.forma.txtPeriodo_Final.value;
    var fecha_pago = document.forma.txtFechaPago.value;
    var dato_fecha = fecha_inicial.split("-");
    var fecha_comparacion = dato_fecha[0]+"-"+dato_fecha[1]+"-01";
    var valida = 0;
    error = 0;
    var mensaje_cantidad = "";
    var mensaje_concepto_d = "";  
    var mensaje_concepto_p = "";
    var mensaje_fecha_final = "";
	var mensaje_fechas = "";
    var mensaje_fechapago = "";
    var mensaje_agrupador = "";
	var mensaje_suma = "";
	var mensaje_subsidio_causado = "";
	var mensaje_op = "";
    document.getElementById('td_fechapago').style.borderColor="#FFF";
    document.getElementById('td_fecha_calcula').style.borderColor="#FFF";
   
	for(i=0; i<renglon; i++)
	{   
		document.getElementById('td_Concepto_p'+i).style.borderColor="#FFF";
		document.getElementById('td_Gravado_p'+i).style.borderColor="#FFF";
		document.getElementById('td_Excento_p'+i).style.borderColor="#FFF";     
		var agrupador = "agrupador" + i;
		var Agrupador_final = document.getElementById(agrupador).value;
		
		if(Agrupador_final == "017" || Agrupador_final == "016")
		{
			valida = 1;
		}
		
		var numero = "numero" + i;
		var Numero_final = document.getElementById(numero).value;
		
		var conceptos = "conceptos" + i;
		var Concepto_final = document.getElementById(conceptos).value;
		
		var gravado = "Gravado" + i;
		var Gravado_final = document.getElementById(gravado).value;
		
		var excento = "Excento" + i;
		var Excento_final = document.getElementById(excento).value;
		
		var importe = "Importe" + i;
		var Importe_final = document.getElementById(importe).value;
		
		var Concepto_cartera = "IDConceptoCartera" + i;
		var Concepto_cartera_final = document.getElementById(Concepto_cartera).value;
		
		var Status_p = "StatusConcepto_p" + i;
		var Status_p_final = document.getElementById(Status_p).value;
                          
		if((Gravado_final == "" || Gravado_final == "0") && (Excento_final == "" || Excento_final == "0") && Status_p_final == "1")
		{
			error++;
			document.getElementById('td_Gravado_p'+i).style.borderColor="#F00";
			document.getElementById('td_Gravado_p'+i).style.borderStyle="solid";
			document.getElementById('td_Gravado_p'+i).style.borderWidth="medium";
			
			document.getElementById('td_Excento_p'+i).style.borderColor="#F00";
			document.getElementById('td_Excento_p'+i).style.borderStyle="solid";
			document.getElementById('td_Excento_p'+i).style.borderWidth="medium";
			mensaje_cantidad="Los importes no pueden ser cero.<br>"; 
		}
		if(Concepto_final == "" && Status_p_final == "1")
		{
			error++;
			document.getElementById('td_Concepto_p'+i).style.borderColor="#F00";
			document.getElementById('td_Concepto_p'+i).style.borderStyle="solid";
			document.getElementById('td_Concepto_p'+i).style.borderWidth="medium";
			mensaje_concepto_p="Seleccione concepto de percepcion.<br>"; 
		}
		
		//-------------------------------------------
		var TipoHorasD = "tipo_horasD" + i;
		var TipoHorasD_final = document.getElementById(TipoHorasD).value;
		
		var HorasD = "HorasD" + i;
		var HorasD_final = document.getElementById(HorasD).value;
                          
		var DiasD = "Dias_horasD" + i;
		var DiasD_final = document.getElementById(DiasD).value;
		
		var GravadoD = "gravado_horasD" + i;
		var GravadoD_final = document.getElementById(GravadoD).value;
		
		var ExcentoD = "excento_horasD" + i;
		var ExcentoD_final = document.getElementById(ExcentoD).value;
		
		var ImporteD = "importe_horasD" + i;
		var ImporteD_final = document.getElementById(ImporteD).value;
		
		//-------------------------------------------
		var TipoHorasT = "tipo_horasT" + i;
		var TipoHorasT_final = document.getElementById(TipoHorasT).value;
		
		var HorasT = "HorasT" + i;
		var HorasT_final = document.getElementById(HorasT).value;
		
		var DiasT = "Dias_horasT" + i;
		var DiasT_final = document.getElementById(DiasT).value;
		
		var GravadoT = "gravado_horasT" + i;
		var GravadoT_final = document.getElementById(GravadoT).value;
		
		var ExcentoT = "excento_horasT" + i;
		var ExcentoT_final = document.getElementById(ExcentoT).value;
		
		var ImporteT = "importe_horasT" + i;
		var ImporteT_final = document.getElementById(ImporteT).value;
		
		//-------------------------------------------
		var TipoHorasS = "tipo_horasS" + i;
		var TipoHorasS_final = document.getElementById(TipoHorasS).value;
		
		var HorasS = "HorasS" + i;
		var HorasS_final = document.getElementById(HorasS).value;
		
		var DiasS = "Dias_horasS" + i;
		var DiasS_final = document.getElementById(DiasS).value;
		
		var GravadoS = "gravado_horasS" + i;
		var GravadoS_final = document.getElementById(GravadoS).value;
		
		var ExcentoS = "excento_horasS" + i;
		var ExcentoS_final = document.getElementById(ExcentoS).value;
		
		var ImporteS = "importe_horasS" + i;
		var ImporteS_final = document.getElementById(ImporteS).value;
		
		//------------------------------------------- Indemnizacion 
		var TotalPagado = "total_pagado" + i;
		var TotalPagado_final = document.getElementById(TotalPagado).value;
		
		var AniosServicio = "num_anyos_servicio" + i;
		var AniosServicio_final = document.getElementById(AniosServicio).value;
		
		var UltimoSueldo = "ultimo_sueldo" + i;
		var UltimoSueldo_final = document.getElementById(UltimoSueldo).value;
		
		var IngresoAcumulableIndem = "acumulable_i" + i;
		var IngresoAcumulableIndem_final = document.getElementById(IngresoAcumulableIndem).value;
		
		var IngresoNoAcumulableIndem = "noacumulable_i" + i;
		var IngresoNoAcumulableIndem_final = document.getElementById(IngresoNoAcumulableIndem).value;
		
		//------------------------------------------- Pension 
		var TotalExhibicion = "total_exhibicion" + i;
		var TotalExhibicion_final = document.getElementById(TotalExhibicion).value;
		
		var TotalParcialidad = "total_parcialidad" + i;
		var TotalParcialidad_final = document.getElementById(TotalParcialidad).value;
		
		var MontoDiario = "MontoDiario" + i;
		var MontoDiario_final = document.getElementById(MontoDiario).value;
		
		var IngresoAcumulablePension = "acumulable_p" + i;
		var IngresoAcumulablePension_final = document.getElementById(IngresoAcumulablePension).value;
		
		var IngresoNoAcumulablePension = "noacumulable_p" + i;
		var IngresoNoAcumulablePension_final = document.getElementById(IngresoNoAcumulablePension).value;
		
		//------------------------------------------- Validaciones
		if (Agrupador_final == "039") /// Validamos que no vengan vacios o en 0 los campos de Jubilacion / Pension en una sola exhibicion
		{
			if (document.getElementById('total_exhibicion'+i).value <= 0)
			{
				error++;
				document.getElementById('td_pension_total_exhibicion_'+i).style.borderColor="#F00";
				document.getElementById('td_pension_total_exhibicion_'+i).style.borderStyle="solid";
				document.getElementById('td_pension_total_exhibicion_'+i).style.borderWidth="medium";
				mensaje_datosPension="Ingresa los datos de la pensión.<br>"; 
			}
			else if (document.getElementById('total_exhibicion'+i).value != document.getElementById('Importe'+i).value)
			{
				error++;
				document.getElementById('td_pension_total_exhibicion_'+i).style.borderColor="#F00";
				document.getElementById('td_pension_total_exhibicion_'+i).style.borderStyle="solid";
				document.getElementById('td_pension_total_exhibicion_'+i).style.borderWidth="medium";
				document.getElementById('td_importe_percepcion'+i).style.borderColor="#F00";
				document.getElementById('td_importe_percepcion'+i).style.borderStyle="solid";
				document.getElementById('td_importe_percepcion'+i).style.borderWidth="medium";
				mensaje_TotalExhibicion="El Total Exhibición y el Total de la percepcion deben ser iguales.<br>"; 
			}
			else
			{
				document.getElementById('td_pension_total_exhibicion_'+i).style.borderStyle="none";
				document.getElementById('td_importe_percepcion'+i).style.borderStyle="none";
			}
				

			if (document.getElementById('acumulable_p'+i).value <= 0)
			{
				error++;
				document.getElementById('td_pension_acumulable_p_'+i).style.borderColor="#F00";
				document.getElementById('td_pension_acumulable_p_'+i).style.borderStyle="solid";
				document.getElementById('td_pension_acumulable_p_'+i).style.borderWidth="medium";
				mensaje_datosPension="Ingresa los datos de la pensión.<br>"; 
			}
			else
				document.getElementById('td_pension_acumulable_p_'+i).style.borderStyle="none";
				
			if (document.getElementById('noacumulable_p'+i).value <= 0)
			{
				error++;
				document.getElementById('td_pension_noacumulable_p_'+i).style.borderColor="#F00";
				document.getElementById('td_pension_noacumulable_p_'+i).style.borderStyle="solid";
				document.getElementById('td_pension_noacumulable_p_'+i).style.borderWidth="medium";
				mensaje_datosPension="Ingresa los datos de la pensión.<br>"; 
			}
			else
				document.getElementById('td_pension_noacumulable_p_'+i).style.borderStyle="none";
		}
		
		if (Agrupador_final == "044") /// Validamos que no vengan vacios o en 0 los campos de Jubilacion / Pension en parcialidades
		{		
			if (document.getElementById('total_parcialidad'+i).value <= 0)
			{
				error++;
				document.getElementById('td_pension_total_parcialidad_'+i).style.borderColor="#F00";
				document.getElementById('td_pension_total_parcialidad_'+i).style.borderStyle="solid";
				document.getElementById('td_pension_total_parcialidad_'+i).style.borderWidth="medium";
				mensaje_datosPension="Ingresa los datos de la pensión.<br>"; 
			}
			else if (document.getElementById('total_parcialidad'+i).value != document.getElementById('Importe'+i).value)
			{
				error++;
				document.getElementById('td_pension_total_parcialidad_'+i).style.borderColor="#F00";
				document.getElementById('td_pension_total_parcialidad_'+i).style.borderStyle="solid";
				document.getElementById('td_pension_total_parcialidad_'+i).style.borderWidth="medium";
				document.getElementById('td_importe_percepcion'+i).style.borderColor="#F00";
				document.getElementById('td_importe_percepcion'+i).style.borderStyle="solid";
				document.getElementById('td_importe_percepcion'+i).style.borderWidth="medium";
				mensaje_TotalParcialidad="El Total Parcialidad y el Total de la percepcion deben ser iguales.<br>"; 
			}
			else
			{
				document.getElementById('td_pension_total_parcialidad_'+i).style.borderStyle="none";
				document.getElementById('td_importe_percepcion'+i).style.borderStyle="none";
			}
				
			if (document.getElementById('MontoDiario'+i).value <= 0)
			{
				error++;
				document.getElementById('td_pension_MontoDiario_'+i).style.borderColor="#F00";
				document.getElementById('td_pension_MontoDiario_'+i).style.borderStyle="solid";
				document.getElementById('td_pension_MontoDiario_'+i).style.borderWidth="medium";
				mensaje_datosPension="Ingresa los datos de la pensión.<br>"; 
			}
			else
				document.getElementById('td_pension_MontoDiario_'+i).style.borderStyle="none";
				
			if (document.getElementById('acumulable_p'+i).value <= 0)
			{
				error++;
				document.getElementById('td_pension_acumulable_p_'+i).style.borderColor="#F00";
				document.getElementById('td_pension_acumulable_p_'+i).style.borderStyle="solid";
				document.getElementById('td_pension_acumulable_p_'+i).style.borderWidth="medium";
				mensaje_datosPension="Ingresa los datos de la pensión.<br>"; 
			}
			else
				document.getElementById('td_pension_acumulable_p_'+i).style.borderStyle="none";
				
			if (document.getElementById('noacumulable_p'+i).value <= 0)
			{
				error++;
				document.getElementById('td_pension_noacumulable_p_'+i).style.borderColor="#F00";
				document.getElementById('td_pension_noacumulable_p_'+i).style.borderStyle="solid";
				document.getElementById('td_pension_noacumulable_p_'+i).style.borderWidth="medium";
				mensaje_datosPension="Ingresa los datos de la pensión.<br>"; 
			}
			else
				document.getElementById('td_pension_noacumulable_p_'+i).style.borderStyle="none";
		}
		
		if ((Agrupador_final == "022") || (Agrupador_final == "023") || (Agrupador_final == "025")) /// Validar que no vengan vacios o en 0 los campos de Indemnización
		{
			if (document.getElementById('total_pagado'+i).value <= 0)
			{
				error++;
				document.getElementById('td_indemnizacion_totalPagado_'+i).style.borderColor="#F00";
				document.getElementById('td_indemnizacion_totalPagado_'+i).style.borderStyle="solid";
				document.getElementById('td_indemnizacion_totalPagado_'+i).style.borderWidth="medium";
				mensaje_total_indemnizacion="Ingrese el total pagado en la indemnización.<br>"; 
			}
			else if (document.getElementById('total_pagado'+i).value != document.getElementById('Importe'+i).value)
			{
				error++;
				document.getElementById('td_indemnizacion_totalPagado_'+i).style.borderColor="#F00";
				document.getElementById('td_indemnizacion_totalPagado_'+i).style.borderStyle="solid";
				document.getElementById('td_indemnizacion_totalPagado_'+i).style.borderWidth="medium";
				document.getElementById('td_importe_percepcion'+i).style.borderColor="#F00";
				document.getElementById('td_importe_percepcion'+i).style.borderStyle="solid";
				document.getElementById('td_importe_percepcion'+i).style.borderWidth="medium";
				mensaje_total_indemnizacion="El Total pagado de indemnización y el Total de la percepcion deben ser iguales.<br>"; 
			}
			else
			{
				document.getElementById('td_indemnizacion_totalPagado_'+i).style.borderStyle="none";
				document.getElementById('td_importe_percepcion'+i).style.borderStyle="none";
			}
				
			if ((document.getElementById('num_anyos_servicio'+i).value < 0) || (document.getElementById('num_anyos_servicio'+i).value > 99))
			{
				error++;
				document.getElementById('td_indemnizacion_num_anyos_servicio_'+i).style.borderColor="#F00";
				document.getElementById('td_indemnizacion_num_anyos_servicio_'+i).style.borderStyle="solid";
				document.getElementById('td_indemnizacion_num_anyos_servicio_'+i).style.borderWidth="medium";
			}
			else
				document.getElementById('td_indemnizacion_num_anyos_servicio_'+i).style.borderStyle="none";
				
			if (document.getElementById('ultimo_sueldo'+i).value <= 0)
			{
				error++;
				document.getElementById('td_indemnizacion_ultimo_sueldo_'+i).style.borderColor="#F00";
				document.getElementById('td_indemnizacion_ultimo_sueldo_'+i).style.borderStyle="solid";
				document.getElementById('td_indemnizacion_ultimo_sueldo_'+i).style.borderWidth="medium";
				mensaje_indemnizacion="Llenar campos de la indemnización.<br>"; 
			}
			else
				document.getElementById('td_indemnizacion_ultimo_sueldo_'+i).style.borderStyle="none";	
				
			if (document.getElementById('acumulable_i'+i).value <= 0)
			{
				error++;
				document.getElementById('td_indemnizacion_acumulable_i_'+i).style.borderColor="#F00";
				document.getElementById('td_indemnizacion_acumulable_i_'+i).style.borderStyle="solid";
				document.getElementById('td_indemnizacion_acumulable_i_'+i).style.borderWidth="medium";
				mensaje_indemnizacion="Llenar campos de la indemnización.<br>"; 
			}
			else
				document.getElementById('td_indemnizacion_acumulable_i_'+i).style.borderStyle="none";	
				
			if (document.getElementById('acumulable_i'+i).value <= 0)
			{
				error++;
				document.getElementById('td_indemnizacion_noacumulable_i_'+i).style.borderColor="#F00";
				document.getElementById('td_indemnizacion_noacumulable_i_'+i).style.borderStyle="solid";
				document.getElementById('td_indemnizacion_noacumulable_i_'+i).style.borderWidth="medium";
				mensaje_indemnizacion="Llenar campos de la indemnización.<br>"; 
			}
			else
				document.getElementById('td_indemnizacion_noacumulable_i_'+i).style.borderStyle="none";	
		}
		
		if(Concepto_final != "") //Si el concepto no tiene numero de cantidad este no se procesa
		{
			document.forma.PercepcionesD.value = document.forma.PercepcionesD.value + "--" + Agrupador_final + "--" + Numero_final + "--" + Concepto_final + "--" + Gravado_final + "--" + Excento_final + "--" + Concepto_cartera_final + "--" + Importe_final + "--" + TipoHorasD_final + "--" + HorasD_final + "--" + DiasD_final + "--" + GravadoD_final+ "--" + ExcentoD_final + "--" + ImporteD_final + "--" + TipoHorasT_final + "--" + HorasT_final + "--" + DiasT_final + "--" + GravadoT_final+ "--" + ExcentoT_final + "--" + ImporteT_final + "--" + TipoHorasS_final + "--" + HorasS_final + "--" + DiasS_final + "--" + GravadoS_final+ "--" + ExcentoS_final+ "--" + ImporteS_final + "--" + TotalPagado_final + "--" + AniosServicio_final + "--" + UltimoSueldo_final + "--" + IngresoAcumulableIndem_final + "--" + IngresoNoAcumulableIndem_final + "--" + TotalExhibicion_final + "--" + TotalParcialidad_final + "--" + MontoDiario_final + "--" + IngresoAcumulablePension_final + "--" + IngresoNoAcumulablePension_final;
		}  
	}
                     
	for(j=0; j<renglon2; j++)
	{   
		document.getElementById('td_Concepto_d'+j).style.borderColor="#FFF";
		document.getElementById('td_Gravado_d'+j).style.borderColor="#FFF";
		document.getElementById('td_Excento_d'+j).style.borderColor="#FFF";    
		
		var agrupadorx = "agrupadorx" + j;
		var Agrupador_finalx = document.getElementById(agrupadorx).value;
		
		var numerox = "numerox" + j;
		var Numero_finalx = document.getElementById(numerox).value;
		
		var conceptosx = "conceptosx" + j;
		var Concepto_finalx = document.getElementById(conceptosx).value;
		
		var gravadox = "Gravadox" + j;
		var Gravadox_final = document.getElementById(gravadox).value;
		
		var excentox = "Excentox" + j;
		var Excentox_final = document.getElementById(excentox).value;
		
		var importex = "Importex" + j;
		var Importe_finalx = document.getElementById(importex).value;
		
		var Status_d = "StatusConcepto_d" + j;
		var Status_d_final = document.getElementById(Status_d).value;
		
		var Concepto_cartera_d = "IDConceptoCartera_d" + j;
		var Concepto_cartera_d_final = document.getElementById(Concepto_cartera_d).value;
		
		if((Excentox_final == "" || Excentox_final == "0") && Status_d_final == "1")
		{
			error++;
			document.getElementById('td_Excento_d'+j).style.borderColor="#F00";
			document.getElementById('td_Excento_d'+j).style.borderStyle="solid";
			document.getElementById('td_Excento_d'+j).style.borderWidth="medium";
			mensaje_cantidad="Los importes no pueden ser cero.<br>"; 
		}
		if(Concepto_finalx == "" && Status_d_final == "1")
		{
			error++;
			document.getElementById('td_Concepto_d'+j).style.borderColor="#F00";
			document.getElementById('td_Concepto_d'+j).style.borderStyle="solid";
			document.getElementById('td_Concepto_d'+j).style.borderWidth="medium";
			mensaje_concepto_d="Seleccione concepto de deduccion.<br>"; 
		}
		
		//----------------------------------------------------
		var Dias = "dias" + j;
		var Dias_final = document.getElementById(Dias).value;
		
		var TipoIncapacidad = "tipo_incapacidad" + j;
		var TipoIncapacidad_final = document.getElementById(TipoIncapacidad).value;
		
		var Descuento = "descuento" + j;
		var Descuento_final = document.getElementById(Descuento).value;
		
		//----------------------------------------------------
		if (Agrupador_finalx == "006") /// Validamos que no vengan vacios o en 0 los campos de incapacidad
		{
			if (document.getElementById('dias'+j).value <= 0)
			{
				error++;
				document.getElementById('td_incapacidad_dias_'+j).style.borderColor="#F00";
				document.getElementById('td_incapacidad_dias_'+j).style.borderStyle="solid";
				document.getElementById('td_incapacidad_dias_'+j).style.borderWidth="medium";
				mensaje_datosIncapacidad="Ingresa los datos de la incapacidad.<br>"; 
			}
			else
				document.getElementById('td_incapacidad_dias_'+j).style.borderStyle="none";
				
			if (document.getElementById('tipo_incapacidad'+j).value == "")
			{
				error++;
				document.getElementById('td_incapacidad_tipo_'+j).style.borderColor="#F00";
				document.getElementById('td_incapacidad_tipo_'+j).style.borderStyle="solid";
				document.getElementById('td_incapacidad_tipo_'+j).style.borderWidth="medium";
				mensaje_datosIncapacidad="Ingresa los datos de la incapacidad.<br>"; 
			}
			else
				document.getElementById('td_incapacidad_tipo_'+j).style.borderStyle="none";
				
			if (document.getElementById('descuento'+j).value <= "0")
			{
				error++;
				document.getElementById('td_incapacidad_descuento_'+j).style.borderColor="#F00";
				document.getElementById('td_incapacidad_descuento_'+j).style.borderStyle="solid";
				document.getElementById('td_incapacidad_descuento_'+j).style.borderWidth="medium";
				mensaje_datosIncapacidad="Ingresa los datos de la incapacidad.<br>"; 
			}
			else
				document.getElementById('td_incapacidad_descuento_'+j).style.borderStyle="none";
		}

		if(Concepto_finalx != "") //Si el concepto no tiene numero de cantidad este no se procesa
		{
			document.forma.DeduccionesD.value = document.forma.DeduccionesD.value + "--" + Agrupador_finalx + "--" + Numero_finalx + "--" + Concepto_finalx  + "--" + Gravadox_final  + "--" + Excentox_final + "--" + Concepto_cartera_d_final + "--" +Importe_finalx + "--" + Dias_final + "--" + TipoIncapacidad_final + "--" + Descuento_final;
		}  
	}
                     
	for(k=0; k<renglon3; k++)
	{   
		var dias = "dias" + k;
		var dias_final = document.getElementById(dias).value;
		
		var tipo_incapacidad = "tipo_incapacidad" + k;
		var tipo_incapacidad_final = document.getElementById(tipo_incapacidad).value;
		
		var descuento = "descuento" + k;
		var descuento_final = document.getElementById(descuento).value;
		
		if(dias_final != "" && descuento_final != "0") //Si el concepto no tiene numero de cantidad este no se procesa
		{
			document.forma.Incapacidades.value = document.forma.Incapacidades.value + "--" + dias_final + "--" + tipo_incapacidad_final + "--" + descuento_final;
		}  
	}
                     
	for(h=0; h<contLin_subcontratacion; h++)
	{
		var RFCLabora = "RFCLabora" + h;
		var RFCLabora_final = document.getElementById(RFCLabora).value;
		
		var PTiempo = "PorcentajeTiempo" + h;
		var PTiempo_final = document.getElementById(PTiempo).value;
		
		if(RFCLabora_final != "" && PTiempo_final != "") //Si el concepto no tiene numero de cantidad este no se procesa
		{
			document.forma.SubContrataciones_hidden.value = document.forma.SubContrataciones_hidden.value + "--" + RFCLabora_final + "--" + PTiempo_final;
		}    
	}
                        
	for(o=0; o<contLin_otrospagos; o++)
	{
		var OtroTipo = "dcOtroTipo" + o;
		var OtroTipo_final = document.getElementById(OtroTipo).value;
		
		var ClaveOtro = "ClaveOtro" + o;
		var ClaveOtro_final = document.getElementById(ClaveOtro).value;
		
		var ConceptoOtro = "ConceptoOtro" + o;
		var ConceptoOtro_final = document.getElementById(ConceptoOtro).value;
		
		var ImporteOtro = "ImporteOtro" + o;
		var ImporteOtro_final = document.getElementById(ImporteOtro).value;
		
		var SubSidioCausado = "SubsidioCausado" + o;
		var SubSidioCausado_final = document.getElementById(SubSidioCausado).value;
		
		var SaldoFavor = "SaldoFavor" + o;
		var SaldoFavor_final = document.getElementById(SaldoFavor).value;
		
		var AnioOtro = "AnioOtro" + o;
		var AnioOtro_final = document.getElementById(AnioOtro).value;
		
		var Remanente = "Remanente" + o;
		var Remanente_final = document.getElementById(Remanente).value;
		
		var Switch_OtrosPagos = "Switch_OtrosPagos" + o;
		var Switch_OtrosPagos_final = document.getElementById(Switch_OtrosPagos).value;
		
		if (OtroTipo_final == "002") ////subsidio/////
		{
			if (eval(document.getElementById('SubsidioCausado'+o).value) < 0 || document.getElementById('SubsidioCausado'+o).value == "")
			{
				error++;
				document.getElementById('td_subsidio_causado_otros'+o).style.borderColor="#F00";
				document.getElementById('td_subsidio_causado_otros'+o).style.borderStyle="solid";
				document.getElementById('td_subsidio_causado_otros'+o).style.borderWidth="medium";
				mensaje_subsidio_causado="El subsidio causado no puede ser menor al total de Otros Pagos.<br>"; 
			}
			else
				document.getElementById('td_subsidio_causado_otros'+o).style.borderStyle="none";
				
			if (eval(document.getElementById('SubsidioCausado'+o).value) < eval(document.getElementById('ImporteOtro'+o).value))
			{
				error++;
				document.getElementById('td_importe_otrospagos'+o).style.borderColor="#F00";
				document.getElementById('td_importe_otrospagos'+o).style.borderStyle="solid";
				document.getElementById('td_importe_otrospagos'+o).style.borderWidth="medium";
				document.getElementById('td_subsidio_causado_otros'+o).style.borderColor="#F00";
				document.getElementById('td_subsidio_causado_otros'+o).style.borderStyle="solid";
				document.getElementById('td_subsidio_causado_otros'+o).style.borderWidth="medium";
				mensaje_subsidio_causado="El subsidio causado no puede ser menor al total de Otros Pagos.<br>"; 
			}
			else
				document.getElementById('td_subsidio_causado_otros'+o).style.borderStyle="none";
		}
        if (Switch_OtrosPagos_final == "1")
		{
			if(ImporteOtro_final == "" || ImporteOtro_final == "0")
			{
				error++;
				document.getElementById('td_importe_otrospagos'+o).style.borderColor="#F00";
				document.getElementById('td_importe_otrospagos'+o).style.borderStyle="solid";
				document.getElementById('td_importe_otrospagos'+o).style.borderWidth="medium";
				mensaje_cantidad="Los importes no pueden ser cero.<br>"; 
			}
			else
			{
				document.getElementById('td_importe_otrospagos'+o).style.borderStyle="none";
			}
		}
		
		var Concepto_cartera_o = "IDConceptoCartera_otro" + o;
		var Concepto_cartera_o_final = document.getElementById(Concepto_cartera_o).value;
                                                    
		if(OtroTipo_final != "" && ClaveOtro_final != "" && ImporteOtro_final != "") //Si el concepto no tiene numero de cantidad este no se procesa
		{
			document.forma.OtrosPagos_hidden.value = document.forma.OtrosPagos_hidden.value + "--" + OtroTipo_final + "--" + ClaveOtro_final+ "--" + ConceptoOtro_final + "--" + ImporteOtro_final + "--" + SubSidioCausado_final + "--" + SaldoFavor_final + "--" + AnioOtro_final + "--" + Remanente_final+ "--" + Concepto_cartera_o_final;
		}    
	}
	if(periodo != "99") 
	{
		var fecha_final = document.forma.fecha_calcula.value;
	}
	else
	{
		var fecha_final = document.forma.txtPeriodo_Final.value;      
	}

	if((fecha_inicio_laboral >= fecha_inicial) && (fecha_inicio_laboral >= fecha_final ))
	{
		error++;
		document.getElementById('td_fecha_calcula').style.borderColor="#F00";
		document.getElementById('td_fecha_calcula').style.borderStyle="solid";
		document.getElementById('td_fecha_calcula').style.borderWidth="medium";
		mensaje_fechas = "La fecha final no puede ser anterior a la fecha de inicio de relación laboral.<br>"; 
	}
	else if(fecha_final == "click para calcular fecha final" || fecha_final == "Seleccione Periodo" || fecha_final == "")
	{
		error++;
		document.getElementById('td_fecha_calcula').style.borderColor="#F00";
		document.getElementById('td_fecha_calcula').style.borderStyle="solid";
		document.getElementById('td_fecha_calcula').style.borderWidth="medium";
		mensaje_fecha_final="Fecha de periodo final no calculada.<br>"; 
	}
	else
	{
		if(fecha_pago == "")
		{
			error++;
			document.getElementById('td_fechapago').style.borderColor="#F00";
			document.getElementById('td_fechapago').style.borderStyle="solid";
			document.getElementById('td_fechapago').style.borderWidth="medium";
			mensaje_fechapago="Ingrese fecha de pago.<br>"; 
		}
		else
		{
			document.forma.action = "nueva_nomina.php"; 
			document.forma.target = "_self";
			if(valida == 1)
			{
				error++;
				mensaje_agrupador="Agrupador invalido en Percepciones '017' o '016'.<br>"; 
			}
		}      
	}
    var TOTAL = document.getElementById('TOTAL').value;
	if (TOTAL <= 0)
	{
		error++;
		document.getElementById('totales').style.borderColor="#F00";
		document.getElementById('totales').style.borderStyle="solid";
		document.getElementById('totales').style.borderWidth="medium";
		mensaje_suma="La suma de percepciones y otros pagos no puede ser mayor o igual a las deducciones.<br>";
	}
	else
		document.getElementById('totales').style.borderStyle="none";
                
	if(error == 0)
		document.forma.submit();
	else
	{
		window.scrollTo(0, 0);
		document.getElementById('tr_mensaje').style.display="table-row";
		document.getElementById("mensaje_errores").innerHTML="<font color='#CC0000'><b>Nomina incorrecta </b><br /><br />" + mensaje_concepto_p+ mensaje_concepto_d+mensaje_cantidad+mensaje_fechas+mensaje_fecha_final+mensaje_fechapago+mensaje_agrupador+mensaje_suma+mensaje_subsidio_causado+mensaje_op;
	}
             
}
 
function Add()
{  
	if(document.getElementById("conceptos0").value != "--") 
	{
		document.getElementById('midiv0').style.display = "table-row";
	}
	else
	{
		alert("Seleccione el concepto");
	}
}
  
function Add_editar(id)
{
	if(document.getElementById("conceptos" + id).value != "--") 
	{
		document.getElementById('midiv' + id).style.display = "table-row";
	}
	else
	{
		alert("Seleccione el concepto");
	}
}

function guardar(valor_insert)
{
	document.getElementById('midiv' + valor_insert).style.display = "none";
}
    
function factura_folio(valor) 
{
	var dato = valor.split("@");
	if(dato[0] == "factura")
	{
		document.getElementById("tabla_folios").style.display = "block";
		document.getElementById("folios").value = dato[1];
		document.getElementById("serie").value = dato[2];
		verificar();
	}
	else
	{
		document.getElementById("tabla_folios").style.display = "none"; 
	}
}
    
function verificar()
{
	if(document.getElementById("Comprados").value == "0")
	{
		alert("No cuenta con folios para facturar!");
		document.getElementById("tabla_folios").style.display = "none";
		document.getElementById("procesar").style.display = "none";
	}
}
    
function cambia_folio(cadena) 
{
	var dato = cadena.split("@"); 
	document.getElementById("serie").value = dato[0];
	document.getElementById("folios").value = dato[1];
}

function cambia_folio_2(serie,folio) 
{
	document.getElementById("serie").value = serie;
	document.getElementById("folios").value = folio;
}
    
function vista_previa()
{
    document.forma.PercepcionesD.value = "";
    document.forma.DeduccionesD.value = "";
    document.forma.OtrosPagos_hidden.value = "";
    document.forma.SubContrataciones_hidden.value = "";
   
	for(i=0; i<renglon; i++)
	{   
		var agrupador = "agrupador" + i;
		var Agrupador_final = document.getElementById(agrupador).value;
		
		if(Agrupador_final == "017" || Agrupador_final == "016")
		{
			valida = 1;
		}
		
		var numero = "numero" + i;
		var Numero_final = document.getElementById(numero).value;
		
		var conceptos = "conceptos" + i;
		var Concepto_final = document.getElementById(conceptos).value;
		
		var gravado = "Gravado" + i;
		var Gravado_final = document.getElementById(gravado).value;
		
		var excento = "Excento" + i;
		var Excento_final = document.getElementById(excento).value;
		
		var importe = "Importe" + i;
		var Importe_final = document.getElementById(importe).value;
                         
		if(Concepto_final != "") //Si el concepto no tiene numero de cantidad este no se procesa
		{
		document.forma.PercepcionesD.value = document.forma.PercepcionesD.value + "@" + Agrupador_final + "@" + Numero_final + "@" + Concepto_final + "@" + Gravado_final + "@" + Excento_final+ "@" + Concepto_final; 
		
		}  
	}
                     
	for(j=0; j<renglon2; j++)
	{                     
		var agrupadorx = "agrupadorx" + j;
		var Agrupador_finalx = document.getElementById(agrupadorx).value;
		
		var numerox = "numerox" + j;
		var Numero_finalx = document.getElementById(numerox).value;
		
		var conceptosx = "conceptosx" + j;
		var Concepto_finalx = document.getElementById(conceptosx).value;
		
		var gravadox = "Gravadox" + j;
		var Gravadox_final = document.getElementById(gravadox).value;
		
		var excentox = "Excentox" + j;
		var Excentox_final = document.getElementById(excentox).value;
		
		var importex = "Importex" + j;
		var Importe_finalx = document.getElementById(importex).value;
		
		if(Concepto_finalx != "") //Si el concepto no tiene numero de cantidad este no se procesa
		{
			document.forma.DeduccionesD.value = document.forma.DeduccionesD.value + "@" + Agrupador_finalx + "@" + Numero_finalx + "@" + Concepto_finalx  + "@" + Gravadox_final  + "@" + Excentox_final+ "@" + Concepto_finalx;
		}  
	}
                    
	for(h=0; h<contLin_subcontratacion; h++)
	{
		var RFCLabora = "RFCLabora" + h;
		var RFCLabora_final = document.getElementById(RFCLabora).value;
		
		var PTiempo = "PorcentajeTiempo" + h;
		var PTiempo_final = document.getElementById(PTiempo).value;
			
		if(RFCLabora_final != "" && PTiempo_final != "") //Si el concepto no tiene numero de cantidad este no se procesa
		{
			document.forma.SubContrataciones_hidden.value = document.forma.SubContrataciones_hidden.value + "@" + RFCLabora_final + "@" + PTiempo_final;
		}    
	}
                    
	for(o=0; o<contLin_otrospagos; o++)
	{
		var OtroTipo = "dcOtroTipo" + o;
		var OtroTipo_final = document.getElementById(OtroTipo).value;
		
		var ClaveOtro = "ClaveOtro" + o;
		var ClaveOtro_final = document.getElementById(ClaveOtro).value;
		
		var ConceptoOtro = "ConceptoOtro" + o;
		var ConceptoOtro_final = document.getElementById(ConceptoOtro).value;
		
		var ImporteOtro = "ImporteOtro" + o;
		var ImporteOtro_final = document.getElementById(ImporteOtro).value;
		
		var SubSidioCausado = "SubsidioCausado" + o;
		var SubSidioCausado_final = document.getElementById(SubSidioCausado).value;
		
		var SaldoFavor = "SaldoFavor" + o;
		var SaldoFavor_final = document.getElementById(SaldoFavor).value;
		
		var AnioOtro = "AnioOtro" + o;
		var AnioOtro_final = document.getElementById(AnioOtro).value;
		
		var Remanente = "Remanente" + o;
		var Remanente_final = document.getElementById(Remanente).value;
		
		var Switch_OtrosPagos = "Switch_OtrosPagos" + o;
		var Switch_OtrosPagos_final = document.getElementById(Switch_OtrosPagos).value;
		
        if (Switch_OtrosPagos_final == "1")
		{
			if(ImporteOtro_final == "" || ImporteOtro_final == "0")
			{
				error++;
				document.getElementById('td_importe_otrospagos'+o).style.borderColor="#F00";
				document.getElementById('td_importe_otrospagos'+o).style.borderStyle="solid";
				document.getElementById('td_importe_otrospagos'+o).style.borderWidth="medium";
				mensaje_cantidad="Los importes no pueden ser cero.<br>"; 
			}
			else
			{
				document.getElementById('td_importe_otrospagos'+o).style.borderStyle="none";
			}
		}
		
		var Concepto_cartera_o = "IDConceptoCartera_otro" + o;
		var Concepto_cartera_o_final = document.getElementById(Concepto_cartera_o).value;
                          
		if(OtroTipo_final != "" && ClaveOtro_final != "" && ImporteOtro_final != "") //Si el concepto no tiene numero de cantidad este no se procesa
		{
			document.forma.OtrosPagos_hidden.value = document.forma.OtrosPagos_hidden.value + "--" + OtroTipo_final + "--" + ClaveOtro_final+ "--" + ConceptoOtro_final + "--" + ImporteOtro_final + "--" + SubSidioCausado_final + "--" + SaldoFavor_final + "--" + AnioOtro_final + "--" + Remanente_final + "--" + Concepto_cartera_o_final;
		}       
	}
	document.forma.action = "vista_previa_nomina.php";
	document.forma.target = "_blank";
	document.forma.submit(); 
}
  
function borrador()
{
	document.forma.PercepcionesD.value = "";
	document.forma.DeduccionesD.value = "";
	for(i=0; i<renglon; i++)
	{                        
		var agrupador = "agrupador" + i;
		var Agrupador_final = document.getElementById(agrupador).value;
		
		var numero = "numero" + i;
		var Numero_final = document.getElementById(numero).value;
		
		var conceptos = "conceptos" + i;
		var Concepto_final = document.getElementById(conceptos).value;
		
		var gravado = "Gravado" + i;
		var Gravado_final = document.getElementById(gravado).value;
		
		var excento = "Excento" + i;
		var Excento_final = document.getElementById(excento).value;
		
		var importe = "Importe" + i;
		var Importe_final = document.getElementById(importe).value;
		
		if(Concepto_final != "--") //Si el concepto no tiene numero de cantidad este no se procesa
		{
			document.forma.PercepcionesD.value = document.forma.PercepcionesD.value + "--" + Agrupador_final + "--" + Numero_final + "--" + Concepto_final + "--" + Gravado_final + "--" + Excento_final + "--" + Importe_final;
		}  
	}
                     
	for(j=0; j<renglon2; j++)
	{   
		var agrupadorx = "agrupadorx" + j;
		var Agrupador_finalx = document.getElementById(agrupadorx).value;
		
		var numerox = "numerox" + j;
		var Numero_finalx = document.getElementById(numerox).value;
		
		
		var conceptosx = "conceptosx" + j;
		var Concepto_finalx = document.getElementById(conceptosx).value;
		
		var gravadox = "Gravadox" + j;
		var Gravadox_final = document.getElementById(gravadox).value;
		
		var excentox = "Excentox" + j;
		var Excentox_final = document.getElementById(excentox).value;
		
		var importex = "Importex" + j;
		var Importe_finalx = document.getElementById(importex).value;
		
		if(Concepto_finalx != "--") //Si el concepto no tiene numero de cantidad este no se procesa
		{
			document.forma.DeduccionesD.value = document.forma.DeduccionesD.value + "--" + Agrupador_finalx + "--" + Numero_finalx + "--" + Concepto_finalx  + "--" + Gravadox_final  + "--" + Excentox_final + "--" + Importe_finalx;
		}  
	}
	document.forma.action = "nomina_temporal.php";
	document.forma.target = "_self";
	document.forma.submit(); 
}
  
function valida_fechas()
{
	var fecha_inicial = new Date(document.forma.txtPeriodo_Inicial.value);
	var fecha_final = new Date(document.forma.txtPeriodo_Final.value);
	if (fecha_inicial > fecha_final)
	{
		alert("La fecha final del periodo no puede ser mayor a la fecha de inicio de relación laboral");
		document.forma.txtPeriodo_Final.value = "";
	}
	else
	{
		var dia = 86400000;
		var diff_miliseg = fecha_final - fecha_inicial;
		var diff_dias = diff_miliseg / dia;
		document.forma.txtDiasPagados.value = diff_dias; 
	}
}

//////////////////// Quitar esta validacion si se puede configurar el primer periodo si esta dentro la fecha de inicio
/*function valida_fecha_inicio_con_inicial()
{
	var fecha_inicial = new Date(document.forma.txtPeriodo_Inicial.value);
	var fecha_inicio_laboral = new Date(document.forma.fecha_inicio_laboral.value);
	if (fecha_inicio_laboral > fecha_inicial)
	{
		document.getElementById('td_periodo_inicial').style.borderColor="#F00";
		document.getElementById('td_periodo_inicial').style.borderStyle="solid";
		document.getElementById('td_periodo_inicial').style.borderWidth="medium";
		alert("La fecha del periodo inicial no puede ser anterior a la fecha de inicio de relación laboral");
		document.forma.txtPeriodo_Inicial.value = "";
	}
	else
	{
		document.getElementById('td_periodo_inicial').style.borderStyle="none";
	}
	limpia_final();
}*/
/////////////////////////////////////////
  
function calcula_fecha_final()
{
	var periodo =  document.getElementById("periodo_pago").value;
    var fecha_inicial = document.forma.txtPeriodo_Inicial.value;
    var dia_fecha = fecha_inicial.split("-");
    var dias = 0;
    var dato_fecha = fecha_inicial.split("-");
	
    if(fecha_inicial == "")
	{
		alert("Seleccione fecha inicial");
	}
    else
	{
		if(periodo == "02")
		{
      		dias = 7;
            sumafechas(dias, fecha_inicial,0);
            document.forma.txtDiasPagados.value = "7";
        }
        if(periodo == "04")
        {     
			ultimo_dia(dato_fecha[0]+"/"+dato_fecha[1]+"/"+dato_fecha[2],periodo);
			document.forma.txtDiasPagados.value = "15";
		}
        if(periodo == "05")
		{
			if(dia_fecha[2] == "01")
			{
                 ultimo_dia(dato_fecha[0]+"/"+dato_fecha[1]+"/"+dato_fecha[2],periodo);
                 document.forma.txtDiasPagados.value = "30";
			}
			else
			{
                 dias = 30;
                 sumafechas(dias, fecha_inicial,0);
                 document.forma.txtDiasPagados.value = "30";     
			}
		}
		if(periodo == "03")
		{
			dias = 14;
			sumafechas(dias, fecha_inicial,0);
			document.forma.txtDiasPagados.value = "14";
		}
		if(periodo == "01")
		{
			dias = 1;
			sumafechas(dias, fecha_inicial,0);
            document.forma.txtDiasPagados.value = "1";
		}
		if(periodo == "06")
		{
        	dias = 60;
          	sumafechas(dias, fecha_inicial,0);
          	document.forma.txtDiasPagados.value = "60";
		}
		if(periodo == "")
		{
			document.forma.fecha_libre.value = "Seleccione Periodo";
			document.forma.fecha_calcula.value = "Seleccione Periodo";
          	document.forma.txtFechaPago.value = "";
            document.forma.txtDiasPagados.value = "0";
		} 
	}
}
  
function sumafechas(dias,fecha2,indicador)
{
	milisegundos=parseInt(35*24*60*60*1000);
	var periodo =  document.getElementById("periodo_pago").value;
	var dia_fecha = fecha2.split("-");
    fecha=new Date(fecha2);
    dia=fecha.getDate();
    // el mes es devuelto entre 0 y 11
    mes=fecha.getMonth()+1;
    year=fecha.getFullYear();
 
 
    //Obtenemos los milisegundos desde media noche del 1/1/1970
    tiempo=fecha.getTime();
    //Calculamos los milisegundos sobre la fecha que hay que sumar o restar...
    milisegundos=parseInt(dias*24*60*60*1000);
    //Modificamos la fecha actual
    total=fecha.setTime(tiempo+milisegundos);
    dia=fecha.getDate();
    mes=fecha.getMonth()+1;
    year=fecha.getFullYear();
	var dia_mes_libre = dia_fecha[2] -1;
    if(dia <10)
	{
		dia = "0"+dia;
	}
     
    if(dia_mes_libre <=9 )
	{
		dia_mes_libre = "0"+dia_mes_libre;  
	} 
        
	if(mes <10)
	{
		mes = "0"+mes;
	}
         
	if(indicador == "1")
	{
		document.forma.fecha_calcula.value = year+"-"+mes+"-"+"15";  
		document.forma.txtFechaPago.value = year+"-"+mes+"-"+"15";
	}
	else
	{
		if(periodo == "05")
		{
			document.forma.fecha_calcula.value = year+"-"+mes+"-"+dia_mes_libre; 
			document.forma.txtFechaPago.value = year+"-"+mes+"-"+dia_mes_libre;
		}
		else
		{
			document.forma.fecha_calcula.value = year+"-"+mes+"-"+dia; 
			document.forma.txtFechaPago.value = year+"-"+mes+"-"+dia;  
		}
	}
}

function ultimo_dia(fecha,periodo)
{
	var dato_fecha2 = fecha.split("/");
	if(periodo == "04" && dato_fecha2[2] < 16)
	{
		sumafechas(14,fecha,1);  
	}
	else
	{
		var date = new Date(fecha);
		var dato_fecha = fecha.split("/");
		var ultimoDia = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    
		document.forma.fecha_calcula.value = dato_fecha[0]+"-"+dato_fecha[1]+"-"+ultimoDia.getDate();
		document.forma.txtFechaPago.value = dato_fecha[0]+"-"+dato_fecha[1]+"-"+ultimoDia.getDate();
	}
}

function limpia_final()
{
	document.forma.fecha_calcula.value = "click para calcular fecha final";
	document.forma.fecha_libre.value = "";
	document.forma.txtFechaPago.value = "";
}
 
function limpia_fechas()
{
	var periodo =  document.getElementById("periodo_pago").value;
	if(periodo == "99")
	{
        document.getElementById("fecha_calcula").type = "hidden";
        document.getElementById("txtPeriodo_Final").type = "text";
        document.getElementById("extraordinaria").value = "1";
	}
	else if ((periodo == "07") || (periodo == "08") || (periodo == "09"))
	{
		document.getElementById("fecha_calcula").type = "hidden";
        document.getElementById("txtPeriodo_Final").type = "text";
		document.getElementById("extraordinaria").value = "0";
	}
	else
	{
        document.getElementById("fecha_calcula").type = "text";
        document.getElementById("txtPeriodo_Final").type = "hidden"; 
        document.getElementById("extraordinaria").value = "0";
	}
  
	document.forma.fecha_calcula.value = "click para calcular fecha final";
	document.forma.fecha_libre.value = "";
	document.forma.txtFechaPago.value = "";
}
 
 function soloNumeros(e,id)
{
   // alert();
	// capturamos la tecla pulsada
	var teclaPulsada=window.event ? window.event.keyCode:e.which;
   
	var valor4=document.getElementById("Excentox"+id).value;
   

	// 45 = tecla simbolo menos (-)
	
	if(teclaPulsada==45 && valor4.indexOf("-")==-1)
	{
		document.getElementById("Excentox"+id).value="-"+valor4;
	  
	}
  

	if(teclaPulsada==13 || (teclaPulsada==46 && valor4.indexOf(".")==-1))
	{
		return true;
	}
   
	return /\d/.test(String.fromCharCode(teclaPulsada));
}

function muestra_subcontratos(casilla)
{
   if (casilla.checked == true)
	{
		document.getElementById('valor_subcontratos').value = "1";
		document.getElementById('muestra_subcontrataciones').style.display = "table";
	} 
	else
	{
		document.getElementById('valor_subcontratos').value = "0";
		document.getElementById('muestra_subcontrataciones').style.display = "none";
	} 
}
   
function actualiza_periodo(valor)
{
	http.abort();  //fija
	cadena = "nueva_nomina_actualiza_periodo.php?token=" + valor;
	http.open("GET", cadena, true); //FIJO
	http.onreadystatechange=function()  //FIJO
	{
		if(http.readyState == 4) //FIJO
		{
			document.getElementById('periodo').innerHTML = http.responseText;
			if (document.getElementById('periodo_pago').value = "99")
			  limpia_fechas();
			
		}
	}
	http.send(null);
}
   
function regresar_listado() 
{
   parent.jQuery.fancybox.close();
}
 
//agregar_pension();
//agregar_indemnizacion();
//agregar_subcontratacion();
// agregar_otrospagos();
//agregar_incapacidad();
//agregar_percepcion();
//agregar_deducciones();
window.scrollTo(0, 0);
 
</script>


  
