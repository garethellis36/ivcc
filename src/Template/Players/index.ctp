<div class="columns break" id="top">

    <div class="column one-fifth">

        <nav class="menu">

            <?php foreach ($players as $player): ?>
                <a class="menu-item" href="#<?= $player["id"] ?>"><?= $this->Player->name($player) ?></a>
            <?php endforeach; ?>
        </nav>

        <?php if ($this->Authorization->isAdmin()): ?>
            <div class="flash-messages">
                <div class="flash">
                    <a href="/admin/players/add">
                        Add player
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <div class="column four-fifths">

        <div class="container">

            <h1>IVCC squad</h1>


            <?php foreach ($players as $player): ?>

                <div class="player" id="<?= $player["id"] ?>">

                    <div class="columns break">
                        <div class="column one-fourth">
                            <?php
                            $img = "anon.png";
                            if (isset($player["photo"]) && file_exists(WWW_ROOT . DS . "img" . DS . "players" . DS . $player["photo"])) {
                              $img = $player["photo"];
                            }
                            ?>
                            <img class="avatar" src="/img/players/<?= $img; ?>">
                        </div>
                        <div class="column one-half">
                            <h2><?= $this->Player->name($player, true) ?><sup class="padding-left">[<a href="#top">top</a>]</sup></h2>
                            <?php if ($this->Authorization->isAdmin()): ?>
                                <p>
                                    <small>
                                        [<a href="/admin/players/edit/<?= $player["id"] ?>">
                                            edit
                                        </a>]
                                        [<a href="/admin/players/delete/<?= $player["id"] ?>" class="confirm">
                                            delete
                                        </a>]
                                    </small>
                                </p>
                            <?php endif; ?>
                            <?php foreach ($player["roles"] as $role): ?>
                                <?= $role["name"] ?><br>
                            <?php endforeach; ?>
                        </div>

                        <div class="column one-fourth">
                            <?php if (!empty($player["previous_clubs"])): ?>
                                <h6>Previous clubs:</h6>
                                <?= nl2br(h($player["previous_clubs"])) ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="horizontal-line">&nbsp;</div>

                    <div class="columns">

                        <div class="one-fourth column">
                            &nbsp;
                        </div>

                        <div class="one-fourth column">
                            <strong>Apps</strong>
                        </div>

                        <div class="one-fourth column">
                            <strong>HS</strong>
                        </div>

                        <div class="one-fourth column">
                            <strong>BB</strong>
                        </div>

                    </div>

                    <div class="columns">

                        <div class="one-fourth column">
                            Career
                        </div>

                        <div class="one-fourth column">
                            <?= $player["career_stats"]["apps"] ?>
                        </div>

                        <?php if ($player["career_stats"]["apps"] > 0): ?>

                            <div class="one-fourth column">
                                <?php if ($player["career_stats"]["bestBatting"]->did_not_bat == 0): ?>
                                    <?= $player["career_stats"]["bestBatting"]->batting_runs ?><?php if ($player["career_stats"]["bestBatting"]->modes_of_dismissal->not_out == 1): ?>*<?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>

                            <div class="one-fourth column">
                                <?php if (isset($player["career_stats"]["bestBowling"]->bowling_order_no)): ?>
                                    <?= $player["career_stats"]["bestBowling"]->bowling_wickets ?>
                                    for <?= $player["career_stats"]["bestBowling"]->bowling_runs ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="columns">

                        <div class="one-fourth column">
                            <?= date("Y") ?>
                        </div>

                        <div class="one-fourth column">
                            <?= $player["this_season_stats"]["apps"] ?>
                        </div>

                        <?php if ($player["this_season_stats"]["apps"] > 0): ?>

                            <div class="one-fourth column">
                                <?php if ($player["this_season_stats"]["bestBatting"]->did_not_bat == 0): ?>
                                    <?= $player["this_season_stats"]["bestBatting"]->batting_runs ?><?php if ($player["this_season_stats"]["bestBatting"]->modes_of_dismissal->not_out == 1): ?>*<?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>

                            <div class="one-fourth column">
                                <?php if (isset($player["this_season_stats"]["bestBowling"]->bowling_order_no)): ?>
                                    <?= $player["this_season_stats"]["bestBowling"]->bowling_wickets ?>
                                    for <?= $player["this_season_stats"]["bestBowling"]->bowling_runs ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="horizontal-line">&nbsp;</div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>