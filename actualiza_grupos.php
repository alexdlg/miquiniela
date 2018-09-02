<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");
require("genera_clave.php");
require("fechas.php");

$IDUsuario = $_SESSION['quiniela_matona_id'];
$IDLiga  = $_SESSION['quiniela_matona_idliga'];
$SessionID = $_GET['session_id'];

if ($_SESSION['quiniela_matona_admin'] != "1")
  {
	echo "<h1><font color='#FFFFFF'>COMING SOON</font></h1>";
	exit();
  }
?>

<div class="container">
    <div class="row">
        <div class="col-md-6" align="center" style="background-color:#000;color:#FFF">
          <h2>GRUPOS</h2>
          <?php
		    $sql_grupos = "SELECT * FROM grupos_social WHERE Modo = '0' AND IDLiga = '$IDLiga'";
			$consulta_grupos = mysql_query($sql_grupos);
			
			echo '<table border="0" cellpadding="4" cellspacing="0" width="100%" id="tabla-grupos">
				    <tr bgcolor="#666666" align="center">
					    <th>
					  Grupo
						</th>
					    <th>
					  Costo
						</th>
					    <th>
					  Miembros
						</th>
					    <th>
						</th>
				    </tr>';
			
			for ($g=0;$g<mysql_num_rows($consulta_grupos);$g++)
			  {
				$renglon_grupos = mysql_fetch_assoc($consulta_grupos);
				$sql_integrantes = "SELECT IDJugador FROM grupos_social_jugadores WHERE IDGrupo = '".$renglon_grupos['IDGrupo']."'";
				$consulta_integrantes = mysql_query($sql_integrantes);
				$miembros = mysql_num_rows($consulta_integrantes);

				$sql_integrantes = "SELECT IDJugador FROM grupos_social_jugadores WHERE IDGrupo = '".$renglon_grupos['IDGrupo']."' AND IDJugador = '$IDUsuario'";
				$consulta_integrantes = mysql_query($sql_integrantes);
				$basura1 = dameReferencia() . dameReferencia() . dameReferencia();
				$basura2 = dameReferencia() . dameReferencia() . dameReferencia();
				$basura3 = dameReferencia() . dameReferencia() . dameReferencia();
				$basura4 = dameReferencia() . dameReferencia() . dameReferencia();
				$basura5 = dameReferencia() . dameReferencia() . dameReferencia();
				$token_grupo = $basura1 . "~" . $basura3 . "~" . $renglon_grupos['IDGrupo'] . "~" . $basura2 . "~" . $basura3 . "~" . $IDLiga . "~" . $basura4 . "~" . $IDUsuario . "~" . $basura2;
				$hash_grupo = md5($token_grupo);
				
				if (mysql_num_rows($consulta_integrantes) == 0)
				  $boton_union = '<input type="button" value="Unirme" onclick="inscribe_publico(\''.$token_grupo.'\',\''.$hash_grupo.'\')">';
				else
				  $boton_union = "";

				if ($renglon_grupos['Costo'] == "0")
				  $Costo = "GRATIS";
				else
				  $Costo = "$ " . number_format($renglon_grupos['Costo'],2);
				  
				if ($trcolor == "#FFFFFF")
				  $trcolor = "#EAEAEA";
				else
				  $trcolor = "#FFFFFF";
				echo '<tr bgcolor="'.$trcolor.'">
					    <td align="left">
						  '.$renglon_grupos['NombreGrupo'].'
						</td>
					    <td align="right">
						  '.$Costo.'
						</td>
					    <td align="center">
						  '.$miembros.'
						</td>
					    <td align="center">
						  '.$boton_union.'
						</td>
					  </tr>';
				
			  }
			  echo "</table>";
		  ?>
        </div>
        <div class="col-md-6" align="center" style="background-color:#000;color:#FFF;">
          <h2>MIS GRUPOS</h2>
          <?php
		    $sql_grupos = "SELECT * FROM grupos_social as gs, grupos_social_jugadores as j 
						   WHERE 
						     gs.IDLiga = '$IDLiga' AND
							 gs.IDGrupo = j.IDGrupo AND
							 j.IDJugador = '$IDUsuario'							 
							 ";
			$consulta_grupos = mysql_query($sql_grupos);
			
			echo '<table border="0" cellpadding="4" cellspacing="0" width="100%" id="tabla-grupos">
				    <tr bgcolor="#666666" align="center">
					  <td colspan="4">
					    <table width="100%" cellpadding="5" cellspacing="0">
						  <tr align="center">
						    <td>
							  CREAR GRUPO
							</td>
							<td>
							  UNIRSE A GRUPO
							</td>
						  </tr>
						</table>
					  </td>
					</tr>
				    <tr bgcolor="#666666" align="center">
					    <th>
					  Grupo
						</th>
					    <th>
					  Costo
						</th>
					    <th>
					  Miembros
						</th>
					    <th>
						</th>
				    </tr>';
			
			for ($g=0;$g<mysql_num_rows($consulta_grupos);$g++)
			  {
				$renglon_grupos = mysql_fetch_assoc($consulta_grupos);
				
				if ($trcolor == "#FFFFFF")
				  $trcolor = "#EAEAEA";
				else
				  $trcolor = "#FFFFFF";
				echo '<tr bgcolor="'.$trcolor.'">
					    <td align="left">
						  '.$renglon_grupos['NombreGrupo'].'
						</td>
					    <td align="right">
						  '.$Costo.'
						</td>
					    <td align="center">
						  '.$miembros.'
						</td>
					    <td align="center">
						  '.$boton_union.'
						</td>
					  </tr>';
			  }
			echo "</table>";
			?>
        </div>
    </div>
</div>
