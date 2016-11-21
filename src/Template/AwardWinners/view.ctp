<?php
/**
 * @var $this Cake\View\View
 * @var $awardWinners array
 */
?>

<div class="container">

    <div class="columns break">

        <div class="column full-width">

            <h3>
                Award winners
                <?php if ($this->Authorization->isAdmin()): ?>
                    [<a href="/admin/award_winners/add">add award winner</a>]
                    [<a href="/admin/awards">manage award types</a>]
                <?php endif; ?>
            </h3>

            <?php foreach ($awardWinners as $year => $yearsAwardWinners): ?>

                <h4><?=$year?></h4>
                <table>
                    <?php foreach ($yearsAwardWinners as $awardWinner): ?>
                        <tr>
                            <td>
                                <?= h($awardWinner->award->name) ?>
                            </td>
                            <td>
                                <?= $this->Player->name($awardWinner->player) ?>
                                <?php if ($this->Authorization->isAdmin()): ?>
                                    [<a href="/admin/award_winners/edit/<?=$awardWinner->id?>">edit</a>]
                                    [<a href="/admin/award_winners/delete/<?=$awardWinner->id?>" class="confirm">delete</a>]
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= nl2br(h($awardWinner->comments)) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            <?php endforeach; ?>

        </div>
    </div>
</div>
