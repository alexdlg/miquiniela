<?php 
session_start();
$IDUsuario = $_SESSION['quiniela_matona_id'];
require ("include/init.php");
require ("fechas.php");
$token = explode("@", $_GET['token']);
$IDJuego = $token[0];

$sql_juego  = "SELECT * , L.Equipo as Local, V.Equipo as Visitante
				FROM juegos as j, equipos as L, equipos as V
		 		WHERE 
				  j.EquipoLocal = L.IDEquipo AND
				  j.EquipoVisitante = V.IDEquipo AND
				  j.IDJuego = '".$IDJuego."' 
				ORDER BY Fecha asc, Hora asc, IDJuego asc";
$consulta_juego = mysql_query($sql_juego);				
$renglon_juego = mysql_fetch_assoc($consulta_juego);
$IDJornada = $renglon_juego['IDJornada'];

$sql = "SELECT * FROM jornadas as j, ligas as l WHERE l.IDLiga = j.IDLiga AND j.IDJornada = '$IDJornada'";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);

$hoy = date("Y-m-d");
$ya = date("H:i:s");


$sql_pronostico = "SELECT COUNT(IDUsuario) as Cantidad, Resultado FROM pronosticos WHERE IDJuego = '$IDJuego' GROUP BY Resultado";
$consulta_pronostico = mysql_query($sql_pronostico);
for ($p=0;$p<mysql_num_rows($consulta_pronostico);$p++)
  {
    $renglon_pronostico = mysql_fetch_assoc($consulta_pronostico);
	$ArregloPronostico[$renglon_pronostico['Resultado']] = $renglon_pronostico['Cantidad'];
  }
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
          <font color="#FFFFFF" size="+1"><b>Los Pronosticos de la Raza</b></font>
        </td>
      </tr>
      <?php
	  
	  if ($renglon_juego['Capturado'] == "1")
	    {
	      $GolesRealesLocal = $renglon_juego['GolesLocal'];
	      $GolesRealesVisitante = $renglon_juego['GolesVisitante'];
		}
	  else
	    {
	      $GolesRealesLocal = "&nbsp;";
	      $GolesRealesVisitante = "&nbsp;";
		}
		
if ($renglon_juego['Fecha'] < $hoy)
  $filtros = "ok";
else
  {
	if ($renglon_juego['Fecha'] == $hoy)
	  if ($renglon_juego['Hora'] < $ya)
	    $filtros = "ok";
  }
	    
	  
	  echo "
      <tr>
        <td colspan='7' align='center'>
          <table border='0' width='100%'>
		    <tr>
  		      <td align='right'>".$renglon_juego['Local']."</td>
  		      <td align='left'><img src='images/escudos/".$renglon_juego['EquipoLocal'].".png' width='60'></td>
  		      <td align='center'>".$GolesRealesLocal."</td>
		      <td align='center'>vs</td>
  		      <td align='center'>".$GolesRealesVisitante."</td>
  		      <td align='right'><img src='images/escudos/".$renglon_juego['EquipoVisitante'].".png'  width='60'></td>
  		      <td>".$renglon_juego['Visitante']."</td>
			  <td width='100'>
			    &nbsp;
			  </td>
			  <td>";
			  
if ($filtros == "ok")			  
	echo "			  
			    <table border='0'>
				  <tr><td align='right'>Gana ".$renglon_juego['Local'].": </td><td align='right'>".$ArregloPronostico['L']."</td></tr>
				  <tr><td align='right'>Gana ".$renglon_juego['Visitante'].": </td><td align='right'>".$ArregloPronostico['V']."</td></tr>
				  <tr><td align='right'>Empate: </td><td align='right'>".$ArregloPronostico['E']."</td></tr>
				</table>";
  
echo "
			  </td>
			</tr>
		  </table>
		</td>
	  </tr>";




if ($filtros == "ok")
{
echo "
	  <tr>
	    <td colspan='5'>
		  <table border='0' width='100%' cellpadding='3' cellspacing='0'>
		    <tr>
			  <td nowrap valign='bottom' colspan='5'>
			    Filtrar Pronosticos por:
			  </td>
			</tr>
			<tr>
			  <td nowrap width='150'>
			    Nombre:<br />
			    <input type='text' id='txtFiltroNombre' onkeyup='actualiza_pronosticos();' style='width:100%' >
			  </td>
			  <td nowrap width='100'>
			    Apodo:<br />
			    <input type='text' id='txtFiltroApodo' onkeyup='actualiza_pronosticos();' style='width:100%' >
			  </td>
			  <td nowrap width='120'>
			    Resultado:<br />
				<select id='dcFiltroResultado' size='1' onchange='actualiza_pronosticos();' style='width:100%' >
				  <option value='*'>Cualquiera</option>
				  <option value='*'>---------</option>
				  <option value='L'>Gana ".$renglon_juego['Local']."</option>
				  <option value='E'>Empate</option>
				  <option value='V'>Gana ".$renglon_juego['Visitante']."</option>
				</select>				
			  </td>
			  <td nowrap width='200'>
			    Goles ".$renglon_juego['Local'].":<br />
			    <input type='text' id='txtFiltroGL' style='width:100%' onkeyup='actualiza_pronosticos();'>
			  </td>
			  <td nowrap width='200'>
			    Goles ".$renglon_juego['Visitante'].":<br />
			    <input type='text' id='txtFiltroGV' style='width:100%' onkeyup='actualiza_pronosticos();'>
			  </td>
			  <td>&nbsp;</td>

			</tr>
		  </table>
		</td>
	  </tr>
	  <tr>
	    <td colspan='5'>
		  <div id='listado_pronosticos_partido'></div>
		</td>
	  </tr>
	  ";
} // cierra el if que me valida si el juego no esta ya en accion
else
  {
	  echo "<tr><td align='center'><b><font color='#FF0000'><br /><br /><br />No se pueden ver los pronosticos de la raza, hasta que inicie el partido</font></b></td></tr>";
  }
		?>
      </table>


<script language="javascript">
    var http = false;

	//verificacion del navegador para el uso de AJAX
    if(navigator.appName == "Microsoft Internet Explorer") {
      http = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
      http = new XMLHttpRequest();
    }


    function actualiza_pronosticos() 
	{
      document.getElementById('listado_pronosticos_partido').innerHTML = "<img src='images/loading.gif'>";
      http.abort();
	  cadena="los-pronosticos-partido-listado.php?token=<?php echo $IDJuego;?>@" +
	  					  document.getElementById('txtFiltroNombre').value + "@" +
	  					  document.getElementById('txtFiltroApodo').value + "@" +
	  					  document.getElementById('dcFiltroResultado').value + "@" +
	  					  document.getElementById('txtFiltroGL').value + "@" +
	  					  document.getElementById('txtFiltroGV').value + "@" +
	  					  "";
						  
	  http.open("GET", cadena, true);
      http.onreadystatechange=function() 
	    {
        if(http.readyState == 4) 
		  {
          document.getElementById('listado_pronosticos_partido').innerHTML = http.responseText;
          document.getElementById('resultados').innerHTML = "<b><i><font size='-1'>" + document.getElementById('txtResultados').value + " registros encontrados</font></i></b>";
		  
		  }
        }
      http.send(null);
 	}

actualiza_pronosticos();

</script>
