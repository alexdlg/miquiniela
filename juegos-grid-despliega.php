<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
require("include/init.php");
require("fechas.php");

$token = explode("@", $_GET['token']);

$IDGrid = $token[0];
$IDUsuario = $_SESSION['quiniela_matona_id'];

$sql_grid = "SELECT * FROM juegos_grid WHERE IDGrid = '$IDGrid'";
$consulta_grid = mysql_query($sql_grid);
if (mysql_num_rows($consulta_grid) == 1)
{
	$renglon_grid = mysql_fetch_assoc($consulta_grid);
	$IDGrid = $renglon_grid['IDGrid'];
	$Costo = $renglon_grid['Costo'];
	$sql_detalle = "SELECT * FROM juegos_grid_detalle WHERE IDGrid = '$IDGrid' ORDER BY Posicion ASC";
	$detalle = mysql_query($sql_detalle);
	$Valores = explode(",", $renglon_grid['Valores']);
	$Participantes = $renglon_grid['Participantes'];
	$IDJuego = $renglon_grid['IDJuego'];
	$sql = "SELECT *
			FROM juegos as j
			WHERE
			  j.IDJuego = '$IDJuego'";
	$consulta = mysql_query($sql);
	$renglon = mysql_fetch_assoc($consulta);

?>

<style>


body
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;
	background:url(images/background.png);
	 color:#FFF;
	
}
</style>


<table border="0" cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td width="50%" valign="top">
	  <table border="0" cellpadding="10" cellspacing="0" width="100%">
        <tr>
          <td align="center">
            <img src="images/logo-rascadito.png" width="280">
            <br><br>
           
          </td>          
        </tr>
        <tr>
          <td>
            <?php
			echo "
            <table cellpadding='5' cellspacing='0' border='0'>  
              <tr>
                <td align='center' colspan='2'>RASCADITO</td>
              </tr>
              <tr>
                <td align='center' colspan='2'>".$renglon_grid['Descripcion']."</td>
              </tr>
              <tr>
                <td width='50%' align='right'>Costo de Casilla:</td>
                <td width='50%' align='left'>$ ".number_format($renglon_grid['Costo'],2)."</td>
              </tr>
            </table>";
			?>
          </td>          
        </tr>
        <tr>
          <td>
          <?php
			$sql_saldo = "SELECT Saldo FROM usuarios WHERE IDUsuario = '$IDUsuario'";
			$consulta_saldo = mysql_query($sql_saldo);
			$renglon_saldo = mysql_fetch_assoc($consulta_saldo);
			$mi_saldo = $renglon_saldo['Saldo'];
			if ($mi_saldo <= 0)
			  $formato_saldo = "<b><font color='#FF0000'>";
			else
			  $formato_saldo = "";
			
		  ?>
				    <table border="0" cellpadding="2" cellspacing="0">
					  <tr>
					    <td rowspan="3">						 
						</td>
						<td align="center" colspan="2" rowspan="2">
						  <img src="images/saldo.png" border="0" hspace="5">
						</td>
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
          </td>          
        </tr>        
        <tr>
          <td>
            <?php 
			$sql_acumulado = "SELECT IDUsuario FROM juegos_grid_detalle WHERE IDGrid = '$IDGrid' AND Status = '1'";
			$consulta_acumulado = mysql_query($sql_acumulado);
			$Jugandose = mysql_num_rows($consulta_acumulado);
			echo  "Acumulado en juego: <b><font size='+2'>$ " . number_format($Jugandose * $Costo * 0.90, 2) . "</font></b>";
			?>
          </td>          
        </tr>
      </table>
    </td>
    <td width="50%" valign="top">
	  <table border="0" cellpadding="10" cellspacing="0" width="100%">
        <tr>
          <td>
          <?php
			$sql_historico = "
							  (SELECT j.IDJuego as FolioInutil, j.EquipoLocal as IDEquipoL, j.EquipoVisitante as IDEquipoV, j.Fecha, j.GolesLocal as GolesL, j.GolesVisitante as GolesV, l.IDEquipo as EquipoLocal, v.IDEquipo as EquipoVisitante, l.Equipo as ELocal, v.Equipo as EVisitante
							   FROM juegos as j, equipos as l, equipos as v 
							   WHERE 
								 j.EquipoLocal = '".$renglon['EquipoLocal']."' AND 
								 j.EquipoVisitante = '".$renglon['EquipoVisitante']."' AND
								 j.EquipoLocal = l.IDEquipo AND
								 j.EquipoVisitante = v.IDEquipo AND
								 j.Capturado = '0'
								 
							  ) 
							  UNION
							  (SELECT j.IDJuego as FolioInutil, j.EquipoLocal as IDEquipoL, j.EquipoVisitante as IDEquipoV, j.Fecha, j.GolesLocal as GolesL, j.GolesVisitante as GolesV, l.IDEquipo as EquipoLocal, v.IDEquipo as EquipoVisitante, l.Equipo as ELocal, v.Equipo as EVisitante
							   FROM juegos as j, equipos as l, equipos as v 
							   WHERE 
								 j.EquipoLocal = '".$renglon['EquipoVisitante']."' AND 
								 j.EquipoVisitante = '".$renglon['EquipoLocal']."' AND
								 j.EquipoLocal = l.IDEquipo AND
								 j.EquipoVisitante = v.IDEquipo AND
								 j.Capturado = '0'
							  ) 
							  Order By Fecha desc
							 ";
			$consulta_historica = mysql_query($sql_historico);	
			$renglon_historico = mysql_fetch_assoc($consulta_historica);
			
					echo "<table border='0' cellpadding='15' cellspacing='0' width='100%'>
							<tr>
							  <td align='right' width='40'><img src='images/bandera-mexico.png' width='70' ></td>
							  <td align='center' width='10'><img src='images/versus.png' width='50'></td>
							  <td align='left' width='40'><img src='images/bandera-suecia.png'  width='70'></td>
							</tr>
						  </table>
						  ";		  
		  ?>
          </td>
        </tr>
        <tr>
          <td>
          <?php		  
			echo "<table border='0' cellpadding='0' cellspacing='10' align='center'>";		
			for ($g=0;$g<mysql_num_rows($detalle);$g++)
			  { 
				$renglon_detalle = mysql_fetch_assoc($detalle);
				$ArregloMarcadores[] = $renglon_detalle['Marcador'];
				$PantallaMarcadores[] = $renglon_detalle['Status'];
				$JugadoresMarcadores[] = $renglon_detalle['IDUsuario'];
			  }
			
			$Jugandose = 0;
			for ($i=0;$i<count($Valores);$i++)
			  {
				echo "<tr height='50'>";		 
				for ($j=0;$j<count($Valores);$j++)
				  {
					$Valor = ($i * 5) + $j;
					$Marcador = $ArregloMarcadores[$Valor];
					$Pantalla = $PantallaMarcadores[$Valor];
					$Jugador = $JugadoresMarcadores[$Valor];
					if ($Jugador == $_SESSION['quiniela_matona_id'])
					  $color = "#CCCCCC";
					else
					  $color = "#000000";
											  
												
					$Hash = md5($Valor . "@" . $IDGrid);
					if ($Pantalla == "0")
					  {
						$TokenCompleto = $Valor."','".$IDGrid."','".$Hash;
						echo "<td align='center' width='80'>
						  	    <a href='juegos-grid-despliega-confirmacion.php?token=' class='confirma' data-fancybox-type='iframe'><img src='images/carta.png' width='75'></a>
						      </td>";
					  }
					else				
					  {
						echo "<td align='center' bgcolor='".$color."' width='80'>" . $Marcador . "</td>";
						$Jugandose++;
					  }
				  }
				echo "</tr>";
			  }
			echo "</table>";
			
		  ?>
          </td>
        </tr>
      </table>      
    </td>
  </tr>
</table>

<?php



	

		
		
		
}
?>