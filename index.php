<?php
session_start();
include("include/init.php");
if($_SESSION['quiniela_matona_id'] != "")
  {
	echo "<script language='javascript'>window.location = 'inicio.php?session_id=".session_id()."';</script>";
  }
else  
if($_SESSION['quiniela_matona_redes'] == "fb")
  {
	require_once "fb-sdk/src/Facebook/autoload.php";
	
	$fb = new Facebook\Facebook([
	  'app_id' => '930159273773842', // Replace {app-id} with your app id
	  'app_secret' => '35181393b01a46181c9c2ff2ce9ec7a3',
	  'default_graph_version' => 'v2.2',
	  ]);
	
	$helper = $fb->getRedirectLoginHelper();
	
	$permissions = ['email']; // Optional permissions
	$loginUrl = $helper->getLoginUrl('http://www.laquinieladelgordo.com/fb-sdk/fb-callback.php', $permissions);
	$_SESSION['quiniela_matona_redes'] = "";
	echo "<script language='javascript'>window.location = '" . $loginUrl . "';</script>";
  }
if($_SESSION['quiniela_matona_redes'] == "tw")
  {
	$_SESSION['quiniela_matona_redes'] = "";
	echo "<script language='javascript'>window.location = 'twitter.php';</script>";
  }
  
$SessionID = $_REQUEST['session_id'];
$SessionURL = "session_id=" . $SessionID;
$currentFile = $_SERVER['SCRIPT_NAME'];
//$URL = "http://www.laquinieladelgordo.com/web_off_2018/";

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
		$LogoMX = "images/Gliga-mx-on.png";
		$LogoChampions = "images/Gliga-champ-off.png";
		$LogoNFL = "images/Gliga-nfl-off.png";
		$fondoLQDG = "images/background-mx.png";		
	  } break;
	case "2": 
	  {
		$LogoMX = "images/Gliga-mx-off.png";
		$LogoChampions = "images/Gliga-champ-on.png";
		$LogoNFL = "images/Gliga-nfl-off.png";
		$fondoLQDG = "images/background-champions.png";		
	  } break;
	case "8": 
	  {
		$LogoMX = "images/Gliga-mx-off.png";
		$LogoChampions = "images/Gliga-champ-off.png";
		$LogoNFL = "images/Gliga-nfl-on.png";
		$fondoLQDG = "images/background-nfl.png";		
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>La Quiniela del Gordo</title>
<!-- Required meta tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

<!-- Ad Sense 
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-6230236180458650",
    enable_page_level_ads: true
  });
</script>-->

<!-- Analytics -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113160224-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113160224-1');
</script>
</head>
<!-- AQUI VIENE LO DEL FANCYBOX PARA EL TUTORIAL ------------------------------------------------------------------>
<!-- Add jQuery library -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script src="js/cquery.min.js?ver=201807281"></script>
<link type="text/css" rel="stylesheet" href="css/estilos.css" />
<link type="text/css" rel="stylesheet" href="css/style.css" />
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
		fitToView	: true,
		width		: '100%',
		height		: '100%',
		autoSize	: true,
		closeClick	: false,
		openEffect	: 'fade',
		closeEffect	: 'fade'
	});
});
$(".login").fancybox({
		maxWidth	: 500,
		maxHeight	: 600,
		fitToView	: false,
		width		: '90%',
		height		: '95%',
		autoSize	: false,
		topRatio    : 0,
		closeClick	: true,
		openEffect	: 'none',
		closeEffect	: 'none',
		helpers   : { 
					   overlay : {closeClick: false} // prevents closing when clicking OUTSIDE fancybox 
				    },
		afterClose: function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
							        actualiza_modulo();
    							}	
		
	});	
//Registro
$(document).ready(function() {
	$(".registro").fancybox({
		maxWidth	: 600,
		maxHeight	: 1000,
		fitToView	: false,
		width		: '90%',
		height		: '95%',
		autoSize	: false,
		topRatio    : 0,
		closeClick	: true,
		openEffect	: 'none',
		closeEffect	: 'none',
		
	});
});	
	
</script>
<!-- AQUI TERMINA LO DEL FANCYBOX PARA EL TUTORIAL ------------------------------------------------------------------>

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


<body>
<input type="hidden" id="txtIDJornada" value="<?php echo $IDJornada;?>" />
<input type="hidden" id="session_id_origen" value="<?php echo $SessionID;?>" />


<div class="container-hr">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-3" align="center">
                <div class="container-fluid">                
                  <img src="images/logo.png" class="img-fluid" />
                </div>              
            </div>
            <div class="col-md-8" align="center">
                <div class="container-fluid">
                    <div class="container-hr">
                        <div class="container-fluid">
                            <div class="row ">
							<div class="col-md-12">&nbsp;</div></div>
                            <div class="row ">
                                <div class="col-md-12 texto_ws" align="right">
                                  <img src="images/ws.png" class="img-fluid" width="40" /> 818 275 4010
                                </div>
                            </div>
							
                            <div class="row">
                                <div class="col-md-2" id="tabla-header">
                                  <a href="index.php" class='menuHeader' data-fancybox-type='iframe'>Inicio</a>
                                </div>
                                <div class="col-md-3" id="tabla-header">
                                  <a href="#" class='menuHeader' OnClick="actualiza_web('como')" data-fancybox-type='iframe'>¿Cómo se Juega?</a>
                                </div>
                                <div class="col-md-2" id="tabla-header">
                                  <a href="#" class='menuHeader' OnClick="actualiza_web('contacta')" data-fancybox-type='iframe'>Contáctanos</a>
                                </div>

								<div class="col-md-3" id="tabla-header">
									<a href="login_fancy.php" class="login" data-fancybox-type="iframe"><button type="button" class="buttonLogin">LOGIN</button></a>
									<a href="registro.php" class="registro" data-fancybox-type="iframe"><button type="button" class="button buttonRegistro">Registro</button></a>
								</div>
															
                                <div class="col-md-2" id="tabla-header">
									<a href="http://www.fb.com/laquinieladelgordo" target="_blank">
										<img src="images/redes-facebook.png" class="img-fluid" />
									</a>
									<a href="http://www.instagram.com/laquinieladelgordo" target="_blank">
										<img src="images/redes-instagram.png" class="img-fluid" />
									</a>
									<a href="http://www.twitter.com/quinielaelgordo" target="_blank">
										<img src="images/redes-twitter.png" class="img-fluid" />
									</a>
									<!--<form class="form-inline align-content-end" action="login.php" method="post">-->
                                      
										
                                    <!--</form>-->
                                </div>
								
							</div>


                            

                                
                            </div>
                        </div>
                    </div>
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
<div class="container">
	<div class="container-fluid">
        <div class="row" id="tabla-ligas">
			<div class="col-md-12" align="center">
				<p  class="headerIndex">INGRESA A LA QUINIELA DEL GORDO</p>
			</div>
        </div>
		<div class="row" id="tabla-ligas">
			<div class="col-md-12" align="left" id="panelA">
				<p class="headerText">
					La quiniela del gordo es un portal online donde puedes armar tus quinielas de los diferentes torneos que ofrece el sitio. 
					Puedes invitar a tus amigos para formar grupos y hacer competencias  que le pongan sabor al deporte.
				</p>
				
				<p class="headerText">
					El objetivo del sitio es facilitar la organización de quinielas así como pasar un rato agradable en las diferentes competencias que se organizan.
				</p>
				
				<p class="headerText">
					Puedes participar en las quinielas oficiales del sitio, para más información puedes revisar la sección de Bases para revisar los costos y reglas de juego. </p>
				</p>
				
				<p class="headerText">
					De momento el portal sólo ofrece el servicio de las siguiente ligas:
				</p>
			</div>
			<div class="col-md-4" align="center">
				<img class="img-responsive" src="images/LIGABancomerMX.png" width="150" />
			</div>
			<div class="col-md-4" align="center">
				<img class="img-responsive" src="images/ChampionsLeague.png" width="150"  />
			</div>
			<div class="col-md-4" align="center">
				<img class="img-responsive" src="images/NFL.png" width="150" />
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

<!--<div class="container">
    <div class="container">
        <div class="row" id="tabla-ligas">        
            <div class="col-md-3" >
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
</div>-->

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

<!--<div class="container-fluid pie">
    <div class="row align-items-center">
      <div style="background-color:#000;" class="col text-center">
        <br /><a href="aviso-de-privacidad.php" class="footer" data-fancybox-type='iframe'>Aviso de Privacidad</a><br /><br />
      </div>
      <div style="background-color:#000;" class="col text-center">
        <br /><a href="bases.php"  data-fancybox-type='iframe'>Bases</a><br /><br />
      </div>
      <div style="background-color:#000;" class="col text-center">
        <br /><font color="#FFFFFF">Terminos y Condiciones</font><br /><br />
      </div>
    </div>
</div>    
-->

<div class="container">
    <div class="container-fluid">
        <div class="row">
            <div class="row "><div class="col-md-12">&nbsp;</div></div>
        </div>
    </div>
</div>



<!--
<div id="sp-cookie-consent" class="position-bottom_right">
  <div>
    <div class="sp-cookie-consent-content">
	  <img src="images/jugador-mundial.png" class="img-fluid" style="max-width:50%"  />
    </div>
  </div>
</div>
-->



</body>
</html>

<script language="javascript">
function actualiza_modulo()
{
	window.location = "index.php";
}

var http = false;

//verificacion del navegador para el uso de AJAX
if(navigator.appName == "Microsoft Internet Explorer") {
  http = new ActiveXObject("Microsoft.XMLHTTP");
} else {
  http = new XMLHttpRequest();
}

function logueo()
{
	document.forma_logueo.submit();
}
  //actualiza_jornadas(1);
</script>