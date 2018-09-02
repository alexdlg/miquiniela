<?php 
session_start();

	if($_SESSION['quiniela_matona_id'] != "")
	{
		echo "<script language='javascript'>window.location = 'inicio.php?session_id=".session_id()."';</script>";
	}
	else
	{
		echo "<script language='javascript'>window.location = 'index.php';</script>";
	}

?>