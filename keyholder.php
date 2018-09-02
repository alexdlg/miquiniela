<?php
include("include/init.php");
$Sexion = $SessionID;
$sql_session = "SELECT * FROM usuarios WHERE Sexion = '$Sexion'";
$consulta_session = mysql_query($sql_session);
if (mysql_num_rows($consulta_session) == 0)
  echo "<script language='javascript'>window.location = 'byebye.php?token=k'</script>";	  
else	
  {
  $renglon_session = mysql_fetch_assoc($consulta_session);
  $_SESSION['quiniela_matona_id'] = $renglon_session['IDUsuario'];
  $_SESSION['quiniela_matona_admin'] = $renglon_session['Admin'];
  $_SESSION['quiniela_matona_nombre'] = $renglon_session['Nombre'];
  
  }

if ($_SESSION['quiniela_matona_id'] != $renglon_session['IDUsuario'])
  echo "<script language='javascript'>window.location = 'byebye.php?token=k'</script>";	  


?>
