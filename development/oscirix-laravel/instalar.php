<?php
echo "<pre>";
// Forzamos la instalación de las dependencias definidas en composer.json
system('composer install 2>&1');
echo "</pre>";
echo "--- Instalación completada ---";
?>