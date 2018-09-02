<?php 
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");
require("fechas.php");

$IDJornada = $_GET['token'];
$IDUsuario = $_SESSION['quiniela_matona_id'];
$sql = "SELECT * FROM ligas as l, temporada as t, jornadas as j 
		     WHERE 
			   j.IDLiga = l.IDLiga AND 
			   t.IDLiga = l.IDLiga AND
			   j.IDTemporada = t.IDTemporada AND
			   j.IDJornada = '$IDJornada'";
$consulta = mysql_query($sql);
$renglon_jornada = mysql_fetch_assoc($consulta);

$hoy = date("Y-m-d");
$ya = date("H:i:s");
$ColumnaPuntos = $renglon_jornada['Puntaje'];

?>

<style>
.rol
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;
	
}
</style>

<table cellpadding="5" cellspacing="0" border="0" width="100%" bordercolor="#FFFFFF" class="rol">
  <tr>
    <td valign="top" align="center">
      <table cellpadding="5" cellspacing="0" border="1" width="100%" bordercolor="#FFFFFF" class="rol">
        <tr align="center" bgcolor="#000000">
          <th>Lugar</td>
          <th>Nombre</td>
          <th>Apodo</td>
          <th>Puntos</td>
          <th>Ver</td>
        </tr>      
      <?php
	  
	  $sql = "SELECT u.*, SUM(p.".$ColumnaPuntos.") as Acumulado 
	  		  FROM usuarios as u, pronosticos as p
			  WHERE 
			    u.IDUsuario = p.IDUsuario AND
			    IDJuego IN (SELECT IDJuego FROM juegos as j, jornadas as jor WHERE j.IDJornada = jor.IDJornada AND jor.IDLiga = '".$renglon_jornada['IDLiga']."' AND jor.IDTemporada = '".$renglon_jornada['IDTemporada']."')
			  GROUP BY 
			    p.IDUsuario 
			  ORDER BY PosicionFinal ASC, Acumulado DESC, Apodo ASC";
	  $consulta = mysql_query($sql);
	  $arreglo_usuarios = "";
	  $TotalQuinielas = 0;
	  $TotalPagos = 0;
	  for ($u=0;$u<mysql_num_rows($consulta);$u++)
	    {
			$renglon = mysql_fetch_assoc($consulta);			
			$arreglo_usuarios.= $renglon['IDUsuario'] . ", "; 
			$sql_p = "SELECT * FROM pronosticos WHERE IDUsuario = '".$renglon['IDUsuario']."' AND IDJuego IN (SELECT IDJuego FROM juegos WHERE IDJornada = '".$renglon_jornada['IDJornada']."')";
			$consulta_p = mysql_query($sql_p);
			if (mysql_num_rows($consulta_p) > 0)
			  {
			  $quiniela = "<img src='images/si.png'>";
			  $TotalQuinielas++;
			  }
			else
			  $quiniela = "<img src='images/no.png'>";

			$sql_pago = "SELECT * FROM pagos WHERE IDUsuario = '" . $renglon['IDUsuario'] . "' AND IDJornada = '" . $renglon_jornada['IDJornada'] . "'";
			$consulta_pago = mysql_query($sql_pago);
			if (mysql_num_rows($consulta_pago) == 0)
			  $pago = "<img src='images/no.png'>";
			else
			  {
				  $renglon_pago = mysql_fetch_assoc($consulta_pago);
				  if ($renglon_pago['StatusPago'] == "1")
				    {
				    $pago = "<img src='images/si.png'>";
					$TotalPagos++;
					}
				  else
				    $pago = "<img src='images/no.png'>";
			  }


			if ($trcolor == "#FFFFFF")
			  $trcolor = "#EAEAEA";
			else
			  $trcolor = "#FFFFFF";
			  
			$Archivo = "perfil/" . $renglon['IDUsuario'] . ".jpg";
			if (file_exists($Archivo))
			  $foto_perfil = "<a class='various' 
			  					 data-fancybox-type='iframe' 
								 href='perfil_usuario.php?token=".$renglon['IDUsuario']."'><img src='".$Archivo."'  
								 															    width='30' 
																								class='rounded-circle' 
																								style='cursor:pointer; border: 2px solid #444;' 
																								valign='middle'></a>";
			else
			  $foto_perfil = "<a class='various' 
			  					 data-fancybox-type='iframe' 
								 href='perfil_usuario.php?token=".$renglon['IDUsuario']."'><img src='images/no-pic.png' 
								 															 	width='30' 
																								class='rounded-circle'
																								style='cursor:pointer; border: 2px solid #444;' 
																								valign='middle'></a>";
						
			
			if ($renglon['IDUsuario'] == $IDUsuario)
			  $trcolor = "#FF9933";
			
			echo "<tr bgcolor='".$trcolor."'>
				    <td align='center'>&nbsp;". ($u + 1 )."</td>
				    <td >".$foto_perfil . "&nbsp;&nbsp;" . $renglon['Nombre']."</td>
				    <td>".$renglon['Apodo']."</td>
				    <td align='center'>".$renglon['Acumulado']."</td>
					<td align='center'><a class='various' data-fancybox-type='iframe' href='tabla_general_usuario.php?token=".$renglon['IDUsuario']."@".$IDJornada."'><img src='images/zoom.png'></a></td>
				  </tr>";
					
		}
		
	  $arreglo_usuarios.="0";	
	  
	
	  ?>   
	  
  <tr bgcolor="#000000">
    <td colspan="5" align="right">&nbsp;  
      
    </td>
  </tr>
      </table> 
    </td>
  </tr>  
</table>
<br /><br />


