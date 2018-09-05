<?php
include("include/init.php");

$token = explode("@", $_GET['token']);

$IDUsuario = $token[0];
$sql_saldo = "SELECT Saldo FROM usuarios WHERE IDUsuario = '$IDUsuario'";
$consulta_saldo = mysql_query($sql_saldo);
$renglon_saldo = mysql_fetch_assoc($consulta_saldo);


if ($renglon_saldo['Saldo'] < 0)
  $SaldoFinal = "<font color='#FF0000'> $ ".number_format($renglon_saldo['Saldo'],2)."</font>";
else				  
  $SaldoFinal = "<font color='#000000'>  $ ".number_format($renglon_saldo['Saldo'],2)."</font>";

echo $SaldoFinal;

?>