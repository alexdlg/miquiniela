<?php
header("Content-Type: text/html; charset=iso-8859-1"); 
session_start();
include("include/init.php");
include("fechas.php");
$IDUsuario = $_SESSION['quiniela_matona_id'];
$sql = "SELECT u.*, i.Apodo as Invitador FROM usuarios as u, usuarios as i WHERE u.IDUsuario = '$IDUsuario' AND u.InvitadoPor = i.IDUsuario";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);

$sql_cuenta = "
SELECT 
  *
FROM 
	usuarios_banco
WHERE 
	IDUsuario = '$IDUsuario' 
";
$consulta_cuenta = mysql_query($sql_cuenta);
$registros = mysql_num_rows($consulta_cuenta);
?>

<table width="80%" cellpadding="5" cellspacing="0" border="0" align="right" class="reporte_pagos">
  <tr>
    <td bgcolor="#00DD00" align="center" colspan="2">
	  <b>¿A donde me depositan mi premio?</b>      
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <br />
      <i>Proximamente</i>
      <br /><br />
    </td>
  </tr>
</table>
