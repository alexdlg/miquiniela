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
      <font color="#FFFFFF"><b>Resumen de Pagos Recibidos</b></font>
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
	  $sql_pagos = "
	  				(
					  SELECT 
					    FolioPago, FechaPago, Monto, 'Pago Directo' as Modo
					  FROM 
					    tickets 
					  WHERE 
					    Status = '1' AND IDUsuario = '$IDUsuario' 
					)
					UNION
					(
					  SELECT 
					    Folio as FolioPago, Fecha as FechaPago, Monto , 'Transfer Recibida' as Modo
					  FROM 
					    tickets_transfers
					  WHERE 
					    IDUsuarioPara = '$IDUsuario' 
					)
				    ORDER BY FechaPago desc
					";
	  $consulta_pagos = mysql_query($sql_pagos);
	  echo "<table cellpadding='5' cellspacing='0' width='100%' border='1' bordercolor='#FFFFFF'>
		      <tr align='center' bgcolor='#999999'>
			    <td>Ticket</td>				
			    <td>Fecha</td>
			    <td>Monto</td>
			    <td>Origen de $</td>
			  </tr>
	       ";
	  for ($i=0;$i<mysql_num_rows($consulta_pagos);$i++)
	    {
		  $renglon_pagos = mysql_fetch_assoc($consulta_pagos);
		  echo "<tr>
		          <td align='center'>".$renglon_pagos['FolioPago']."</td>
		          <td>".dameFechaCortaCompleta($renglon_pagos['FechaPago'])."</td>
		          <td align='right'> $ ". number_format($renglon_pagos['Monto'],2)."</td>
		          <td align='left'>".$renglon_pagos['Modo']."</td>
				</tr>";
		  $acumulado+= $renglon_pagos['Monto'];
		}
	  
	  echo "<tr bgcolor='#999999'>
	          <td colspan='2' align='right'>Total Pagado</td>
			  <td align='right'>$ ".number_format($acumulado,2)."</td>
			  <td>&nbsp;</td>
	        </tr>
			</table>";
	  ?>
	</td>
  </tr>
</table>
</td>
</tr>
</table></td></tr></table>
