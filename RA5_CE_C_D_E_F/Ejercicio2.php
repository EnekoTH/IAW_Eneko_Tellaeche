<?php
// Mostrar números primos entre 3 y 999
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
    }
}
?>
