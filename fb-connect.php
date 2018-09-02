<?php
session_start();
require_once "fb-sdk/src/Facebook/autoload.php";

$fb = new Facebook\Facebook([
  'app_id' => '930159273773842', // Replace {app-id} with your app id
  'app_secret' => '35181393b01a46181c9c2ff2ce9ec7a3',
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://www.laquinieladelgordo.com/web/fb-sdk/fb-callback.php?url_perfil=PerfilFB', $permissions);


$Primero = explode("?", $loginUrl);
$Segundo = explode("&", $Primero[1]);
$Tercero = explode("=", $Segundo[0]);

if ($Tercero[1] != "930159273773842")
  echo "<input type='hidden' id='txtFBLoginID' value='".$Tercero[1]."'>";
else
  {
    echo "<a href='" . htmlspecialchars($loginUrl). "'><img src='images/fb_login.png' width='100%'></a><br><br>";
    echo "<input type='hidden' id='txtFBLoginID' value=''>";
  }


?>