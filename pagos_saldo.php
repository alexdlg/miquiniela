<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");
require("genera_clave.php");
$IDUsuario = $_SESSION['quiniela_matona_id'];
$sql = "SELECT u.*, i.Apodo as Invitador FROM usuarios as u, usuarios as i WHERE u.IDUsuario = '$IDUsuario' AND u.InvitadoPor = i.IDUsuario";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);

$mi_saldo = $renglon['Saldo'];
if ($mi_saldo <= 0)
  $formato_saldo = "<b><font color='#FF0000'>";
else
  $formato_saldo = "";


              $sql_pagos = "SELECT SUM(Monto) FROM tickets WHERE IDUsuario = '".$renglon['IDUsuario']."' AND Status = '1'";
              $consulta_pagos = mysql_query($sql_pagos);
              $renglon_pagos = mysql_fetch_row($consulta_pagos);
              $TotalPagos = $renglon_pagos[0];

              $sql_pagos = "SELECT SUM(MontoTransfer + MontoSPEI) FROM premios WHERE IDUsuario = '".$renglon['IDUsuario']."'";
              $consulta_pagos = mysql_query($sql_pagos);
              $renglon_pagos = mysql_fetch_row($consulta_pagos);
              $TotalGanado = $renglon_pagos[0];

              $sql_transfers = "SELECT SUM(Monto) FROM tickets_transfers WHERE IDUsuarioPara = '".$renglon['IDUsuario']."'";
              $consulta_transfers = mysql_query($sql_transfers);
              $renglon_transfers = mysql_fetch_row($consulta_transfers);
              $TotalDepositoTransfer = $renglon_transfers[0];
        
              $sql_transfers = "SELECT SUM(Monto) FROM tickets_transfers WHERE IDUsuarioDe = '".$renglon['IDUsuario']."'";
              $consulta_transfers = mysql_query($sql_transfers);
              $renglon_transfers = mysql_fetch_row($consulta_transfers);
              $TotalTransferido = $renglon_transfers[0];

              $sql_transfers = "SELECT SUM(MontoSPEI) FROM premios WHERE IDUsuario = '".$renglon['IDUsuario']."'";
              $consulta_transfers = mysql_query($sql_transfers);
              $renglon_transfers = mysql_fetch_row($consulta_transfers);
              $TotalTransferidoSPEI = $renglon_transfers[0];

              
              $TotalPagosGlobal = $TotalPagos + $TotalDepositoTransfer;
              $sql_jugado = "SELECT SUM(StatusPago * Costo) as Cuota, SUM(BonoExtra * 10) as Extras FROM pagos as p, jornadas as j, temporada as t, ligas as l
                             WHERE
							   l.IDLiga = j.IDLiga AND
							   j.IDTemporada = t.IDTemporada AND
                               p.IDJornada = j.IDJornada AND
                               p.StatusPago = '1' AND
                               p.IDUsuario = '" . $renglon['IDUsuario'] . "'";
              $consulta_jugado = mysql_query($sql_jugado);
			  $renglon_jugado = mysql_fetch_row($consulta_jugado);
			  
              $sql_jugado2 = "SELECT SUM(StatusPago * Costo) as Cuota FROM temporada_pagos as p, temporada as t
                             WHERE
							   p.IDTemporada = t.IDTemporada AND
                               p.StatusPago = '1' AND
                               p.IDUsuario = '" . $renglon['IDUsuario'] . "'";
              $consulta_jugado2 = mysql_query($sql_jugado2);
			  echo mysql_error();
			  $renglon_jugado2 = mysql_fetch_row($consulta_jugado2);

			  
              $TotalJugado = $renglon_jugado[0] + $renglon_jugado[1]+ $renglon_jugado2[0];
			  


?>

<table width="80%" cellpadding="5" cellspacing="0" border="0" align="right" class="reporte_pagos">
  <tr>
    <td bgcolor="#00DD00" align="center" colspan="2">
	  <b>¿Cuanto tengo de saldo?</b>      
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    <br />
    <table border="0" cellpadding="2" cellspacing="0">
      <tr>
        <td rowspan="3">						 
        </td>
        <td align="center" colspan="2">
          <img src="images/saldo.png" border="0" hspace="5">
        </td>
      </tr>
      <tr>
        <td rowspan="2" align="right">
          <font size="+3">
          <?php echo $formato_saldo;?>
          $<?php echo $mi_saldo;?>
          </font>
        </td>
        <td valign="bottom">
          <font size="-1">
            <?php echo $formato_saldo;?>00
          </font>
        </td>
      </tr>
      <tr>
        <td>
          <font size="-1">
          m.n.
          </font>
        </td>
      </tr>
    </table>				  
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      ¿Que? ¿Como? ¿Porque? 
      <br /><br />
      <a onclick="saldo_detalle();" style="cursor:pointer">Ver Detalle de Saldo</a>
    </td>
  </tr>
  <tr>
    <td>
      <table cellpadding="5" cellspacing="0" border="0" width="100%" style="display:none;" id="saldo_detalle">
        <tr>
          <td align="right">+ Total de Pagos:</td>
          <td align="right">$ <?php echo number_format($TotalPagosGlobal,2);?></td>
          <td>
            <a href="detalle_pagos.php?token=<?php echo $renglon['IDUsuario'];?>" class="various" data-fancybox-type="iframe">
            <img src="images/help.png" title="Ver Detalle" border="0" width="32"></a></td>
        </tr>
        <tr>
          <td align="right">+ Total Ganado:</td>
          <td align="right">$ <?php echo number_format($TotalGanado,2);?></td>
          <td>
            <a href="detalle_premios.php?token=<?php echo $renglon['IDUsuario'];?>" class="various" data-fancybox-type="iframe">
            <img src="images/help.png" title="Ver Detalle" border="0" width="32"></a></td>
        </tr>
        <tr>
          <td align="right">- Total Jugado:</td>
          <td align="right">$ <?php echo number_format($TotalJugado,2);?></td>
          <td>
            <a href="detalle_jugado.php?token=<?php echo $renglon['IDUsuario'];?>" class="various" data-fancybox-type="iframe">
            <img src="images/help.png" title="Ver Detalle" border="0" width="32"></a></td>
        </tr>
        <tr>
          <td align="right">- Total Transferido:</td>
          <td align="right">$ <?php echo number_format($TotalTransferido,2);?></td>
          <td>
            <a href="detalle_transfers.php?token=<?php echo $renglon['IDUsuario'];?>" class="various" data-fancybox-type="iframe">
            <img src="images/help.png" title="Ver Detalle" border="0" width="32"></a></td>
        </tr>
        <tr>
          <td align="right">- Total enviado a SPEI:</td>
          <td align="right">$ <?php echo number_format($TotalTransferidoSPEI,2);?></td>
          <td>
            <a href="detalle_transfers_spei.php?token=<?php echo $renglon['IDUsuario'];?>" class="various" data-fancybox-type="iframe">
            <img src="images/help.png" title="Ver Detalle" border="0" width="32"></a></td>
        </tr>
        <tr>
          <td width="65%" align="right">= Saldo Actual:</td>
          <td width="35%" align="right">$ <?php echo number_format($renglon['Saldo'],2);?></td>
          <td>
<?php
$basura1 = dameReferencia() . dameReferencia() . dameReferencia() . dameReferencia() . dameReferencia();
$basura2 = dameReferencia() . dameReferencia() . dameReferencia() . dameReferencia() . dameReferencia();
$basura3 = dameReferencia() . dameReferencia() . dameReferencia() . dameReferencia() . dameReferencia();
$basura4 = dameReferencia() . dameReferencia() . dameReferencia() . dameReferencia() . dameReferencia();
$basura5 = dameReferencia() . dameReferencia() . dameReferencia() . dameReferencia() . dameReferencia();
$TokenPagos = $basura1 . "~" . $basura3 . "~" . $basura4 . "~" . $basura5 . "~" . $basura2 . "~" . $renglon['IDUsuario'] . "~" . $basura3 . "~" . $basura2 . "~" . $basura5;
?>
            <a href="transfer-pagos.php?token=<?php echo $TokenPagos;?>" class="various-refresh" data-fancybox-type="iframe">
            <img src="images/transfer.png" title="Transferir Saldo" border="0" width="32"></a></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
