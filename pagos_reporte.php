<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");

$IDUsuario = $_SESSION['quiniela_matona_id'];
$sql = "SELECT u.*, i.Apodo as Invitador FROM usuarios as u, usuarios as i WHERE u.IDUsuario = '$IDUsuario' AND u.InvitadoPor = i.IDUsuario";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);

$Saldo = number_format($renglon['Saldo'],2);

?>

<table width="80%" cellpadding="5" cellspacing="0" border="0" align="right" class="reporte_pagos">
  <tr>
    <td bgcolor="#00DD00" align="center" colspan="2">
	  <b>¿Cómo reporto mi pago?</b>      
    </td>
  </tr>
  <tr>
    <td colspan="2">
      Si ya realizaste tu pago, te agradecemos que, para agilizar el proceso
      de abono a tu cuenta, nos reportes el pago llenando la siguiente informacion. 
      Agradecemos tu paciencia ya que aunque reportes el pago, no se autoriza de inmediato,
      requerimos validacion humana, normalmente el proceso de autorizacion no debera
      tardar mas de 10 minutos, PERO dependera de la hora a la que reportes tu pago.      
    </td>
  </tr>
  <tr>
    <td>
    <form name="FormaPago" action="pagos.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="session_id" value="<?php echo $SessionID;?>" />
    <input type="hidden" name="txtIDUsuario" value="<?php echo $IDUsuario;?>" />
    <table width="100%" cellpadding="5" cellspacing="0" border="0" align="center" class="reporte_pagos">
      <tr>
        <td>
          Saldo Actual:<br />
          <input type="text" id="txtSaldo" name="txtSaldo" value="<?php echo $Saldo;?>" autocomplete="off" disabled="disabled"/>                  
        </td>		
      </tr>
      <tr>
        <td align="left">
          Fecha Pago:<br />
          <input type="text" id="txtFechaPago" name="txtFechaPago" value="<?php echo date("Y-m-d");?>" autocomplete="off" />
        </td>
      </tr>
      <tr>
        <td align="left">
          Monto del Pago:<br />
          <input type="text" id="txtMontoPago" name="txtMontoPago" value="" autocomplete="off"/>
        </td>
      </tr>
      <tr>
        <td align="left">
          Adjuntar Comprobante:<br />
          <input type="file" name="adjunto"  autocomplete="off"/>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top">
          Comentarios Adicionales:<br />
          <textarea name="txtComentarios" rows="3" autocomplete="off"></textarea>
        </td>          
      </tr>
      <tr>
        <td colspan="5" align="center">
          <input type="button" value="Reportar Pago" onclick="valida_pago();" id="cmdPago" />
        </td>
      </tr>
    </table>
    </form>
    </td>
  </tr>
</table>
