<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");
require("fechas.php");
require("genera_clave.php");

$IDLiga = $_SESSION['quiniela_matona_idliga'];
$IDJornada = $_GET['j'];
$Seccion = $_GET['s'];

?>
  <div align="right">          
  <table id="tabla-jornadas">
    <tbody>
      <tr>
          <td>Ver Jornada:</td>
          <?php 
		  $sql_jornadas = "	SELECT * 
							FROM jornadas as j, temporada as t 
							WHERE j.IDLiga = '".$IDLiga."'
								AND j.IDTemporada = t.IDTemporada
								AND t.Status = '1'
							ORDER BY IDJornada asc";
		  $consulta_jornadas = mysql_query($sql_jornadas);
		  for($j=0;$j<mysql_num_rows($consulta_jornadas);$j++)
		    {
				$renglon_jornadas = mysql_fetch_assoc($consulta_jornadas);
				if ($IDJornada == $renglon_jornadas['IDJornada'])
				  $color_adecuado = "#AAAAAA";
				else
				  $color_adecuado = "#000000";
				  
				echo "<td align='center' bgcolor='".$color_adecuado."' id='J".$renglon_jornadas['IDJornada']."' 
									 onclick=\"cambia_jornada('".$renglon_jornadas['IDJornada']."', this, '".$Seccion."')\" 
									 style='cursor:pointer;'>".$renglon_jornadas['Jornada']."</td>";
			}
		  ?>
      </tr>
    </tbody>
  </table>
  </div>
