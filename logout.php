<?php
include 'handler.php';

// Meghívjuk a session megsemmisítő függvényt
destroySession();

// Átirányítjuk a főoldalra
toHomePage();
?>