<!DOCTYPE html>
<html>
<head>
    <title>Ejercicio 8</title>
    <link rel="stylesheet" media="screen" href="css/estilo.css" >
</head>
<body>

<?php
ini_set("display_errors", true);
require_once 'funcionesBaseDatos.php';
session_start();

if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"] == null) {
    header("Location: login_MySQLi.php");
    exit;
}

// Si venimos de pulsar "Actualizar"
if (isset($_POST["actualizar"])) {
    $librosanyos  = isset($_POST["librosanyos"])  ? $_POST["librosanyos"]  : [];
    $anyo_edicion = isset($_POST["anyo_edicion"]) ? $_POST["anyo_edicion"] : [];

    // Recorremos arrays paralelos numero_ejemplar / anyo_edicion
    if (!empty($librosanyos) && !empty($anyo_edicion)) {
        $total = count($librosanyos);
        for ($i = 0; $i < $total; $i++) {
            $num  = (int)$librosanyos[$i];
            $anyo = (int)$anyo_edicion[$i];
            modificarLibroAnyo_MySQLi($num, $anyo);
        }
        echo "<div class='aviso'>Actualizados los años</div>";
    }
}
?>

<form class="formulario" action="" method="post" name="formulario">
    <ul>
        <li>
             <h2>Libros que se van a actualizar</h2>
             <span class="mensaje_obligatorio">* Campo obligatorio</span>
        </li>

        <li>
            <label for="libro">Libros:*</label>
            <select name="libro">
                <?php
                    $libros = getLibrosTitulo_MySQLi();
                    foreach ($libros as $libro) {
                        // Suponiendo que getLibrosTitulo_MySQLi devuelve objetos con ->titulo
                        $titulo = $libro->titulo;
                        echo "<option value='$titulo'";
                        if (isset($_POST['libro']) && $titulo == $_POST['libro']) {
                            echo " selected='true'";
                        }
                        echo ">$titulo</option>";
                    }
                ?>
            </select>
        </li>

        <li>
            <button class="submit" type="submit" name="mostrar">Mostrar</button>
        </li>
    </ul>
</form>

<?php
// Comprobamos si tenemos que mostrar los libros del título elegido
if (isset($_POST['mostrar']) && !empty($_POST['libro'])) {
    $libro = $_POST['libro'];
    // Obtiene un array con toda la información de los libros que coinciden con el título
    $librosanyos = getLibrosAnyo_MySQLi($libro);
?>
<form id="actualizar" method="post" action="">
<table class="tabla">
<thead>
    <tr>
        <th>Titulo</th>
        <th>Anyo Edicion</th>
    </tr>
</thead>
<tbody>
    <?php
        foreach ($librosanyos as $libroanyo) {
            // Mantener el título seleccionado
            echo "<input type='hidden' name='libro' value='" . htmlspecialchars($libro, ENT_QUOTES) . "'>";
            // Mantener el número de ejemplar en un array oculto
            echo "<tr>";
            echo "<input type='hidden' name='librosanyos[]' value='{$libroanyo['numero_ejemplar']}'>";
            echo "<td>" . htmlspecialchars($libroanyo['titulo'], ENT_QUOTES) . "</td>";
            // Campo editable de año
            echo "<td><input type='text' size='4' name='anyo_edicion[]' value='{$libroanyo['anyo_edicion']}'> Años </td>";
            echo "</tr>";
        }
    ?>
</tbody>
</table>
    <button class="submit actualizar" type="submit" name="actualizar">Actualizar</button>
</form>
<?php
}
?>

<br>
<a href="indice_MySQLi.php">Volver</a>

</body>
</html>
