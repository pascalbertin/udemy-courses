<?php

declare(strict_types = 1);

namespace QuickNotes\controllers;

use QuickNotes\Exception\NotFoundException;

class NoteController extends AbstractController
{
    private const PAGE_SIZE = 10;

    public function createAction(): void
    {
        if ($this->request->hasPost()) {
            $noteData = [
                'title' => $this->request->postParam('title'),
                'description' => $this->request->postParam('description')
            ];
            $this->noteModel->create($noteData);
            header("Location: /?before=created");
            exit();
        }

        $this->view->renderLayout('create');
    }

    public function showAction(): void
    {
        $this->view->renderLayout(
            'show', 
            ['note' => $this->getNote()]
        );
    }

    public function listAction(): void
    {
        $phrase = $this->request->getParam('phrase');

        $pageNumber = (int) $this->request->getParam('page', 1);
        $pageSize = (int) $this->request->getParam('pagesize', self::PAGE_SIZE);

        $sortBy = $this->request->getParam('sortby', 'title');
        $sortOrder = $this->request->getParam('sortorder', 'desc');

        if (!in_array($pageSize, [1, 5, 10, 25])) {
            $pageSize = self::PAGE_SIZE;
        }

        if ($phrase) {
            $noteList = $this->noteModel->search($phrase, $pageSize, $pageNumber, $sortBy, $sortOrder);
            $notes = $this->noteModel->searchCount($phrase);
        } else {
            $noteList = $this->noteModel->list($pageSize, $pageNumber, $sortBy, $sortOrder);
            $notes = $this->noteModel->count();
        }

        $this->view->renderLayout(
            'list',
            [
                'page' => ['number' => $pageNumber, 'size' => $pageSize, 'pages' => (int) ceil($notes / $pageSize)],
                'sort' => ['by' => $sortBy, 'order' => $sortOrder],
                'phrase' => $phrase,
                'notes' => $noteList,
                'before' => $this->request->getParam('before'),
                'error' => $this->request->getParam('error')
            ]
        );
    }

    public function editAction(): void
    {
        if ($this->request->isPost()) {
            $noteId = (int) $this->request->postParam('id');
            $noteData = [
                'title' => $this->request->postParam('title'),
                'description' => $this->request->postParam('description')
            ];
            $this->noteModel->edit($noteId, $noteData);

            header("Location: /?before=edited");
            exit();
        }

        $this->view->renderLayout(
            'edit',
            ['note' => $this->getNote()]
        );
    }

    public function deleteAction(): void
    {
        if ($this->request->isPost()) {
            $id = (int) $this->request->postParam('id');
            $this->noteModel->delete($id);
            header("Location: /?before=deleted");
            exit();
        }

        $this->view->renderLayout(
            'delete', 
            ['note' => $this->getNote()]
        );
    }

    final private function getNote(): array
    {
        $noteId = (int) $this->request->getParam('id');
        if (!$noteId) {
            header("Location: /?error=missingNoteId");
            exit();
        }

        try {
            $note = $this->noteModel->get($noteId);
        } catch(NotFoundException $e) {
            header("Location: /?error=noteNotFound");
            exit();
        }

        return $note;
    }
}