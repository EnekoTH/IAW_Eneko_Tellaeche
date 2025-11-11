<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Buscador de películas</title>
    <link rel="stylesheet" media="screen" href="css/estilo.css">
</head>
<body>

<?php
// Array de películas
$peliculas = array(
    "El pianista", "El caballero oscuro", "Origen", "Memento",
    "El lobo de Wall Street", "12 años de esclavitud", "Spotlight",
    "Amelie", "Malditos bastardos", "No es país para viejos"
);

// Array con las imágenes correspondientes (nombres de archivo)
$imagenes = array(
    "pianista.jpg", "caballero.jpg", "origen.jpg", "memento.jpg",
    "lobo.jpg", "12años.jpg", "spotlight.jpg",
    "amelie.jpg", "bastardos.jpg", "noespais.jpg"
);

// Función para convertir a minúsculas
function paso_a_minusculas($texto)
{
    return strtolower($texto);
}

// Variable para mantener el valor del buscador
$buscador_valor = "";

if(isset($_POST["buscar"]))
{
    $buscador_valor = $_POST["buscador"];
    $buscador = paso_a_minusculas($buscador_valor);

    $encontradas = array(); // Array para guardar coincidencias

    foreach($peliculas as $indice => $peli)
    {
        if(strpos(strtolower($peli), $buscador) !== false)
        {
            $encontradas[] = $indice; // Guardamos el índice de la película encontrada
        }
    }

    $num = count($encontradas);
    echo "<p>Se han encontrado $num películas:</p>";
    
    if($num > 0)
    {
        echo "<ul>";
        foreach($encontradas as $i)
        {
            echo "<li>";
            echo "<img src='imagenes/".$imagenes[$i]."' alt='".$peliculas[$i]."' width='100'> ";
            echo $peliculas[$i];
            echo "</li>";
        }
        echo "</ul>";
    }
    else
    {
        echo "<p>No se encontraron películas.</p>";
    }
}
?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="formulario">
    <ul>
        <li>
            <h2>Buscador de películas</h2>
        </li>
        <li>
            <label for="buscador">Buscador:</label>
            <input type="text" name="buscador" value="<?php echo htmlspecialchars($buscador_valor); ?>">
        </li>
        <li>
            <button class="submit" type="submit" name="buscar">Buscar</button>
        </li>
    </ul>
</form>

</body>
</html>
