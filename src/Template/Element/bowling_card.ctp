<?php if(isset($scorecard["opposition_total"]) && !empty($scorecard["opposition_total"])): ?>
    <div class="columns">

        <div class="column">

            <strong>
                <?= $scorecard["opposition"] ?> innings
            </strong>

        </div>

    </div>

    <div class="columns padding-bottom">
        <div class="column one-fourth">
            <strong>Total</strong>
        </div>
        <div class="column three-fourths text-right">
            <?= $this->Scorecard->total($scorecard["opposition_total"], $scorecard["opposition_wickets"], $scorecard["opposition_overs"]) ?>
        </div>
    </div>

    <?php if (count($bowling)): ?>
        <div class="columns">
            <div class="column">
                <strong>IVCC bowling</strong>
            </div>
        </div>

        <?php foreach ($bowling as $k => $bowler): ?>

        <?php if (!isset($bowler["bowling_order_no"])) {
            continue;
        }
        $last = false;
        if ($k+1 == count($bowling)) {
            $last = true;
        } ?>

            <div class="columns<?= ($last ? " padding-bottom" : "") ?>">
                <div class="column one-half">
                    <?= $this->Player->truncateName($bowler['player']) ?>
                </div>
                <div class="column one-half">
                    <?= $bowler["bowling_overs"] . "-" . $bowler["bowling_maidens"] . "-" . $bowler["bowling_runs"] . "-" . $bowler["bowling_wickets"] ?>
                </div>
            </div>

        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>