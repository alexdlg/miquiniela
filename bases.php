<?php 
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");
require("genera_clave.php");
require("fechas.php");


$IDLiga  = $_SESSION['quiniela_matona_idliga'];
if ($IDLiga == "")
  $IDLiga = "1";


$sql = "SELECT * FROM ligas as l WHERE l.IDLiga = '$IDLiga'";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);
$LogoLiga = "<img src='images/".$renglon['Logo']."' height='60%'>";


$sql = "SELECT * FROM bases as b
		WHERE 
		  b.IDLiga = '$IDLiga'
		ORDER BY Orden asc";					  
$consulta = mysql_query($sql);


?>


<style>
.listado
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;	
}
</style>

<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="tabla_principal"><tr height="30"><td align="center">
<table border="0" cellpadding="10" cellspacing="0" width="100%" align="center" class="listado">
  <tr>
    <td valign="top" align="center" bgcolor="#000000"> 
      <font color='#FFFFFF' size="+2"><b>BASES</b></font>	 
    </td>
  </tr>  
  <tr>
    <td align="right">
	  <?php echo $LogoLiga;?>
    </td>
  </tr>
  <tr>
    <td valign="top" align="left">
      <font size="+1">
      <ol>
      <?php
	  for ($b=0;$b<mysql_num_rows($consulta);$b++)
	    {
		  $renglon = mysql_fetch_assoc($consulta);
		  echo "<li>" . $renglon['Regla'] . "</li><br />";
		}
	  ?>

      </ol>
      </font>
    </td>
  </tr>  
  <tr>
    <td align="right">
      Fecha: 03 de Junio de 2017
    </td>
  </tr>
</table>
<br /><br />
</td></tr></table>
<div id="mensaje"></div>

<br /><br />


<script language="javascript">

    var http = false;

	//verificacion del navegador para el uso de AJAX
    if(navigator.appName == "Microsoft Internet Explorer") {
      http = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
      http = new XMLHttpRequest();
    }


    function activa_pago(aidi, elemento) 
	{
      http.abort();
	  cadena="registra_pago.php?token=" + aidi + "@" + elemento.checked + "@<?php echo $renglon_jornada['IDJornada'];?>" ;
	  http.open("GET", cadena, true);
      http.onreadystatechange=function() 
	    {
        if(http.readyState == 4) 
		  {
			  a = "no hacemos nada";
		  }
        }
      http.send(null);
 	}

</script>
