<div class="columns break">

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
            <div class="columns break">
        <?php endif; ?>

        <div class="column one-third">
            <?= $this->Photo->showPhoto($photo, true) ?>
        </div>

    <?php endforeach; ?>

</div>