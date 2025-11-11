<?php
session_start();
$instante = date('d/m/Y H:i:s');
if(!isset($_SESSION['instantes'])){
$_SESSION['instantes'] = [];
echo "Bienvenido por primera vez.";
} else {
echo "Visitas anteriores:<ul>";
foreach($_SESSION['instantes'] as $v){
echo "<li>$v</li>";
}
echo "</ul>";
}
$_SESSION['instantes'][] = $instante;
?>