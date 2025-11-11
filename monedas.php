<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Conversor de monedas</title>
    <link rel="stylesheet" media="screen" href="css/estilo.css">
</head>
<body>

<?php
// Arrays con los tipos de cambio
$cambios_euros = array("euros"=> 1, "libras" => 0.88, "dólares" => 1.15);
$cambios_libras = array("libras"=>1, "euros" => 1/$cambios_euros["libras"], "dólares" => 1.31);
$cambios_dolares = array("dólares"=>1, "euros" => 1/$cambios_euros["dólares"], "libras" => 1/$cambios_libras["dólares"]);

$cambios = array("euros" => $cambios_euros, "libras" => $cambios_libras, "dólares" => $cambios_dolares);

// Variables para mantener los valores
$cantidad = $origen = $destino = "";
$resultado = "";

// Si se ha enviado el formulario
if(isset($_POST["convertir"])) {
    $cantidad = $_POST["cantidad"];
    $origen = $_POST["origen"];
    $destino = $_POST["destino"];

    // Calculamos el resultado usando el array de cambios
    $resultado = round($cantidad * $cambios[$origen][$destino], 2);

    // Mostramos el resultado
    echo "<div class='aviso'>$cantidad $origen son $resultado $destino</div>";
}
?>

<!-- Formulario -->
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="formulario">
    <ul>
        <li>
            <h2>Conversor de monedas</h2>
        </li>
        <li>
            <label for="cantidad">Cantidad:*</label>
            <!-- Se mantiene el valor introducido -->
            <input type="number" name="cantidad" value="<?php echo htmlspecialchars($cantidad); ?>" required>
        </li>
        <li>
            <label for="origen">Origen:*</label>
            <select name="origen" required>
                <?php
                foreach ($cambios as $key => $valor) {
                    // Si coincide con el seleccionado, se marca como 'selected'
                    $seleccionado = ($key == $origen) ? "selected" : "";
                    echo "<option value='$key' $seleccionado>$key</option>";
                }
                ?>
            </select>
        </li>
        <li>
            <label for="destino">Destino:*</label>
            <select name="destino" required>
                <?php
                foreach ($cambios as $key => $valor) {
                    $seleccionado = ($key == $destino) ? "selected" : "";
                    echo "<option value='$key' $seleccionado>$key</option>";
                }
                ?>
            </select>
        </li>
        <li>
            <button class="submit" type="submit" name="convertir">Convertir</button>
        </li>
    </ul>
</form>

</body>
</html>
