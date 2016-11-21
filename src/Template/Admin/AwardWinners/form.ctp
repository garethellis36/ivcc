<?= $this->Form->create($awardWinner); ?>
<fieldset>
    <legend><?= __(ucfirst($this->request->params['action']) . ' Award Winner') ?></legend>
    <?php
    echo $this->Form->input('year', ['type' => 'select', 'default' => date('Y')]);
    echo $this->Form->input('award_id', ['empty' => 'Select']);
    echo $this->Form->input('player_id', ['empty' => 'Select']);
    echo $this->Form->input('comments');
    ?>
</fieldset>
<?= $this->Form->button(__('Submit'), ["class" => "btn btn-primary"]) ?>
<?= $this->Form->end() ?>

