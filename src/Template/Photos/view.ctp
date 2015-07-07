<div class="align-center">
    <?php if ($next): ?>
        <?= $this->Html->link($this->Photo->showPhoto($photo), [
            "controller" => "photos",
            "action" => "view",
            $next->id
        ], [
            "escape" => false
        ]) ?>
    <?php else: ?>
        <?= $this->Photo->showPhoto($photo) ?>
    <?php endif; ?>
</div>

<div class="columns">


    <div class="column one-fifth">
        <?php if ($prev): ?>
            <a href="/photos/view/<?= $prev->id ?>" class="prev-link">
                Previous
            </a>
        <?php else: ?>
            &nbsp;
        <?php endif; ?>
    </div>

    <div class="column three-fifths align-center"><a href="/photos">View all photos</a></div>

    <div class="column one-fifth align-right">
        <?php if ($next): ?>
            <a href="/photos/view/<?= $next->id ?>" class="next-link">
                Next
            </a>
        <?php else: ?>
            &nbsp;
        <?php endif; ?>
    </div>


</div>
<div class="columns">
    <p>
        <?php if ($photo->title): ?>
            <strong>
                <?= h($photo->title) ?>
            </strong><br>
        <?php endif; ?>
        <?= $photo->date->format("D jS F Y") ?>
    </p>
</div>

<?php if ($this->Authorization->isAdmin()): ?>

    <div class="flash">
        <a href="/admin/photos/edit/<?= $photo->id ?>" class="margin-right">
            Edit photo
        </a>
        <a href="/admin/photos/delete/<?= $photo->id ?>" class="margin-right confirm">
            Delete photo
        </a>
    </div>

<?php endif; ?>

<?= $this->Html->scriptBlock("

    var prevUrl = document.getElementsByClassName('prev-link');
    var nextUrl = document.getElementsByClassName('next-link');

    var leftArrow = 37;
    var rightArrow = 39;

    document.onkeyup = function(evt) {

        evt = evt || window.event;

        if (evt.keyCode == leftArrow && prevUrl.length > 0) {
            return window.location = prevUrl[0].getAttribute('href');
        }

        if (evt.keyCode == rightArrow && nextUrl.length > 0) {
            return window.location = nextUrl[0].getAttribute('href');
        }

    };

", ['block' => 'scriptBottom']) ?>