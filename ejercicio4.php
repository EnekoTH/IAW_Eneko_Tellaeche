<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobar número primo</title>
</head>
<body>
    <h2>Comprobar si un número es primo</h2>

    <form method="post">
        <label>Introduce un número: </label>
        <input type="number" name="numero" min="0" required>
        <input type="submit" value="Comprobar">
    </form>

    <?php
        if (isset($_POST['numero'])) {
            $num = (int)$_POST['numero'];
            $esPrimo = true;

            if ($num <= 1) {
                $esPrimo = false;
            } else {
                for ($i = 2; $i <= sqrt($num); $i++) {
                    if ($num % $i == 0) {
                        $esPrimo = false;
                        break;
                    }
                }
            }

            if ($esPrimo) {
                echo "<p>$num es un número primo</p>";
            } else {
                echo "<p>$num no es primo</p>";
            }
        }
    ?>
</body>
</html>
