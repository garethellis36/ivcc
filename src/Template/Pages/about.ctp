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
There was once a dream that was IVCC. You could only whisper it. Anything more than a whisper and it would vanish... it was so fragile. And we feared that it will not survive the 2013 winter. But survive it did, and so the gentlemen players got together in a backstreet boozer in OX4 and established Iffley Village Cricket Club in 2014. With the ethos behind the club being to play friendly 40-over and 20-over cricket matches against like-minded clubs around the country, our focus is as much on playing competitive cricket as it is enjoying a beer with the opposition after the game, and with the journey being far more important than the result.
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
