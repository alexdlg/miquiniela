<?php
header("Content-Type: text/html; charset=iso-8859-1"); 
require("include/init.php");
require("fechas.php");
$token = explode("@", $_GET['token']);
$IDUsuario = $token[0];
$sql = "SELECT * FROM usuarios WHERE IDUsuario = '$IDUsuario'";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);


$Archivo = "perfil/" . $IDUsuario . ".jpg";
if (file_exists($Archivo))
	  $foto_perfil = "<img src='".$Archivo."'  width='200'>";
else
	  $foto_perfil = "<img src='images/no-pic.png' width='200'>";

$Correo = $renglon['CoreoPRE'] . "@" . $renglon['CoreoPOST'];
?>

<style>
.tabla_principal
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;
	
}
</style>
<center>
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="tabla_principal"><tr height="30"><td align="center">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="listado">
  <tr height="30">
    <td align="center" bgcolor="#000000">
      <font color="#FFFFFF"><b>Perfil de Usuario</b></font>
    </td>
  </tr>
  <tr>
    <td align="center">
    <br /><br />
<table border="0" cellpadding="5" cellspacing="0"  bordercolor="#000000">
  <tr>
    <td rowspan="9" align="center" valign="top">
      <?php echo $foto_perfil;?>
    </td>
    <td align="right" width="200" bgcolor="#FFFFFF"><b>Nombre</b></td>
    <td align="left" width="250" bgcolor="#FFFFFF"><?php echo $renglon['Nombre'];?></td>    
  </tr>
  <?php
  if ($renglon['Apodo'] != "")
  {
  ?>
  <tr>
    <td align="right" ><b>Apodo</b></td>
    <td align="left" ><?php echo $renglon['Apodo'];?></td>    
  </tr>
  <?php
  }
  ?>
  <tr>
    <td align="right" ><b>Correo Electronico</b></td>
    <td align="left" ><?php echo $Correo;?></td>    
  </tr>
  <?php
  if ($renglon['Twitter'] != "")
  {
  ?>
  <tr>
    <td align="right" ><b>Twitter</b></td>
    <td align="left" >@<?php echo $renglon['Twitter'];?></td>    
  </tr>
  <?php
  }
  if ($renglon['Facebook'] != "")
  {
  ?>
  <tr>
    <td align="right" ><b>Facebook</b></td>
    <td align="left" ><a href="http://www.fb.com/<?php echo $renglon['Facebook'];?>" target="_blank">/<?php echo $renglon['Facebook'];?></a></td>    
  </tr>
  <?php
  }
  if ($renglon['Celular'] != "")
  {
  ?>
  <tr>
    <td align="right" ><b>Celular</b></td>
    <td align="left" ><?php echo $renglon['Celular'];?></td>    
  </tr>
  <?php
  }
  ?>
  <tr height="200">&nbsp;</tr>
</table>
    </td>
  </tr>
</table>
<br /><br />
</td></tr></table>
</center>
