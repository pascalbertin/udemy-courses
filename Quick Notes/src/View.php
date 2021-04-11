<?php

declare(strict_types=1);

namespace QuickNotes;

class View
{
    public function render($page, $params): void
    {
        require_once("templates/layout.php");
    }
}