<?php

try {
    $conection = new PDO("mysql:host=$host;dbname=$db", $user, $password);
} catch (Exception $ex) {
    echo $ex->getMessage();
}
