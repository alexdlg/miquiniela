<?php
session_start();
include("include/init.php");
$SessionID = $_REQUEST['session_id'];
$SessionURL = "session_id=" . $SessionID;
$currentFile = $_SERVER['SCRIPT_NAME'];

$parts = Explode('/', $currentFile); 
$currentFile = $parts[count($parts) - 1]; 
include("keyholder.php");

$IDUsuario = $_SESSION['quiniela_matona_id'];


$Archivo = "perfil/" . $IDUsuario . ".jpg";
if (file_exists($Archivo))
  $foto_perfil = "<a class='various' data-fancybox-type='iframe' href='perfil.php?token=".$renglon['IDUsuario']."'><img src='".$Archivo."' class='rounded-circle' width='64'></a>";
else
  $foto_perfil = "<a class='various' data-fancybox-type='iframe' href='perfil.php?token=".$renglon['IDUsuario']."'><img src='images/no-pic.png' class='rounded-circle'  width='64'></a>";


$sql_saldo = "SELECT Saldo, Sexo FROM usuarios WHERE IDUsuario = '$IDUsuario'";
$consulta_saldo = mysql_query($sql_saldo);
$renglon_saldo = mysql_fetch_assoc($consulta_saldo);

if ($_SESSION['quiniela_matona_sexo'] == "")
  {
	if ($renglon_saldo['Sexo'] == "m")
	  $_SESSION['quiniela_matona_sexo'] = "a";
	else
	  $_SESSION['quiniela_matona_sexo'] = "o";	  
  }
											
$mi_saldo = $renglon_saldo['Saldo'];
if ($mi_saldo <= 0)
  $formato_saldo = "<font color='#FF0000'>";
else
  $formato_saldo = "<font color='#009900'>";

$IDLiga  = $_SESSION['quiniela_matona_idliga'];
if ($IDLiga == "")
  {
    $IDLiga = 1;
	$_SESSION['quiniela_matona_idliga'] = 1;	
  }
  
switch ($IDLiga)
{
	case "1": 
	  {
		$LogoMX = "images/liga-mx-on.png";
		$LogoChampions = "images/liga-champ-off.png";
		$LogoNFL = "images/liga-nfl-off.png";
		$fondoLQDG = "images/background-mx.png";		
		$pronostico = "2";  //seccion = 2 es que me lleve a mi pronostico
		$Seccion = '2';
		
	  } break;
	case "2": 
	  {
		$LogoMX = "images/liga-mx-off.png";
		$LogoChampions = "images/liga-champ-on.png";
		$LogoNFL = "images/liga-nfl-off.png";
		$fondoLQDG = "images/background-champions.png";		
		$pronostico = "2";  //seccion = 2 es que me lleve a mi pronostico
		$Seccion = '2';
	  } break;
	case "8": 
	  {
		$LogoMX = "images/liga-mx-off.png";
		$LogoChampions = "images/liga-champ-off.png";
		$LogoNFL = "images/liga-nfl-on.png";
		$fondoLQDG = "images/background-mundial.png";		
		$pronostico = "2.1";  //seccion = 2 es que me lleve a mi pronostico
		$Seccion = '2.1';
	  } break;
}


$sql = "SELECT * 
		FROM
		  jornadas AS j,
		  temporada AS t,
		  ligas AS l
		WHERE 
		  j.IDLiga = l.IDLiga AND
		  j.IDTemporada = t.IDTemporada AND
		  j.IDLiga = t.IDLiga AND
		  t.Status = '1' AND
		  j.Status = '1' AND
		  l.IDLiga = '$IDLiga'
		";
$consulta_jornada = mysql_query($sql);
$renglon_jornada = mysql_fetch_assoc($consulta_jornada);
$IDJornada = $renglon_jornada['IDJornada'];
$Modo = $renglon_jornada['Modo'];

$url = $_GET['url'];


 
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>La Quiniela del Gordo</title>
<!-- Required meta tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
<link rel="shortcut icon" href="favicon.ico">
</head>

<style>
body
{
	font-family:Verdana, Geneva, sans-serif;
	background: url(<?php echo $fondoLQDG;?>) no-repeat center center fixed; 
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}

</style>


<!-- AQUI VIENE LO DEL FANCYBOX PARA EL TUTORIAL ------------------------------------------------------------------>
<!-- Add jQuery library -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script src="js/cquery.min.js?ver=201808281"></script>
<link type="text/css" rel="stylesheet" href="css/estilos.css" />

<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.5&v2=20170925_1" type="text/css" media="screen" />
<script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>


<script language="javascript">
$(document).ready(function() {
	$(".detalle").fancybox({
		maxWidth	: 600,
		maxHeight	: 400,
		fitToView	: false,
		width		: '90%',
		height		: '90%',
		autoSize	: false,
		topRatio    : 0,
		closeClick	: true,
		openEffect	: 'none',
		closeEffect	: 'none',
		helpers   : { 
					   overlay : {closeClick: false} // prevents closing when clicking OUTSIDE fancybox 
				    }
	});
});

$(document).ready(function() {
	$(".various").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '100%',
		height		: '100%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'fade',
		closeEffect	: 'fade'
	});
});

	$(".avisos").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: true,
		width		: '100%',
		height		: '100%',
		autoSize	: true,
		closeClick	: false,
		openEffect	: 'fade',
		closeEffect	: 'fade',
		closeBtn    : false,
		closeClick	: false,
		keys : {
    			close  : null
  				},
		helpers   : { 
					   overlay : {closeClick: false} // prevents closing when clicking OUTSIDE fancybox 
				    },				
	    afterClose: function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
									consulta_saldo();
									//setInterval(actualiza_marcadores, 5000);
    							}					
		
	});

	$(".rasca").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		padding	 	: 0,
		width		: '100%',
		height		: '100%',
		autoSize	: false,
		topRatio    : 0,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none',
		helpers   : { 
					   overlay : {closeClick: false} // prevents closing when clicking OUTSIDE fancybox 
				    },
		keys : {
    			close  : null
  				}
		
	});

</script>
<!-- AQUI TERMINA LO DEL FANCYBOX PARA EL TUTORIAL ------------------------------------------------------------------>

<body>

<input type="hidden" id="txtIDJornada" value="<?php echo $IDJornada;?>" />
<input type="hidden" id="session_id_origen" value="<?php echo $SessionID;?>" />
<input type="hidden" id="txtIDSeccion" value="<?php echo $Seccion;?>" />
<div class="container-hr">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-3 col-lg-2" align="center">
                <div class="container-fluid">                
                  <img src="images/logo.png" class="img-fluid" />
                </div>              
            </div>
            <div class="col-md-5 col-lg-6">           
            </div>
            <div class="col-md-3 col-lg-3">
              <br />
    	      <div class="row ">                
	            <div class="col-md-8 col-lg-8 text-left" >
                    <table border="0" cellpadding="2" cellspacing="0" bordercolor="#FF0000">
                      <tr>
                        <td rowspan="2" align="center">
		                  <img src="images/saldo.png"  />
                        </td>
                        <td rowspan="2" align="right" style="text-shadow:-3px -3px 3px #FFF, 3px -3px 3px #FFF, -3px 3px 3px #FFF, 3px 3px 3px #FFF;; font-weight:bold;" >
                          <font size="+3">
                          <?php echo $formato_saldo;?>
                          $<?php echo $mi_saldo;?>
                          </font>
                        </td>
                        <td valign="bottom" style="text-shadow:-3px -3px 3px #FFF, 3px -3px 3px #FFF, -3px 3px 3px #FFF, 3px 3px 3px #FFF; font-weight:bold;" >
                            <?php echo $formato_saldo;?>00
                        </td>
                      </tr>
                      <tr>
                        <td style="text-shadow:-3px -3px 3px #FFF, 3px -3px 3px #FFF, -3px 3px 3px #FFF, 3px 3px 3px #FFF; font-weight:bold;" >
                          <font size="-1">
                          m.n.
                          </font>
                        </td>
                      </tr>
                    </table>
                </div>
	            <div class="col-md-4 col-lg-4 text-right" style="padding-right:0;">
                  <?php echo $foto_perfil;?>
                </div>
              </div>
    	      <div class="d-flex justify-content-end" style="background-color:#000; color:#FFF;">
	            <div class="p-2">
                  Bienvenid<?php echo $_SESSION['quiniela_matona_sexo'];?>
                </div>
	            <div class="p-2">
                  <?php echo $_SESSION['quiniela_matona_apodo'];?>
                </div>
	            <div class="p-2">
                  <a href="byebye.php" style="text-decoration:none; color:#FFF">Cerrar Sesion</a>
                </div>
              </div>
    	      <div class="d-flex justify-content-center" style="background-color:#FFF;">
	            <div class="p-2">
                  <a class='various' data-fancybox-type='iframe' href="pagos.php" style="text-decoration:none; color:#000; font-weight:bold;">Mis Pagos</a>
                </div>
                <?php
				if ($_SESSION['quiniela_matona_admin'] == "1")
				  {
				?>
	            <div class="p-2">
                  <a href="http://www.laquinieladelgordo.com/web_off_2018/inicio.php?<?php echo $SessionURL;?>" target="_blank" style="text-decoration:none; color:#000; font-weight:bold;">Admin</a>
                </div>                
	            <div class="p-2">
                  <a class='rasca' 
                  	 data-fancybox-type='iframe' 
                     href="http://www.laquinieladelgordo.com/grid.php?token=859.1&<?php echo $SessionURL;?>" 
                     style="text-decoration:none; color:#000; font-weight:bold;">Rasca</a>
                </div>                
	            <div class="p-2">
                  <a class='rasca' 
                  	 data-fancybox-type='iframe' 
                     href="http://www.laquinieladelgordo.com/survivor.php?token=859.1&<?php echo $SessionURL;?>" 
                     style="text-decoration:none; color:#000; font-weight:bold;">SurvivorNFL</a>
                </div>                
                <?php
				  }
				?>
              </div>
            </div>
            <div class="col-md-1 col-lg-1" align="center"></div>
        </div>
    </div>
</div>
<div class="container">
    <div class="container-fluid">
        <div class="row">
            <div class="row "><div class="col-md-12">&nbsp;</div></div>
        </div>
    </div>
</div>
<div class="container">
        <div class="row" id="tabla-ligas">
            <div class="col-md-4" align="center">
              <a href="favorito.php?idliga=1&url=<?php echo $currentFile;?>&<?php echo $SessionURL ;?>"><img src="<?php echo $LogoMX;?>" class="img-fluid" /></a>
            </div>
            <div class="col-md-4" align="center">
              <a href="favorito.php?idliga=8&url=<?php echo $currentFile;?>&<?php echo $SessionURL ;?>"><img src="<?php echo $LogoNFL;?>" class="img-fluid" /></a>
            </div>
            <div class="col-md-4" align="center">
              <a href="favorito.php?idliga=2&url=<?php echo $currentFile;?>&<?php echo $SessionURL ;?>"><img src="<?php echo $LogoChampions;?>" class="img-fluid" /></a>
            </div>
        </div>
</div>
<div class="container">
    <div class="container-fluid">
        <div class="row">
            <div class="row "><div class="col-md-12">&nbsp;</div></div>
        </div>
    </div>
</div>
<div class="container">
        <div class="row" id="tabla-botones">
            <div class="col-md-2" align="center" id="btn_pronostico">
              <a onclick="activa_seccion('<?php echo $pronostico;?>');">MI PRONOSTICO</a>              
            </div>
            <div class="col-md-2" align="center" id="btn_grupos">
              <a onclick="activa_seccion('5');">MIS GRUPOS</a>
            </div>
            <div class="col-md-2" align="center" id="btn_tabla">
              <?php 
			  if ($Modo == "torneo") 
                echo "<a onclick=\"activa_seccion('4.2');\">TABLA SEMANAL</a>";
			  else			  
                echo "<a onclick=\"activa_seccion('4');\">TABLA SEMANAL</a>";
			  ?>
            </div>
            <div class="col-md-2" align="center" id="btn_jornada">
              <a onclick="activa_seccion('1');">JORNADA</a>
            </div>
            <div class="col-md-2" align="center" id="btn_estadisticas">
              <?php 
			  if ($Modo == "torneo" && $_SESSION['quiniela_matona_id'] == 2) 
                echo "<a onclick=\"activa_seccion('3.2');\">ESTADISTICAS</a>";
			  else			  
                echo "<a onclick=\"activa_seccion('3');\">ESTADISTICAS</a>";              
			  ?>
            </div>
        </div>
</div>
<?php
if ($_SESSION['quiniela_matona_admin'] == "1")
  {
?>
<div class="container">
        <div class="row" id="tabla-botones">
            <div class="col-md-2" align="center" id="btn_captura">
              <a onclick="activa_seccion('21');">Captura Juegos</a>              
            </div>
            <div class="col-md-2" align="center" id="btn_tickets">
              <a onclick="activa_seccion('22');">Tickets</a>
            </div>
            <div class="col-md-2" align="center" id="btn_participantes">
              <a onclick="activa_seccion('23');">Participantes</a>
            </div>
        </div>
</div>

<?php
  }
else
  {
?>
<div class="container">
        <div class="row" id="tabla-botones">
            <div class="col-md-2" align="center" id="btn_captura">
            </div>
            <div class="col-md-2" align="center" id="btn_tickets">
            </div>
            <div class="col-md-2" align="center" id="btn_participantes">
            </div>
        </div>
</div>

<?php
  }

?>


<div class="container">
    <div class="container-fluid">
        <div class="row">
            <div class="row "><div class="col-md-12">&nbsp;</div></div>
        </div>
        <div class="row">
            <div class="row "><div class="col-md-12">&nbsp;</div></div>
        </div>
    </div>
</div>

<div class="container">
    <div class="container">
        <div class="row" id="tabla-ligas">        
            <div class="col-md-3" >
              <img id="btnSeccion" src="images/boton-jornada.png" class="img-fluidsss" style="position:relative;top:-50%" />
            </div>            
            <div class="col-md-9" id="subtituloB">
            </div>                    
        </div>
        <div class="row ">
            <div class="col-md-12" align="center" id="panelAFondo">
                <div class="container-fluid" id="panelA">                
                </div>              
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="container-fluid">
        <div class="row">
            <div class="row "><div class="col-md-12">&nbsp;</div></div>
        </div>
    </div>
</div>

<div class="container-fluid pie">
    <div class="row align-items-center">
      <div style="background-color:#000;" class="col text-center footer">
        <br /><a href="#" OnClick="actualiza_web('aviso')" data-fancybox-type='iframe'>Aviso de Privacidad</a> |
        <a href="bases.php" class="various" data-fancybox-type='iframe'>Bases</a> |
        <font color="#FFFFFF">Terminos y Condiciones</font><br />
		&copy; 2018
		<br /><br /> 
		
      </div>
    </div>
</div>



</body>
</html>


<script language="javascript">
  
activa_seccion('<?php echo $Seccion;?>');


<?php 
$hoy = date("Y-m-d");
$sql_aviso = "SELECT * 
			  FROM avisos 
			  WHERE 
			    Status = '1' AND 
				FechaInicio <= '$hoy' AND
				FechaFinal >= '$hoy' AND				
				IDAviso NOT IN (Select IDAviso FROM avisos_log WHERE IDUsuario = '$IDUsuario') ORDER BY rand() LIMIT 0,1";
$consulta_aviso = mysql_query($sql_aviso);
if (mysql_num_rows($consulta_aviso) > 0)
  {
    $renglon_aviso = mysql_fetch_assoc($consulta_aviso);
	$TokenCompleto = "0@0@" . $renglon_aviso['IDAviso'];
  }


if (mysql_num_rows($consulta_aviso) > 0)
{
echo "
        $(document).ready(function() {\n
		  $('#el_aviso').trigger('click');\n
 		});\n
	  ";
}
?>

</script>

<a class='avisos' id='el_aviso' data-fancybox-type='iframe' href="avisos_usuario.php?token=<?php echo $TokenCompleto;?>"></a>
