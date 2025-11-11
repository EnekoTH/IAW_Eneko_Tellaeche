<?php
// Función para pasar texto a minúsculas
function pasar_a_minuscula($cadena) {
    return strtolower($cadena);
}
// Array de películas
$pelis = ["UNO", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", "DIEZ"];
$resultado = "";
// Comprobamos si hay búsqueda
if (isset($_GET['buscar'])) {
    $busqueda = pasar_a_minuscula(trim($_GET['buscar']));

    foreach ($pelis as $titulo) {
        if (str_contains(pasar_a_minuscula($titulo), $busqueda)) {
            $resultado .= ucfirst(pasar_a_minuscula($titulo)) . "<br>";
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
    <title>Buscador con función</title>
</head>
<body>
    <h2>Buscador de Películas</h2>
    <form method="get">
        <input type="text" name="buscar" placeholder="Introduce un título">
        <input type="submit" value="Buscar">
    </form>
    <div style="margin-top:10px;">
        <?php echo $resultado; ?>
    </div>
</body>
</html>
