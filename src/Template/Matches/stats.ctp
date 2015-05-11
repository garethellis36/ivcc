<div class="columns">

    <div class="one-fifth column">

        <?php foreach ($years as $y): ?>
            <ul class="filter-list">
                <li>
                    <a href="/stats?year=<?= strtolower($y) ?>" class="filter-item<?php echo ($year == strtolower($y) ? " selected" : ""); ?>">
                        <?= $y ?>
                    </a>
                </li>
            </ul>
        <?php endforeach; ?>

    </div>

    <div class="four-fifths column">

        <h2>Stats</h2>

        <div class="columns padding-bottom">
            <div class="column one-fifth bold">
                <strong>Highest team total</strong>
            </div>
            <div class="column four-fifths">
                <?php if ($stats["highestScore"]): ?>
                    <?= $stats['highestScore']->ivcc_total ?>
                    vs <?= h($stats['highestScore']->opposition) ?>,
                    <?= $stats['highestScore']->date->format("jS M Y") ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </div>
        </div>

        <div class="columns padding-bottom">
            <div class="column one-fifth">
                <strong>Lowest team total</strong>
            </div>
            <div class="column four-fifths">
                <?php if ($stats["lowestScore"]): ?>
                    <?= $stats['lowestScore']->ivcc_total ?>
                    vs <?= h($stats['lowestScore']->opposition) ?>,
                    <?= $stats['lowestScore']->date->format("jS M Y") ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </div>
        </div>

        <div class="columns padding-bottom">
            <div class="column one-fifth">
                <strong>Leading run scorer</strong>
            </div>
            <div class="column four-fifths">
                <?php if ($stats["leadingRunScorer"]): ?>
                    <?= number_format($stats["leadingRunScorer"][0]->total) ?>:&nbsp;
                    <?php
                    $first = true;
                    foreach ($stats["leadingRunScorer"] as $leadingRunScorer): ?>
                        <?php if ($first): $first = false; else: echo ",&nbsp;"; endif; ?>
                        <?= $this->Player->name($leadingRunScorer->player) ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </div>
        </div>

        <div class="columns padding-bottom">
            <div class="column one-fifth">
                <strong>Leading wicket taker</strong>
            </div>
            <div class="column four-fifths">
                <?php if ($stats["leadingWicketTaker"]): ?>
                    <?= number_format($stats["leadingWicketTaker"][0]->total) ?>:&nbsp;
                    <?php
                    $first = true;
                    foreach ($stats["leadingWicketTaker"] as $leadingWicketTaker): ?>
                        <?php if ($first): $first = false; else: echo ",&nbsp;"; endif; ?>
                        <?= $this->Player->name($leadingWicketTaker->player) ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </div>
        </div>



        <div class="columns padding-bottom">
            <div class="column one-fifth">
                <strong>Highest individual score</strong>
            </div>
            <div class="column four-fifths">
                <?php if ($stats["highestIndividualScore"]): ?>
                    <?= $this->Player->name($stats["highestIndividualScore"]->player) ?>:
                    <?= $stats['highestIndividualScore']->batting_runs ?><?php if ($stats["highestIndividualScore"]->modes_of_dismissal->not_out == 1): ?>*<?php endif; ?>
                    vs <?= h($stats['highestIndividualScore']->match->opposition) ?>,
                    <?= $stats['highestIndividualScore']->match->date->format("jS M Y") ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </div>
        </div>

        <div class="columns padding-bottom">
            <div class="column one-fifth">
                <strong>Best individual bowling</strong>
            </div>
            <div class="column four-fifths">
                <?php if ($stats["bestBowling"]): ?>
                    <?= $this->Player->name($stats["bestBowling"]->player) ?>:
                    <?= $stats['bestBowling']->bowling_wickets ?>
                    for <?= $stats["bestBowling"]->bowling_runs ?>
                    vs <?= h($stats['bestBowling']->match->opposition) ?>,
                    <?= $stats['bestBowling']->match->date->format("jS M Y") ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </div>
        </div>

        <div class="columns padding-bottom">
            <div class="column one-fifth">
                <strong>Semi-Centurions</strong>
            </div>
            <div class="column four-fifths">
                <?php if ($stats["fifties"]->count() > 0): ?>
                    <?php foreach ($stats["fifties"] as $fifty): ?>
                        <?= $this->Player->name($fifty->player) ?>
                        (<?= $fifty->batting_runs . ($fifty->modes_of_dismissal->not_out == 1 ? "*" : "") ?>)<br>
                    <?php endforeach; ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </div>
        </div>

        <div class="columns padding-bottom">
            <div class="column one-fifth">
                <strong>Michelle Pfive-fors</strong>
            </div>
            <div class="column four-fifths">
                <?php if ($stats["fivefors"]->count() > 0): ?>
                    <?php foreach ($stats["fivefors"] as $fivefor): ?>
                        <?= $this->Player->name($fivefor->player) ?>
                        (<?= $fivefor->bowling_wickets . " for " . $fivefor->bowling_runs ?>)<br>
                    <?php endforeach; ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </div>
        </div>

        <div class="columns padding-bottom">
            <div class="column one-fifth">
                <strong>Club Zero</strong>
            </div>
            <div class="column four-fifths">
                <?php if ($stats["ducks"]->count() > 0): ?>
                    <?php foreach ($stats["ducks"] as $duck): ?>
                    <?= $this->Player->name($duck->player) ?><br>
                    <?php endforeach; ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </div>
        </div>

        <div class="padding-bottom">
            <h2>Batting averages</h2>

            <table>
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Innings</th>
                        <th>Not out</th>
                        <th>Runs</th>
                        <th>Average</th>
                        <th>High score</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($batsmen as $batsman): ?>
                    <tr>
                        <td><?= h($batsman->name) ?></td>
                        <td><?= number_format($batsman->batting_innings) ?></td>
                        <td><?= number_format($batsman->batting_not_out) ?></td>
                        <td><?= number_format($batsman->batting_runs) ?></td>
                        <td><?= $batsman->batting_average ?></td>
                        <td>
                            <?= $batsman->batting_high_score->batting_runs . ($batsman->batting_high_score->modes_of_dismissal->not_out == 1 ? "*" : "") ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="padding-bottom">

            <h2>Bowling averages</h2>

            <table>
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Overs</th>
                    <th>Maidens</th>
                    <th>Runs</th>
                    <th>Wickets</th>
                    <th>Average</th>
                    <th>Economy</th>
                    <th>Best bowling</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($bowlers as $bowler): ?>
                    <tr>
                        <td><?= h($bowler->name) ?></td>
                        <td><?= number_format($bowler->bowling_overs) ?></td>
                        <td><?= number_format($bowler->bowling_maidens) ?></td>
                        <td><?= number_format($bowler->bowling_runs) ?></td>
                        <td><?= number_format($bowler->bowling_wickets) ?></td>
                        <td><?= $bowler->bowling_average ?></td>
                        <td><?= $bowler->bowling_economy ?></td>
                        <td>
                            <?= $bowler->best_bowling->bowling_wickets . " for " . $bowler->best_bowling->bowling_runs ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

    </div>

</div>