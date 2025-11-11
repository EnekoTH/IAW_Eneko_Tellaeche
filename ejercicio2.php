<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Suma y producto iguales</title>
</head>
<body>
    <h2>Números cuya suma de dígitos es igual al producto</h2>
    <?php
        // Recorremos los números del 100 al 999 (3 cifras)
        for ($n = 10; $n <= 999; $n++) {
            $digitos = str_split($n);
            $suma = array_sum($digitos);
            
            // Calculamos el producto de los dígitos
            $producto = 1;
            foreach ($digitos as $d) {
                $producto *= $d;
            }

            if ($suma == $producto) {
                echo $n . "<br>";
            }
        }
    ?>
</body>
</html>
