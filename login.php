<?php
session_start();

$redes = $_POST['redes'];

if ($redes == "fb")
  $_SESSION['quiniela_matona_redes'] = "fb";
else 
if ($redes == "tw")
  $_SESSION['quiniela_matona_redes'] = "tw";

if ($redes != "")
  echo "<script language='javascript'>parent.$.fancybox.close();</script>";


include("include/init.php");

define('dbHost','localhost');
define('dbUser','gordo_user');
define('dbPass','3l$0Yns2tZ$658kd');
define('dbName','quiniela');

$conexion = new mysqli(dbHost, dbUser, dbPass, dbName);

if ($conexion->connect_error) {
 die("La conexion falló: " . $conexion->connect_error);
}

$Usuario = explode("@", trim($_POST['email']));
$Clave = md5($_POST['pwd']);
$Clave2 = $_POST['pwd'];

function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

if ($Usuario == "")
  {
  echo "Nice Try";
  exit();
  }
else
if ($Clave2 == "")
  {
  echo "Nice Try";
  exit();
  }
else
  {
	$sql = "CALL LoginSelect('$Usuario[0]','$Usuario[1]','$Clave','$Clave2')";
	  
	$result = $conexion->query($sql);	
	if ($result->num_rows > 0)	
	  {
		  $renglon = $result->fetch_array(MYSQLI_ASSOC);		
		  $_SESSION['quiniela_matona_id'] = $renglon['IDUsuario'];
		  $_SESSION['quiniela_matona_admin'] = $renglon['Admin'];
		  $_SESSION['quiniela_matona_nombre'] = $renglon['Nombre'];
		  $_SESSION['quiniela_matona_apodo'] = $renglon['Apodo'];
		  if ($renglon['Sexo'] == "m")
		    $_SESSION['quiniela_matona_sexo'] = "a";
		  else
		    $_SESSION['quiniela_matona_sexo'] = "o";
		  
		  if (session_id() != "")
		    {
				$IDU = $renglon['IDUsuario'];
				$IDS = session_id();
				
			  //$conexion = new mysqli(dbHost, dbUser, dbPass, dbName);
			  //$sql_sesion = "CALL LoginSesionMgtUpdate('$IDU','$IDS')";
				
			  $sql_sesion = "UPDATE usuarios SET Sexion = '".session_id()."' WHERE IDUsuario = '".$renglon['IDUsuario']."'";
			  $modificacion_sesion = mysql_query($sql_sesion);
			  //$stmt2 = $conexion->query($sql_sesion);
			  //var_dump($stmt2);
			  			
			}
			
 		  $user_ip = getUserIP();
		  $Hoy = date("Y-m-d");
		  $Ya = date("H:i:s");
		  $conexion = new mysqli(dbHost, dbUser, dbPass, dbName);
		  $sql_log = "CALL LogInsert('".$renglon['IDUsuario']."','$Hoy','$Ya','$user_ip')";
		  //"INSERT INTO access_log (IDUsuario, Fecha, Hora, IPOrigen) VALUES ('".$renglon['IDUsuario']."','$Hoy','$Ya','$user_ip')";
		  $insercion_log = $conexion->query($sql_log);
		  
		  if ($renglon['IDInterfaz'] > 0)	
		    {
			  $conexion = new mysqli(dbHost, dbUser, dbPass, dbName);
			  $sql_interfaz = "CALL InterfazSelect('".$renglon['IDInterfaz']."')";
			  //"SELECT * FROM interfaces WHERE IDInterfaz = '".$renglon['IDInterfaz']."'";
			  $consulta_interfaz = $conexion->query($sql_interfaz);
			  if($consulta_interfaz->num_rows > 0)
			  {
				  $renglon_interfaz = $result->fetch_array(MYSQLI_ASSOC);
			  }
				
			}
							  echo "<script language='javascript'>parent.$.fancybox.close();</script>";

	  }
	else
	  {
	  $_SESSION['quiniela_matona_y'] = "error";
      echo "<script language='javascript'>window.location = 'login_fancy.php?login=no'</script>";	  
	  }
  }

?>