<?php
session_start();
include("include/init.php");

$token = $_GET['token'];

$IDUsuario = $_SESSION['quiniela_matona_id'];
$sql_saldo = "SELECT Saldo FROM usuarios WHERE IDUsuario = '$IDUsuario'";
echo $sql_saldo;
$consulta_saldo = mysql_query($sql_saldo);
$renglon_saldo = mysql_fetch_assoc($consulta_saldo);
$mi_saldo = $renglon_saldo['Saldo'];
if ($mi_saldo <= 0)
  $formato_saldo = "<b><font color='#FF0000'>";
else
  $formato_saldo = "";

?>
si entre o no entre o que

<div class="container">
        <div class="row">
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
