<?php
include("encabezado_fancy.php");

$IDUsuario = $_SESSION['quiniela_matona_id'];


if ($_SERVER['REQUEST_METHOD'] == "POST")
  {
	 
	$uploadedfile = $_FILES['upfile']['tmp_name'];
	if ($uploadedfile != "")
	  {
        if ($_FILES['upfile']['type'] == "image/jpeg")
		  {
		    $src = imagecreatefromjpeg($uploadedfile);
		    list($width,$height)=getimagesize($uploadedfile);
		    if ($width > 200)
			  {
			    $newwidth=200;
			    $newheight=($height/$width)*$newwidth;
		    	$tmp=imagecreatetruecolor($newwidth,$newheight);

			    imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height); 
			
				$filename = "perfil/" . $IDUsuario . ".jpg";				
			    imagejpeg($tmp,$filename,100);

			    imagedestroy($src);
			    imagedestroy($tmp); // NOTE: PHP will clean up the temp file it created when the request
	  	 	  }
		    else
		      {
				$path_real= "perfil/" . $IDUsuario . ".jpg";
	    		if (!move_uploaded_file($_FILES['upfile']['tmp_name'], $path_real))
	    	  	  echo "No se pudo enviar el archivo correctamente";		
		      }  
		  }
		else
		  {
			  echo "<font color='#FF0000'>Tu foto de perfil no es formato JPG, favor de intentarlo de nuevo</font>";
		  }
	  }
	  $Nombre = $_POST['txtNombre'];
	  $Apodo= $_POST['txtApodo'];
	  $Facebook = $_POST['txtFacebook'];
	  $Twitter = $_POST['txtTwitter'];
	  $Correo= explode("@", $_POST['txtUsuario']);
	  $CorreoPre= $Correo[0];
	  $CorreoPost= $Correo[1];
	  $Celular = $_POST['txtCelular'];	  
      $Clave = md5($_POST['txtClave1']);
	  
	  if ($_POST['txtClave1'] != "********")	  
	    {
	    $Clave = md5($_POST['txtClave1']);
		$sql = "UPDATE usuarios SET CoreoPRE = '$CorreoPre', CoreoPOST = '$CorreoPost', Twitter = '$Twitter', Facebook = '$Facebook', Celular = '$Celular', Apodo = '$Apodo', Nombre = '$Nombre', KLV = '$Clave' WHERE IDUsuario = '$IDUsuario'";
		}
	  else
	    {
		$sql = "UPDATE usuarios SET CoreoPRE = '$CorreoPre', CoreoPOST = '$CorreoPost', Twitter = '$Twitter', Facebook = '$Facebook', Celular = '$Celular', Apodo = '$Apodo', Nombre = '$Nombre' WHERE IDUsuario = '$IDUsuario'";
		}
	  $modificacion = mysql_query($sql);
	  
  if ($modificacion)
    {
	$mensaje = "Los Cambios se han Registrado Correctamente ";
	$color_mensaje = "#00FF00";
    }
  else
    {
    $mensaje = "Error No. " . mysql_errno() . ": " . mysql_error();
	$cuerpo = $mensaje . "<br />Pagina: " . $_SERVER["SCRIPT_NAME"]. "<br />Ocurrido el dia: " . date("d-m-Y") . "<br />A las " . date("H:i:s");
	$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
	$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Cabeceras adicionales
	$cabeceras .= 'From: <webmaster@coinfo.mx>' . "\r\n";
	mail("mgarcia@coinfo.mx","Error en Admin Grupal", $cuerpo, $cabeceras);
	$color_mensaje = "#FF0000";
    }
	  
	  	  
  }
  
  
$sql = "SELECT * FROM usuarios WHERE IDUsuario = '$IDUsuario'";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);

$Correo = $renglon['CoreoPRE'] . "@" . $renglon['CoreoPOST'];
$Archivo = "perfil/" . $IDUsuario . ".jpg";
if (file_exists($Archivo))
  {
	  $foto_perfil = "<img src='".$Archivo."'  width='200' id='yourBtn' style='cursor:pointer;' onclick='getFile()'>";
  }
else
  {
	  $foto_perfil = "<img src='images/no-pic.png' width='200' id='yourBtn' style='cursor:pointer;' onclick='getFile()'>";
  }

if ($renglon['AlarmaQuiniela'] == "1")
  $chkAlarmaQuiniela = " checked = checked ";

if ($renglon['AlarmaPago'] == "1")
  $chkAlarmaPago = " checked = checked ";

if ($renglon['ReporteCierre'] == "1")
  $chkReporteCierre = " checked = checked ";

if ($renglon['ReporteJuego'] == "1")
  $chkReporteJuego = " checked = checked ";

if ($renglon['ReporteResultados'] == "1")
  $chkReporteResultados= " checked = checked ";

if ($renglon['Novedades'] == "1")
  $chkNovedades= " checked = checked ";

?>


<form  name="forma" action="perfil.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="session_id" value="<?php echo $SessionID;?>" />
<div style='height: 0px;width: 0px; overflow:hidden;'><input name="upfile" id="upfile" type="file" value="upload" onchange="readURL(this);"/></div>
<center>
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="tabla_principal"><tr height="30"><td align="center">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="listado">
  <tr height="30">
    <td align="center" bgcolor="#009900">
      <font color="#FFFFFF"><b>Mi Perfil</b></font>
    </td>
  </tr>
  <tr>
    <td align="center">
    <br /><br />
<table border="0" cellpadding="5" cellspacing="0"  bordercolor="#000000">
<?php
  if ($mensaje != "")
    {
	echo "
	      <tr>
		    <td colspan='3' align='center'>
			  <table border='1' cellpadding='10' cellspacing='0' width='90%' bordercolor='#000000'>
			    <tr><td bgcolor='$color_mensaje' align='center'><font color='#000000'><b>" . $mensaje . "</b></font></td></tr>
		      </table>
			</td>
		  </tr>
		 ";
	}
?>
  <tr>
    <td rowspan="19" align="center" valign="top">
      <?php echo $foto_perfil;?>
    </td>
    <td align="right" width="200" bgcolor="#FFFFFF"><b>Nombre</b></td>
    <td align="left" width="250" bgcolor="#FFFFFF"><input type="text" name="txtNombre"  value="<?php echo $renglon['Nombre'];?>" style="width:250px"/></td>    
  </tr>
  <tr>
    <td align="right" ><b>Apodo</b></td>
    <td align="left" ><input type="text" name="txtApodo"  value="<?php echo $renglon['Apodo'];?>" style="width:250px"/></td>    
  </tr>
  <tr>
    <td align="right" ><b>Correo Electronico</b></td>
    <td align="left" ><input type="text" name="txtUsuario"   value="<?php echo $Correo;?>"style="width:250px"/></td>    
  </tr>
  <tr>
    <td align="right" ><b>Twitter</b></td>
    <td align="left" >@ <input type="text" name="txtTwitter"   value="<?php echo $renglon['Twitter'];?>"style="width:230px" placeholder="ej. quinielaelgordo"/></td>    
  </tr>
  <tr>
    <td align="right" ><b>Facebook</b></td>
    <td align="left" >/ <input type="text" name="txtFacebook"   value="<?php echo $renglon['Facebook'];?>"style="width:230px" placeholder="ej. laquinieladelgordo"/></td>    
  </tr>
  <tr>
    <td align="right" ><b>Celular</b></td>
    <td align="left" ><input type="text" name="txtCelular"   value="<?php echo $renglon['Celular'];?>"style="width:250px"/></td>    
  </tr>
  <tr>
    <td align="right" ><b>Clave de Acceso</b></td>
    <td align="left" ><input type="password" name="txtClave1"  value="********" style="width:250px"/></td>    
  </tr>
  <tr>
    <td align="right" ><b>Confirma Clave de Acceso</b></td>
    <td align="left" ><input type="password" name="txtClave2"  value="********" style="width:250px"/></td>    
  </tr>
  <tr>
    <td align="right" ><b>Tu Codigo de Invitacion</b></td>
    <td align="left" ><input type="text" name="txtCodigoInvitacion"   value="<?php echo $renglon['IDUsuario'] . " - " . $renglon['TokenInvitacion'];?>"style="width:250px"/></td>    
  </tr>
  <tr>
    <td align="right" valign="top"><b>Facebook ID</b></td>
    <td align="left">
	  <input type="text" autocomplete="off" name="txtFacebookID" id="txtFacebookID" value="<?php echo $renglon['fbus3r1d'];?>" style="width:150px" readonly />
	  <?php
	  if ($renglon['fbus3r1d'] == "")
	    echo "<input type='button' value='Obtener ID' onclick='getFBID();'>";
	  
	  ?>
	  <br>
	  Recuerda que no guardamos tu clave de Facebook, solo tu ID para validar tu acceso.
    </td>
  </tr>
  <tr>
    <td></td>
    <td><div id="aux_fb"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" value="Guardar" onclick="valida_registro();" /></td>
  </tr>
</table>
<br /><br />
<table width="100%" cellpadding="5" cellspacing="0" border="0">
  <tr>
	<td width="100%" valign="top">
		<table width="100%" cellpadding="5" cellspacing="0">
		  <tr>
			<td bgcolor="#00CC33" align="center"><b><font size="+1">NOTIFICACIONES</font></b></td>    
		  </tr>
		  <tr>
			<td align="center">
			  <table cellpadding="10" cellspacing="0">
				<tr>
				  <td><input type="checkbox" name="chkNovedades" <?php echo $chkNovedades;?> onclick="activaCheck(this, '6')"/></td>
				  <td>Noticias / Novedades del Sistema</td>
				</tr>
				<tr>
				  <td><input type="checkbox" name="chkAlarmaQuiniela" <?php echo $chkAlarmaQuiniela;?> onclick="activaCheck(this, '1')"/></td>
				  <td>Recordarme si no he hecho mi pronóstico</td>
				</tr>
				<tr>
				  <td><input type="checkbox" name="chkAlarmaPago" <?php echo $chkAlarmaPago;?> onclick="activaCheck(this, '2')"/></td>
				  <td>Recordarme si no he realizado mi pago</td>
				</tr>
				<tr>
				  <td><input type="checkbox" name="chkReporteCierre" <?php echo $chkReporteCierre;?> onclick="activaCheck(this, '3')"/></td>
				  <td>Cierre de Jornada</td>
				</tr>
				<tr>
				  <td><input type="checkbox" name="chkReporteJuego" <?php echo $chkReporteJuego;?> onclick="activaCheck(this, '4')"/></td>
				  <td>Resultado de cada Juego de la Jornada</td>
				</tr>
				<tr>
				  <td><input type="checkbox" name="chkReporteResultados" <?php echo $chkReporteResultados;?> onclick="activaCheck(this, '5')"/></td>
				  <td>Resultado Final de la Jornada</td>
				</tr>
			  </table>
			  <br />
			  <div id="aux"></div>
			</td>
		  </tr>
		</table>	  
    </td>	
  </tr>
</table>


	</td>
  </tr>
</table>
<br /><br />
</td></tr></table>
<br /><br />
</center>
</form>

<script language="javascript">
    var http = false;

	//verificacion del navegador para el uso de AJAX
    if(navigator.appName == "Microsoft Internet Explorer") {
      http = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
      http = new XMLHttpRequest();
    }


function getFile()
{
    document.getElementById("upfile").click();
}
  
function valida_registro()
{
	if (document.forma.txtNombre.value == "")
	  alert("No has capturado tu Nombre");
	else
	if (document.forma.txtUsuario.value == "")
	  alert("No has capturado tu Correo Electronico");
	else
	  document.forma.submit();
	
}

function getFile()
{
    document.getElementById("upfile").click();
}

function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
				document.getElementById('yourBtn').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }


function activaCheck(check, identificador)
{
      http.abort();
	  cadena="perfil_configura.php?token=" + identificador + "@" + check.checked + "@<?php echo $IDUsuario;?>";
	  http.open("GET", cadena, true);
      http.onreadystatechange=function() 
	    {
        if(http.readyState == 4) 
		  {
			  a = "no hago nada";
			document.getElementById("aux").innerHTML =  http.responseText;
			  
		  }
        }
      http.send(null);
}


function getFBID()	
{
      http.abort();
	  cadena="fb-connect.php";
	  http.open("GET", cadena, true);
      http.onreadystatechange=function() 
	    {
        if(http.readyState == 4) 
		  {
          document.getElementById('aux_fb').innerHTML = http.responseText;
		  if (document.getElementById('txtFBLoginID').value != "")
		    document.getElementById('txtFacebookID').value = document.getElementById('txtFBLoginID').value;
		    
		  }
        }
      http.send(null);
}

</script>
