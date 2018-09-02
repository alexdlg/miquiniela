<?php
header("Content-Type: text/html; charset=iso-8859-1"); 
require("include/init.php");
require("fechas.php");
$token = explode("@", $_GET['token']);
$IDUsuario = $token[0];
$IDJornada = $token[1];
$sql = "SELECT * FROM jornadas as j, ligas as l WHERE l.IDLiga = j.IDLiga AND IDJornada = '$IDJornada'";

$consulta = mysql_query($sql);
$renglon_jornada = mysql_fetch_assoc($consulta);


$hoy = date("Y-m-d");
$ya = date("H:i:s");

$sql_usuario = "SELECT * FROM usuarios AS u WHERE IDUsuario = '$IDUsuario'";
$consulta_usuario = mysql_query($sql_usuario);
if (mysql_num_rows($consulta_usuario) != 1)
  {
  echo "No se encontro al usuario";
  exit();
  }
else
  {
	  $renglon_usuario = mysql_fetch_assoc($consulta_usuario);
	  $Jugador = $renglon_usuario['Nombre'];
	  $Apodo = $renglon_usuario['Apodo'];
  }

?>

<style>
.rol
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;
	
}

.rol input
{
	text-align:center;
}

.rol th
{
	font-weight:bold;
	color:#000;
}
</style>

      <table cellpadding="5" cellspacing="0" border="0" width="100%" bordercolor="#FFFFFF" class="rol">
        <tr align="center" bgcolor="#00CC33">
          <td colspan="9" align="center">
            <b>Los Resultados de <?php echo $Jugador;?> alias <i>"<?php echo $Apodo;?>"</i></b>
          </td>
        </tr>
        <?php
		  $sql = "SELECT * FROM jornadas WHERE IDLiga = '".$renglon_jornada['IDLiga']."' AND IDTemporada = '".$renglon_jornada['IDTemporada']."'  ORDER BY IDJornada asc";
		  
		  $consulta = mysql_query($sql);
		  $Acumulado = 0;
		  for ($j=1;$j<=mysql_num_rows($consulta);$j++)
		    {
				$renglon = mysql_fetch_assoc($consulta);
				
				if ($trcolor == "#FFFFFF")
				  $trcolor = "#EAEAEA";
				else
				  $trcolor = "#FFFFFF";
				
				$sql_puntos = "SELECT SUM(Puntos) FROM pronosticos WHERE IDUsuario = '$IDUsuario' AND IDJuego IN (SELECT IDJuego FROM juegos WHERE IDJornada = '".$renglon['IDJornada']."')";
				$consulta_puntos = mysql_query($sql_puntos);
				$renglon_puntos = mysql_fetch_row($consulta_puntos);
				
				echo "<tr bgcolor='".$trcolor."'>
				        <td align='right'>Jornada ".$renglon['Jornada']."</td>
				        <td align='right'>".number_format($renglon_puntos[0])." puntos</td>
						<td align='center'><a href='actualiza_pronosticos.php?token=".$IDUsuario."@".$renglon['IDJornada']."'><img src='images/zoom.png'></a></td>
					  </tr>";
				$Acumulado+=$renglon_puntos[0];
			}
		?>
        <tr bgcolor="#00FF33">
          <td align="right" ><b>ACUMULADO TOTAL</b></td>
          <td align="right"><b><?php echo $Acumulado;?> puntos</b></td>
          <td>&nbsp;</td>
        </tr>
      </table>