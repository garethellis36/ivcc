<?php
/**
 * @var $this \App\View\AppView
 * @var $committee array
 * @var $vicePresidents array
 */
?>
<div class="container">

    <div class="columns break">

        <div class="column one-third">

            <h3>About IVCC</h3>

            <p>
                Iffley Village Cricket Club is a social cricket club based in Oxford. Founded in 2014, we play friendly
                matches against like-minded clubs from around the county.
            </p>

        </div>

        <div class="column one-third">

            <h3>IVCC committee</h3>

            <table>
                <?php foreach ($committee as $title => $name): ?>
                    <tr>
                        <td>
                            <?= h($title) ?>
                        </td>
                        <td>
                            <?php if (is_array($name)): ?>
                                    <?php foreach ($name as $value): ?>
                                        <?= h($value) ?><br>
                                    <?php endforeach; ?>
                            <?php else: ?>
                                <?= h($name) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <p>
                To contact the committee, please email <a href="mailto:committee@ivcc.co.uk">committee@ivcc.co.uk</a>.
            </p>

        </div>

        <div class="column one-third">

            <h3>Vice Presidents</h3>

            <p>IVCC are grateful for the on-going support of our wonderful Vice Presidents:</p>

            <ul>
                <?php foreach ($vicePresidents as $vp): ?>
                    <li style="list-style: none;">
                        <?= h($vp) ?>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>
    </div>
</div>