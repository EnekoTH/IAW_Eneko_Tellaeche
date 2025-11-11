<?php
session_start();
if(!isset($_SESSION['autenticado'])){
$_SESSION['autenticado'] = true;
}
if(isset($_POST['borrar'])){
unset($_SESSION['historial']);
echo "Historial borrado correctamente.";
}
$fecha = date('d/m/Y H:i:s');
if(!isset($_SESSION['historial'])){
$_SESSION['historial'] = [];
echo "¡Bienvenido por primera vez!";
} else {
echo "Historial de visitas anteriores:<ul>";
foreach($_SESSION['historial'] as $idx=>$h){
if($idx<count($_SESSION['historial'])){
echo "<li>$h</li>";
}
}
echo "</ul>";
}
$_SESSION['historial'][] = $fecha;
?>
<form method="post">
<button type="submit" name="borrar">Borrar historial</button>
</form>