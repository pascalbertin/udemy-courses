<?php

declare(strict_types = 1);

spl_autoload_register(function (string $classNamespace) {
    $trimPath = str_replace(['\\', 'QuickNotes/'], ['/', ''], $classNamespace);
    $path = "src/$trimPath.php";
    require_once($path);
});

require_once("src/utils/debug.php");
$configuration = require_once("config/config.php");

use QuickNotes\Exception\ConfigurationException;
use QuickNotes\Exception\QuickNotesException;
use QuickNotes\Request;
use QuickNotes\controllers\AbstractController;
use QuickNotes\controllers\NoteController;

$request = new Request($_GET, $_POST, $_SERVER);

try {
    AbstractController::initConfiguration($configuration);
    (new NoteController($request))->run();
} catch(ConfigurationException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo '<h3>Skontaktuj się z administratorem</h3>';
} catch(QuickNotesException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo '<h3>' . $e->getMessage() . '</h3>';
} catch(\Throwable $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo '<h3>' . $e->getMessage() . '</h3>';
}