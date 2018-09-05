<?php
header("Content-Type: text/html; charset=iso-8859-1"); 
require("include/init.php");
require("fechas.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>La Quiniela del Gordo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">

<?php
$token = explode(".", $_GET['token']);

$IDJuego = $token[0];
$IDGridToken = $token[1];


$sql = "SELECT * FROM juegos_grid WHERE IDJuego = '$IDJuego'";
echo $sql;
$consulta = mysql_query($sql);

if (mysql_num_rows($consulta) == 1)
{
	$renglon = mysql_fetch_assoc($consulta);
	$IDGrid = $renglon['IDGrid'];
	if ($IDGrid != $IDGridToken)
	  {
	    echo "Rascadito no encontrado";
		exit();
	  }
	  
	$sql_detalle = "SELECT * FROM juegos_grid_detalle WHERE IDGrid = '$IDGrid' ORDER BY Posicion ASC";
	$detalle = mysql_query($sql_detalle);
	$Valores = explode(",", $renglon['Valores']);
	$Participantes = $renglon['Participantes'];
	if (mysql_num_rows($detalle) == 0)
	  {
		for ($i=0;$i<count($Valores);$i++)
		  for ($j=0;$j<count($Valores);$j++)
		    {
			  $Marcador = $i . " - " . $j;
			  $ArregloMarcadores[] = $Marcador;
			}
		
	    shuffle($ArregloMarcadores);
	    shuffle($ArregloMarcadores);
	    shuffle($ArregloMarcadores);
		
		$sql_insercion = "INSERT INTO juegos_grid_detalle (IDGrid, Posicion, Marcador, IDUsuario, Status) VALUES ";
		for ($i=0;$i<count($Valores);$i++)
		  {
		    for ($j=0;$j<count($Valores);$j++)
		      {
				$Valor = ($i * 5) + $j;
				$Marcador = $ArregloMarcadores[$Valor];
				$sql_insercion.= "('$IDGrid','$Valor','$Marcador','0','0'),";				
			  }
		  }
		$sql_insercion = substr($sql_insercion, 0, strlen($sql_insercion)-1);
		$insercion = mysql_query($sql_insercion);		
	  }
	  
	echo "<div id='grid'></div>";	
	$funcion_javascript = "despliega_grid('".$IDGrid."');";
}
else
if (mysql_num_rows($consulta) == 0)
  {
    echo "<br /><br />No hay Grids para este juego, quieres crear uno?";  
	exit();
  }
else
  {
    echo "<br /><br />Hay mas de 1 Grid para este juego, ¿cual quieres ver?";  
	exit();
  }
?>
<div id="aux_grid" align="center"></div>


</body>
<script language="javascript">
  var http = false;    

  if(navigator.appName == "Microsoft Internet Explorer") {
      http = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
      http = new XMLHttpRequest();
    }

    
    
function abre_marcador(casilla, grid, hash)
  {
      http.abort();
	  cadena="juegos-grid-marcador.php?token=" + casilla + "~" + grid + "~" + hash;
	  http.open("GET", cadena, true);
      http.onreadystatechange=function() 
	    {
        if(http.readyState == 4) 
		  {
			  document.getElementById('aux_grid').innerHTML = http.responseText;
			  despliega_grid(grid);
		  }
        }
      http.send(null);
	
  }
  
function despliega_grid(grid)
  {
      http.abort();
	  cadena="juegos-grid-despliega.php?token=" + grid;
	  http.open("GET", cadena, true);
      http.onreadystatechange=function() 
	    {
        if(http.readyState == 4) 
		  {
			  document.getElementById('grid').innerHTML = http.responseText;
		  }
        }
      http.send(null);
	
  }
  
	
<?php 
if ($funcion_javascript != "")
  echo $funcion_javascript;
  
?>  
</script>