<?php

declare(strict_types=1);

namespace QuickNotes\controllers;

use QuickNotes\Exception\ConfigurationException;
use QuickNotes\Request;
use QuickNotes\model\NoteModel;
use QuickNotes\Exception\StorageException;
use QuickNotes\View;

abstract class AbstractController
{
    protected const DEFAULT_ACTION = "list";

    private static array $configuration = [];

    protected Request $request;
    protected View $view;
    protected NoteModel $noteModel;

    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }

    public function __construct(Request $request)
    {
        if (empty(self::$configuration['db'])) {
            throw new ConfigurationException('Configuration Error');
        }

        $this->noteModel = new NoteModel(self::$configuration['db']);
        
        $this->request = $request;
        $this->view = new View();
    }

    public function run(): void
    {
        $action = $this->action() . 'Action';
        if (!method_exists($this, $action)) {
            $action = self::DEFAULT_ACTION . 'Action';
        }

        $this->$action();     
    }

    public function action(): string
    {
        return  $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}