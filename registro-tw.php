<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");
require("fechas.php");

$Correo = explode("@", $_POST['txtCorreoTW']);
$Clave = md5($_POST['txtClaveTW']);

$CorreoOriginal = $_POST['txtCorreoTW'];
$ClaveOriginal = $_POST['txtClaveTW'];

$IDTW = $_POST['TokenTwitterID'];
$Twitter = $_POST['TokenTwitterName'];


$sql = "SELECT * FROM usuarios WHERE CoreoPRE = '$Correo[0]' AND CoreoPOST = '$Correo[1]' AND KLV = '$Clave'";
$consulta = mysql_query($sql);
if (mysql_num_rows($consulta) == 1)
  {
	$renglon = mysql_fetch_assoc($consulta);
	$IDUsuario = $renglon['IDUsuario'];
	
	$sql_tw = "UPDATE usuarios SET twus3r1d = '$IDTW', Twitter = '$Twitter' WHERE IDUsuario = '$IDUsuario'";
	$tw = mysql_query($sql_tw);
	
?>
<form action="login.php" method="post" id="forma_login">
  <input type="hidden" id="email" name="email" value="<?php echo $CorreoOriginal;?>" >
  <input type="hidden" id="pwd" name="pwd" value="<?php echo $ClaveOriginal;?>">
</form>

<script language="javascript">
  document.getElementById('forma_login').submit();
</script>

<?php	
	
  }
else
  {
	echo "No encontramos tu usuario y/o la contraseña esta mal";
	exit();
  }


?>