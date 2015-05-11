

<div class="container">

    <div class="columns">

        <div class="column one-half">

            <img src="/img/ivcclarge.png" class="centered">

            <p>
                We are a social cricket club based in Oxford. We play friendly 40- and 20-over cricket matches against like-minded clubs around the county.
            </p>

            <p>
                Want to contact us? Try howling at the moon.
            </p>
        </div>

        <div class="column one-half">

            <?php if ($this->Authorization->isAdmin()): ?>
                <div>[<a href="/admin/news/add">
                        Add news item
                    </a>]</div>
            <?php endif; ?>

            <?php if (empty($news)): ?>

                <p>No news items found.</p>

            <?php else: ?>

                <?php foreach ($news as $newsItem): ?>

                    <div class="columns">

                        <div class="column">
                            <h4><?= h($newsItem["title"]) ?></h4>
                        </div>

                    </div>

                    <div class="columns">

                        <div class="column">

                            <p><?= nl2br(h($newsItem['body'])) ?></p>

                            <small><em>Posted <?= $newsItem["created"]->format("jS M Y") ?> by <?= $newsItem["user"]["name"] ?></em></small>

                            <p>
                                <?php if ($this->Authorization->isAdmin()): ?>
                                    [<a href="/admin/news/edit/<?= $newsItem->id ?>">edit</a>]
                                    [<a href="/admin/news/delete/<?= $newsItem->id ?>" class="confirm">delete</a>]
                                <?php endif; ?>
                            </p>

                        </div>

                    </div>

                <?php endforeach; ?>

                <?= $this->Element("paginator") ?>

            <?php endif; ?>

        </div>

    </div>

</div>