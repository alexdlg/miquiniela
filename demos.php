<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>La Quiniela del Gordo</title>
<!-- Required meta tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
</head>

<style>
body
{
	font-family:Verdana, Geneva, sans-serif;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}


#fondo
{
  max-width: 100%;
  max-height: auto;
  object-fit: contain;
}

#tabla-header
{
	color:#FFF;
	background-color:#000;
	border-bottom-width:thin;
	border-bottom-style:solid;
	border-bottom-color:#FFF;
	padding:2px;
	padding-bottom:10px;
	padding-top:10px;
	
}

#tabla-login
{
	background-color:rgba(0, 0, 0, 0.5);
	padding:5px;
}

#tabla-ligas
{
	background-color:rgba(0, 0, 0, 0.5);
	padding:5px;
}

#panelAFondo
{
	background-color:rgba(0, 0, 0, 0.5);
	padding:5px;
}

.margin-bottom {
    margin-bottom:15px;
}
.container-full
{
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
}

#tabla-mundial td
{
	padding-top:5px;
	padding-bottom:5px;
	padding-left:5px;
	padding-right:5px;
}
</style>

<!-- AQUI VIENE LO DEL FANCYBOX PARA EL TUTORIAL ------------------------------------------------------------------>
<!-- Add jQuery library -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script src="js/cquery.min.js?ver=201806072"></script>

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
});</script>
<!-- AQUI TERMINA LO DEL FANCYBOX PARA EL TUTORIAL ------------------------------------------------------------------>

<body>

<div class="container">
  <div class="row justify-content-md-center">
    <div class="col-md-auto" style="border-style:solid; border-width:thin;">
      peru
    </div>
    <div class="col"  style="border-style:solid; border-width:thin;">
      argentina
    </div>
  </div>
</div>

<div class="row">
  <div style="border-style:solid; border-width:thin;"class="col">col1</div>
  <div style="border-style:solid; border-width:thin;"class="col">col2</div>
  <div style="border-style:solid; border-width:thin;"class="col">col3</div>
  <div style="border-style:solid; border-width:thin;"class="w-100"></div>
  <div style="border-style:solid; border-width:thin;"class="col">col4</div>
</div>
</body>
</html>

