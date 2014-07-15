<table class="table table-striped table-bordered" id="accumulatedloot">
    <thead>
    <tr>
        <th>Loot</th>
        <th>Amount</th>
        <th>Loot</th>
        <th>Amount</th>
    </tr>
    </thead>
    <?php
    $i = 0;
    foreach($accumulatedloot as $loot) {
        if ($i % 2 == 0) {
            echo '<tr>';
            echo '<td>'.$loot->Type.'</td>';
            echo '<td><div class="show-tooltip">'.$loot->Amount.'</div></td>';
        } else {
            echo '<td>'.$loot->Type.'</td>';
            echo '<td><div class="show-tooltip">'.$loot->Amount.'</div></td>';
            echo '</tr>';
        }
        $i++;
    }
    ?>
</table>