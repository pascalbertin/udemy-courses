<div class="show">
    <?php $note = $params['note'] ?? null ?>
    <?php if ($note): ?>
        <ul>
            <li>Tytuł: <?php echo $note['title'] ?></li>
            <li><?php echo $note['description'] ?></li>
            <li>Utworzono: <?php echo $note['created'] ?></li>
        </ul>

        <a href="/?action=edit&id=<?php echo $note['id'] ?>">
            <button>Edytuj</button>
        </a>
    <?php endif; ?>
    <a href="/">
        <button>Powrót</button>
    </a>
</div>