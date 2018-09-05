<?php
session_start();

$IDLiga = $_GET['idliga'];
$url = $_GET['url'];
$session_id = $_GET['session_id'];

$_SESSION['quiniela_matona_idliga'] = $IDLiga;

?>

<script language="javascript">
  cadena = "<?php echo $url. "?session_id=" . $session_id;?>";
  window.location = cadena;
</script>


