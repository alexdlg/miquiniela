<?php
session_start();
include("include/init.php");

$IDUsuario = $_SESSION['quiniela_matona_id'];


if ($_SERVER['REQUEST_METHOD'] == "POST")
{
	$IDUsuarioDe = $_SESSION['quiniela_matona_id'];
	$IDUsuarioPara = $_POST['dcUsuarioPara'];
	$Monto = $_POST['txtTransfer'];
	$Fecha = date("Y-m-d");
	$Hora = date ("H:i:s");
	
	$sql_transfer = "INSERT INTO tickets_transfers (Folio, Fecha, Hora, IDUsuarioDe, IDUsuarioPara, Monto) VALUES
													('','$Fecha','$Hora','$IDUsuarioDe','$IDUsuarioPara','$Monto');";
	$transfer = mysql_query($sql_transfer);
	
	if (mysql_affected_rows() == 1)
	  {
		  $sql_usuario_de = "UPDATE usuarios SET Saldo = (Saldo - $Monto) WHERE IDUsuario = '$IDUsuarioDe'";
		  $modificacion_usuario_de = mysql_query($sql_usuario_de);

		  $sql_usuario_para = "UPDATE usuarios SET Saldo = (Saldo + $Monto) WHERE IDUsuario = '$IDUsuarioPara'";
		  $modificacion_usuario_para = mysql_query($sql_usuario_para);
		  
		  echo "<br /><br /><center>Se transfirio correctamente el saldo";
		  

	  }
	
	
}


$sql = "SELECT * FROM usuarios WHERE IDUsuario = '$IDUsuario'";
$consulta = mysql_query($sql);
$renglon = mysql_fetch_assoc($consulta);

if ($renglon['Saldo'] <= 0)
  {
    $Saldo = "<font color='#FF0000'><b>$ " . number_format($renglon['Saldo'],2) . "</font>";
	$BotonTransfer = " disabled = disabled  title='No tienes saldo para transferir'";
  }
else
  {
    $Saldo = "<font color='#000000'><b>$ " . number_format($renglon['Saldo'],2) . "</font>";
	$BotonTransfer = "";
  }
  

$Archivo = "perfil/" . $IDUsuario . ".jpg";
if (file_exists($Archivo))
	  $foto_perfil = "<img src='".$Archivo."'  height='100'>";
else
	  $foto_perfil = "<img src='images/no-pic.png' height='100'>";

$Correo = $renglon['CoreoPRE'] . "@" . $renglon['CoreoPOST'];



?>
<style>
.tabla_principal
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;
	
}
</style>
<center>
<form name="FormaTransfer" action="transfer-pagos.php" method="post">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="tabla_principal"><tr height="30"><td align="center">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="listado">
  <tr height="30">
    <td align="center" bgcolor="#009900">
      <font color="#FFFFFF"><b>Transferencia de Saldo</b></font>
    </td>
  </tr>
  <tr>
    <td align="center">
	  <table border="0" cellpadding="5" cellspacing="0"  bordercolor="#000000" width="100%" bgcolor="#FFFFFF">
		<tr>
			<td rowspan="3" align="left" valign="top">
			<?php echo $foto_perfil;?>
			</td>
			<td align="right" width="100"><b>Nombre</b></td>
			<td align="left" width="550"><?php echo $renglon['Nombre'];?></td>    
		</tr>
	    <tr>
		  <td align="right" ><b>Apodo</b></td>
		  <td align="left" ><?php echo $renglon['Apodo'];?></td>    
	    </tr>
		<tr>
		  <td>&nbsp;</td>
		</tr>
	    <tr>
		  <td colspan='3'>          
            <table cellpadding='5' cellspacing='0' width='100%' border='0'>
		      <tr>
			    <td width="50%" align="right">Mi Saldo Actual:</td>		
                <td width="50%" ><?php echo $Saldo;?></td>		
			  </tr>
			  <tr>
                <td align="right">
                  Transferir A:
                </td>
		        <td align='left'>
                    <select id="dcUsuarioPara" name="dcUsuarioPara" size="1" onchange="revisa_saldo(this.value);">
                      <option value="-">Selecciona una Opcion</option>
                      <option value="-">--------------------</option>    
                      <optgroup label="Mis Invitados">          
                      <?php                      
                      $sql_admins = "SELECT * FROM usuarios WHERE InvitadoPor = '$IDUsuario' AND Status = '1' ORDER BY Nombre asc, Apodo asc";
                      $consulta_admins = mysql_query($sql_admins);
                      for ($a=0;$a<mysql_num_rows($consulta_admins);$a++)
                        {
                            $renglon_admins = mysql_fetch_assoc($consulta_admins);
                            echo "<option value='".$renglon_admins['IDUsuario']."'>".$renglon_admins['Nombre'] . " (" . $renglon_admins['Apodo'].") </option>";
                        }
					  ?>
                      </optgroup>
                      <optgroup label="Otros Participantes">          
                      <?php                      
                      $sql_admins = "SELECT * FROM usuarios WHERE NOT InvitadoPor = '$IDUsuario' AND Status = '1' ORDER BY Nombre asc, Apodo asc";
                      $consulta_admins = mysql_query($sql_admins);
                      for ($a=0;$a<mysql_num_rows($consulta_admins);$a++)
                        {
                            $renglon_admins = mysql_fetch_assoc($consulta_admins);
                            echo "<option value='".$renglon_admins['IDUsuario']."'>".$renglon_admins['Nombre'] . " (" . $renglon_admins['Apodo'].") </option>";
                        }
					  ?>
                      </optgroup>
                      
                    </select>
				</td>
              </tr>
              <tr>
                <td align="right">
                  Su Saldo Actual: 
                </td>
                <td><div id="saldo"></div></td>
              </tr>
              <tr>
                <td align="right">Monto a Transferir:</td>
		        <td><input type='text' name='txtTransfer' id='txtTransfer' value=''></td>
              </tr>
              <tr>
				<td colspan="2" align="center"><input type='button' value='Transferir' onclick='valida_transfer();' <?php echo $BotoTransfer;?>></td>
			  </tr>
	  		</table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</td></tr></table>
</form>
