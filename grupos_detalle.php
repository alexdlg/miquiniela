<?php
session_start();
include("include/init.php");
require("genera_clave.php");
$IDLiga  = $_SESSION['quiniela_matona_idliga'];

if ($_SERVER['REQUEST_METHOD'] == "POST")
  {
	  $Hoy = date("Y-m-d");
	  $Ya = date("H:i:s");
	  $NombreGrupo = $_POST['txtNombreGrupo'];
	  $IDCreador = $_SESSION['quiniela_matona_id'];
	  $IDLiga = $_SESSION['quiniela_matona_idliga'];
	  
	$sql = "SELECT * FROM ligas as l, temporada as t, jornadas as j 
				 WHERE 
				   j.IDLiga = '$IDLiga' AND
				   j.IDLiga = l.IDLiga AND 
				   t.IDLiga = l.IDLiga AND
				   j.IDTemporada = t.IDTemporada AND
				   t.Status = '1'
				   ";
				   
	$consulta = mysql_query($sql);
	$renglon_temporada = mysql_fetch_assoc($consulta);
	$IDTemporada = $renglon_temporada['IDTemporada'];
	$TokenInvitacion = $_POST['txtCodigo'];
	$Modo = $_POST['txtPrivacidad'];
	$MaximoIntegrantes = $_POST['txtMaximo'];
	$Costo = 0;
	$IDTipoTorneo = $_POST['dcTipoGrupo'];
	
	$sql = "INSERT INTO grupos_social (IDGrupo, NombreGrupo, FechaCreacion, HoraCreacion, IDCreador, IDLiga, IDTemporada, TokenInvitacion, Modo, MaximoIntegrantes, Costo, IDTipoTorneo)
									  VALUES
									  ('','$NombreGrupo','$Hoy','$Ya','$IDCreador','$IDLiga','$IDTemporada','$TokenInvitacion','$Modo','$MaximoIntegrantes','$Costo','$IDTipoTorneo')";
	$insercion = mysql_query($sql);
		
	
	if (mysql_affected_rows() == 1)
	  {
		$IDGrupo = mysql_insert_id();
		
		$sql = "INSERT INTO grupos_social_jugadores (Folio, IDJugador, IDGrupo, FechaInscripcion, HoraInscripcion)
													VALUES
													('','$IDCreador','$IDGrupo','$Hoy','$Ya')";
		$insercion = mysql_query($sql);													
		echo "<center><br /><br />Grupo Creado Exitosamente<br /><br />";
		echo "<input type='button' value='Cerrar'>&nbsp;";
		echo "<input type='button' value='Abrir Grupo'>";
		exit(); 
	  }
									  
	
	  
	  
	  
  }
else
  {
	
	$token = $_GET['token'];
	$hash = $_GET['hash'];
	$TokenHash = md5($token);
	if ($TokenHash != $hash)
	  {
		  echo "bye";
		  exit();
	  }
	$token = explode("~", $_GET['token']);
	$IDGrupo = $token[2];
	  
	$sql = "SELECT * FROM grupos_social as g, grupos_social_torneos as t WHERE g.IDTipoTorneo = t.IDTipoTorneo AND g.IDGrupo = '$IDGrupo'";
	$consulta = mysql_query($sql);
	if (mysql_num_rows($consulta) == 0)
	  {
		echo "grupo no encontrado. bye";
		exit();
	  }
	else
	  {
		$renglon = mysql_fetch_assoc($consulta);
		if ($renglon['Modo'] == "1")
		  $Privacidad = "PRIVADO";
		else
		  $Privacidad = "PUBLICO";
		  
		if ($renglon['MaximoIntegrantes'] == "0")
		  $MaximoIntegrantes = "Sin Limite";
		else
		  $MaximoIntegrantes = $renglon['MaximoIntegrantes'];
		  
		$sql_grupos2 = "SELECT * FROM grupos_social as gs, grupos_social_jugadores as j 
					   WHERE 
						 gs.IDLiga = '$IDLiga' AND
						 gs.IDGrupo = j.IDGrupo AND
						 j.IDGrupo = '".$IDGrupo."'
						 ";
		$consulta_grupos2 = mysql_query($sql_grupos2);
		
		$Integrantes = mysql_num_rows($consulta_grupos2);
		
	  }
  }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Alta de Grupos</title>
</head>

<style>
body
  {
	  font-family:Verdana, Geneva, sans-serifM
  }

input[type="text"], select
{
	width:100%;
}

th
{
	color:#FFF;
	font-weight:bold;
}
</style>
<body>
<form name="forma" id="forma" action="grupos_detalle.php" method="post">
<input type="hidden" name="txtPrivacidad" id="txtPrivacidad" value="0" />
<input type="hidden" name="txtCodigo"  id ="txtCodigo"  value="<?php echo $CodigoInvitacion;?>" />
<table border="0" width="100%" cellpadding="10" cellspacing="0">
  <tr>
    <th colspan="2" align="center" bgcolor="#000000">
      Administrador de Grupos LQDG
    </th>
  </tr>
  <tr>
    <td width="40%" align="right">Nombre del Grupo</td>
    <td width="60%" align="left"><?php echo $renglon['NombreGrupo'];?></td>
  </tr>
  <tr>
    <td align="right">Tipo de Grupo</td>
    <td align="left"><?php echo $renglon['TipoTorneo'];?></td>
  </tr>
  <tr>
    <td valign="top" align="right">Privacidad</td>
    <td align="left"><?php echo $Privacidad;?></td>
  </tr>
  <tr> 
    <td align="right">Codigo de Invitacion</td>
    <td align="left"><b><font color="#FF0000"><?php echo $renglon['TokenInvitacion'];?></font></b></td>
  </tr>
  <tr>
    <td valign="top" align="right">Intergrantes</td>
    <td align="left"><?php echo $Integrantes . " / " . $MaximoIntegrantes;?></td>
  </tr>
</table>
</form>
</body> 
</html>
