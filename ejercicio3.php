<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factorial interactivo</title>
</head>
<body>
    <h2>Calcular el factorial de un número</h2>

    <form method="post">
        <label>Introduce un número: </label>
        <input type="number" name="numero" min="0" required>
        <input type="submit" value="Calcular">
    </form>

    <?php
        if (isset($_POST['numero'])) {
            $num = (int)$_POST['numero'];
            $factorial = 1;

            for ($i = 1; $i <= $num; $i++) {
                $factorial *= $i;
            }

            echo "<p>El factorial de $num es: $factorial</p>";
        }
    ?>
</body>
</html>
