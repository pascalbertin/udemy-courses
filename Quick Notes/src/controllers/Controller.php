<?php

declare(strict_types=1);

namespace QuickNotes\controllers;

use QuickNotes\Request;
use QuickNotes\View;

class Controller 
{
    const DEFAULT_ACTION = 'productList';

    private Request $request;
    private View $layout;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function run(): void
    {
        $this->layout = new View();
        $action = $this->request->getGetArray('action');

        $viewParams = [
            'name' => $this->request->getPostArray('product-name'),
            'description' => $this->request->getPostArray('product-description'),
            'price' => $this->request->getPostArray('product-price'),
            'category' => $this->request->getPostArray('product-category'),
            'weight' => $this->request->getPostArray('product-weight')
        ];

        switch ($action) {
            case 'add':
                $page = 'add';
                break;
            default:
                $page = self::DEFAULT_ACTION;
                break;
        }

        $this->layout->render($page, $viewParams);
    }
    
}