<div class="container">
    <div class="columns break">

        <div class="column full-width">

            <h3>
                Awards
            </h3>

            <ul>
                <?php foreach ($awards as $award): ?>

                    <li>
                        <?= $award->name ?>
                        [<a href="/admin/awards/edit/<?=$award->id?>">edit</a>]
                        [<a href="/admin/awards/delete/<?=$award->id?>" class="confirm">delete</a>]
                    </li>

                <?php endforeach; ?>
            </ul>

            <a href="/admin/awards/add">Add award</a>


        </div>
    </div>
</div>
