<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");
require("fechas.php");

$IDJornada = $_GET['token'];
$IDUsuario = $_SESSION['quiniela_matona_id'];
$sql = "SELECT * FROM ligas as l, temporada as t, jornadas as j 
		     WHERE 
			   j.IDLiga = l.IDLiga AND 
			   t.IDLiga = l.IDLiga AND
			   j.IDTemporada = t.IDTemporada AND
			   j.IDJornada = '$IDJornada'";
$consulta = mysql_query($sql);
$renglon_jornada = mysql_fetch_assoc($consulta);

$hoy = date("Y-m-d");
$ya = date("H:i:s");
$ColumnaPuntos = $renglon_jornada['Puntaje'];

$dias = count(dates_range($hoy, $renglon_jornada['FechaCierre']));

if ($dias > 0)
  $semaforo = "rojo";
else
  if ($renglon_jornada['FechaCierre']  == $hoy)
    {
	  $tiempo = $renglon_jornada['HoraCierre'] - $ya;
	  if ($tiempo > 0)
	    $semaforo = "amarillo";
	  else
	    $semaforo = "rojo";
	}
  else
  if ($renglon_jornada['FechaCierre']  < $hoy)
    $semaforo = "verde";
  else
    $semaforo = "rojo";


if ($renglon_jornada['Status'] == "2")
  $semaforo = "verde";


?>

<style>
  .rol
  {
	  font-family:Verdana, Geneva, sans-serif;
	  font-size:16px;
  }
  
  .rol th
    {
		font-weight:bold;
		color:#FFF
	}
	 
  .rol a
  {
	  color:#000;
	  text-decoration:none;
  }
  
  .titulo
  {
	  font-family:Verdana, Geneva, sans-serif;
	  font-size:24px;
	  font-weight:bold;
	  color:#FFF;
  }
</style>

      <table cellpadding="5" cellspacing="0" border="0" width="100%" bordercolor="#FFFFFF" class="rol">
	    <tr>
    	  <td valign="top" align="center" bgcolor="#000000" colspan="8">  
	        <?php
		    echo "<font color='#FFFFFF'><b>Jornada " . $renglon_jornada['Jornada'] . " - " . $renglon_jornada['Liga'] . "</b></font>";
		    ?>
    	  </td>
	    </tr>  
        <tr align="center" bgcolor="#444444">
          <th>Lugar</th>
          <th>Nombre</th>
          <th>Apodo</th>
          <th>Puntos</th>
          <th>Quiniela</th>
          <th>Pago</th>
        </tr>      
      <?php
	  
	  $sql = "SELECT u.*, SUM(p.".$ColumnaPuntos.") as Acumulado 
	  		  FROM usuarios as u, pronosticos as p, juegos as j
			  WHERE 
			    u.IDUsuario = p.IDUsuario AND 
				j.IDJuego = p.IDJuego AND
				j.IDJornada = '".$renglon_jornada['IDJornada']."' 
			  GROUP BY p.IDUsuario ORDER BY Acumulado DESC, Apodo ASC";
	  $consulta = mysql_query($sql);
	  
	  $arreglo_usuarios = "";
	  $TotalQuinielas = 0;
	  $TotalPagos = 0;
	  $contador_bono_especial = 0;

	  for ($u=0;$u<mysql_num_rows($consulta);$u++)
	    {
			$renglon = mysql_fetch_assoc($consulta);			
			$arreglo_usuarios.= $renglon['IDUsuario'] . ", "; 
			$sql_p = "SELECT * FROM pronosticos WHERE IDUsuario = '".$renglon['IDUsuario']."' AND IDJuego IN (SELECT IDJuego FROM juegos WHERE IDJornada = '".$renglon_jornada['IDJornada']."')";
			$consulta_p = mysql_query($sql_p);
			if (mysql_num_rows($consulta_p) > 0)
			  {
			  $quiniela = "<a class='various' data-fancybox-type='iframe' href='actualiza_pronosticos.php?token=".$renglon['IDUsuario']."@".$renglon_jornada['IDJornada']."'><img src='images/zoom.png'></a>";
			  $TotalQuinielas++;
			  }
			else
			  $quiniela = "";

			$sql_pago = "SELECT * FROM pagos WHERE IDUsuario = '" . $renglon['IDUsuario'] . "' AND IDJornada = '" . $renglon_jornada['IDJornada'] . "'";
			$consulta_pago = mysql_query($sql_pago);
			if (mysql_num_rows($consulta_pago) == 0)
			  {
			  $pago = "<img src='images/no.png'>";
			  $bono = "<img src='images/no.png'>";
			  $TotalGolesBono = " - ";					  
			  if ($semaforo == "rojo")
				$TotalGolesBono = "";
			  }
			else
			  {
				  $renglon_pago = mysql_fetch_assoc($consulta_pago);
				  if ($renglon_pago['StatusPago'] == "1")
				    {
				    $pago = "<img src='images/si.png'>";
					$TotalPagos++;
					}
				  else
				    $pago = "<img src='images/no.png'>";





				  if ($renglon_pago['BonoExtra'] == "1")
				    {
				      $bono = "<font color='#00AA00' size='+2'><b>".$renglon_pago['GolesBonoExtra']."</b></font>";
					  $TotalBono++;
					}
				  else
				    {
				      $bono = "<img src='images/no.png'>";
					}
			  }


			if ($trcolor == "#FFFFFF")
			  $trcolor = "#EAEAEA";
			else
			  $trcolor = "#FFFFFF";
			
			$Archivo = "perfil/" . $renglon['IDUsuario'] . ".jpg";
			if (file_exists($Archivo))
			  $foto_perfil = "<a class='various' data-fancybox-type='iframe' href='perfil_usuario.php?token=".$renglon['IDUsuario']."'><img src='".$Archivo."'  width='30' style='cursor:pointer;' valign='middle'></a>";
			else
			  $foto_perfil = "<a class='various' data-fancybox-type='iframe' href='perfil_usuario.php?token=".$renglon['IDUsuario']."'><img src='images/no-pic.png' width='30' style='cursor:pointer;' valign='middle'></a>";
			
			
			if ($renglon['IDUsuario'] == $IDUsuario)
			  $trcolor = "#FF9933";
			  
			  
			$sql_especial = "SELECT * FROM jugadores as j, pronosticos_bonogol AS p 
							 WHERE 
							   p.IDJugador = j.IDJugador AND
							   p.IDJornada = '".$IDJornada."' AND
							   p.IDUsuario = '".$renglon['IDUsuario']."'";
			$consulta_especial = mysql_query($sql_especial);
			if (mysql_num_rows($consulta_especial) == 1)
			  {
				$renglon_especial = mysql_fetch_assoc($consulta_especial);
				$bono_especial = $renglon_especial['Nombre'];
				$contador_bono_especial++;
			  }
			else
			  $bono_especial = "&nbsp;";
			echo "<tr bgcolor='".$trcolor."'>
				    <td align='center'>". ($u + 1 )."</td>
				    <td >".$foto_perfil . "&nbsp;&nbsp;" . $renglon['Nombre']."</td>
				    <td>".$renglon['Apodo']."</td>
				    <td align='center'>".$renglon['Acumulado']."</td>
				    <td align='center'>".$quiniela."</td>
				    <td align='center'>".$pago."</td>
				  </tr>";
					
		}


	  ?>   
  <tr bgcolor="#000000" align="center">
    <th colspan="4" style="text-align:right;">  
      TOTALES
    </th>
    <th>
      <?php echo $TotalQuinielas;?>
    </th>
    <th>
      <?php echo $TotalPagos;?>
    </th>
  </tr>
</table> 
<br /><br />
