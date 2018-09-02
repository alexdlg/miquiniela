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



?>

<style>
  .estadisticas
  {
	  font-family:Verdana, Geneva, sans-serif;
	  font-size:16px;
  }
  
  .estadisticas th
    {
		font-weight:bold;
		color:#FFF
	}
	 
  .estadisticas a
  {
	  color:#000;
	  text-decoration:none;
  }
  
  .titulo
  {
	  font-family:Verdana, Geneva, sans-serif;
	  font-size:24px;
	  font-weight:bold;
	  color:#FFF;
  }
</style>
<table border="0" cellpadding="0" cellspacing="0" width="1000" align="center" class="tabla_principal"><tr height="30"><td align="center">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="listado">
<tr>
  <td align="center" colspan="2">  
    <table width="1000" cellpadding="10" cellspacing="0" border="0" align="center">
      <tr>
        <td align="center" valign="bottom" class="titulo" colspan="3">
		<?php
			// Esta limitada a Liga MX solamente (hay que agregar un parametro para pedir liga ID)
			$sql_nombre = "	SELECT CONCAT(l.Liga, ' ', t.Nombre) as Titulo
							FROM  ligas as l, temporada as t
							WHERE l.IDLiga = t.IDLiga
								AND t.Status = '1'
								AND l.IDLiga = '$IDLiga'";
			$consulta_nombre = mysql_query($sql_nombre);
			$renglon_nombre = mysql_fetch_assoc($consulta_nombre);
			$titulo = $renglon_nombre['Titulo'];
			echo 'Tabla General - ' .$titulo;
		?>
		
		
          <!--Tabla General - Liga MX Apertura 2016-->
        </td>
      </tr>      
      <tr>
        <td colspan="2">
          <table border="0" cellpadding="3" cellspacing="0" class="estadisticas" width="100%" bordercolor="#FFFFFF">
            <tr bgcolor="#000000" align="center">
              <th>Lugar</th>
              <th align="left">Equipo</th>
              <th>JJ</th>
              <th>JG</th>
              <th>JP</th>
              <th>JE</th>
              <th>GF</th>
              <th>GC</th>
              <th>Dif</th>
              <th>Pts</th>
            </tr>
            <?php
			  $sql = "SELECT * 
			  		  FROM 
					    equipos as e 
				      WHERE 
					    IDEquipo IN (
									  SELECT j.EquipoLocal FROM juegos as j, jornadas as jor, ligas as l, temporada as t WHERE 
										  j.IDJornada = jor.IDJornada AND
										  jor.IDLiga = l.IDLiga AND
										  jor.IDTemporada = t.IDTemporada AND
										  jor.IDLiga = t.IDLiga AND
										  t.Status = '1' AND
										  jor.Status = '1' AND
										  l.IDLiga = '$IDLiga'
									)
						OR
					    IDEquipo IN (
									  SELECT j.EquipoVisitante FROM juegos as j, jornadas as jor, ligas as l, temporada as t  WHERE  
										  j.IDJornada = jor.IDJornada AND
										  jor.IDLiga = l.IDLiga AND
										  jor.IDTemporada = t.IDTemporada AND
										  jor.IDLiga = t.IDLiga AND
										  t.Status = '1' AND
										  jor.Status = '1' AND
										  l.IDLiga = '$IDLiga'
								  )						
					  ORDER BY 
					    Equipo";
			  $consulta = mysql_query($sql);
			  $estadistica = "";

			  for ($e=0;$e<mysql_num_rows($consulta);$e++)
			    {
//-------------------------------------------------------------------------------------------------------------------------------------------
  $renglon = mysql_fetch_assoc($consulta);
  $sql_JJ = "	SELECT * 
				FROM juegos
				WHERE IDJornada IN (SELECT IDJornada FROM jornadas as jo, temporada as t WHERE t.IDTemporada = jo.IDTemporada AND t.Status = '1' AND jo.IDLiga = '$IDLiga' and jo.Estadisticable = '1' and jo.IDLiga = t.IDLiga)
				AND Capturado = '1' AND (EquipoLocal = '" . $renglon['IDEquipo'] . "' OR EquipoVisitante = '" . $renglon['IDEquipo'] . "')";
  $consulta_JJ = mysql_query($sql_JJ);
  $JJ = mysql_num_rows($consulta_JJ);
  
  
  $TGF = 0;  //total goles a favor
  $TGC = 0;  //total goles en contra
  $Pts = 0;
  $JG = 0;
  $JP = 0;
  $JE = 0;
  for($j=0;$j<$JJ;$j++)
    {
	$registro_juego = mysql_fetch_assoc($consulta_JJ);
	if ($registro_juego['EquipoLocal'] == $renglon['IDEquipo'])
	  {
	  $GF = $registro_juego['GolesLocal'];
	  $TGF = $TGF + $GF;
	  $GC = $registro_juego['GolesVisitante'];
	  $TGC = $TGC + $GC;
	  
	  if ($GF > $GC)
	    {
	    $JG++;
		$Pts = $Pts + 3;
		}
	  else
	    if ($GF < $GC)
	      $JP++;
		else
		  {
		  $JE++;
		  $Pts = $Pts + 1;
		  }	    
	  } //esta llave cierra el IF de que si es LOCAL
    else
	  {
	  $GF = $registro_juego['GolesVisitante'];
	  $TGF = $TGF + $GF;
	  $GC = $registro_juego['GolesLocal'];
	  $TGC = $TGC + $GC;
	  
	  if ($GF > $GC)
	    {
	    $JG++;
		$Pts = $Pts + 3;
		}
	  else
	    if ($GF < $GC)
	      $JP++;
		else
		  {
		  $JE++;
		  $Pts = $Pts + 1;
		  }	    
	  } //esta llave cierra el IF de que si es VISITANTE
	
	
	}
	

  $estadistica[] = array('IDEquipo' => $renglon['IDEquipo'],'Equipo' => $renglon['Equipo'],'JJ' => $JJ, 'JG' => $JG, 'JP' => $JP, 'JE' => $JE, 'TGF' => $TGF, 'TGC' => $TGC, 'Pts' => $Pts);		
}

foreach ($estadistica as $key => $row) 
  {
    $pts[$key]  = $row['Pts'];
    $gf[$key] = $row['TGF'];
    $gc[$key] = $row['TGC'];
    $dif[$key] = ($row['TGF'] - $row['TGC']);
  }

// Ordenar los datos con volumen descendiente, edicion ascendiente
// Agregar $datos como el último parámetro, para ordenar por la llave común
array_multisort($pts, SORT_DESC, $dif, SORT_DESC, $gf, SORT_DESC, $gc, SORT_ASC, $estadistica, $estadistica);

for ($e=0;$e<count($estadistica);$e++)
  {
	if ($trcolor == "#FFFFFF")
	  $trcolor = "#EAEAEA";
	else
	  $trcolor = "#FFFFFF";
  
  echo "<tr bgcolor='$trcolor' align='center'>
          <td>" . ($e + 1) . "</td>
		  <td align='left'>
		    <a class='various' data-fancybox-type='iframe' href='estadisticas-equipo.php?token=" . $estadistica[$e]['IDEquipo'] . "'><img src='images/escudos/".$estadistica[$e]['IDEquipo'].".png' width='32' hspace='5' valign='middle'>".$estadistica[$e]['Equipo']."</a>
		  </td>
		  <td>" . $estadistica[$e]['JJ'] . "</td>
          <td>" . $estadistica[$e]['JG'] . "</td>
          <td>" . $estadistica[$e]['JP'] . "</td>
          <td>" . $estadistica[$e]['JE'] . "</td>
          <td>" . $estadistica[$e]['TGF'] . "</td>
          <td>" . $estadistica[$e]['TGC'] . "</td>
          <td>" . ($estadistica[$e]['TGF'] - $estadistica[$e]['TGC']) . "</td>
          <td>" . $estadistica[$e]['Pts'] . "</td>
      </tr>"; 
	  
	  
    $SQL_ESPECIAL = "INSERT INTO equipos_stats(IDEquipo, Posicion,JJ,JG,JP,JE,GF,GC,DIF,PTS) VALUES ('".$estadistica[$e]['IDEquipo']."',
																						 			 '".($e + 1)."',
																						 			 '".$estadistica[$e]['JJ']."',
																						 			 '".$estadistica[$e]['JG']."',
																						 			 '".$estadistica[$e]['JP']."',
																						 			 '".$estadistica[$e]['JE']."',
																						 			 '".$estadistica[$e]['TGF']."',
																						 			 '".$estadistica[$e]['TGC']."',
																						 			 '".($estadistica[$e]['TGF'] - $estadistica[$e]['TGC'])."',
																						 			 '".$estadistica[$e]['Pts']."'
																									 );";
	//$insercion_especial = mysql_query($SQL_ESPECIAL);
  } 
  
  
//-------------------------------------------------------------------------------------------------------------------------------------------
			?>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
</table>
</td></tr></table>
