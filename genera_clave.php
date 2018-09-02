<?php

function dameToken()
{
	$letras[0] = "A";
	$letras[1] = "B";
	$letras[2] = "C";
	$letras[3] = "D";
	$letras[4] = "E";
	$letras[5] = "F";
	$letras[6] = "G";
	$letras[7] = "H";
	$letras[8] = "J";
	$letras[9] = "K";
	$letras[10] = "M";
	$letras[11] = "N";
	$letras[12] = "P";
	$letras[13] = "Q";
	$letras[14] = "R";
	$letras[15] = "S";
	$letras[16] = "T";
	$letras[17] = "U";
	$letras[18] = "V";
	$letras[19] = "W";
	$letras[20] = "X";
	$letras[21] = "Y";
	$letras[22] = "Z";
	
	$numeros[0] = "2";
	$numeros[1] = "3";
	$numeros[2] = "4";
	$numeros[3] = "5";
	$numeros[4] = "6";
	$numeros[5] = "7";
	$numeros[6] = "8";
	$numeros[7] = "9";
	
	$simbolos[0] = "$";
	$simbolos[1] = "?";
	$simbolos[2] = "!";
	$simbolos[3] = "_";
	$simbolos[4] = "-";	
	
	$Clave = "";
	$Clave[0] = $letras[rand(0,22)];
	$Clave[1] = $letras[rand(0,22)];
	$Clave[2] = $letras[rand(0,22)];
	$Clave[3] = $letras[rand(0,22)];
	$Clave[4] = $numeros[rand(0,7)];
	$Clave[5] = $numeros[rand(0,7)];
	$Clave[6] = $numeros[rand(0,7)];
	$Clave[7] = $simbolos[rand(0,4)];
	
	for ($i=0;$i<8;$i++)
	  $Token = $Token . $Clave[$i];

    return $Token;
}



function dameReferencia()
{
	$letras[0] = "a";
	$letras[1] = "b";
	$letras[2] = "c";
	$letras[3] = "d";
	$letras[4] = "e";
	$letras[5] = "f";
	$letras[6] = "g";
	$letras[7] = "h";
	$letras[8] = "j";
	$letras[9] = "k";
	$letras[10] = "m";
	$letras[11] = "n";
	$letras[12] = "p";
	$letras[13] = "q";
	$letras[14] = "r";
	$letras[15] = "s";
	$letras[16] = "t";
	$letras[17] = "u";
	$letras[18] = "v";
	$letras[19] = "w";
	$letras[20] = "x";
	$letras[21] = "y";
	$letras[22] = "z";
	$letras[23] = "2";
	$letras[24] = "3";
	$letras[25] = "4";
	$letras[26] = "5";
	$letras[27] = "6";
	$letras[28] = "7";
	$letras[29] = "8";
	$letras[30] = "A";
	$letras[31] = "B";
	$letras[32] = "C";
	$letras[33] = "D";
	$letras[34] = "E";
	$letras[35] = "F";
	$letras[36] = "G";
	$letras[37] = "H";
	$letras[38] = "J";
	$letras[39] = "K";
	$letras[40] = "M";
	$letras[41] = "N";
	$letras[42] = "P";
	$letras[43] = "Q";
	$letras[44] = "R";
	$letras[45] = "S";
	$letras[46] = "T";
	$letras[47] = "U";
	$letras[48] = "V";
	$letras[49] = "W";
	$letras[50] = "X";
	$letras[51] = "Y";
	$letras[52] = "Z";
	
	$Clave = "";
	for($i=0;$i<10;$i++)
	  $Clave = $Clave . $letras[rand(0,52)];

    return $Clave;
}


function dameTokenSix()
{
	$letras[0] = "A";
	$letras[1] = "B";
	$letras[2] = "C";
	$letras[3] = "D";
	$letras[4] = "E";
	$letras[5] = "F";
	$letras[6] = "G";
	$letras[7] = "H";
	$letras[8] = "J";
	$letras[9] = "K";
	$letras[10] = "M";
	$letras[11] = "N";
	$letras[12] = "P";
	$letras[13] = "Q";
	$letras[14] = "R";
	$letras[15] = "S";
	$letras[16] = "T";
	$letras[17] = "U";
	$letras[18] = "V";
	$letras[19] = "W";
	$letras[20] = "X";
	$letras[21] = "Y";
	$letras[22] = "Z";
	
	$numeros[0] = "2";
	$numeros[1] = "3";
	$numeros[2] = "4";
	$numeros[3] = "5";
	$numeros[4] = "6";
	$numeros[5] = "7";
	$numeros[6] = "8";
	$numeros[7] = "9";
	
	
	$Clave = "";
	$Clave[0] = $numeros[rand(0,7)];
	$Clave[1] = $numeros[rand(0,7)];
	$Clave[2] = $numeros[rand(0,7)];
	$Clave[3] = $numeros[rand(0,7)];
	$Clave[4] = $numeros[rand(0,7)];
	$Clave[5] = $numeros[rand(0,7)];
	
	for ($i=0;$i<6;$i++)
	  $Token = $Token . $Clave[$i];

    return $Token;
}

?>

