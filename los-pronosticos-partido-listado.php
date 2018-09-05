<?php 
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");
require("fechas.php");

$token_interno = explode("@", $_GET['token']);
$IDJuego = $token_interno[0];
$MiIDUsuario = $_SESSION['quiniela_matona_id'];

$Nombre = $token_interno[1];
$Apodo = $token_interno[2];
$Resultado = $token_interno[3];
$GL = $token_interno[4];
$GV = $token_interno[5];

$criterio_usuario = "";
$criterio_pronostico = "";

if ($Nombre != "")
  $criterio_usuario.= " AND Nombre LIKE '%$Nombre%'";

if ($Apodo != "")
  $criterio_usuario.= " AND Apodo LIKE '%$Apodo%'";

if ($Resultado != "*")
  $criterio_pronostico.= " AND Resultado = '$Resultado'";

if ($GL != "")
  $criterio_pronostico.= " AND GFLocal =  '$GL'";

if ($GV != "")
  $criterio_pronostico.= " AND GFVisitante =  '$GV'";


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


$sql = "SELECT * FROM ligas as l, temporada as t, jornadas as j 
		     WHERE 
			   j.IDLiga = l.IDLiga AND 
			   t.IDLiga = l.IDLiga AND
			   j.IDTemporada = t.IDTemporada AND
			   j.IDJornada = '$IDJornada'";
$consulta = mysql_query($sql);
$renglon_jornada = mysql_fetch_assoc($consulta);
$ColumnaPuntos = $renglon_jornada['Puntaje'];


	  $sql_u = "SELECT u.*, SUM(p.".$ColumnaPuntos.") as Acumulado 
	  		  FROM usuarios as u, pronosticos as p, juegos as j
			  WHERE 
			    u.IDUsuario = p.IDUsuario AND 
				j.IDJuego = p.IDJuego AND
				j.IDJornada = '".$IDJornada."' 
				$criterio_usuario
			  GROUP BY p.IDUsuario ORDER BY Acumulado DESC, Apodo ASC";
	  $consulta_u  = mysql_query($sql_u );
		$contador = 0;
echo '      <table cellpadding="5" cellspacing="0" border="0" width="100%" bordercolor="#000000" class="rol">
              <tr>
			    <td align="left">
				  <div id="resultados"></div>
				</td>
				<td align="center">Acumulado</td>				
  		        <td align="left"><img src="images/escudos/'.$renglon_juego['EquipoLocal'].'.png" width="40"></td>
		        <td align="center"></td>
  		        <td align="right"><img src="images/escudos/'.$renglon_juego['EquipoVisitante'].'.png"  width="40"></td>				
			  </tr>
     ';
		for ($u=0;$u<mysql_num_rows($consulta_u);$u++)
		  {
			  $renglon_u = mysql_fetch_assoc($consulta_u);
			  $IDUsuarioP = $renglon_u['IDUsuario'];
			  $sql_p = "SELECT * FROM pronosticos WHERE IDUsuario = '$IDUsuarioP' AND IDJuego = '$IDJuego' $criterio_pronostico";
			  $consulta_p = mysql_query($sql_p);
			  if (mysql_num_rows($consulta_p) == 1)
			    {
					$renglon_p = mysql_fetch_assoc($consulta_p);
					$GFLP = $renglon_p['GFLocal'];
					$GFVP = $renglon_p['GFVisitante'];
					$mostrar = "si";
					$contador++;
				}
			  else
			    {
					$GFLP = "";
					$GFVP = "";
					$mostrar = "no";
				}

			if ($mostrar == "si")
			  {
				if ($trcolor == "#FFFFFF")
				  $trcolor = "#EAEAEA";
				else
				  $trcolor = "#FFFFFF";
			
				$Archivo = "perfil/" . $renglon_u['IDUsuario'] . ".jpg";
				if (file_exists($Archivo))
				  $foto_perfil = "<a class='various' data-fancybox-type='iframe' href='perfil_usuario.php?token=".$renglon_u['IDUsuario']."'><img src='".$Archivo."'  width='30' style='cursor:pointer;' valign='middle'></a>";
				else
				  $foto_perfil = "<a class='various' data-fancybox-type='iframe' href='perfil_usuario.php?token=".$renglon_u['IDUsuario']."'><img src='images/no-pic.png' width='30' style='cursor:pointer;' valign='middle'></a>";

			  if ($renglon_u['IDUsuario'] == $MiIDUsuario )
			    $trcolor = "#FF9933";

				echo "<tr bgcolor='".$trcolor."' align='center'>
					      <td align='left'>".$foto_perfil . "&nbsp;&nbsp;" . $renglon_u['Nombre']." ( " . $renglon_u['Apodo'] . " )</td>
		  		    	  <td width='40'>".$renglon_u['Acumulado']."</td>
		  		    	  <td width='40'>".$GFLP."</td>
					      <td width='10'>-</td>
		  		    	  <td width='40'>".$GFVP."</td>
						</tr>
					";
			  }  //cierra el if del mostrar
		  }  //cierra el ciclo del usuario
?>
<input type="hidden" id="txtResultados" value="<?php echo $contador;?>">