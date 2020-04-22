<?php

declare(strict_types=1);

try {
    $bdd = new PDO('mysql:host=localhost;dbname=kdo', 'root', 'root');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Throwable $throwable) {
    die(print $throwable->getMessage());
}

$bdd->query('SET NAMES "utf8"');
