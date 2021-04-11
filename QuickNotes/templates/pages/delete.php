<div class="show">
    <?php $note = $params['note'] ?? null ?>
    <?php if ($note): ?>
        <ul>
            <li>Tytuł: <?php echo $note['title'] ?></li>
            <li><?php echo $note['description'] ?></li>
            <li>Utworzono: <?php echo $note['created'] ?></li>
        </ul>
        <form method="POST" action="/?action=delete">
            <input type="hidden" value="<?php echo $note['id'] ?>" name="id">
            <input type="submit" value="Usuń">
        </form>
    <?php endif; ?>
    <a href="/">
        <button>Powrót</button>
    </a>
</div>