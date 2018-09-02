<?php 
session_start();
$IDUsuario = $_SESSION['quiniela_matona_id'];
require ("include/init.php");
require ("fechas.php");
$token = explode("@", $_GET['token']);
$IDJuego = $token[0];
$IDJornada = $token[1];

$sql = "SELECT *
		FROM juegos as j
		WHERE
		  j.IDJuego = '$IDJuego'";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);

$sql_historico = "
				  (SELECT h.*, l.IDEquipo as EquipoLocal, v.IDEquipo as EquipoVisitante, l.Equipo as ELocal, v.Equipo as EVisitante
				   FROM historico as h, equipos as l, equipos as v 
				   WHERE 
				     h.IDEquipoL = '".$renglon['EquipoLocal']."' AND 
					 h.IDEquipoV = '".$renglon['EquipoVisitante']."' AND
					 h.IDEquipoL = l.IDEquipo AND
					 h.IDEquipoV = v.IDEquipo
				  ) 
				  UNION
				  (SELECT h.*, l.IDEquipo as EquipoLocal, v.IDEquipo as EquipoVisitante, l.Equipo as ELocal, v.Equipo as EVisitante
				   FROM historico as h, equipos as l, equipos as v 
				   WHERE 
				     h.IDEquipoL = '".$renglon['EquipoVisitante']."' AND 
					 h.IDEquipoV = '".$renglon['EquipoLocal']."' AND
					 h.IDEquipoL = l.IDEquipo AND
					 h.IDEquipoV = v.IDEquipo
				  ) 
				  UNION
				  (SELECT j.IDJuego as FolioInutil, j.EquipoLocal as IDEquipoL, j.EquipoVisitante as IDEquipoV, j.Fecha, j.GolesLocal as GolesL, j.GolesVisitante as GolesV, l.IDEquipo as EquipoLocal, v.IDEquipo as EquipoVisitante, l.Equipo as ELocal, v.Equipo as EVisitante
				   FROM juegos as j, equipos as l, equipos as v 
				   WHERE 
				     j.EquipoLocal = '".$renglon['EquipoLocal']."' AND 
					 j.EquipoVisitante = '".$renglon['EquipoVisitante']."' AND
					 j.EquipoLocal = l.IDEquipo AND
					 j.EquipoVisitante = v.IDEquipo AND
					 j.Capturado = '1'
					 
				  ) 
				  UNION
				  (SELECT j.IDJuego as FolioInutil, j.EquipoLocal as IDEquipoL, j.EquipoVisitante as IDEquipoV, j.Fecha, j.GolesLocal as GolesL, j.GolesVisitante as GolesV, l.IDEquipo as EquipoLocal, v.IDEquipo as EquipoVisitante, l.Equipo as ELocal, v.Equipo as EVisitante
				   FROM juegos as j, equipos as l, equipos as v 
				   WHERE 
				     j.EquipoLocal = '".$renglon['EquipoVisitante']."' AND 
					 j.EquipoVisitante = '".$renglon['EquipoLocal']."' AND
					 j.EquipoLocal = l.IDEquipo AND
					 j.EquipoVisitante = v.IDEquipo AND
					 j.Capturado = '1'
				  ) 
				  Order By Fecha desc
				 ";
$consulta_historica = mysql_query($sql_historico);	
?>

<style>
.rol
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;
	
}
</style>
      
      <table cellpadding="5" cellspacing="0" border="0" width="100%" bordercolor="#000000" class="rol">
      <tr>
        <td colspan="7" align="center" bgcolor="#000000">
          <font color="#FFFFFF" size="+1"><b>Estadisticas Historicas del Encuentro</b></font>
        </td>
      </tr>
        <?php
		if (mysql_num_rows($consulta_historica) == 0)
		  echo "<tr><td colspan='7' align='center'><font color='#FF0000'><b><br />No hay informacion disponible en este momento. Intenta mas tarde.</font></td></tr>";
		
		
		
		for ($i=0;$i<mysql_num_rows($consulta_historica);$i++)
		  {
		    $renglon_historico = mysql_fetch_assoc($consulta_historica);
		  echo "
		  		<tr>
				  <td colspan='7' align='center'>
				    ".dameFechaCompleta($renglon_historico['Fecha'])."					
				  </td>
				</tr>
		  		<tr>
	  		      <td align='right' width='50%'>".$renglon_historico['ELocal']."</td>
	  		      <td align='right' width='40'><img src='images/escudos/".$renglon_historico['EquipoLocal'].".png' width='40'></td>
	  		      <td align='right' width='40'>".$renglon_historico['GolesL']."</td>
			      <td align='center' width='10'>-</td>
	  		      <td align='right' width='40'>".$renglon_historico['GolesV']."</td>
	  		      <td align='left' width='40'><img src='images/escudos/".$renglon_historico['EquipoVisitante'].".png' width='40'></td>
	  		      <td align='left' width='50%'>".$renglon_historico['EVisitante']."</td>
				</tr>
				<tr>
				  <td colspan='7' align='center'>
				    <hr size='1' width='90%' color='#000000'>
				  </td>
				</tr>
				";
		  }
		?>
      </table>


