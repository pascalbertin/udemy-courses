<?php

declare(strict_types = 1);

namespace QuickNotes;

class View
{
    public function renderLayout(string $page, array $params = []): void
    {
        $params = $this->escapeData($params);
        require_once("templates/layout.php");
    }

    private function escapeData(array $params): array
    {
        $clearedParams = [];
    
        foreach ($params as $key => $param) {
            switch (true) {
                case is_array($param):
                    $clearedParams[$key] = $this->escapeData($param);
                    break;
                case is_int($param):
                    $clearedParams[$key] = $param;
                    break;
                case $param:
                    $clearedParams[$key] = htmlentities($param);
                    break;
                default:
                    $clearedParams[$key] = $param;
                    break; 
            }
        }

        return $clearedParams;
    }
}
