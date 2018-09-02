<?php
header("Content-Type: text/html; charset=iso-8859-1"); 
session_start();
include("include/init.php");
$IDUsuario = $_SESSION['quiniela_matona_id'];
$sql = "SELECT u.*, i.Apodo as Invitador FROM usuarios as u, usuarios as i WHERE u.IDUsuario = '$IDUsuario' AND u.InvitadoPor = i.IDUsuario";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);

$Saldo = number_format($renglon['Saldo'],2);

$sql_pago = "
SELECT 
  *
FROM 
	tickets 
WHERE 
	IDUsuario = '$IDUsuario'
ORDER BY FolioPago desc
";
$consulta_pago = mysql_query($sql_pago);
$registros = mysql_num_rows($consulta_pago);
if ($registros > 0)
  {
	if ($registros > 5)
	  $ciclo = 5;
	else
	  $ciclo = $registros;
  }
else
  $ciclo = 0;
?>

<table width="80%" cellpadding="5" cellspacing="0" border="0" align="right" class="reporte_pagos">
  <tr>
    <td bgcolor="#00DD00" align="center" colspan="2">
	  <b>¿Cuanto he Pagado?</b>      
    </td>
  </tr>
  <tr>
    <td colspan="2">
      A continuacion te mostramos tus ultimos <?php echo $ciclo;?> movimientos. 
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%" cellpadding="5" cellspacing="0" border="1" align="center" class="reporte_pagos" bordercolor="#FFFFFF">
      <?php
		for ($i=0;$i<$ciclo;$i++)
		  {
			$renglon_pago = mysql_fetch_assoc($consulta_pago);
			if ($trcolor == "#FAFAFA")
			  $trcolor = "#FFFFFF";
			else
			  $trcolor = "#FAFAFA";
			if ($renglon_pago['Status'] == "-1")
			  $ElStatus = "EN REVISION";
			else
			if ($renglon_pago['Status'] == "0")
			  $ElStatus = "CANCELADO";
			else
			  $ElStatus = "APLICADO";
			  
			echo "<tr bgcolor='$trcolor'>
					<td>".$renglon_pago['FolioPago']."</td>
					<td>".$renglon_pago['FechaPago']."</td>
					<td>$ ". number_format($renglon_pago['Monto'],2)."</td>
					<td>".$ElStatus."</td>
				  </tr>";
		  }
		if ($registros > 5)
		  echo "<tr>
		          <td colspan='4' align='right'>
				    <a href='detalle_pagos.php?token=".$IDUsuario."' class='various' data-fancybox-type='iframe'>Ver Todos</a>
				  </td>
				</tr>";
	  ?>
      </table>
    </td>
  </tr>
</table>
