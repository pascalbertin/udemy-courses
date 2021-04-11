<?php

declare(strict_types=1);

namespace QuickNotes;

use QuickNotes\controllers\Controller;

require_once("src/utils/debug.php");
require_once("src/View.php");
require_once("src/Request.php");
require_once("src/controllers/Controller.php");

$request = new Request($_GET, $_POST);
(new Controller($request))->run();