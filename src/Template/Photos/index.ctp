<?php if ($this->Authorization->isAdmin()): ?>

    <div class="flash">
        <a href="/admin/photos/add">
            Add photo(s)
        </a>
    </div>

<?php endif; ?>

<div class="columns break padding-bottom">

    <?php
    $photosPerRow = 3;
    $numPhotos = count($photos);

    if ($numPhotos == 0) {
        echo "No photos found";
    }
    foreach ($photos as $k => $photo):
    ?>

        <?php if ($k % $photosPerRow == 0): ?>
            </div>
            <div class="columns break padding-bottom">
        <?php endif; ?>

        <div class="column one-third align-center">
            <?= $this->Html->link($this->Photo->showPhoto($photo, true), [
                    "action" => "view",
                    "controller" => "photos",
                    $photo->id
            ], [
                "escape" => false
            ]) ?>
        </div>

    <?php endforeach; ?>

</div>

<?= $this->Element("paginator") ?>