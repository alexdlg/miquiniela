<?php
session_start();
include("include/init.php");
require("genera_clave.php");
require("fechas.php");

$IDUsuario = $_SESSION['quiniela_matona_id'];



if ($_SERVER['REQUEST_METHOD'] == "POST")
  {
	require_once ("PHPMailer/class.phpmailer.php");
	include("PHPMailer/class.smtp.php");
	$IDUsuario = $_POST['txtIDUsuario'];
	$FechaPago = $_POST['txtFechaPago'];
	$MontoPago = $_POST['txtMontoPago'];
	$Comentarios = $_POST['txtComentarios'];
	
	$sql_usuario = "SELECT * FROM usuarios WHERE IDUSuario = '$IDUsuario'";
	$consulta_usuario = mysql_query($sql_usuario);
	$renglon_usuario = mysql_fetch_assoc($consulta_usuario);
	$FechaCaptura = date("Y-m-d");
	$HoraCaptura = date("H:i:s");
	$IDCapturista = $_SESSION['quiniela_matona_id'];
	
	$sql = "INSERT INTO tickets (FolioPago, FechaPago, FechaCaptura, HoraCaptura, IDUsuario, Monto, IDCapturista, Comentarios, Status) VALUES
								('','$FechaPago','$FechaCaptura','$HoraCaptura','$IDUsuario','$MontoPago','$IDCapturista','$Comentarios','-1');";
	$insercion = mysql_query($sql);
	$FolioPago = mysql_insert_id();
	
	$resultado = mysql_affected_rows();
	$mensaje_error = mysql_error();  
		
	if ($_FILES['adjunto']['name'] != "")	  
	  {
		$FileName = $FolioPago. "_" . $IDUsuario. "_" . $_FILES['adjunto']['name'];
		$Adjunto = "1";
		$Archivo = $_FILES['adjunto']['name'];
	  }
	else
	  {
		$FileName = '';
		$Adjunto = "0";
		$Archivo = "";
	  }
	
	if ($_FILES['adjunto']['tmp_name'] != "")
	{
	  $path_real= "t1cket5/" . $FileName;
	  if (!move_uploaded_file($_FILES['adjunto']['tmp_name'], $path_real))
		echo "No se pudo enviar el archivo correctamente";		
	}  
	
	$sql_adjunto = "UPDATE tickets SET Archivo = '$Archivo', Adjunto = '$Adjunto' WHERE FolioPago = '$FolioPago'";
	$modificacion_adjunto = mysql_query($sql_adjunto);	  	  
	
	if ($resultado > 0)
	  {
		$mensaje = "Pago Recibido correctamente";
		$color_mensaje = "#00FF00";
	  }
    else
	  {
		$mensaje = "Error No. " . mysql_errno() . ": " . mysql_error();
		$color_mensaje = "#FF0000";
	  }
	  
	  $Mensaje = "Pago Recibido en LQDG";
	  $Mensaje.= "<br />Folio Pago: " . $FolioPago;
	  $Mensaje.= "<br />Fecha de Pago: " . dameFechaCompleta($FechaPago);
	  $Mensaje.= "<br />Jugador: " . $renglon_usuario['Nombre'] . " ( " . $renglon_usuario['Apodo'] . " ) ";
	  $Mensaje.= "<br />Monto: " . $MontoPago;
	  $Mensaje.= "<br />Comentarios: " . $Comentarios;
	  
			$mail = new PHPMailer();
	//CONFIGURACIONES DE SMTP
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Username = "notificaciones@laquinieladelgordo.com";
			$mail->Password = "8ev7n3A*G_ry92t8";		
			$mail->Port     = 25;		
			$mail->SMTPSecure = "tls";
			$mail->Host       = "mail.laquinieladelgordo.com"; // SMTP server
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
	//FIN DE CONFIGURACIONES
			$mail->ContentType = "text/html";
			$mail->SetFrom('notificaciones@laquinieladelgordo.com','El Gordo');
			$mail->Subject = "Reporte de Pago";
			$mail->Body = str_replace("\n", "<br />", $Mensaje);
			$mail->AddAddress('webmaster@laquinieladelgordo.com');        
			$mail->Send();	  
	
	
	
	
	echo "<script language='javascript'>window.location = 'pagos.php?seccion=3&".$SessionURL."';</script>";
  }
  
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<style>
  body
  {
	  font-family:Verdana, Geneva, sans-serif;
	  font-size:14px;
	  color:#000;	  
  }
  

.show {
    display: block;
}


  .reporte_pagos td
  {
	  font-family:Verdana, Geneva, sans-serif;
	  font-size:18px;
  }

  .reporte_pagos td input[type=text]
  {
	  font-family:Verdana, Geneva, sans-serif;
	  font-size:18px;
	  width:100%
  }

  .reporte_pagos td textarea
  {
	  font-family:Verdana, Geneva, sans-serif;
	  font-size:18px;
	  width:100%
  }

  .tabla_faq td
  {
	  border-top-style:solid;
	  border-top-width:medium;
	  border-top-color:#090;
  }
</style>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
$("#txtFechaPago").datepicker({ dateFormat: "yy-mm-dd" });
});
</script>

<body>


<nav class="navbar navbar-expand-sm bg-dark navbar-dark">

  <!-- Links -->
  <ul class="navbar-nav">
    <!-- Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
        <font size="+2"><b>&equiv;</b>&nbsp;Mis Pagos</font>
      </a>
      <div class="dropdown-menu">
	   <a class="dropdown-item" onclick="faq_pagos('1')" style="cursor:pointer">Lugares de Pago</a>
        <a class="dropdown-item" onclick="faq_pagos('2')" style="cursor:pointer">Reportar mi Pago</a>
        <a class="dropdown-item" onclick="faq_pagos('3')" style="cursor:pointer">Ver el Status de mi Pago</a>
        <a class="dropdown-item" onclick="faq_pagos('4')" style="cursor:pointer">Mi Historial de Pagos</a>
        <a class="dropdown-item" onclick="faq_pagos('5')" style="cursor:pointer">Consultar Saldo</a>
        <a class="dropdown-item" onclick="faq_pagos('6')" style="cursor:pointer">Administrar mis Bancos</a>
        <a class="dropdown-item" onclick="faq_pagos('7')" style="cursor:pointer">Transferir Saldo</a>
      </div>
    </li>
  </ul>
</nav>

<br /><br />               
<div id="pagos_detalle"></div>
</center>

<script language="javascript">
    var http = false;

	//verificacion del navegador para el uso de AJAX
    if(navigator.appName == "Microsoft Internet Explorer") {
      http = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
      http = new XMLHttpRequest();
    }
	
	function valida_pago()
	{
		document.getElementById('cmdPago').disabled = true;
		
		if (document.FormaPago.txtFechaPago.value == "")
		  {
		    alert("Por favor ingresa la Fecha del Pago");
			document.getElementById('cmdPago').disabled = false;
		  }
		else
		if (document.FormaPago.txtMontoPago.value == "")
		  {
		    alert("Por favor ingresa el Monto del Pago");
			document.getElementById('cmdPago').disabled = false;
		  }
		else
		if (document.FormaPago.adjunto.value == "")
		  {
		    alert("Por favor adjunta tu comprobante de pago JPG, PNG o PDF");
			document.getElementById('cmdPago').disabled = false;
		  }
		else
		  document.FormaPago.submit();
	}
	
	
	function faq_pagos(seccion)
	{
      http.abort();
	  cadena="pagos_actualiza_faq.php?token=" + seccion + "&<?php echo $SessionURL ;?>";
	  http.open("GET", cadena, true);
      http.onreadystatechange=function() 
	    {
        if(http.readyState == 4) 
		  {
          document.getElementById('pagos_detalle').innerHTML = http.responseText;
		  }
        }
      http.send(null);		
	}
	
	function saldo_detalle()
	{
		document.getElementById('saldo_detalle').style.display = "table";
	}

  function valida_transfer()
  {
	  if (document.getElementById('dcUsuarioPara').value == "-")
	    alert("Selecciona a quien le quieres transferir saldo");
	  else
	  if (document.getElementById('txtTransfer').value == "")
	    alert("Ingresa el Monto a Transferir");
	  else
	  if (Number(document.getElementById('txtTransfer').value) < 0)
	    alert("El Monto a Transferir NO puede ser negativo");
	  else
	    document.FormaTransfer.submit();
	    
  }

    var http = false;

	//verificacion del navegador para el uso de AJAX
    if(navigator.appName == "Microsoft Internet Explorer") {
      http = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
      http = new XMLHttpRequest();
    }



  function revisa_saldo(aidi)
  {
      http.abort();
	  cadena="revisa_saldo.php?token=" + aidi;
	  http.open("GET", cadena, true);
      http.onreadystatechange=function() 
	    {
        if(http.readyState == 4) 
		  {
              document.getElementById("saldo").innerHTML = http.responseText;			  
		  }
        }
      http.send(null);
	  
  }
	<?php 
	if ($_GET['seccion'] == 3)
	  echo 'faq_pagos(3);';
	else
	  echo 'faq_pagos(1);';
	?>
</script>
