<div class="columns break">

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

        <div class="columns padding-bottom break">
            <div class="column one-fifth smaller-bottom-margin">
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

        <div class="columns padding-bottom break">
            <div class="column one-fifth smaller-bottom-margin">
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

        <div class="columns padding-bottom break">
            <div class="column one-fifth smaller-bottom-margin">
                <strong>Leading run scorer</strong>
            </div>
            <div class="column four-fifths">
                <?= $this->Stats->leading($stats, "leadingRunScorer") ?>
            </div>
        </div>

        <div class="columns padding-bottom break">
            <div class="column one-fifth smaller-bottom-margin">
                <strong>Leading wicket taker</strong>
            </div>
            <div class="column four-fifths">
                <?= $this->Stats->leading($stats, "leadingWicketTaker") ?>
            </div>
        </div>



        <div class="columns padding-bottom break">
            <div class="column one-fifth smaller-bottom-margin">
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

        <div class="columns padding-bottom break">
            <div class="column one-fifth smaller-bottom-margin">
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

        <div class="columns padding-bottom break">
            <div class="column one-fifth smaller-bottom-margin">
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

        <div class="columns padding-bottom break">
            <div class="column one-fifth smaller-bottom-margin">
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

        <div class="columns padding-bottom break">
            <div class="column one-fifth smaller-bottom-margin">
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

        <?php if (count($batsmen) > 0): ?>
            <div class="padding-bottom">
                <h2>Batting averages</h2>

                <table>
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>I</th>
                            <th>NO</th>
                            <th>R</th>
                            <th>AVG</th>
                            <th>HS</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($batsmen as $batsman): ?>
                        <tr>
                            <td><?= h($batsman->name) ?></td>
                            <td><?= number_format($batsman->batting_innings) ?></td>
                            <td><?= number_format($batsman->batting_not_out) ?></td>
                            <td><?= number_format($batsman->batting_runs) ?></td>
                            <td><?= ($batsman->batting_average !== false ? $batsman->batting_average : "-" ) ?></td>
                            <td>
                                <?= $batsman->batting_high_score->batting_runs . ($batsman->batting_high_score->modes_of_dismissal->not_out == 1 ? "*" : "") ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if (count($bowlers) > 0): ?>
            <div class="padding-bottom">

                <h2>Bowling averages</h2>

                <table>
                    <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>O</th>
                        <th>M</th>
                        <th>R</th>
                        <th>W</th>
                        <th>AVG</th>
                        <th>ECON</th>
                        <th>BB</th>
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
                            <td><?= ($bowler->bowling_average !== false ? $bowler->bowling_average : "-") ?></td>
                            <td><?= ($bowler->bowling_economy !== false ? $bowler->bowling_economy : "-") ?></td>
                            <td class="last">
                                <?= $bowler->best_bowling->bowling_wickets . "-" . $bowler->best_bowling->bowling_runs ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        <?php endif; ?>

    </div>

</div>