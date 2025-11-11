<?php
$fecha = date('d/m/Y H:i:s');
if(isset($_COOKIE['ultima_visita'])){
echo "Bienvenido de nuevo. Tu última visita fue $_COOKIE[ultima_visita]";
} else {
echo "¡Bienvenido por primera vez!";
}
setcookie('ultima_visita', $fecha, time()+30*24*60*60);
?>