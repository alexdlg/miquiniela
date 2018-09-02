<?php
session_start();
function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}


require("include/init.php");
$SessionID = $_REQUEST['session_id'];
$SessionURL = "session_id=" . $SessionID;
include("keyholder.php");
$IDLiga  = $_SESSION['quiniela_matona_idliga'];


$IDUsuario = $_SESSION['quiniela_matona_id'];
$IDJornada = $_POST['Jornada'];
$Hoy = date("Y-m-d");
$Ya = date("H:i:s");
$YaCorto = date("H:i");

$LaIP = getUserIP();


$IDTemporada  = $_POST['IDTemporada'];
$sql = "DELETE FROM 
		  pronosticos 
		WHERE 
		  IDUsuario = '$IDUsuario' AND IDJuego IN (SELECT IDJuego 
												   FROM juegos 
												   WHERE 
												     IDJornada = '$IDJornada' AND 
													 Capturado = '0'  AND 
													 (Fecha > '$Hoy' OR (Fecha = '$Hoy' AND Hora > '$YaCorto'))
												)";
$eliminacion = mysql_query($sql);
$IDAccion = '1'; // 1 = Registro de Pronostico
$sql_log = "INSERT INTO log_mi_pronostico (IDLog, Fecha, Hora, IDUsuario, IDJornada, IDAccion, IPOrigen, Sexion) VALUES
										  ('','$Hoy','$Ya','$IDUsuario','$IDJornada','$IDAccion','$LaIP','$SessionID');";
$insercion_log = mysql_query($sql_log);
$IDLog = mysql_insert_id();

$sql = "SELECT * FROM jornadas WHERE IDJornada = '$IDJornada'";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);

$sql = "SELECT * , L.Equipo as Local, V.Equipo as Visitante
		FROM juegos as j, equipos as L, equipos as V
 		WHERE 
		  j.EquipoLocal = L.IDEquipo AND
		  j.EquipoVisitante = V.IDEquipo AND
		  j.IDJornada = '".$IDJornada."' AND 
		  (Fecha > '$Hoy' OR (Fecha = '$Hoy' AND Hora > '$YaCorto')) AND
		  Capturado = '0'
		ORDER BY Fecha asc, Hora asc, IDJuego asc";
$consulta = mysql_query($sql);

$errores = 0;
for ($j=0;$j<mysql_num_rows($consulta);$j++)
  {
	  $renglon = mysql_fetch_assoc($consulta);
	  $IDJuego = $renglon['IDJuego'];
	  $Local = $renglon['EquipoLocal'];
	  $Visitante = $renglon['EquipoVisitante'];
	  
	  if ($IDLiga == 8) //esta es la NFL
	  {
		  $AuxJuego = "txtP-" . $IDJuego;
		  $AuxAltasBajas = "dcAltasBajas-" . $IDJuego;
		  		  
		  $Resultado = $_POST[$AuxJuego];
		  $Linea = $_POST[$AuxAltasBajas];
		  if ($Resultado != "")
		    {		  
			  if ($Resultado == "EV")
			    {
				  $Resultado = "E";
				  $GFL = "0";
				  $GFV = "1";
				}
			  else
			  if ($Resultado == "EL")
			    {
				  $Resultado = "E";
				  $GFL = "1";
				  $GFV = "0";
				}
			  else
			    {
				  $GFL = "0";
				  $GFV = "0";
				}
			  
			  
			  $sql = "INSERT INTO pronosticos (IDUsuario, IDJuego, GFLocal, GFVisitante, Resultado, Puntos) VALUES ('$IDUsuario','$IDJuego','$GFL','$GFV','$Resultado','$Puntos');";
			  $insercion = mysql_query($sql);
			  if (mysql_error() != "")		  
				$errores++;
			  else
				{
				  $sql_detalle = "INSERT INTO log_mi_pronostico_detalle (IDLog, IDJuego, GFL, GFV, Resultado) VALUES ('$IDLog','$IDJuego','$GFL','$GFV','$Resultado');";
				  $insercion_detalle = mysql_query($sql_detalle);
				}
			}
	  }
	  else
	    {
		  $AuxGFL = "txtGFL-" . $IDJuego . "-" . $Local;
		  $AuxGFV = "txtGFV-" . $IDJuego . "-" . $Visitante;
		  $AuxPuntos = "txtPuntos-" . $IDJuego;
		  
		  $GFL = $_POST[$AuxGFL];
		  $GFV = $_POST[$AuxGFV];
		  $Puntos = $_POST[$AuxPuntos];
		  
		  if ($GFL != "")
			if ($GFV != "")
			  {
			  if ($GFL > $GFV) 
				$Resultado = "L";
			  else
			  if ($GFL < $GFV) 
				$Resultado = "V";
			  else
				$Resultado = "E";
		  
			  $sql = "INSERT INTO pronosticos (IDUsuario, IDJuego, GFLocal, GFVisitante, Resultado, Puntos) VALUES ('$IDUsuario','$IDJuego','$GFL','$GFV','$Resultado','$Puntos');";
			  $insercion = mysql_query($sql);
			  if (mysql_error() != "")		  
				$errores++;
			  else
				{
				  $sql_detalle = "INSERT INTO log_mi_pronostico_detalle (IDLog, IDJuego, GFL, GFV, Resultado) VALUES ('$IDLog','$IDJuego','$GFL','$GFV','$Resultado');";
				  $insercion_detalle = mysql_query($sql_detalle);
				}
			  }
		}
	  
  }


$RifaEspecial = $_POST['chkRifaEspecial'];

if ($RifaEspecial != "")
  {
	$sql_rifa = "INSERT INTO avisos_log (Folio, Fecha, Hora, IDUsuario, IP, Sexion, IDAviso, Comentario) VALUES
										('','$Hoy','$Ya','$IDUsuario','$LaIP','$SessionID','4','Interesado en Participar');";
	$insercion_rifa = mysql_query($sql_rifa);
  }

?>
<script language="javascript">
  cadena = "inicio.php?<?php echo $SessionURL ;?>&url=mi_pronostico&res=<?php echo $errores;?>";
  window.location = cadena;
</script>