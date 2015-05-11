<div class="columns">

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

        <div class="container">

            <h1>Matches</h1>

            <?php if (empty($matches)): ?>
                <p>No matches found.</p>
            <?php else: ?>

                <table>

                    <thead>

                        <tr>
                            <th>Date</th>
                            <th>Opposition</th>
                            <th>Format</th>
                            <th>Result</th>
                            <th>Match report &amp; scorecard</th>
                            <?php if ($this->Authorization->isAdmin()): ?>
                                <th>Edit/delete</th>
                            <?php endif; ?>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($matches as $match): ?>

                            <tr>

                                <td>
                                    <?= $match["date"]->format("D jS M Y @ H:i") ?>
                                </td>

                                <td>
                                    <?= h($match["opposition"]) ?> (<?= $match["venue"] ?>)
                                </td>

                                <td>
                                    <?= $match["format"]["name"] ?>
                                </td>

                                <td>
                                    <?php if (isset($match["result"])): ?>
                                        <?= $match["result"] ?>
                                        <?php if (!empty($match["result_more"])): ?>
                                            by <?= h($match["result_more"]) ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (isset($match["result"])): ?>
                                    <a href="/matches/view/<?= $match["id"] ?>">
                                        View
                                    </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <?php if ($this->Authorization->isAdmin()): ?>
                                    <td>
                                        [<a href="/admin/matches/edit/<?= $match["id"] ?>">
                                            edit
                                        </a>]
                                        [<a href="/admin/matches/delete/<?= $match["id"] ?>" class="confirm">
                                            delete
                                        </a>]
                                    </td>
                                <?php endif; ?>

                            </tr>

                        <?php endforeach; ?>


                    </tbody>

                </table>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php if ($this->Authorization->isAdmin()): ?>

    <div class="flash-messages">
        <div class="flash flash-with-icon">
            <span class="octicon octicon-diff-added"></span>
            <a href="/admin/matches/add">
                Add match
            </a>
        </div>
    </div>

<?php endif; ?>