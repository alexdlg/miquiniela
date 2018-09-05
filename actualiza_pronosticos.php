<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
require("include/init.php");
require("fechas.php");
$token = explode("@", $_GET['token']);
$IDUsuario = $token[0];
$IDJornada = $token[1];

$sql_jornada = "SELECT * FROM ligas as l, temporada as t, jornadas as j 
		     WHERE 
			   j.IDLiga = l.IDLiga AND 
			   t.IDLiga = l.IDLiga AND
			   j.IDTemporada = t.IDTemporada AND
			   j.IDJornada = '$IDJornada'";
$consulta_jornada = mysql_query($sql_jornada);
$renglon = mysql_fetch_assoc($consulta_jornada);
$ColumnaPuntos = $renglon['Puntaje'];
$IDLiga = $renglon['IDLiga'];

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
	color:#FFFFFF;
}


</style>

      <table cellpadding="5" cellspacing="0" border="0" width="100%" bordercolor="#FF00FF" class="rol">
        <tr>
          <td colspan="8" align="center">
            <b>Los Pronosticos de <?php echo $Jugador;?> alias <i>"<?php echo $Apodo;?>"</i></b>
          </td>
        </tr>
	    <tr>
    	  <td valign="top" align="center" bgcolor="#000000" colspan="8"> 
	        <?php
		    echo "<font color='#FFFFFF'><b>Jornada " . $renglon['Jornada'] . " - " . $renglon['Liga'] . "</b></font>";
		    ?>
    	  </td>
	    </tr>  
        <tr align="center" bgcolor="#444444">
          <th width="200" align="right">Local</th>
          <th colspan="5">&nbsp;</th>
          <th width="200" align="left">Visitante</th>
          <th >Pts.</th>
        </tr>
        <?php
		$sql = "SELECT * , L.Equipo as Local, V.Equipo as Visitante
				FROM juegos as j, equipos as L, equipos as V
		 		WHERE 
				  j.EquipoLocal = L.IDEquipo AND
				  j.EquipoVisitante = V.IDEquipo AND
				  j.IDJornada = '".$renglon['IDJornada']."' 
				ORDER BY Fecha asc, Hora asc, IDJuego asc";
		$consulta = mysql_query($sql);
		$Acumulado = 0;
		$TotalGolesBono = 0;  
		for ($j=0;$j<mysql_num_rows($consulta);$j++)
		  {
			  $renglon_juego = mysql_fetch_assoc($consulta);
			  $sql_p = "SELECT * FROM pronosticos WHERE IDUsuario = '$IDUsuario' AND IDJuego = '" . $renglon_juego['IDJuego'] . "'";
			  $consulta_p = mysql_query($sql_p);
			  if (mysql_num_rows($consulta_p) == 1)
			    {
					$renglon_p = mysql_fetch_assoc($consulta_p);
					
					if ($IDLiga == "8")
					  {
						
						if ($renglon_p['Resultado'] == "L")
						  {
							$icoL = "<img src='images/si.png' width='20'>";
							$icoV = "<img src='images/spacer.gif' width='20'>";
							$icoE = "<img src='images/spacer.gif' width='20'>";
						  }
						else
						if ($renglon_p['Resultado'] == "V")
						  {
							$icoV = "<img src='images/si.png' width='20'>";
							$icoL = "<img src='images/spacer.gif' width='20'>";
							$icoE = "<img src='images/spacer.gif' width='20'>";
						  }
						else
						  {
							$icoE = "<img src='images/si.png' width='20'>";
							
							if ($renglon_p['GFLocal'] == "1")
							  $icoL = "<img src='images/si.png' width='20'>";
							else							
							  $icoL = "<img src='images/spacer.gif' width='20'>";
							  
							if ($renglon_p['GFVisitante'] == "1")
							  $icoV = "<img src='images/si.png' width='20'>";
							else							
							  $icoV = "<img src='images/spacer.gif' width='20'>";

							
						  }
						$GFLP = $renglon_juego['GolesLocal'];
						$GFVP = $renglon_juego['GolesVisitante'];
						if ($renglon_juego['Capturado'] == "1")
						  {
							$TDPuntos = $renglon_p[$ColumnaPuntos];
							$Acumulado+= $renglon_p[$ColumnaPuntos];
						  }
						else
						  $TDPuntos = "";
					  }
					else
					  {
						$GFLP = $renglon_p['GFLocal'];
						$GFVP = $renglon_p['GFVisitante'];
						if ($renglon_juego['Capturado'] == "1")
						  {
							$TDPuntos = $renglon_p[$ColumnaPuntos];
							$Acumulado+= $renglon_p[$ColumnaPuntos];
						  }
						else
						  $TDPuntos = "";
					  }
				}
			  else
			    {
					$GFLP = "";
					$GFVP = "";
					$renglon_p = "";
					$TDPuntos = "";
				}
			  
				if ($trcolor == "#FFFFFF")
				  $trcolor = "#EAEAEA";
				else
				  $trcolor = "#FFFFFF";
				  
				$TotalGolesBono += $GFLP + $GFVP;


			  if ($renglon_juego['Fecha'] > $hoy)
			    {
					$GFLP = "<img src='images/secret.png' width='40' title='Espera a que se cierre la jornada para ver este pronostico'>";
					$GFVP = "<img src='images/secret.png' width='40' title='Espera a que se cierre la jornada para ver este pronostico'>";
				}
			  else
			  if ($renglon_juego['Fecha'] == $hoy)
			    if ($renglon_juego['Hora'] > $ya)
			      {
					$GFLP = "<img src='images/secret.png' width='40' title='Espera a que se cierre la jornada para ver este pronostico'>";
					$GFVP = "<img src='images/secret.png' width='40' title='Espera a que se cierre la jornada para ver este pronostico'>";
				  }
			    

				echo "<tr bgcolor='".$trcolor."'>
						<td colspan='8' align='center'>
						  ".dameFechaCortaDiaCortoCompleto($renglon_juego['Fecha'])." - 
						  ".$renglon_juego['Hora']." hrs. 
						</td>
					  </tr>
					  <tr bgcolor='".$trcolor."'>
						<td rowspan='2' align='right'>".$renglon_juego['Local']."</td>
						<td rowspan='2' align='left'><img src='images/escudos/".$renglon_juego['EquipoLocal'].".png' width='50'></td>
						<td align='center'>$GFLP</td>
						<td align='center'>vs</td>
						<td align='center'>$GFVP</td>
						<td rowspan='2' align='right'><img src='images/escudos/".$renglon_juego['EquipoVisitante'].".png'  width='50'></td>
						<td rowspan='2' >".$renglon_juego['Visitante']."</td>
						<td rowspan='2' align='center'>".$TDPuntos."</td>
					  </tr>
					  <tr bgcolor='".$trcolor."'>
						<td align='center'>$icoL</td>
						<td align='center'>$icoE</td>
						<td align='center'>$icoV</td>
					  </tr>
					  ";
		  }
		?>
        <tr bgcolor="#444444">
          <th colspan="7" align="right" ><b>ACUMULADO SEMANAL</b></td>
          <th align="center"><b><?php echo $Acumulado;?></b></td>
        </tr>
        <?php
		if ($IDLiga != "8")
		{
		?>
        <tr bgcolor="#000000">
          <th colspan="8" align="left">
            Total de Goles de Jornada: <b><?php echo $TotalGolesBono;?></b>
          </th>
        </tr>
        <?php
		}
		?>
      </table>