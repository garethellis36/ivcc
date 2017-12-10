<div class="columns break">

    <div class="one-fifth column">

        <div class="padding-bottom">

            <h6>Filter by year:</h6>
            <?php foreach ($years as $y): ?>
                <ul class="filter-list">
                    <li>
                        <a href="/stats?year=<?= strtolower($y) ?>&format=<?= strtolower($format) ?>" class="filter-item<?php echo ($year == strtolower($y) ? " selected" : ""); ?>">
                            <?= $y ?>
                        </a>
                    </li>
                </ul>
            <?php endforeach; ?>

        </div>

        <div class="padding-bottom">

            <h6>Filter by format:</h6>
            <?php foreach ($formats as $id => $f): ?>
                <ul class="filter-list">
                    <li>
                        <a href="/stats?year=<?= strtolower($year) ?>&format=<?= strtolower($id) ?>" class="filter-item<?php echo ($format == strtolower($id) ? " selected" : ""); ?>">
                            <?= $f ?>
                        </a>
                    </li>
                </ul>
            <?php endforeach; ?>

        </div>

    </div>

    <div class="four-fifths column">

        <h2>Stats</h2>

        <div class="columns padding-bottom break">
            <div class="column one-fifth smaller-bottom-margin">
                <strong>Overall record</strong>
            </div>
            <div class="column four-fifths">
                <?php foreach ($stats["results"] as $result => $num): ?>
                    <?= $result . $num ?>&nbsp;
                <?php endforeach; ?>
            </div>
        </div>

        <div class="columns padding-bottom break">
            <div class="column one-fifth smaller-bottom-margin">
                <strong>Most appearances</strong>
            </div>
            <div class="column four-fifths">
                <?= $this->Stats->leading($stats, "mostApps") ?>
            </div>
        </div>

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
                <strong>Most catches</strong>
            </div>
            <div class="column four-fifths">
                <?= $this->Stats->leading($stats, "mostCatches") ?>
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
                <strong>Highest individual score by position</strong>
            </div>
            <div class="column four-fifths">
                <?php for ($i = 1; $i <= 11; $i++): ?>
                    #<?= $i ?>:
                    <?php if ($stats["highestIndividualScoreByPosition"][$i]): ?>
                        <?= $this->Player->name($stats["highestIndividualScoreByPosition"][$i]->player) ?>:
                        <?= $stats['highestIndividualScoreByPosition'][$i]->batting_runs ?><?php if ($stats["highestIndividualScoreByPosition"][$i]->modes_of_dismissal->not_out == 1): ?>*<?php endif; ?>
                        vs <?= h($stats['highestIndividualScoreByPosition'][$i]->match->opposition) ?>,
                        <?= $stats['highestIndividualScoreByPosition'][$i]->match->date->format("jS M Y") ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                    <br>
                <?php endfor; ?>
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
                <strong>Tons</strong>
            </div>
            <div class="column four-fifths">
                <?php if ($stats["hundreds"]->count() > 0): ?>
                    <?php foreach ($stats["hundreds"] as $fifty): ?>
                        <?= $this->Player->name($fifty->player) ?>
                        (<?= $fifty->batting_runs . ($fifty->modes_of_dismissal->not_out == 1 ? "*" : "") ?>
                        vs. <?= h($fifty->match->opposition) ?>, <?= $fifty->match->date->format("jS M Y") ?>)<br>
                    <?php endforeach; ?>
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
                        (<?= $fifty->batting_runs . ($fifty->modes_of_dismissal->not_out == 1 ? "*" : "") ?>
                        vs. <?= h($fifty->match->opposition) ?>, <?= $fifty->match->date->format("jS M Y") ?>)<br>
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
                        (<?= $fivefor->bowling_wickets . " for " . $fivefor->bowling_runs ?>
                        vs. <?= h($fivefor->match->opposition) ?>, <?= $fivefor->match->date->format("jS M Y") ?>)<br>
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
                    <?= $this->Player->name($duck->player) ?>
                        vs. <?= h($duck->match->opposition) ?>, <?= $duck->match->date->format("jS M Y") ?><br>
                    <?php endforeach; ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($all)): ?>
            <div class="padding-bottom">
                <h2>Appearances &amp; catching stats</h2>

                <table>
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>Apps</th>
                            <th>Catches</th>
                            <th>MOTM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all as $player): ?>
                            <tr>
                                <td><?= $this->Player->truncateName($player) ?></td>
                                <td><?= number_format($player->appearances) ?></td>
                                <td><?= number_format($player->catches) ?></td>
                                <td><?= number_format($player->motm) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if (!empty($batsmen)): ?>
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
                            <th>100</th>
                            <th>50</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($batsmen as $batsman): ?>
                        <tr>
                            <td><?= $this->Player->truncateName($batsman) ?></td>
                            <td><?= number_format($batsman->batting_innings) ?></td>
                            <td><?= number_format($batsman->batting_not_out) ?></td>
                            <td><?= number_format($batsman->batting_runs) ?></td>
                            <td><?= ($batsman->batting_average !== false ? number_format($batsman->batting_average,2) : "-" ) ?></td>
                            <td>
                                <?= $batsman->batting_high_score->batting_runs . ($batsman->batting_high_score->modes_of_dismissal->not_out == 1 ? "*" : "") ?>
                            </td>
                            <td>
                                <?= number_format($batsman->hundreds) ?>
                            </td>
                            <td>
                                <?= number_format($batsman->fifties) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if (!empty($bowlers)): ?>
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
                        <th>SR</th>
                        <th>BB</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($bowlers as $bowler): ?>
                        <tr>
                            <td><?= $this->Player->truncateName($bowler, true, 14) ?></td>
                            <td><?= number_format($bowler->bowling_overs, 1) ?></td>
                            <td><?= number_format($bowler->bowling_maidens) ?></td>
                            <td><?= number_format($bowler->bowling_runs) ?></td>
                            <td><?= number_format($bowler->bowling_wickets) ?></td>
                            <td><?= ($bowler->bowling_average !== false ? number_format($bowler->bowling_average,2) : "-") ?></td>
                            <td><?= ($bowler->bowling_economy !== false ? number_format($bowler->bowling_economy,2) : "-") ?></td>
                            <td><?= ($bowler->bowling_strike_rate ? number_format($bowler->bowling_strike_rate,2) : "-") ?></td>
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