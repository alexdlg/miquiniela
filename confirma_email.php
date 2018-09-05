<?php
header("Content-Type: text/html; charset=iso-8859-1"); 
include ("encabezado_fancy.php");

$token = explode("@", $_GET['token']);

$IDUsuario = $token[1];
$IDAviso = $token[0];

$hoy = date("Y-m-d");
$ya = date("H:i:s");

$sql = "INSERT INTO avisos_log (Folio, Fecha, Hora, IDUsuario, IP, Sexion, IDAvisoCorreo ) VALUES
							   ('','$hoy','$ya','$IDUsuario','$IP','$sexion','$IDAviso');";
$insercion = mysql_query($sql);							   
							   

?>


<style>
.rol
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;
	
}
</style>

<table border="0" cellpadding="0" cellspacing="0" width="1000" align="center" class="tabla_principal"><tr height="30"><td align="center"><br /><br />
<table border="0" cellpadding="10" cellspacing="0" width="100%" align="center" class="listado">
  <tr>
    <td valign="top" align="center" bgcolor="#009900"> 
      <font color='#FFFFFF' size="+2"><b>Confirmacion de Correo</b></font>	 
    </td>
  </tr>  
  <tr>
    <td align="center">
      <br /><br />
      Gracias por confirmar, ¿que hacemos ahora? Pronosticos? Estadisticas? tu dices...
      <br /><br /><br />
      <a href="inicio.php?session_id=<?php echo $_GET['session_id'];?>">Entrar al Site</a>
    </td>
  </tr>
</table>
</td></tr></table>