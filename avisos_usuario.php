<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");
require("fechas.php");
require("genera_clave.php");

$IDUsuario = $_SESSION['quiniela_matona_id'];
$token = explode("@", $_GET['token']);

$IDAviso = $token[2];
$sql = "SELECT * FROM avisos WHERE IDAviso ='$IDAviso'";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);


if ($renglon['Imagen'] != "")
  $la_imagen = "<img src='http://www.laquinieladelgordo.com/imagenes/".$renglon['Imagen']."' width='100%'>";
else
  $la_imagen = "<img src='http://www.laquinieladelgordo.com/web/images/logo.png'>";

$Basura1 = dameReferencia() . dameReferencia() . dameReferencia();
$Basura2 = dameReferencia() . dameReferencia() . dameReferencia();
$Basura3 = dameReferencia() . dameReferencia() . dameReferencia();
$Basura4 = dameReferencia() . dameReferencia() . dameReferencia();

$TokenCompleto = $Basura1 . "@" . $Basura2 . "@" . $IDUsuario . "@" . $Basura3 . "@" . $Basura4 . "@" . $IDAviso. "@" . $Basura1 . "@" . $Basura3;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Notificaciones</title>
</head>

<style>
body
{
	font-family:Verdana, Geneva, sans-serif;	
}

.tabla_negra td a
{
	color:#FFF;
	cursor:pointer;
	text-decoration:none;
	font-weight:bold;
}

.tabla_negra td a:hover
{
	color:#090;
}

.mensaje
{
	border-color:#0C3;
	border-style:solid;
	border-width:medium;
	border-radius:25px;	
}
</style>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" bgcolor="#FFFFFF">

<table width="95%" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td align="center">
      <?php echo $la_imagen;?>
    </td>
  </tr>
  <tr>
    <td align="center">
      <br />
      <table class="mensaje" cellpadding="10">
        <tr>
          <td align="center">
	        <font size="+2" color="#663333"><?php echo strtoupper($renglon['Titulo']);?></font>
            <p align="justify">
              <?php echo $renglon['Resumen'];?>
            </p>
          </td>
        </tr>
      </table>
      <br /><br />
    </td>
  </tr>
  <tr>
    <td>
      <table border="0" cellpadding="10" cellspacing="0" align="center" width="100%" bordercolor="#000066" class="tabla_negra">
        <tr align="center">
          <td width="30%" bgcolor="#000000">
            <a onclick="no_volver();">No volver a mostrar</a>
          </td>
          <td bgcolor="#FFFFFF">
            &nbsp;
          </td>
          <td width="30%" bgcolor="#000000">
            <div id="boton_ver"><a onclick="ver_mas();">Ver Mas >></a></div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr id="tr_mensaje" style="display:none;">
    <td align="center" bgcolor="#DDDDDD"  style="padding:10px;">
      <p align="justify">
        <?php echo $renglon['Mensaje'];?>
      </p>
    </td>
  </tr>  
</table>
<br /><br />
</body>
</html>


<script language="javascript">
  function ver_mas()
  {
	  document.getElementById('tr_mensaje').style.display = "table";	 
	  document.getElementById('boton_ver').innerHTML = "<a onclick=\"ver_menos();\">Ver Menos <<</a>";
  }

  function ver_menos()
  {
	  document.getElementById('tr_mensaje').style.display = "none";	  
	  document.getElementById('boton_ver').innerHTML = "<a onclick=\"ver_mas();\">Ver Mas >></a>";
  }

 
  
    var http = false;

	//verificacion del navegador para el uso de AJAX
    if(navigator.appName == "Microsoft Internet Explorer") {
      http = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
      http = new XMLHttpRequest();
    }


    function no_volver() 
	{
      http.abort();
	  cadena="avisos_usuario_leido.php?token=<?php echo $TokenCompleto;?>";
	  http.open("GET", cadena, true);
      http.onreadystatechange=function() 
	    {
        if(http.readyState == 4) 
		  {
		    parent.jQuery.fancybox.close();
		  }
        }
      http.send(null);
 	}
  
</script>