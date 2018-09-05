<?php 
session_start();
require("include/init.php");
$token = explode("@", $_GET['token']);



$IDJuego = $token[0];
$GL = $token[1];
$GV = $token[2];


if ($GL > $GV)
  $Resultado = "L";
else
if ($GL < $GV)
  $Resultado = "V";
else
  $Resultado = "E";


$IDLiga  = $_SESSION['quiniela_matona_idliga'];

$sql = "SELECT * 
		FROM
		  jornadas AS j,
		  temporada AS t,
		  ligas AS l
		WHERE 
		  j.IDLiga = l.IDLiga AND
		  j.IDTemporada = t.IDTemporada AND
		  j.IDLiga = t.IDLiga AND
		  t.Status = '1' AND
		  j.Status = '1' AND
		  l.IDLiga = '$IDLiga'
		";
$consulta_jornada = mysql_query($sql);
$renglon_jornada = mysql_fetch_assoc($consulta_jornada);
$IDJornada = $renglon_jornada['IDJornada'];
$IDTemporada = $renglon_jornada['IDTemporada'];
$ModoTorneo = $renglon_jornada['Modo'];
$sql = "UPDATE juegos SET GolesLocal = '$GL', GolesVisitante = '$GV', Capturado = '1' WHERE IDJuego = '$IDJuego'";
$modificacion = mysql_query($sql);

$sql_juegox2 = "SELECT * FROM juegos WHERE IDJuego = '$IDJuego'";
$consulta_juegox2 = mysql_query($sql_juegox2);
$renglon_juegox2 = mysql_fetch_assoc($consulta_juegox2);
if ($renglon_juegox2['Rompequinielas'] == "1")
  $Multiplicador = 2;
else
  $Multiplicador = 1;
  
  
if ($IDLiga == "8")  //NFL
{
$sql = "SELECT * FROM juegos WHERE IDJuego = '$IDJuego'";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);
$AltasBajas = $renglon['AltasBajas'];

$sql = "SELECT * FROM pronosticos WHERE IDJuego = '$IDJuego'";
$consulta = mysql_query($sql);
for ($p=0;$p<mysql_num_rows($consulta);$p++)
  {
	  $renglon = mysql_fetch_assoc($consulta);
	  
	  if ($ModoTorneo == "semanal")
	    $sql_pago = "SELECT * FROM pagos WHERE IDUsuario = '".$renglon['IDUsuario']."' AND IDJornada = '$IDJornada'";
	  else
	    $sql_pago = "SELECT * FROM temporada_pagos WHERE IDUsuario = '".$renglon['IDUsuario']."' AND IDTemporada = '$IDTemporada'";
	  
	  
	  $consulta_pago = mysql_query($sql_pago);
	  if (mysql_num_rows($consulta_pago) > 0)
	    {
		    
			  $puntos = 0;
			  
			  if ($renglon['Resultado'] == "L")
			    if ($Resultado == "L")
				  $puntos = 1;
				  
			  if ($renglon['Resultado'] == "V")
			    if ($Resultado == "V")
				  $puntos = 1;
			  
			  $DiferencialPuntos = abs($GL - $GV);
			  if ($DiferencialPuntos <= 3)
			    $JuegoCerrado = "si";
			  else
			    $JuegoCerrado = "no";
				

			  if ($renglon['Resultado'] == "E")
			    {
			      if ($JuegoCerrado == "si")
				    $puntos = 2;
				  
				  if ($renglon['GFLocal'] > 0)
				    {
				    if ($Resultado == "L")
					  $puntos++;		
					else
					  $puntos--;
					}

				  if ($renglon['GFVisitante'] > 0)
				    {
				    if ($Resultado == "V")
					  $puntos++;				
					else
					  $puntos--;
					}
				}

			if ($puntos < 0)
			  $puntos = 0;

				
			  
			  
			  $renglon_pago = mysql_fetch_assoc($consulta_pago);
			  if ($renglon_pago['StatusPago'] == 1)
				{
					
				  $sql_usuario = "UPDATE pronosticos SET puntos = '$puntos' WHERE IDUsuario = '".$renglon['IDUsuario']."' AND IDJuego = '".$renglon['IDJuego']."'";
				  $modificacion_usuario = mysql_query($sql_usuario);
				}
			  else
				echo "No capto el status pago";
			
		  
		}
  }



}
else  // ES DE FUTBOL
{
	
  
	$sql = "SELECT * FROM pronosticos WHERE IDJuego = '$IDJuego'";
	$consulta = mysql_query($sql);
	for ($p=0;$p<mysql_num_rows($consulta);$p++)
	  {
		  $renglon = mysql_fetch_assoc($consulta);
		  
		  if ($ModoTorneo == "semanal")
			$sql_pago = "SELECT * FROM pagos WHERE IDUsuario = '".$renglon['IDUsuario']."' AND IDJornada = '$IDJornada'";
		  else
			$sql_pago = "SELECT * FROM temporada_pagos WHERE IDUsuario = '".$renglon['IDUsuario']."' AND IDTemporada = '$IDTemporada'";
		  
		  
		  $consulta_pago = mysql_query($sql_pago);
		  if (mysql_num_rows($consulta_pago) > 0)
			{
			  if ($renglon_jornada['Puntaje'] == "Puntos2")
				{
				  $puntos = 0;
				  if ($Resultado == $renglon['Resultado'])
					{
					  $puntos++;
					  if ($renglon['GFLocal'] == $GL)
						$puntos++;
						
					  if ($renglon['GFVisitante'] == $GV)  
						$puntos++;
					}
				  $puntos = $puntos * $Multiplicador;
				  $renglon_pago = mysql_fetch_assoc($consulta_pago);
				  if ($renglon_pago['StatusPago'] == 1)
					{
						
					  $sql_usuario = "UPDATE pronosticos SET puntos2 = '$puntos' WHERE IDUsuario = '".$renglon['IDUsuario']."' AND IDJuego = '".$renglon['IDJuego']."'";
					  $modificacion_usuario = mysql_query($sql_usuario);
					}
				  else
					echo "No capto el status pago";
					
				}
			  else
				{
				  $puntos = 0;
				  if ($renglon['GFLocal'] == $GL)
					{
					if ($renglon['GFVisitante'] == $GV)  
					  {
					  $puntos++;
					  $puntos++;
					  }
					}
			  
				  if ($Resultado == $renglon['Resultado'])
					$puntos++;
				  $puntos = $puntos * $Multiplicador;
				  $renglon_pago = mysql_fetch_assoc($consulta_pago);
				  if ($renglon_pago['StatusPago'] == 1)
					{
						
					  $sql_usuario = "UPDATE pronosticos SET puntos = '$puntos' WHERE IDUsuario = '".$renglon['IDUsuario']."' AND IDJuego = '".$renglon['IDJuego']."'";
					  $modificacion_usuario = mysql_query($sql_usuario);
					}
				  else
					echo "No capto el status pago";
				}
			  
			}
	  }

}
?>