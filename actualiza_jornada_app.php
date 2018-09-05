<?php
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");
require("fechas.php");
require("genera_clave.php");

$IDJornada = $_GET['token'];
$sql = "SELECT * FROM jornadas as j, ligas as l WHERE l.IDLiga = j.IDLiga AND IDJornada = '$IDJornada'";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);
$SessionID = $_GET['sesion'];

$TokenBono = $SessionID . "~" . $IDUsuario . "~" . $IDJornada;
$TokenBonoHash = md5($TokenBono);
$TokenBonogol = $TokenBono . "&hash=" . $TokenBonoHash;

?>



<?php
		$sql = "SELECT * , L.Equipo as Local, V.Equipo as Visitante
				FROM juegos as j, equipos as L, equipos as V
		 		WHERE 
				  j.EquipoLocal = L.IDEquipo AND
				  j.EquipoVisitante = V.IDEquipo AND
				  j.IDJornada = '".$IDJornada."' 
				ORDER BY Fecha asc, Hora asc, IDJuego asc";
		$consulta = mysql_query($sql);
		if (mysql_num_rows($consulta) == 0)
		  echo "<tr><td align='center' colspan='10'><br><br>NO HAY INFORMACION POR EL MOMENTO.<br><br></td></tr>";
		  
		$TotalGolesBono = 0;  
		for ($j=0;$j<mysql_num_rows($consulta);$j++)
		  {
			    $renglon_juego = mysql_fetch_assoc($consulta);
				if ($trcolor == "#FFFFFF")
				  $trcolor = "#EAEAEA";
				else
				  $trcolor = "#FFFFFF";
				if ($renglon_juego['Capturado'] == 1)
				  {
				    $tdGL = $renglon_juego['GolesLocal'];
				    $tdGV = $renglon_juego['GolesVisitante'];
					$TotalGolesBono += $tdGL + $tdGV;
				  }
				else
				  {
				    $tdGL = "";
				    $tdGV = "";
				  }	
				  
			    $tokenJuego = $renglon_juego['IDJuego'] . "@" . $renglon['IDJornada'];
				  

			    $boton_historico = "<a class='various' data-fancybox-type='iframe' href='juegos-admin-historico-listado.php?token=".$tokenJuego."'><img src='images/history.png'  class='img-fluid'title='Ver Historico de Enfrentamientos'></a>";

				$boton_pronosticos = "<a class='various' data-fancybox-type='iframe' href='los-pronosticos-partido.php?token=".$tokenJuego."'><img src='images/zoom.png'  class='img-fluid' title='Ver Pronosticos de la Raza para este Enfrentamiento'></a>";

				$boton_mxm = "<a href='mxm.php?session_id=" . $SessionID . "&token=".$tokenJuego."'><img src='images/mxm.png'  class='img-fluid' title='Seguir Minuto a Minuto'></a>";		


				$Basura1 = dameReferencia() . dameReferencia() . dameReferencia();
				$Basura2 = dameReferencia() . dameReferencia() . dameReferencia();
				$Basura3 = dameReferencia() . dameReferencia() . dameReferencia();
				$Basura4 = dameReferencia() . dameReferencia() . dameReferencia();

				$TokenAviso = $Basura3 .$Basura1 . "@" . $Basura2 .$Basura3 . "@18@" . $Basura1 . $Basura2 ;
	  
				if ($renglon_juego['Bonogol'] == "1")
				  $iconoEspecial = "<a class='notificaciones' id='bono_especial' data-fancybox-type='iframe' href='bono_especial.php?token=".$TokenBonogol."'><img src='images/gorra.png' class='img-fluid'></a>";
				else
				if ($renglon_juego['Rompequinielas'] == "1")
				  $iconoEspecial = "<a class='notificaciones' id='rompequinielas' data-fancybox-type='iframe' href='avisos_usuario.php?token=".$TokenAviso."'><img src='images/ico-x2.png'  class='img-fluid'></a>";
				else
				  $iconoEspecial = "<img src='images/spacer32.gif'  class='img-fluid'>";

				$Botones = '
								    '.$iconoEspecial.'
								    '.$boton_historico.'
								    '.$boton_pronosticos.'
								    '.$boton_mxm.'
						   ';
							
				echo '
<div class="row" style="background-color:'.$trcolor.';">
    <div class="col-md-auto">'.dameFechaCorta($renglon_juego['Fecha']). ' | ' . $renglon_juego['Hora'].' hrs.</div>
    <div class="col-md-8">
		<div class="container">
		  <div class="table-responsive">          
		  <table id="tabla-mundial" border="0">
			<tbody>
			  <tr>
				<td width="45%" align="right">
				  <a class="various" data-fancybox-type="iframe" href="estadisticas-equipo.php?token=' . $renglon_juego['EquipoLocal'] . '"><img src="images/escudos/'.$renglon_juego['EquipoLocal'].'.png" class="escudos">
				</td>
				<td width="4%" align="center">'.$tdGL.'</td>
				<td width="2%" align="center">vs</td>
				<td width="4%" align="center">'.$tdGV.'</td>
				<td width="45%" align="left">
				  <a class="various" data-fancybox-type="iframe" href="estadisticas-equipo.php?token=' . $renglon_juego['EquipoVisitante'] . '"><img src="images/escudos/'.$renglon_juego['EquipoVisitante'].'.png" class="escudos">
				</td>
			  </tr>
			</tbody>
		  </table>
		  </div>
		</div>						 
    </div>
    <div class="col-md-auto">'.$Botones.'</div>
</div>				
';
		  }
?>







<?php
exit();
?>


  <div class="table-responsive">          
  <table class="table">
    <tbody>
      <tr>
          <td>Ver Jornada:</td>
          <?php 
		  $sql_jornadas = "	SELECT * 
							FROM jornadas as j, temporada as t 
							WHERE j.IDLiga = '".$renglon['IDLiga']."'
								AND j.IDTemporada = t.IDTemporada
								AND t.Status = '1'
							ORDER BY IDJornada asc";
		  $consulta_jornadas = mysql_query($sql_jornadas);
		  for($j=0;$j<mysql_num_rows($consulta_jornadas);$j++)
		    {
				$renglon_jornadas = mysql_fetch_assoc($consulta_jornadas);
				if ($IDJornada == $renglon_jornadas['IDJornada'])
				  $color_adecuado = "#99FF00";
				else
				  $color_adecuado = "#00CC33";
				  
				echo "<td align='center' bgcolor='".$color_adecuado."' 
									 onclick=\"cambia_jornada('".$renglon_jornadas['IDJornada']."')\" 
									 style='cursor:pointer;'>".$renglon_jornadas['Jornada']."</td>";
			}
		  ?>
      </tr>
    </tbody>
  </table>
  </div>

  <div class="table-responsive">          
  <table class="table">
    <tbody>
      <tr>
    	  <td valign="top" align="center" bgcolor="#009900" colspan="10">  
	        <?php
		    echo "<font color='#FFFFFF'><b>Jornada " . $renglon['Jornada'] . " - " . $renglon['Liga'] . "</b></font>";
		    ?>
    	  </td>
	  </tr> 
    </tbody>
    <thead> 
      <tr align="center" bgcolor="#00CC33">
          <th>Fecha / Hora</th>
          <th align="right">Local</th>
          <th colspan="5">&nbsp;</th>
          <th align="left">Visitante</th>
          <th colspan="2">&nbsp;</th>
      </tr>
    </thead>

      <tbody>
        <tr bgcolor="#00CC33">
          <td colspan="11">
            Total de Goles de Jornada: <b><?php echo $TotalGolesBono;?></b>
          </td>
        </tr>
      </tbody>
	</table>
    </div>
