<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

function dump($param): void
{
    echo '
        <div style="background-color: lightgray;
                    display: inline-block;
                    padding: 10px;
                    border: 2px dotted black">
            <pre>';
                print_r($param);
    echo '  </pre>
        </div>';
}