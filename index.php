<?php
session_set_cookie_params(2592000); // Durée de vie des cookies de 2592000s = 30j.
session_name("mymeals");
session_start();

setlocale(LC_ALL, "French");

$path = join(DIRECTORY_SEPARATOR, array(__DIR__, 'Framework', 'App.php'));
include($path);
use \Framework\App;

$app = new App();
echo $app->Run();