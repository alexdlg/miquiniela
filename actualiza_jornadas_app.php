<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");
require("fechas.php");
require("genera_clave.php");

$IDLiga = $_SESSION['quiniela_matona_idliga'];
$IDJornada = $_GET['j'];
$Seccion = $_GET['s'];

  $sql_jornadas = "	SELECT * 
					FROM jornadas as j, temporada as t 
					WHERE j.IDLiga = '".$IDLiga."'
						AND j.IDTemporada = t.IDTemporada
						AND t.Status = '1'
					ORDER BY IDJornada asc";
  $consulta_jornadas = mysql_query($sql_jornadas);
  echo "<select size='1' name='dcJornada' id='dcJornada' onchange=\"cambia_jornada(this.value, 'app', '".$Seccion."');\">";
  echo "<option value=''>Selecciona una opcion</option>";
  for($j=0;$j<mysql_num_rows($consulta_jornadas);$j++)
	{
		$renglon_jornadas = mysql_fetch_assoc($consulta_jornadas);
		if ($IDJornada == $renglon_jornadas['IDJornada'])
		  echo "<option selected value='".$renglon_jornadas['IDJornada']."'>".$renglon_jornadas['Jornada']."</option>";
		else
		  echo "<option value='".$renglon_jornadas['IDJornada']."'>".$renglon_jornadas['Jornada']."</option>";
		
	}
  echo "</selected>";
?>