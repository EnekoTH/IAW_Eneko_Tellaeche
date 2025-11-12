<?php
$contador = 0;
for ($num = 3; $num <= 999; $num++) {
    $esPrimo = true;

    for ($div = 2; $div <= sqrt($num); $div++) {
        if ($num % $div == 0) {
            $esPrimo = false;
            break;
        }
    }
    if ($esPrimo) {
        echo $num . " ";
        $contador++;

        // Cada 10 números, salto de línea
        if ($contador % 10 == 0) {
            echo "<br>";
        }
    }
}
?>
