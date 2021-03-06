<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");
require("genera_clave.php");
require("fechas.php");

$IDJornada = $_GET['token'];
$IDUsuario = $_SESSION['quiniela_matona_id'];
$IDLiga  = $_SESSION['quiniela_matona_idliga'];
$SessionID = $_GET['session_id'];

$Bono = $_GET['bono'];
if ($Bono == "-1")
  {
	$sql_bono = "SELECT * FROM pagos WHERE IDUsuario = '$IDUsuario' AND IDJornada = '$IDJornada'";
	$consulta_bono = mysql_query($sql_bono);
	$Bono = mysql_num_rows($consulta_bono);
  }

$sql_jornada = "SELECT *, j.Status as StatusJornada
				FROM
				  jornadas AS j,
				  temporada AS t,
				  ligas AS l
				WHERE 
				  j.IDLiga = l.IDLiga AND
				  j.IDTemporada = t.IDTemporada AND
				  j.IDLiga = t.IDLiga AND
				  t.Status = '1' AND
				  j.IDJornada = '$IDJornada' AND
				  l.IDLiga = '$IDLiga'
		";
$consulta_jornada = mysql_query($sql_jornada);
$renglon_jornada = mysql_fetch_assoc($consulta_jornada);

$IDTemporada = $renglon_jornada['IDTemporada'];

$LogoLiga = "<img src='images/".$renglon_jornada['Logo']."' height='60%'>";

$hoy = date("Y-m-d");
$ya = date("H:i:s");

$dias = dates_range($hoy, $renglon_jornada['FechaCierre']);
if ($dias > 0)
  $semaforo = "verde";
else
  if ($renglon_jornada['FechaCierre']  == $hoy)
    {
	  $tiempo = $renglon_jornada['HoraCierre'] - $ya;
	  if ($tiempo > 0)
	    $semaforo = "amarillo";
	  else
	    $semaforo = "rojo";
	}
  else
    $semaforo = "rojo";

$year = substr($renglon_jornada['FechaCierre'], 0,4);
$month = substr($renglon_jornada['FechaCierre'], 5,2);
$day = substr($renglon_jornada['FechaCierre'], 8,2);
$hour = substr($renglon_jornada['HoraCierre'], 0,2);
$minute = substr($renglon_jornada['HoraCierre'], 3,2);
$second = 0;

$TiempoLimite = mktime($hour, $minute, $second,$month,$day,$year);


$sql_saldo = "SELECT Saldo FROM usuarios WHERE IDUsuario = '$IDUsuario'";
$consulta_saldo = mysql_query($sql_saldo);
$renglon_saldo = mysql_fetch_assoc($consulta_saldo);
$mi_saldo = $renglon_saldo['Saldo'];

if ($renglon_jornada['Modo'] == "torneo")
  {
	$sql_pagos = "SELECT * FROM temporada_pagos WHERE IDTemporada = '".$IDTemporada."' AND IDUsuario = '$IDUsuario'";

	$consulta_pagos = mysql_query($sql_pagos);
	if (mysql_num_rows($consulta_pagos) == 1)
	  {
		
		$renglon_pagos = mysql_fetch_assoc($consulta_pagos);
	    if ($renglon_pagos['StatusPago'] == "1")
		  $mi_saldo = $mi_saldo + $renglon_jornada['Costo'];
						   
	  }
	  
  }
else
  {
	$sql_pagos = "SELECT * FROM pagos WHERE IDJornada = '".$IDJornada."' AND IDUsuario = '$IDUsuario'";
	$consulta_pagos = mysql_query($sql_pagos);
	if (mysql_num_rows($consulta_pagos) == 1)
	  {		
		$renglon_pagos = mysql_fetch_assoc($consulta_pagos);
	    if ($renglon_pagos['StatusPago'] == "1")
		  $mi_saldo = $mi_saldo + $renglon_jornada['Costo'];
		  
		if ($renglon_pagos['BonoExtra'] == "1")
		  {
			$chkBonoExtra = " checked = checked ";
			$TotalGolesBono = $renglon_pagos['GolesBonoExtra'];
			
		  }
		else
		  {
			$chkBonoExtra = "";
			$TotalGolesBono = "";
		  }
	  }
	else
	  $chkBonoExtra = "";
  }

if ($semaforo == "rojo")
  $chkBonoExtraSemaforo = " disabled = disabled ";
else
  $chkBonoExtraSemaforo = "";
  



$TokenBono = $SessionID . "~" . $IDUsuario . "~" . $IDJornada;
$TokenBonoHash = md5($TokenBono);
$TokenBonogol = $TokenBono . "&hash=" . $TokenBonoHash;


?>
...
<table id="tabla-pronostico" cellpadding="5" cellspacing="0" border="0" width="100%" bordercolor="#FFFFFF" class="rol" bgcolor="#AAAAAA">
  <tr>
    <td> 
      <table width="100%" >
        <tr>
          <td align="left">
            <div id="saldo_pronostico">
            </div>
          </td>
          <td valign="top" align="right" style="text-shadow:2px 2px 2px black; color:#FFF;">
            <?php
			
            if ($semaforo != "rojo")
              echo "<table><tr><td align='right'><font size='+1' color='#FF9900'>Jornada Inicia el </td></tr>
			  			   <tr><td align='right'>".dameFechaCompleta($renglon_jornada['FechaCierre'])."</td></tr>
			  			   <tr><td align='right'>a las ".$renglon_jornada['HoraCierre']." hrs.</td></tr>
				    </table>";
            else
			  {
			  if ($renglon_jornada['StatusJornada'] == "1")
                echo "<font size='+2' color='#00CC00'><b>Jornada en Curso</b></font><br />
						Recuerda que puedes hacer cambios en tus pronosticos <br />
						hasta la hora de inicio del mismo.
						<br /><br />
						<b> Suerte!!</b></font>";
			  else
                echo "<font size='+2' color='#CC0000'><b>Jornada FINALIZADA </b></font><br />";
			  
			  }
          
            ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td bgcolor="#999999">
      <form name="forma-pronosticos" id="forma-pronosticos" action="registra_pronostico.php" method="post">
	  <input type="hidden" name="session_id" id="session_id" value="" />
      <input type="hidden" name="Jornada" value="<?php echo $renglon_jornada['IDJornada'];?>" />
      <input type="hidden" name="IDLiga" value="<?php echo $IDLiga;?>" />
      <div id="div_mensaje" align="center"></div>
	  <table cellpadding="5" cellspacing="0" border="0" width="100%" bordercolor="#FFFFFF" class="rol">
            <?php 
		 $sql_timestamp = "SELECT * FROM log_mi_pronostico WHERE IDUsuario = '$IDUsuario'  AND IDJornada = '$IDJornada' ORDER BY Fecha desc, Hora desc"; 
		 $consulta_timestamp = mysql_query($sql_timestamp);
		 $renglon_timestamp = mysql_fetch_assoc($consulta_timestamp);
		 $TimeStamp = "Pronostico guardado <b>EXITOSAMENTE</b> el " . dameFechaCompleta($renglon_timestamp['Fecha']) . " - " . $renglon_timestamp['Hora'] . " hrs. desde " . $renglon_timestamp['IPOrigen'] ;
		 
		 if (mysql_num_rows($consulta_timestamp) > 0)
		   echo "<tr><td colspan='10' bgcolor='#99FF00' align='center'><font size='2'><i>$TimeStamp</i></font></td></tr>";		  
			?> 
        <tr>
          <td colspan="5" align="center">&nbsp;
            
          </td>
		  <td align="right" colspan="5">
		    <div id="txt" align="right"></div>     
		  </td>
        </tr>
        <tr align="center" bgcolor="#000000">
          <th>Fecha / Hora</th>
          <th colspan="3" width="150" align="right">Local</th>
          <th >&nbsp;</th>
          <th colspan="3" width="150" align="left">Visitante</th>
          <th width="50" colspan="2">Puntos Obtenidos</th>
        </tr>
        <?php
		$sql = "SELECT * , L.Equipo as Local, V.Equipo as Visitante
				FROM juegos as j, equipos as L, equipos as V
		 		WHERE 
				  j.EquipoLocal = L.IDEquipo AND
				  j.EquipoVisitante = V.IDEquipo AND
				  j.IDJornada = '".$IDJornada."' 
				ORDER BY Fecha asc, Hora asc, IDJuego asc";
		$consulta = mysql_query($sql);
		$BonoExtra = 0;
		for ($j=0;$j<mysql_num_rows($consulta);$j++)
		  {
			  $renglon_juego = mysql_fetch_assoc($consulta);
				
			  $tokenJuego = $renglon_juego['IDJuego'] . "@" . $renglon_jornada['IDJornada'];
			  
			  $BotonHistorico = "<a class='various' data-fancybox-type='iframe' href='juegos-admin-historico-listado.php?token=".$tokenJuego."'><img src='images/history.png' width='32' title='Ver Historico de Enfrentamientos'></a>";
			   
			  $sql_p = "SELECT * FROM pronosticos WHERE IDUsuario = '$IDUsuario' AND IDJuego = '" . $renglon_juego['IDJuego'] . "'";
			  $consulta_p = mysql_query($sql_p);
			  if (mysql_num_rows($consulta_p) == 1)
			    {
					$renglon_p = mysql_fetch_assoc($consulta_p);
					$GFLP = $renglon_p['GFLocal'];
					$GFVP = $renglon_p['GFVisitante'];
					if ($renglon_juego['Capturado'] == "1")					
					  $PtsP = $renglon_p['Puntos'];
					else
					  $PtsP = "";
					
				}
			  else
			    {
					$GFLP = "";
					$GFVP = "";
					$PtsP = "";
				}
			  
				if ($trcolor == "#FFFFFF")
				  $trcolor = "#EAEAEA";
				else
				  $trcolor = "#FFFFFF";
				
				if ($renglon_juego['Fecha'] < $hoy)
				  $bloqueo_pronostico = " readonly = readonly";
				else
				  if ($renglon_juego['Fecha'] == $hoy)
				    {
					  if ($renglon_juego['Hora'] < $ya)
					    $bloqueo_pronostico = " readonly = readonly ";
					  else
					    $bloqueo_pronostico = "";
					}
				  else
				    $bloqueo_pronostico = "";
				  
				$StatusJuego = "";
				$EstiloJuego = "style='text-align:center;'";
				if ($renglon_juego['Capturado'] == "1")
				  {
				    $StatusJuego = " - <font color='#FF0000'><b>FINALIZADO</b></font>";
					$EstiloJuego = " style='background-color:#CCC;text-align:center;' ";
				  }
				else
				  if ($renglon_juego['Fecha'] == $hoy)
				    if ($renglon_juego['Hora'] < $ya)
				      {
						$StatusJuego = " - <font color='#00CC00'><b>EN CURSO</b></font>";
						$EstiloJuego = " style='background-color:#CCC;text-align:center;' ";
					  }

				$Basura1 = dameReferencia() . dameReferencia() . dameReferencia();
				$Basura2 = dameReferencia() . dameReferencia() . dameReferencia();
				$Basura3 = dameReferencia() . dameReferencia() . dameReferencia();
				$Basura4 = dameReferencia() . dameReferencia() . dameReferencia();

				$TokenAviso = $Basura3 .$Basura1 . "@" . $Basura2 .$Basura3 . "@18@" . $Basura1 . $Basura2 ;
	  
				if ($renglon_juego['Bonogol'] == "1")
				  $iconoEspecial = "<a class='notificaciones' id='bono_especial' data-fancybox-type='iframe' href='bono_especial.php?token=".$TokenBonogol."'><img src='images/gorra.png' width='40'></a>";
				else
				if ($renglon_juego['Rompequinielas'] == "1")
				  $iconoEspecial = "<a class='notificaciones' id='rompequinielas' data-fancybox-type='iframe' href='avisos_usuario.php?token=".$TokenAviso."'><img src='images/ico-x2.png' width='30'></a>";
				else
				  $iconoEspecial = "<img src='images/spacer.gif' width='30'>";
									  
				echo "<tr bgcolor='".$trcolor."'>
			  		    <td align='right'>
						  ".dameFechaCompleta($renglon_juego['Fecha'])."<br />
						  ".$renglon_juego['Hora']." hrs. 
						  $StatusJuego
						</td>
			  		    <td align='right'><a class='various' data-fancybox-type='iframe' href='estadisticas-equipo.php?token=" . $renglon_juego['EquipoLocal'] . "'>".$renglon_juego['Local']."</a></td>
			  		    <td align='right'><img src='images/escudos/".$renglon_juego['EquipoLocal'].".png' class='escudos'></td>
					    <td align='center'>
						  <input type='text' 
						  		 name='txtGFL-".$renglon_juego['IDJuego']."-".$renglon_juego['EquipoLocal']."' 
								 value='".$GFLP."' 
								 size='1' 
								 ".$bloqueo_pronostico." 
								 maxlength='2' 
								 onkeypress='return Decimal(event);'
								 autocomplete = 'off'
								 ".$EstiloJuego." 
								 
								 >
						</td>
					    <td align='center'>vs</td>
					    <td align='center'>
						  <input type='text' 
						  	     name='txtGFV-".$renglon_juego['IDJuego']."-".$renglon_juego['EquipoVisitante']."' 
								 value='".$GFVP."' 
								 size='1'  
								 ".$bloqueo_pronostico." 
								 maxlength='2' 
								 onkeypress='return Decimal(event);'
								 autocomplete = 'off'
								 ".$EstiloJuego." 
								 
								 >
						</td>
			  		    <td align='left'><img src='images/escudos/".$renglon_juego['EquipoVisitante'].".png' class='escudos'></td>
			  		    <td><a class='various' data-fancybox-type='iframe' href='estadisticas-equipo.php?token=" . $renglon_juego['EquipoVisitante'] . "'>".$renglon_juego['Visitante']."</a></td>
						<td align='center'><input type='text' readonly='readonly' name='txtPuntos-".$renglon_juego['IDJuego']."' value='$PtsP' size='1' style='background-color:#CCC; text-align:center;'></td>
						<td align='center' nowrap width='80'>
						  $BotonHistorico
						  $iconoEspecial
						</td>						
					  </tr>";
					  
					  
					  
		  } //esta llave cierra el for
		  
         $BotonGuardar = '<img src="images/boton-salvar.png" onclick="registra_pronosticos()" style="cursor:pointer;">';
		  
		      
      ?>  
        <tr>
          <td colspan="9" align="center">
            <?php 
			  if ($mi_saldo < 0)
			    {					
				  $mensaje_deudor = "<font color='#FF0000' size='+2'><b>I M P O R T A N T E</b></font><br /><br />Estimado " . $_SESSION['quiniela_matona_nombre'] . ": <br />Tienes un adeudo de <font color='#FF0000'> $ ". number_format($mi_saldo,2). " pesos </font>con LaQuinielaDelGordo.com, <br />lamentablemente, no puedes realizar tu pronostico hasta que no se regularice tu situacion, <br />por favor, enviale un mensaje a tu invitador para que puedan ponerse al corriente con los pagos <br />o revisar si se trata de un error. <br /><br />Gracias por tu comprension.<br /><br />";			
				  echo '<input type="hidden" id="Ejecucion" value="deuda" />
				        <input type="hidden" id="mensaje_sistema" value="'.$mensaje_deudor.'" />';				  
				}
			  else					
			    {
				  //echo "<input type='checkbox' id='chkRifa' name='chkRifaEspecial' value='si'> <font color='#FF0000' size='+2'> <b>Quiero Participar en la RIFA ESPECIAL A BENEFICIO: Playera Rayados Autografiada <a class='aviso_manual' id='el_aviso' data-fancybox-type='iframe' href=\"avisos_usuario.php?token=0@0@4\">(VER INFO)</a>";
			      echo "<br />" . $BotonGuardar;
				  echo '<input type="hidden" id="Ejecucion" value="timer" />';
				  echo '<input type="hidden" id="txtTiempoLimite" value="'.$TiempoLimite.'" />';
				  
				}
			?>
          </td>
        </tr>
        <tr>
          <td colspan="9" align="right">
			<?php
            if (($IDTemporada == 12) && ($Bono == "1"))
            {
            ?>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" background='images/fondo-amarillo.jpg' style='background-repeat:repeat-x; background-position:center;'>            
              <tr>
			    <td align="right" width="35%">
					    <font size="+2">
					    <input type="checkbox" <?php echo $chkBonoExtraSemaforo;?>  name="chkBono" id="chkBono" <?php echo $chkBonoExtra;?>  onclick="activa_bono(this);" />&nbsp;ACTIVAR                               
					    </font>
				</td>
                <td align="center" width="10%">
				  <img src="images/bono-extra.png" height="150%" border="0" hspace="20">
                </td>
                <td align="left" width="55%" nowrap="nowrap">
						<font size="+2">
					    Total de Goles Pronosticados: 
                        
					    <input type="text" 
                               name="txtTotalGolesBono" 
                               id="txtTotalGolesBono" 
                               value="<?php echo $TotalGolesBono;?>" 
                               size="2" 
                               <?php echo $chkBonoExtraSemaforo;?>
                               maxlength="3" 
                               onblur="actualizaGolesBono(this);"
                               onkeypress='return Decimal(event);' autocomplete = 'off' />
                        <a class='aviso_manual' id='el_aviso' data-fancybox-type='iframe' href="avisos_usuario.php?token=0@0@17"><img src='images/help.png' width="20" onclick="" style="cursor:pointer" /></a>
					    </font>
                </td>
              </tr>
            </table>
            <?php
			}
			else
			  echo "&nbsp;";
			?>
          </td>
        </tr>       
        <?php
		  $BonoGol = "&nbsp;";
		  $sql_bonogol = "SELECT * FROM pronosticos_bonogol WHERE IDUsuario = '$IDUsuario' AND IDJornada = '$IDJornada'";
		  $consulta_bonogol = mysql_query($sql_bonogol);
		  if (mysql_num_rows($consulta_bonogol) == 1)
			{
		      $renglon_bonogol = mysql_fetch_assoc($consulta_bonogol);
			  $BonoGol = "Tienes seleccionado a ";
			  $sql_jugador = "SELECT * FROM jugadores as j, equipos as e WHERE j.IDEquipo = e.IDEquipo AND IDJugador = '".$renglon_bonogol['IDJugador']."'";
			  $consulta_jugador = mysql_query($sql_jugador);
			  $renglon_jugador = mysql_fetch_assoc($consulta_jugador);
			  $BonoGol.= "<b>" . $renglon_jugador['Nombre'] . "</b> de <i>" . $renglon_jugador['Equipo'] . "</i>";
		?>
        <tr>
          <td colspan="9" align="center" bgcolor="orange"><font size="+2"><?php echo $BonoGol;?></font> <a class='notificaciones' id='bono_especial' data-fancybox-type='iframe' href="bono_especial.php?token=<?php echo $TokenBonogol;?>">cambiar</a>
          </td>
        </tr> 
        <?php
			}
		?>
      </table>
      </form>
    </td>
  </tr>
</table>
<br /><br />
</td></tr></table>
<br /><br />

