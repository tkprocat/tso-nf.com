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
    foreach($accumulatedLoot as $loot) {
        if ($i % 2 == 0) {
            echo '<tr>';
            echo '<td>'.$loot->type.'</td>';
            echo '<td><div class="show-tooltip">'.$loot->amount.'</div></td>';
        } else {
            echo '<td>'.$loot->type.'</td>';
            echo '<td><div class="show-tooltip">'.$loot->amount.'</div></td>';
            echo '</tr>';
        }
        $i++;
    }
    ?>
</table>