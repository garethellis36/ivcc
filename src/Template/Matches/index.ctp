<div class="columns break">

    <div class="one-fifth column">

        <?php foreach ($years as $y): ?>
        <ul class="filter-list">
            <li>
                <a href="/matches?year=<?= $y ?>" class="filter-item<?php echo ($year == $y ? " selected" : ""); ?>">
                    <?= $y ?>
                </a>
            </li>
        </ul>
        <?php endforeach; ?>

    </div>

    <div class="four-fifths column">

        <h1>Matches</h1>

        <?php if (empty($matches)): ?>
            <p>No matches found.</p>
        <?php else: ?>

            <?php foreach ($matches as $match): ?>

                <div class="horizontal-line padding-bottom"></div>

                <div class="columns break">

                    <div class="column one-fourth smaller-bottom-margin">
                        <strong>Date</strong>
                    </div>
                    <div class="column three-fourths">
                        <?= $match->date->format("D jS M Y @ H:i") ?>
                    </div>

                </div>

                <div class="columns break">

                    <div class="column one-fourth smaller-bottom-margin">
                        <strong>Opposition</strong>
                    </div>
                    <div class="column three-fourths">
                        <?= h($match->opposition) ?>
                        (<?= h($match->venue) ?>)
                    </div>

                </div>

                <div class="columns break">

                    <div class="column one-fourth smaller-bottom-margin">
                        <strong>Format</strong>
                    </div>
                    <div class="column three-fourths">
                        <?= h($match->format->name) ?>
                    </div>

                </div>

                <?php if ($match->date->format("Y-m-d") <= date("Y-m-d")): ?>

                    <div class="columns break">

                        <div class="column one-fourth smaller-bottom-margin">
                            <strong>Result</strong>
                        </div>

                        <div class="column three-fourths">
                            <?php if (isset($match["result"])): ?>
                                <?= $match["result"] ?>
                                <?php if (!empty($match["result_more"])): ?>
                                    by <?= h($match["result_more"]) ?>
                                <?php endif; ?><br>
                                <a href="/matches/view/<?= $match["id"] ?>">
                                    Match report &amp; scorecard
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </div>

                    </div>

                <?php endif; ?>

                <?php if ($this->Authorization->isAdmin()): ?>
                    <div class="columns break">

                        <div class="column one-fourth smaller-bottom-margin">
                            &nbsp;
                        </div>

                        <div class="column three-fourths">
                            [<a href="/admin/matches/edit/<?= $match->id ?>">edit</a>]
                            [<a href="/admin/matches/delete/<?= $match->id ?>" class="confirm">delete</a>]
                        </div>

                    </div>
                <?php endif; ?>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>

<?php if ($this->Authorization->isAdmin()): ?>

    <div class="flash-messages">
        <div class="flash">
            <a href="/admin/matches/add">
                Add match
            </a>
        </div>
    </div>

<?php endif; ?>