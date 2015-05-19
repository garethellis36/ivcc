<a href="/matches?year=<?= $scorecard["date"]->format("Y") ?>">&lt;&lt; back to match list</a>

<h2>
    <?= $scorecard["date"]->format("D jS M Y") ?> vs. <?= h($scorecard["opposition"]) ?> (<?= $scorecard["venue"] ?>)
</h2>

<?php if ($this->Authorization->isAdmin()): ?>
    [<a href="/admin/matches/edit/<?= $scorecard["id"] ?>">edit</a>]
<?php endif; ?>

<div class="columns break">
    <div class="column one-third">

            <p>
                <strong>Format:</strong> <?= $scorecard["format"]["name"] ?>
                <br>
                <strong>Result:</strong> <?= $scorecard["result"] ?>
                <?php if (isset($scorecard["result_more"]) && !empty($scorecard["result_more"])): ?>
                    by <?= h($scorecard["result_more"]) ?>
                <?php endif; ?>
            </p>

            <?php if ($scorecard["ivcc_batted_first"] == 1): ?>
                <?= $this->Element("batting_card") ?>
                <?= $this->Element("bowling_card") ?>
            <?php else: ?>
                <?= $this->Element("bowling_card") ?>
                <?= $this->Element("batting_card") ?>
            <?php endif; ?>

    </div>

    <?php if (!empty($scorecard["match_report"])): ?>
        <div class="column two-thirds">
            <h4>Match report</h4>
            <?= nl2br(h($scorecard["match_report"])) ?>
        </div>
    <?php endif; ?>

</div>
