<?php
session_start();
include("include/init.php");

$token = $_GET['token'];

$IDUsuario = $_SESSION['quiniela_matona_id'];
$IDJornada = $token;


$sql = "SELECT * FROM ligas as l, temporada as t, jornadas as j 
		     WHERE 
			   j.IDLiga = l.IDLiga AND 
			   t.IDLiga = l.IDLiga AND
			   j.IDTemporada = t.IDTemporada AND
			   j.IDJornada = '$IDJornada'";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);
$IDTemporada = $renglon['IDTemporada'];
$IDLiga = $renglon['IDLiga'];
$CostoLiga = $renglon['Costo'];
$Politica = $renglon['Modo'];


if ($Politica == "semanal")
  {
    $sql_pagos = "SELECT * FROM pagos WHERE IDJornada = '".$IDJornada."' AND StatusPago = '1'";
    $consulta_pagos = mysql_query($sql_pagos);
    $Pagados = mysql_num_rows($consulta_pagos);
    $el_acumulado  = ($Pagados * $CostoLiga) * 0.90;
	$Etiqueta = "<font size='+1'>
						  Acumulado
						  <br>
						  de la Jornada
						  </font>";
  }
else
  {
    $sql_pagos = "SELECT * FROM temporada_pagos WHERE StatusPago = '1' AND IDTemporada = '".$IDTemporada."'";
    $consulta_pagos = mysql_query($sql_pagos);
    $Pagados = mysql_num_rows($consulta_pagos);
    $el_acumulado  = ($Pagados * $CostoLiga) * 0.90;
	$Etiqueta = "<font size='+1'>
						  Acumulado
						  <br>
						  del Torneo
						  </font>";
  }
  


$sql_saldo = "SELECT Saldo FROM usuarios WHERE IDUsuario = '$IDUsuario'";
$consulta_saldo = mysql_query($sql_saldo);
$renglon_saldo = mysql_fetch_assoc($consulta_saldo);
$mi_saldo = $renglon_saldo['Saldo'];
if ($mi_saldo <= 0)
  $formato_saldo = "<b><font color='#FF0000'>";
else
  $formato_saldo = "";


$sql_pagos = "SELECT * FROM pagos WHERE IDJornada = '".$IDJornada."' AND BonoExtra = '1'";
$consulta_pagos = mysql_query($sql_pagos);
$Pagados = mysql_num_rows($consulta_pagos);


$sql_bono = "SELECT * FROM pagos as p, jornadas as j WHERE p.IDJornada = j.IDJornada AND p.BonoExtra = '1' AND j.PremioBonoExtra = '0' AND IDLiga = '$IDLiga' AND j.Status > 0 AND IDTemporada = '".$IDTemporada."'";
$consulta_bono= mysql_query($sql_bono);
$Bonos = mysql_num_rows($consulta_bono);

$el_bono  = ($Bonos * 10) * 0.90;




?>


<div class="container">
        <div class="row">
            <div class="col-md-4 col-lg-4">
				    <table border="0" cellpadding="2" cellspacing="0" >
					  <tr>
					    <td rowspan="3">
						  <img src="images/bono-semanal.png" border="0" hspace="5">
						</td>
						<td align="center" colspan="2">
						  <?php echo $Etiqueta;?>
						</td>
					  </tr>
					  <tr>
					    <td rowspan="2" align="right">
						  <font size="+3">
						  $<?php echo $el_acumulado;?>
						  </font>
						</td>
						<td valign="bottom">
						  <font size="-1">
						  00
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
            </div>
            <div class="col-md-4 col-lg-4">
                    <?php
		            if ($IDTemporada == 12)
					{
					?>
				    <table border="0" cellpadding="2" cellspacing="0">
					  <tr>
					    <td rowspan="3">
						  <img src="images/bono-goles.png" border="0" hspace="5">
						</td>
						<td align="center" colspan="2">
						  <font size="+1">
						  Acumulado
						  <br>
						  del Bono Extra
						  </font>
						</td>
					  </tr>
					  <tr>
					    <td rowspan="2" align="right">
						  <font size="+3">
						  $<?php echo $el_bono;?>
						  </font>
						</td>
						<td valign="bottom">
						  <font size="-1">
						  00
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
                    <?php
					}
					?>
			</div>
            <div class="col-md-4 col-lg-4">
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
            </div>
        </div>
</div>
