<?php
session_start();
include("include/init.php");
require("genera_clave.php");

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
$CodigoInvitacion = dameToken();
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
<form name="forma" id="forma" action="grupos_admin.php" method="post">
<input type="hidden" name="txtPrivacidad" id="txtPrivacidad" value="0" />
<input type="hidden" name="txtCodigo"  id ="txtCodigo"  value="<?php echo $CodigoInvitacion;?>" />
<table border="0" width="100%" cellpadding="10" cellspacing="0">
  <tr>
    <th colspan="2" align="center" bgcolor="#000000">
      Creador de Grupos LQDG
    </th>
  </tr>
  <tr>
    <td width="40%" align="right">Nombre del Grupo</td>
    <td width="60%" align="left"><input type="text" name="txtNombreGrupo"  id ="txtNombreGrupo"  value=""/></td>
  </tr>
  <tr>
    <td align="right">Tipo de Grupo</td>
    <td align="left">
      <select name="dcTipoGrupo" size="1">
        <option value="-">Selecciona una Opcion</option>
        <option value="-">-------------------------</option>
        <?php
		$sql_tipo_grupo = "SELECT * FROM grupos_social_torneos WHERE Status = '1' ORDER BY TipoTorneo desc";
		$consulta_tipo_grupo = mysql_query($sql_tipo_grupo);
		for ($g=0;$g<mysql_num_rows($consulta_tipo_grupo);$g++)
		  {
			$renglon_tipo_grupo = mysql_fetch_assoc($consulta_tipo_grupo);
			echo "<option value='".$renglon_tipo_grupo['IDTipoTorneo']."'>".$renglon_tipo_grupo['TipoTorneo']."</option>";
		  }
		?>
      </select>
    </td>
  </tr>
  <tr>
    <td valign="top" align="right">Privacidad</td>
    <td align="left">
      <input type="radio" name="rbPrivadidad" onclick="privacidad('0')" checked="checked" />Publico<br />
      <input type="radio" name="rbPrivadidad" onclick="privacidad('1')" />Privado
    </td>
  </tr>
  <tr style="display:none;" id="trCodigo"> 
    <td align="right">Codigo de Invitacion</td>
    <td align="left"><b><font color="#FF0000"><?php echo $CodigoInvitacion;?></font></b></td>
  </tr>
  <tr>
    <td valign="top" align="right">Maximo de Intergrantes</td>
    <td align="left">
      <input type="text" name="txtMaximo"  id ="txtMaximo"  value="0"/>
      <font size="1">0 = Sin Limite de Integrantes</font>
    </td>
  </tr>
  <tr bgcolor="#000000">
    <td></td>
    <td align="left" >
      <input type="button" value="Cancelar" />
      &nbsp;&nbsp;
      <input type="button" value="Crear" onclick="valida();" />
    </td>
  </tr>
</table>
</form>
</body> 
</html>

<script language="javascript">
  function privacidad(valor)
  {
	  if (valor == "1")
	    {
	      document.getElementById('trCodigo').style.display = "table-row";		  
		}
	  else
	    {
	      document.getElementById('trCodigo').style.display = "none";		  
		}
	  
	  document.getElementById('txtPrivacidad').value = valor;
  }
  
  
function valida()
{
	document.getElementById('forma').submit();
}
</script>