<?php
header("Content-Type: text/html; charset=iso-8859-1"); 
session_start();
include("include/init.php");
include("fechas.php");
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
?>

<table width="80%" cellpadding="5" cellspacing="0" border="0" align="right" class="reporte_pagos">
  <tr>
    <td bgcolor="#00DD00" align="center" colspan="2">
	  <b>¿Como veo el Status de mi Pago?</b>      
    </td>
  </tr>
<?php
if ($registros > 0)
  {
	$renglon_pago = mysql_fetch_assoc($consulta_pago);
	if ($renglon_pago['Status'] == "-1")
	  $ElStatus = "<font color='#FF9900'>EN REVISION</font>";
	else
	if ($renglon_pago['Status'] == "0")
	  $ElStatus = "<font color='#FF0000'>CANCELADO</font>";
	else
	  $ElStatus = "<font color='#00CC00'>APLICADO</font>";
?>
  <tr>
    <td colspan="2">
      Recibimos tu comprobante de pago el dia <i><?php echo dameFechaCompleta($renglon_pago['FechaPago']);?></i> 
      por la cantidad de <font color='#0000CC'>$ <?php echo number_format($renglon_pago['Monto'],2);?> pesos</font>, con el numero de 
      Folio <font color="#FF0000"><u><?php echo $renglon_pago['FolioPago'];?></u></font>
      <br /><br />
      Status Actual: <?php echo $ElStatus;?> <br /><br />
      ¿Quieres ver que mas has pagado? <br /><br />
      <a href='detalle_pagos.php?token=<?php echo $IDUsuario;?>' class='various' data-fancybox-type='iframe'>Ver Mis Pagos</a>      
    </td>
  </tr>
<?php	
  }
else
  {
    $ciclo = 0;
?>
  <tr>
    <td colspan="2">
      No tenemos ningun pago registrado. 
    </td>
  </tr>
<?php	
  }
?>
</table>
