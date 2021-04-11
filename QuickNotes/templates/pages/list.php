<div class="list">
    <section>
        <div class="message">
        <?php
        if (!empty($params['before'])) {
            switch ($params['before']) {
            case 'created':
                echo 'Notatka zostało utworzona.';
                break;
            case 'edited':
                echo 'Notatka została zaktualizowana.';
                break;
            case 'deleted':
                echo 'Notatka została usunięta.';
                break;    
            }   
        }
        ?>
        </div>

        <div class="message">
        <?php
        if (!empty($params['error'])) {
            switch ($params['error']) {
            case 'noteNotFound':
                echo 'Nie znaleziono notatki.';
                break;
            }
        }
        ?>
        </div>

        <?php
        $sort = $params['sort'];
        $by = $sort['by'];
        $order = $sort['order'];

        $page = $params['page'] ?? [];
        $size = $page['size'] ?? 10;
        $currentPageNumber = $page['number'] ?? 1;
        $pages = $page['pages'] ?? 1;

        $phrase = $params['phrase'] ?? null;

        ?>

        <div>
            <form action="/" method="GET" class="settings-form">
                <div>
                    <label>Szukaj: <input type="text" name="phrase" value="<?php echo $phrase ?>"></label>
                </div>
                
                <div>
                    <div>Sortuj po:</div>
                    <label>Tytule:<input type="radio" value="title" name="sortby"
                    <?php echo $by === 'title' ? 'checked' : '' ?>></label>
                    <label>Dacie:<input type="radio" value="created" name="sortby"
                    <?php echo $by === 'created' ? 'checked' : '' ?>></label>
                </div>
                <div>
                    <div>Kierunek sortowania:</div>
                    <label>Rosnąco:<input type="radio" value="asc" name="sortorder"
                    <?php echo $order === 'asc' ? 'checked' : '' ?>></label>
                    <label>Malejąco:<input type="radio" value="desc" name="sortorder"
                    <?php echo $order === 'desc' ? 'checked' : '' ?>></label>
                </div>
                <div>
                    <div>Wyświetlaj:</div>
                    <label>1 <input name="pagesize" value="1" type="radio" 
                    <?php echo $size === 1 ? 'checked' : ''  ?>></label>
                    <label>5 <input name="pagesize" value="5" type="radio" 
                    <?php echo $size === 5 ? 'checked' : ''  ?>></label>
                    <label>10 <input name="pagesize" value="10" type="radio" 
                    <?php echo $size === 10 ? 'checked' : ''  ?>></label>
                    <label>25 <input name="pagesize" value="25" type="radio" 
                    <?php echo $size === 25 ? 'checked' : ''  ?>></label>
                </div>
                <input type="submit" value="Zastosuj">
            </form>
        </div>

        <div class="tbl-header">
        <table cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr>
                <th>Id</th>
                <th>Tytuł</th>
                <th>Data</th>
                <th>Opcje</th>
            </tr>
            </thead>
        </table>
        </div>
        <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0">
                <tbody>
                    <?php foreach($params['notes'] ?? [] as $note): ?>
                        <tr>
                            <td><?php echo $note['id'] ?></td>
                            <td><?php echo $note['title'] ?></td>
                            <td><?php echo $note['created'] ?></td>
                            <td>
                                <a href="/?action=show&id=<?php echo $note['id'] ?>">
                                    <button>Pokaż</button>
                                </a>
                                <a href="/?action=delete&id=<?php echo $note['id'] ?>">
                                    <button>Usuń</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php
            $paginationUrl = "&phrase=$phrase&pagesize=$size&sortby=$by&sortorder=$order";
        ?>

        <ul class="pagination">
            <?php if($currentPageNumber !== 1): ?>
            <li>
                <a href="/?page=<?php echo $currentPageNumber - 1 . $paginationUrl?>">
                    <button>Prev</button>
                </a>
            </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <li>
                    <a href="/?page=<?php echo $i . $paginationUrl?>">
                        <button><?php echo $i; ?></button>
                    </a>
                </li>
            <?php endfor;?>
            <?php if($currentPageNumber < $pages): ?>
            <li>
                <a href="/?page=<?php echo $currentPageNumber + 1 . $paginationUrl?>">
                    <button>Next</button>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </section>
</div>