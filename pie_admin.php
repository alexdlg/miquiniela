<style>
.navbar {
    overflow: hidden;
    background-color: #333;
    font-family: Arial, Helvetica, sans-serif;
}

.navbar a {
    float: left;
    font-size: 16px;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

.dropdown {
    float: left;
    overflow: hidden;
}

.dropdown .dropbtn {
    cursor: pointer;
    font-size: 16px;    
    border: none;
    outline: none;
    color: white;
    padding: 14px 16px;
    background-color: inherit;
    font-family: inherit;
    margin: 0;
}

.navbar a:hover, .dropdown:hover .dropbtn, .dropbtn:focus {
    background-color: red;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    float: none;
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    text-align: left;
}

.dropdown-content a:hover {
    background-color: #ddd;
}

.show {
    display: block;
}
</style>

<script>
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction(seccion) {
    document.getElementById(seccion).classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(e) {
  if (!e.target.matches('.dropbtn')) {
    var myDropdown = document.getElementById('catalogos');
      if (myDropdown.classList.contains('show')) {
        myDropdown.classList.remove('show');
      }
    var myDropdown = document.getElementById('finanzas');
      if (myDropdown.classList.contains('show')) {
        myDropdown.classList.remove('show');
      }
    var myDropdown = document.getElementById('quiniela');
      if (myDropdown.classList.contains('show')) {
        myDropdown.classList.remove('show');
      }
    var myDropdown = document.getElementById('logs');
      if (myDropdown.classList.contains('show')) {
        myDropdown.classList.remove('show');
      }
  }
}
</script>

<table border="0" cellpadding="10" cellspacing="0" width="1000" align="center" class="tabla_principal">
  <tr align="center">
    <td>
        <div class="navbar">
          <div class="dropdown">
            <button class="dropbtn" onclick="myFunction('catalogos')">Catalogos
              <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content" id="catalogos">
              <a href="#">Ligas</a>
              <a href="#">Temporadas</a>
              <a href="#">Jornadas</a>
              <a href="#">Equipos</a>
              <a href="#">Juegos</a>
            </div>
          </div> 
          <div class="dropdown">
            <button class="dropbtn" onclick="myFunction('finanzas')">Finanzas
              <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content" id="finanzas">
              <a href="#">Tickets</a>
              <a href="#">Auditoria</a>
              <a href="#">Estado de Cuenta</a>
              <a href="#">Gastos</a>
            </div>
          </div> 
          <div class="dropdown">
            <button class="dropbtn" onclick="myFunction('quiniela')">Quiniela
              <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content" id="quiniela">
              <a href="#">Capturar Resultados</a>
              <a href="#">Bono Extra</a>
            </div>
          </div> 
          <div class="dropdown">
            <button class="dropbtn" onclick="myFunction('logs')">Logs
              <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content" id="logs">
              <a href="#">Log Pronosticos</a>
              <a href="#">Log Notificaciones</a>
            </div>
          </div> 
        </div>    
    </td>
  </tr>
</table>


<table border="0" cellpadding="10" cellspacing="0" width="1000" align="center" class="tabla_principal">
  <tr align="center">
    <td><a href="participantes.php?<?php echo $SessionURL ;?>">Participantes</a></td>
    <td><a href="ligas-admin.php?<?php echo $SessionURL ;?>">Admin Ligas</a></td>
    <td><a href="temporadas-admin.php?<?php echo $SessionURL ;?>">Admin Temporadas</a></td>
    <td><a href="jornadas-admin.php?<?php echo $SessionURL ;?>">Admin Jornadas</a></td>
    <td><a href="equipos-admin.php?<?php echo $SessionURL ;?>">Admin Equipos</a></td>
    <td><a href="juegos-admin.php?<?php echo $SessionURL ;?>">Admin Juegos</a></td>
    <td></td>
  </tr>
</table>
<br />
<table border="0" cellpadding="10" cellspacing="0" width="1000" align="center" class="tabla_principal">
  <tr align="center">
    <td nowrap="nowrap"><a href="participantes-bono-extra.php?<?php echo $SessionURL ;?>">Bono Extra</a></td>
    <td nowrap="nowrap"><a href="log-pronosticos.php?<?php echo $SessionURL ;?>">Log Pronosticos</a></td>
    <td nowrap="nowrap"><a href="log-notificaciones.php?<?php echo $SessionURL ;?>">Log Notificaciones</a></td>
    <td nowrap="nowrap"><a href="juegos-resultados.php?<?php echo $SessionURL ;?>">Capturar Resultados</a></td>
    <td><a href="notificaciones.php?<?php echo $SessionURL ;?>">Notificaciones</a></td>
    <td><a href="tickets.php?<?php echo $SessionURL ;?>">Tickets</a></td>
    <td nowrap="nowrap"><a href="auditoria.php?<?php echo $SessionURL ;?>">Auditoria $</a></td>
    <td width="100%"></td>
  </tr>
</table>
<br />


<!--    <td nowrap="nowrap"><a href="tabla_semanal_comparativo.php?<?php echo $SessionURL ;?>">Comparativo</a></td>-->
