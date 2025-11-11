<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fecha actual</title>
</head>
<body>
    <h2>Fecha actual en castellano</h2>
    <?php
        // Establecemos la zona horaria
        date_default_timezone_set("Europe/Madrid");

        // Creamos un formateador con idioma español y formato largo
        $formatter = new IntlDateFormatter(
            'es_ES',                    // Idioma
            IntlDateFormatter::FULL,    // Formato de fecha largo (lunes, 27 de octubre de 2025)
            IntlDateFormatter::NONE,    // Sin hora
            'Europe/Madrid',
            IntlDateFormatter::GREGORIAN
        );

        // Mostramos la fecha actual formateada
        echo "<p>" . $formatter->format(new DateTime()) . "</p>";
    ?>
</body>
</html>
