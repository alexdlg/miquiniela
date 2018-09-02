<?php
session_start();
include("include/init.php");
require("fechas.php");
$IDUsuario = $_SESSION['quiniela_matona_id'];

$sql = "SELECT * FROM usuarios WHERE IDUsuario = '$IDUsuario'";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);


$Archivo = "perfil/" . $IDUsuario . ".jpg";
if (file_exists($Archivo))
	  $foto_perfil = "<img src='".$Archivo."'  height='100'>";
else
	  $foto_perfil = "<img src='images/no-pic.png' height='100'>";

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
    <td align="center" bgcolor="#009900">
      <font color="#FFFFFF"><b>Resumen de Transferencias Realizadas</b></font>
    </td>
  </tr>
  <tr>
    <td align="center">
	  <table border="0" cellpadding="5" cellspacing="0"  bordercolor="#000000" width="100%" bgcolor="#FFFFFF">
		<tr>
			<td rowspan="3" align="left" valign="top">
			<?php echo $foto_perfil;?>
			</td>
			<td align="right" width="100"><b>Nombre</b></td>
			<td align="left" width="550"><?php echo $renglon['Nombre'];?></td>    
		</tr>
	    <tr>
		  <td align="right" ><b>Apodo</b></td>
		  <td align="left" ><?php echo $renglon['Apodo'];?></td>    
	    </tr>
		<tr>
		  <td>&nbsp;</td>
		</tr>
	    <tr>
		  <td colspan='3'>
		  <?php
	  $sql_pagos = "SELECT 
				    * 
					FROM 
					  tickets_transfers as tt, usuarios as u
					WHERE 
					  tt.IDUsuarioDe = '$IDUsuario' AND
					  tt.IDUsuarioPara = u.IDUsuario 
					ORDER BY Fecha DESC, Hora DESC
					  ";
	  $consulta_pagos = mysql_query($sql_pagos);
	  echo "<table cellpadding='5' cellspacing='0' width='100%' border='1' bordercolor='#FFFFFF'>
		      <tr align='center' bgcolor='#999999'>
			    <td>Fecha</td>
			    <td>Hora</td>
			    <td>Transferido A</td>
			    <td>Monto</td>
			  </tr>
	       ";
	  for ($i=0;$i<mysql_num_rows($consulta_pagos);$i++)
	    {
		  $renglon_pagos = mysql_fetch_assoc($consulta_pagos);
		  echo "<tr>
		          <td>".dameFechaCortaCompleta($renglon_pagos['Fecha'])."</td>
		          <td>".$renglon_pagos['Hora']."</td>
		          <td>".$renglon_pagos['Nombre']." (". $renglon_pagos['Apodo'].")</td>
		          <td align='right'> $ ". number_format($renglon_pagos['Monto'],2)."</td>
				</tr>";
		  $acumulado+= $renglon_pagos['Monto'];
		}
	  
	  echo "<tr bgcolor='#999999'>
	          <td colspan='3' align='right'>Total Transferido</td>
			  <td align='right'>$ ".number_format($acumulado,2)."</td>
	        </tr>
			</table>";
?>
	</td>
  </tr>
</table>
</td>
</tr>
</table></td></tr></table>
