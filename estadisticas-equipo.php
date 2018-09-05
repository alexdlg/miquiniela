<?php 
session_start();
$IDUsuario = $_SESSION['quiniela_matona_id'];
require ("include/init.php");
require ("fechas.php");
$token = explode("@", $_GET['token']);
$IDEquipo = $token[0];
$IDLiga  = $_SESSION['quiniela_matona_idliga'];

$sql_equipo = "SELECT * FROM equipos WHERE IDEquipo = '$IDEquipo'";
$consulta_equipo = mysql_query($sql_equipo);
$renglon_equipo = mysql_fetch_assoc($consulta_equipo);

$sql_historico = "
				  (SELECT j.*, l.IDEquipo as EquipoLocal, v.IDEquipo as EquipoVisitante, l.Equipo as ELocal, v.Equipo as EVisitante, jor.Jornada
				   FROM juegos as j, equipos as l, equipos as v, jornadas as jor, ligas as li, temporada as t
				   WHERE 
					  jor.IDLiga = li.IDLiga AND
					  jor.IDTemporada = t.IDTemporada AND
					  jor.IDLiga = t.IDLiga AND
					  t.Status = '1' AND
					  li.IDLiga = '$IDLiga'	AND			     
				     j.IDJornada = jor.IDJornada AND
				     j.EquipoLocal = '".$IDEquipo."' AND 
					 j.EquipoLocal = l.IDEquipo AND
					 j.EquipoVisitante = v.IDEquipo
				  ) 
				  UNION
				  (SELECT j.*, l.IDEquipo as EquipoLocal, v.IDEquipo as EquipoVisitante, l.Equipo as ELocal, v.Equipo as EVisitante, jor.Jornada
				   FROM juegos as j, equipos as l, equipos as v, jornadas as jor, ligas as li, temporada as t
				   WHERE 
					  jor.IDLiga = li.IDLiga AND
					  jor.IDTemporada = t.IDTemporada AND
					  jor.IDLiga = t.IDLiga AND
					  t.Status = '1' AND
					  li.IDLiga = '$IDLiga' AND		     
				     j.IDJornada = jor.IDJornada AND
					 j.EquipoVisitante = '".$IDEquipo."' AND
					 j.EquipoLocal = l.IDEquipo AND
					 j.EquipoVisitante = v.IDEquipo
				  ) 
				  Order By Fecha asc
				 ";
$consulta_historica = mysql_query($sql_historico);				
$sql_stats = "SELECT * FROM equipos_stats WHERE IDEquipo = '$IDEquipo'";
$consulta_stats = mysql_query($sql_stats);
$renglon_stats = mysql_fetch_assoc($consulta_stats);
?>

<style>
.info td
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:14px;
	color:#000;
	font-weight:normal;
	
}

.info th
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:14px;
	color:#FFF;
	font-weight:bold;
	
	
}

.rol
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
	
}

a
{
	color:#000;
	font-size:14px;
	font-weight:bold;
	text-decoration:none;
}

a:hover
{
	color:#06F;	
}
</style>
      
      <table cellpadding="5" cellspacing="0" border="0" width="100%" bordercolor="#000000" class="rol">
      <tr>
        <td colspan="9" align="left">
          <table border="0" cellpadding="5" cellspacing="0" width="100%"> 
            <tr>
              <td width="100"><img src="images/escudos/<?php echo $IDEquipo;?>.png" width="100" /></td>
              <td>
                <font size="+3"><?php echo $renglon_equipo['Equipo'];?></font>
                <br />
                Posicion Actual: <?php echo $renglon_stats['Posicion'];?>
              </td>
              <td align="right">
                <table border="1" cellpadding="3" cellspacing="0" class="info" bordercolor="#FFFFFF">
                  <tr align="center" bgcolor="#000000">
                    <th width="30">JJ</th>
                    <th width="30">JG</th>
                    <th width="30">JE</th>
                    <th width="30">JP</th>
                    <th width="30">GF</th>
                    <th width="30">GC</th>
                    <th width="30">PTS</th>
                  </tr>
                  <tr align="center">
                    <td><?php echo $renglon_stats['JJ'];?></td>
                    <td><?php echo $renglon_stats['JG'];?></td>
                    <td><?php echo $renglon_stats['JE'];?></td>
                    <td><?php echo $renglon_stats['JP'];?></td>
                    <td><?php echo $renglon_stats['GF'];?></td>
                    <td><?php echo $renglon_stats['GC'];?></td>
                    <td><?php echo $renglon_stats['PTS'];?></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan="9" align="center" bgcolor="#000000">
          <font color="#FFFFFF" size="+1"><b>Resultados - Liga MX Apertura 2016</b></font>
        </td>
      </tr>
        <?php
		if (mysql_num_rows($consulta_historica) == 0)
		  echo "<tr><td colspan='9' align='center'><font color='#FF0000'><b><br />No hay informacion disponible en este momento. Intenta mas tarde.</font></td></tr>";
		
		
		
		for ($i=0;$i<mysql_num_rows($consulta_historica);$i++)
		  {
		    $renglon_historico = mysql_fetch_assoc($consulta_historica);
			
			
			if ($trcolor == "#FFFFFF")
			  $trcolor = "#EAEAEA";
			else
			  $trcolor = "#FFFFFF";

			if ($renglon_historico['Capturado'] == "1")
			  {
				$GL = $renglon_historico['GolesLocal'];
				$GV = $renglon_historico['GolesVisitante'];
			  }
			else
			  {
				$GL = "";
				$GV = "";
			  }
			
		    echo "
		  		<tr bgcolor='".$trcolor."'>
			      <td align='center' width='40'>J ".$renglon_historico['Jornada']."</td>
				  <td align='center'>
				    ".dameFechaCompleta($renglon_historico['Fecha'])."					
				  </td>
	  		      <td align='right'><a href='estadisticas-equipo.php?token=".$renglon_historico['EquipoLocal']."'>".$renglon_historico['ELocal']."</a></td>
	  		      <td align='right' width='40'><img src='images/escudos/".$renglon_historico['EquipoLocal'].".png' width='40'></td>
	  		      <td align='center' width='40'>".$GL."</td>
			      <td align='center' width='10'>-</td>
	  		      <td align='center' width='40'>".$GV."</td>
	  		      <td align='left' width='40'><img src='images/escudos/".$renglon_historico['EquipoVisitante'].".png' width='40'></td>
	  		      <td align='left'><a href='estadisticas-equipo.php?token=".$renglon_historico['EquipoVisitante']."'>".$renglon_historico['EVisitante']."</a></td>
				</tr>
				";
		  }
		?>
      </table>


