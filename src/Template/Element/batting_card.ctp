<?php if (isset($scorecard["ivcc_total"]) && !empty($scorecard["ivcc_total"])): ?>

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
                <?= $this->Player->truncateName($batsman['player'])
                . $this->Player->scorecardSymbols($batsman['player'], $scorecard) ?>
            </div>

            <?php if ($batsman["did_not_bat"] == 1): ?>
                <div class="column one-half">
                    did not bat
                </div>
            <?php else: ?>
                <div class="column one-fourth">
                    <?php if ($batsman["modes_of_dismissal"]["name"] == "Not out"): ?>
                        <em><?= strtolower($batsman["modes_of_dismissal"]["name"]) ?></em>
                    <?php else: ?>
                        <?= strtolower($batsman["modes_of_dismissal"]["name"]) ?>
                    <?php endif; ?>
                </div>
                <div class="column one-fourth text-right">
                    <?= $batsman["batting_runs"] ?>
                </div>
            <?php endif; ?>

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
        <div class="column one-fourth">
            <strong>Total</strong>
        </div>
        <div class="column three-fourths text-right">
            <?= $this->Scorecard->total($scorecard["ivcc_total"], $scorecard["ivcc_wickets"], $scorecard["ivcc_overs"]) ?>
        </div>
    </div>

<?php endif; ?>