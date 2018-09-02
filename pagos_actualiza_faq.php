<?php
$token = $_GET['token'];
$SessionID = $_GET['session_id'];

switch($token)
{
	case "1": include("pagos_info.php");break;
	case "2": include("pagos_reporte.php");break;
	case "3": include("pagos_status.php");break;
	case "4": include("pagos_listado.php");break;
	case "5": include("pagos_saldo.php");break;
	case "6": include("pagos_mi_cuenta.php");break;
	case "7": include("transfer-pagos.php");break;
	default: include("pagos_hacker.php");break;
}
?>