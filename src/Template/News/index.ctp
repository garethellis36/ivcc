

<div class="container">

    <div class="columns break">

        <div class="column one-half">

            <img src="/img/ivcclarge.png" class="centered">

            <p>
                There was once a dream that was IVCC. You could only whisper it. Anything more than a whisper and it would vanish... it was so fragile. And we feared that it will not survive the 2013 winter. But survive it did, and so the gentlemen players got together in a backstreet boozer in OX4 and established Iffley Village Cricket Club in 2014. With the ethos behind the club being to play friendly 40-over and 20-over cricket matches against like-minded clubs around the country, our focus is as much on playing competitive cricket as it is enjoying a beer with the opposition after the game, and with the journey being far more important than the result.
            </p>
            <p>
                If you want to get in touch with us for any reason, please email <a href="mailto:committee@ivcc.co.uk?subject=IVCC">committee@ivcc.co.uk</a>.
                We are also on Twitter <a href="http://twitter.com/iffleyvillagecc" target="_blank">@IffleyVillageCC</a>.
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

                <h3>Latest news</h3>

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