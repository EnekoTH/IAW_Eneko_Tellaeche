<?php
// Array con nombres del uno al diez
$pelis = ["Uno", "Dos", "Tres", "Cuatro", "Cinco", "Seis", "Siete", "Ocho", "Nueve", "Diez"];
$resultado = "";
// Si se ha hecho una búsqueda
if (isset($_GET['buscar'])) {
    $busqueda = strtolower(trim($_GET['buscar']));
    foreach ($pelis as $titulo) {
        if (str_contains(strtolower($titulo), $busqueda)) {
            $resultado .= $titulo . "<br>";
        }
    }
    if ($resultado == "") {
        $resultado = "No se encontraron coincidencias.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscador de Películas</title>
</head>
<body>
    <h2>Buscador de Películas</h2>
    <form method="get">
        <input type="text" name="buscar" placeholder="Escribe un nombre">
        <input type="submit" value="Buscar">
    </form>
    <div style="margin-top:10px;">
        <?php echo $resultado; ?>
    </div>
</body>
</html>
