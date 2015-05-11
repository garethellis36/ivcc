<div class="columns">

    <div class="column">

        <strong>
            IVCC innings
        </strong>

    </div>

</div>


<?php foreach ($batting as $batsman): ?>
    <div class="columns">

        <div class="column one-half">
            <?= $this->Player->name($batsman['player']) ?>
        </div>

        <div class="column one-fourth">
            <?php if ($batsman["did_not_bat"] == 1): ?>
                did not bat
            <?php else: ?>
                <?php if ($batsman["modes_of_dismissal"]["name"] == "Not out"): ?>
                    <em><?= strtolower($batsman["modes_of_dismissal"]["name"]) ?></em>
                <?php else: ?>
                    <?= strtolower($batsman["modes_of_dismissal"]["name"]) ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="column one-fourth text-right">
            <?= $batsman["batting_runs"] ?>
        </div>

    </div>
<?php endforeach; ?>

<div class="columns">
    <div class="column one-half">
        Extras
    </div>
    <div class="column one-fourth">&nbsp;</div>
    <div class="column one-fourth text-right"><?= $scorecard["ivcc_extras"] ?></div>
</div>

<div class="columns padding-bottom">
    <div class="column one-half">
        <strong>Total</strong>
    </div>
    <div class="column one-half text-right">
        <?= $this->Scorecard->total($scorecard["ivcc_total"], $scorecard["ivcc_wickets"], $scorecard["ivcc_overs"]) ?>
    </div>
</div>