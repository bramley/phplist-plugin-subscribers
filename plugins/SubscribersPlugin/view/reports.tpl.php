<div class="panel">
    <div class="content">
        <table>
<?php foreach ($links as $l) : ?>
            <tr>
                <td><label><?= $l['caption']; ?></label></td>
                <td><?= $l['button']; ?></td>
            </tr>
<?php endforeach; ?>
        </table>
    </div>
</div>
