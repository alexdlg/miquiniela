<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
require("include/init.php");
require("fechas.php");
require("genera_clave.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
  {
	
	$IDSurvivor = $_POST['txtIDSurvivor'];
	$IDUsuario = $_SESSION['quiniela_matona_id'];
	$IDJornada = $_POST['txtIDJornada'];
	$IDEquipo = $_POST['txtIDEquipoSeleccionado'];
	
	  
	$sql = "SELECT IDEquipo FROM survivor_detalle WHERE IDUsuario = '$IDUsuario' AND IDSurvivor = '$IDSurvivor' AND IDJornada = '$IDJornada'";
	$consulta = mysql_query($sql);
	if (mysql_num_rows($consulta) == 1)
	  {
		$sql_survivor = "UPDATE survivor_detalle SET IDEquipo = '$IDEquipo'  WHERE IDUsuario = '$IDUsuario' AND IDJornada = '$IDJornada'";
		$actualiza = mysql_query($sql_survivor);
	  }
	else
	  {
		$hoy = date("Y-m-d H:i:s");
		$sql_survivor = "INSERT INTO survivor_detalle (IDEquipo, IDUsuario, IDJornada, IDSurvivor, SelloFecha) VALUES ('$IDEquipo','$IDUsuario','$IDJornada','$IDSurvivor','$hoy')";
		$actualiza = mysql_query($sql_survivor);
	  }
	  
  }
  
  
  
	//jornadas de la 142 a la 150 para pruebas
	$IDJornada = 144;
	$IDSurvivor = 1;
	$IDLiga  = $_SESSION['quiniela_matona_idliga'];
	
	$IDUsuario = $_SESSION['quiniela_matona_id'];
	$MisElegidos[] = "";
	
	$sql = "SELECT IDEquipo FROM survivor_detalle WHERE IDUsuario = '$IDUsuario' AND IDSurvivor = '$IDSurvivor' AND NOT IDJornada = '$IDJornada'";
	$consulta = mysql_query($sql);
	for ($i=0;$i<mysql_num_rows($consulta);$i++)
	  {
		$renglon = mysql_fetch_assoc($consulta);
		$MisElegidos[] = $renglon['IDEquipo'];
	  }
	
	
	
	$sql = "SELECT s.IDEquipo, e.Equipo
		    FROM 
			  survivor_detalle s, equipos as e
			WHERE 
			  s.IDEquipo = e.IDEquipo AND
			  s.IDUsuario = '$IDUsuario' AND 
			  s.IDSurvivor = '$IDSurvivor' AND 
			  s.IDJornada = '$IDJornada'
			ORDER BY s.IDJornada desc
			  ";
	$consulta = mysql_query($sql);
	if (mysql_num_rows($consulta) == 1)
	  {
		$renglon = mysql_fetch_assoc($consulta);
		$IDEquipoActual = $renglon['IDEquipo'];	
		$EquipoActual = $renglon['Equipo'];	
		
	  }
	else
	  {
		$IDEquipoActual = 0;
		$EquipoActual = "";
	  }
	  
	
	if ($IDSurvivor == "1")
	  {
		$Titulo = "SURVIVOR EXTREME";	
	  }
	else
	  {
		$Titulo = "SURVIVOR";
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
					  t.Status > '-1' AND
					  j.IDJornada = '$IDJornada' AND
					  l.IDLiga = '$IDLiga'
			";
	$consulta_jornada = mysql_query($sql_jornada);
	$renglon_jornada = mysql_fetch_assoc($consulta_jornada);

	if ($IDSurvivor == 1)
	  {
		$sql_conferencia = "SELECT *
							FROM 
							  survivor_detalle s, equipos as e
							WHERE 
							  s.IDEquipo = e.IDEquipo AND
							  s.IDUsuario = '$IDUsuario' AND 
							  s.IDSurvivor = '$IDSurvivor' 
							ORDER BY IDJornada desc ";		
		echo $sql_conferencia;
		$consulta_conferencia = mysql_query($sql_conferencia);
		$renglon_conferencia = mysql_fetch_assoc($consulta_conferencia);
		
		
		if ($renglon_conferencia['Conferencia'] == "1")
		  {
		    $clickeo1 = "off"; 
			$clickeo2 = "ok"; 
		  }
		else
		if ($renglon_conferencia['Conferencia'] == "2")
		  {
		    $clickeo1 = "ok"; 
		    $clickeo2 = "off"; 
		  }
		else
		  {
		    $clickeo1 = "off"; 
		    $clickeo2 = "off"; 
		  }
		  
	  }



?>
<style>

.survivor
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:16px;
}

.survivor th
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:18px;
	color:#FFF;
}

img 
{
	width:80%;
	cursor:pointer;
	border:0;
}

.bloqueado {
    opacity: 0.2;
    filter: alpha(opacity=20); /* For IE8 and earlier */
}


</style>

<br>
<form name="forma" action="survivor.php" method="post">
<input type="hidden" id="txtIDEquipoSeleccionado" name="txtIDEquipoSeleccionado" value="<?php echo $IDEquipoActual;?>" />
<input type="hidden" id="txtIDJornada" name="txtIDJornada" value="<?php echo $IDJornada;?>" />
<input type="hidden" id="txtIDSurvivor" name="txtIDSurvivor" value="<?php echo $IDSurvivor;?>" />

<table border="0" width="100%" cellpadding="5" cellspacing="0" class="survivor" bgcolor="#FFFFFF">
  <tr>
    <td colspan="5" bgcolor="#000000" align="center">
      <b><font size="+2" color="#FFFFFF"><?php echo $Titulo;?></font></b>
    </td>
  </tr>
  <tr>
    <td colspan="5" bgcolor="#444444" align="center">
      <font size="+1" color="#FFFFFF">Semana <?php echo $renglon_jornada['Jornada'];?></font>
    </td>
  </tr>
  <tr><td colspan="2"><img src="images/spacer.gif" height="1" /></td></tr>
  <tr align="center" bgcolor="#000000">
    <th width="3%" rowspan="2" bgcolor="#FFFFFF"></th>
    <th width="45%">Conferencia Nacional</th>
    <th width="4%" rowspan="2" bgcolor="#FFFFFF"></th>
    <th width="45%">Conferencia Americana</th>
    <th width="3%" rowspan="2" bgcolor="#FFFFFF"></th>
  </tr>
  <tr>
    <td align="center" style="border-color:#000; border-style:solid; border-width:thin;">
	  <table border="0" width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000">        
        <tr>
		  <?php
          $sql = "SELECT * FROM equipos WHERE Conferencia = '2' ORDER BY Equipo asc";
          $consulta = mysql_query($sql);
          for ($i=0;$i<mysql_num_rows($consulta);$i++)
            {
              $renglon = mysql_fetch_assoc($consulta);
			  $basura1 = dameReferencia().dameReferencia().dameReferencia();
			  $basura2 = dameReferencia().dameReferencia().dameReferencia();
			  $basura3 = dameReferencia().dameReferencia().dameReferencia();
			  $basura4 = dameReferencia().dameReferencia().dameReferencia();
			  $basura5 = dameReferencia().dameReferencia().dameReferencia();
			  $TokenEquipo = $basura1 . $basura2 . "~" . $renglon['IDEquipo'] . "~" . $basura3 . $basura4;
              if ($i % 4 == 0)
                echo "</tr><tr>";
				
			  if ($renglon['IDEquipo'] == $IDEquipoActual)
			    $EstiloTD = " class='bloqueado' ";
              else
			    $EstiloTD = "";
              
			  if ($clickeo2 == "ok")
			    $accion2 = " onclick='alert(this.title)' class='bloqueado' title='No puedes elegir equipo de la conferencia Nacional en esta jornada' ";
			  else
			    if ($IDEquipoActual == 0)
				  $accion2 = " onclick=\"selecciona_survivor('".$renglon['IDEquipo']."')\" ";
				else
				  $accion2 = " onclick='alert(this.title)' class='bloqueado' title='Ya seleccionaste a " . $EquipoActual. " para esta jornada' ";
			  
			  if ($renglon['StatusSurvivor'] == "0")
                echo "<td width='25%' align='center'>
					    <img src='images/escudos/".$renglon['IDEquipo'].".png' onclick='alert(this.title)' class='bloqueado' title='Rankeado Top 4 NFL - No se puede elegir'>
					  </td>";
			  else
			    if (in_array($renglon['IDEquipo'], $MisElegidos))
                  echo "<td width='25%' align='center'>".$renglon['IDEquipo']."<img src='images/escudos/".$renglon['IDEquipo'].".png' onclick='alert(this.title)' class='bloqueado' title='Ya has elegido a este equipo previamente.'></td>";
				else				  
	              echo "<td width='25%' align='center' id ='tdEquipo".$renglon['IDEquipo']."'>
				   	      ".$renglon['IDEquipo']."<img src='images/escudos/".$renglon['IDEquipo'].".png' $EstiloTD $accion2>
						</td>";
			  
            }
          ?>
        </tr>
      </table>
    </td>
    <td align="center" style="border-color:#000; border-style:solid; border-width:thin;">
	  <table border="0" width="100%" cellpadding="0" cellspacing="0">        
        <tr>
		  <?php
          $sql = "SELECT * FROM equipos WHERE Conferencia = '1' ORDER BY Equipo asc";
          $consulta = mysql_query($sql);
          for ($i=0;$i<mysql_num_rows($consulta);$i++)
            {
              $renglon = mysql_fetch_assoc($consulta);
			  			  
			  $basura1 = dameReferencia().dameReferencia().dameReferencia();
			  $basura2 = dameReferencia().dameReferencia().dameReferencia();
			  $basura3 = dameReferencia().dameReferencia().dameReferencia();
			  $basura4 = dameReferencia().dameReferencia().dameReferencia();
			  $basura5 = dameReferencia().dameReferencia().dameReferencia();
			  $TokenEquipo = $basura1 . $basura2 . "~" . $renglon['IDEquipo'] . "~" . $basura3 . $basura4;
              if ($i % 4 == 0)
                echo "</tr><tr>";
				
			  if ($renglon['IDEquipo'] == $IDEquipoActual)
			    $EstiloTD = " class='bloqueado' ";
              else
			    $EstiloTD = "";
              
			  if ($clickeo1 == "ok")
			    $accion1 = " onclick='alert(this.title)' class='bloqueado' title='No puedes elegir equipo de la conferencia Americana en esta jornada' ";
			  else
			    if ($IDEquipoActual == 0)
				  $accion1 = " onclick=\"selecciona_survivor('".$renglon['IDEquipo']."')\" ";
				else
				  $accion1 = " onclick='alert(this.title)' class='bloqueado' title='Ya seleccionaste a " . $EquipoActual. " para esta jornada' ";
			  
			  if ($renglon['StatusSurvivor'] == "0")
                echo "<td width='25%' align='center'>
					    <img src='images/escudos/".$renglon['IDEquipo'].".png' onclick='alert(this.title)' class='bloqueado' title='Rankeado Top 4 NFL - No se puede elegir'>
					  </td>";
			  else
			    if (in_array($renglon['IDEquipo'], $MisElegidos))
                  echo "<td width='25%' align='center'>".$renglon['IDEquipo']."<img src='images/escudos/".$renglon['IDEquipo'].".png' onclick='alert(this.title)' class='bloqueado' title='Ya has elegido a este equipo previamente.'></td>";
				else				  
	              echo "<td width='25%' align='center' id ='tdEquipo".$renglon['IDEquipo']."'>
				   	      ".$renglon['IDEquipo']."<img src='images/escudos/".$renglon['IDEquipo'].".png' $EstiloTD $accion1>
						</td>";
            }
          ?>
        </tr>        
      </table>
    </td>
  </tr>
  <?php 
  if ($IDEquipoActual == 0)
    {
  ?>
  <tr>
    <td colspan="5" align="center" id="tdEquipo0">
      <img src="images/boton-salvar-nfl.png" style="cursor:pointer;width:170px;" onclick="confirma_survivor();" />
    </td>
  </tr>
  <?php
	}
  else
    {
  ?>
  <tr><td colspan="2"><img src="images/spacer.gif" height="1" /></td></tr>
  <tr>
    <td colspan="5" align="center" bgcolor="#00CC33">
      <font color="#FFFFFFF"><b>Tu equipo para esta Jornada es <?php echo $EquipoActual;?></b></font>
    </td>
  </tr>
  <?php
	}
  ?>	  
</table>
</form>
<div id="aux"></div>

<script language="javascript">
function selecciona_survivor(valor)
{
  if (valor > 0)
    {
		var el_td = "tdEquipo" + document.getElementById('txtIDEquipoSeleccionado').value;
		document.getElementById(el_td).style.borderStyle = "none";
		document.getElementById('txtIDEquipoSeleccionado').value = valor;
		el_td = "tdEquipo" + valor;
		document.getElementById(el_td).style.borderStyle = "solid";
		document.getElementById(el_td).style.borderColor = "#0C3";
		document.getElementById(el_td).style.borderWidth = "thick";	
	}
}

function confirma_survivor()
  {
	  if (eval(document.getElementById('txtIDEquipoSeleccionado').value) > 0)
	    document.forma.submit();
  }
  
selecciona_survivor('<?php echo $IDEquipoActual;?>')
</script>
