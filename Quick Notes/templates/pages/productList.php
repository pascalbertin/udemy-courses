<div>
    <h3>Lista produktów</h3>
    <table>
        <thead>
            <tr>
                <th>Nazwa</th>
                <th>Cena</th>
                <th>Kategoria</th>
                <th>Waga</th>
            </tr>
        </thead>
    
        <tbody>
            <tr>
                <td><?php echo $params['name'] ?? '' ?></td>
                <td><?php echo $params['price'] ?? '' ?></td>
                <td><?php echo $params['category'] ?? '' ?></td>
                <td><?php echo $params['weight'] ?? '' ?></td>
            </tr>
        </tbody>
    </table>

</div>