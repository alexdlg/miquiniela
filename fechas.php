<?php
function dameFecha($la_fecha)
{
if (strlen($la_fecha) == 10)
  {
  $year = substr($la_fecha, 0,4);
  $month = substr($la_fecha, 5,2);
  $day = substr($la_fecha, 8,2);
  switch($month)
    {
    case "01" : $mes = "Enero";break;
    case "02" : $mes = "Febrero";break;
    case "03" : $mes = "Marzo";break;
    case "04" : $mes = "Abril";break;
    case "05" : $mes = "Mayo";break;
    case "06" : $mes = "Junio";break;
    case "07" : $mes = "Julio";break;
    case "08" : $mes = "Agosto";break;
    case "09" : $mes = "Septiembre";break;
    case "10" : $mes = "Octubre";break;
    case "11" : $mes = "Noviembre";break;
    case "12" : $mes = "Diciembre";break;
    }
  $fecha = $mes . " " . $day . ", " . $year;
  return $fecha;
  }
else
  return "";
}

function dameFechaCorta($la_fecha)
{
if (strlen($la_fecha) == 10)
  {
  $year = substr($la_fecha, 2,2);
  $month = substr($la_fecha, 5,2);
  $day = substr($la_fecha, 8,2);
  switch($month)
    {
    case "01" : $mes = "Ene";break;
    case "02" : $mes = "Feb";break;
    case "03" : $mes = "Mar";break;
    case "04" : $mes = "Abr";break;
    case "05" : $mes = "May";break;
    case "06" : $mes = "Jun";break;
    case "07" : $mes = "Jul";break;
    case "08" : $mes = "Ago";break;
    case "09" : $mes = "Sep";break;
    case "10" : $mes = "Oct";break;
    case "11" : $mes = "Nov";break;
    case "12" : $mes = "Dic";break;
    }
  $fecha = $mes . " " . $day;
  return $fecha;
  }
else
  return "";
}

function dameFechaCortaCompleta($la_fecha)
{
if (strlen($la_fecha) == 10)
  {
  $year = substr($la_fecha, 0,4);
  $month = substr($la_fecha, 5,2);
  $day = substr($la_fecha, 8,2);
  switch($month)
    {
    case "01" : $mes = "Ene";break;
    case "02" : $mes = "Feb";break;
    case "03" : $mes = "Mar";break;
    case "04" : $mes = "Abr";break;
    case "05" : $mes = "May";break;
    case "06" : $mes = "Jun";break;
    case "07" : $mes = "Jul";break;
    case "08" : $mes = "Ago";break;
    case "09" : $mes = "Sep";break;
    case "10" : $mes = "Oct";break;
    case "11" : $mes = "Nov";break;
    case "12" : $mes = "Dic";break;
    }
  $fecha = $mes . " " . $day . ", " . $year;
  return $fecha;
  }
else
  return "";
}


function dameFechaCortaDia($la_fecha)
{
if (strlen($la_fecha) == 10)
  {
  $year = substr($la_fecha, 2,2);
  $month = substr($la_fecha, 5,2);
  $day = substr($la_fecha, 8,2);
  
  $fecha2 = mktime(0,0,0,$month,$day,$year);
  
  $dia = date("w", $fecha2);
  switch($dia)
    {
	case "1" : $el_dia = "Lunes";break;
	case "2" : $el_dia = "Martes";break;
	case "3" : $el_dia = "Miercoles";break;
	case "4" : $el_dia = "Jueves";break;
	case "5" : $el_dia = "Viernes";break;
	case "6" : $el_dia = "Sabado";break;
	case "0" : $el_dia = "Domingo";break;
	}
  
  switch($month)
    {
    case "01" : $mes = "Ene";break;
    case "02" : $mes = "Feb";break;
    case "03" : $mes = "Mar";break;
    case "04" : $mes = "Abr";break;
    case "05" : $mes = "May";break;
    case "06" : $mes = "Jun";break;
    case "07" : $mes = "Jul";break;
    case "08" : $mes = "Ago";break;
    case "09" : $mes = "Sep";break;
    case "10" : $mes = "Oct";break;
    case "11" : $mes = "Nov";break;
    case "12" : $mes = "Dic";break;
    }
  $fecha = $el_dia . "<br />" . $mes . " " . $day;
  return $fecha;
  }
else
  return "";
}

function dameFechaCompletaDia($la_fecha)
{
if (strlen($la_fecha) == 10)
  {
  $year = substr($la_fecha, 0,4);
  $month = substr($la_fecha, 5,2);
  $day = substr($la_fecha, 8,2);
  
  $fecha2 = mktime(0,0,0,$month,$day,$year);
  
  $dia = date("w", $fecha2);
  switch($dia)
    {
	case "1" : $el_dia = "Lunes";break;
	case "2" : $el_dia = "Martes";break;
	case "3" : $el_dia = "Miercoles";break;
	case "4" : $el_dia = "Jueves";break;
	case "5" : $el_dia = "Viernes";break;
	case "6" : $el_dia = "Sabado";break;
	case "0" : $el_dia = "Domingo";break;
	}
  
  switch($month)
    {
    case "01" : $mes = "Enero";break;
    case "02" : $mes = "Febrero";break;
    case "03" : $mes = "Marzo";break;
    case "04" : $mes = "Abril";break;
    case "05" : $mes = "Mayo";break;
    case "06" : $mes = "Junio";break;
    case "07" : $mes = "Julio";break;
    case "08" : $mes = "Agosto";break;
    case "09" : $mes = "Septiembre";break;
    case "10" : $mes = "Octubre";break;
    case "11" : $mes = "Noviembre";break;
    case "12" : $mes = "Diciembre";break;
    }
  $fecha = $el_dia . "<br />" . $mes . " " . $day . ", " . $year;
  return $fecha;
  }
else
  return "";
}


function dameFechaCompleta($la_fecha)
{
if (strlen($la_fecha) == 10)
  {
  $year = substr($la_fecha, 0,4);
  $month = substr($la_fecha, 5,2);
  $day = substr($la_fecha, 8,2);
  $timestamp = mktime(0,0,0,$month,$day,$year);
  
  $mes = dimeMes($la_fecha);
  
  $el_dia = date("D",$timestamp);
  switch($el_dia)
  {
    case "Mon": $dayweek = "Lunes ";break;
    case "Tue": $dayweek = "Martes ";break;
    case "Wed": $dayweek = "Miercoles ";break;
    case "Thu": $dayweek = "Jueves ";break;
    case "Fri": $dayweek = "Viernes ";break;
    case "Sat": $dayweek = "Sabado ";break;
    case "Sun": $dayweek = "Domingo ";break;
  }	
  $fecha = $dayweek . $day . " de " . $mes . " del " . $year;
  return $fecha;
  }
else
  return "";
}


function scaleimage($location, $maxw, $maxh){
    $img = getimagesize($location);
    if($img)
	{
        $w = $img[0];
        $h = $img[1];

        $dim = array('w','h');
        foreach($dim AS $val){
            $max = "max{$val}";
            if(${$val} > ${$max} && ${$max}){
                $alt = ($val == 'w') ? 'h' : 'w';
                $ratio = ${$alt} / ${$val};
                ${$val} = ${$max};
                ${$alt} = ${$val} * $ratio;
            }
        }

        return("<img src='{$location}' alt='image' width='{$w}' height='{$h}' />");
    }
} 


function dameHora($la_hora)
{
  $horas= substr($la_hora, 0,2);
  $minutos = substr($la_hora, 3,2);

  if ($horas > 12)
    {
    $las_horas = $horas - 12;
	$final = "p.m.";
	}
  else
    {
    $las_horas = $horas;
	$final = "a.m.";
	}
  
  $hora = $las_horas . ":" . $minutos . " " . $final;
  return $hora;
}

function sumaHora($la_hora,$sumar)
{
  $horas= substr($la_hora, 0,2);
  $minutos = substr($la_hora, 3,2);
  
  $horas = intval($horas) + intval($sumar);
  
  if ($horas > 23)
    $horas = $horas - 24;

  if ($horas > 12)
    {
    $las_horas = $horas - 12;
	$final = "p.m.";
	}
  else
    {
    $las_horas = $horas;
	$final = "a.m.";
	}
  
  $hora = $las_horas . ":" . $minutos . " " . $final;
  return $hora;
}

function dimeDia($la_fecha)
{
if (strlen($la_fecha) == 10)
  {
  $year = substr($la_fecha, 0,4);
  $month = substr($la_fecha, 5,2);
  $day = substr($la_fecha, 8,2);
  $estampa = mktime(0,0,0,$month,$day,$year);
  
  $el_dia = date("w",$estampa);
  
  switch($el_dia)
    {
    case "0" : $dia = "Domingo";break;
    case "1" : $dia = "Lunes";break;
    case "2" : $dia = "Martes";break;
    case "3" : $dia = "Miercoles";break;
    case "4" : $dia = "Jueves";break;
    case "5" : $dia = "Viernes";break;
    case "6" : $dia = "Sabado";break;
    } //final del switch
  return $dia;
  } // final de la verificacion de validez de fecha
  return "Fecha Invalida";
}


function dimeMes($la_fecha)
{
  $year = substr($la_fecha, 0,4);
  $month = substr($la_fecha, 5,2);
  $day = substr($la_fecha, 8,2);
  switch($month)
    {
    case "01" : $mes = "Enero";break;
    case "02" : $mes = "Febrero";break;
    case "03" : $mes = "Marzo";break;
    case "04" : $mes = "Abril";break;
    case "05" : $mes = "Mayo";break;
    case "06" : $mes = "Junio";break;
    case "07" : $mes = "Julio";break;
    case "08" : $mes = "Agosto";break;
    case "09" : $mes = "Septiembre";break;
    case "10" : $mes = "Octubre";break;
    case "11" : $mes = "Noviembre";break;
    case "12" : $mes = "Diciembre";break;
    }
return $mes;
}


function dates_range($date1, $date2) 
{ 
   if ($date1<$date2) 
   { 
       $dates_range[]=$date1; 
       $date1=strtotime($date1); 
       $date2=strtotime($date2); 
       while ($date1!=$date2) 
       { 
           $date1=mktime(0, 0, 0, date("m", $date1), date("d", $date1)+1, date("Y", $date1)); 
           $dates_range[]=date('Y-m-d', $date1); 
       } 
   } 
   return $dates_range; 
} 

function dameFechaCortaDiaCortoCompleto($la_fecha)
{
if (strlen($la_fecha) == 10)
  {
  $year = substr($la_fecha, 0,4);
  $month = substr($la_fecha, 5,2);
  $day = substr($la_fecha, 8,2);
  
  $fecha2 = mktime(0,0,0,$month,$day,$year);
  
  $dia = date("w", $fecha2);
  switch($dia)
    {
	case "1" : $el_dia = "Lun";break;
	case "2" : $el_dia = "Mar";break;
	case "3" : $el_dia = "Mie";break;
	case "4" : $el_dia = "Jue";break;
	case "5" : $el_dia = "Vie";break;
	case "6" : $el_dia = "Sab";break;
	case "0" : $el_dia = "Dom";break;
	}
  
  switch($month)
    {
    case "01" : $mes = "Ene";break;
    case "02" : $mes = "Feb";break;
    case "03" : $mes = "Mar";break;
    case "04" : $mes = "Abr";break;
    case "05" : $mes = "May";break;
    case "06" : $mes = "Jun";break;
    case "07" : $mes = "Jul";break;
    case "08" : $mes = "Ago";break;
    case "09" : $mes = "Sep";break;
    case "10" : $mes = "Oct";break;
    case "11" : $mes = "Nov";break;
    case "12" : $mes = "Dic";break;
    }
  $fecha = $el_dia . " " . $mes . " " . $day . ", " . $year;

  return $fecha;
  }
else
  return "";
}


?>