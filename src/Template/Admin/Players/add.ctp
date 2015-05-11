<?= $this->Form->create($player); ?>
<fieldset>
    <legend><?= __('Add Player') ?></legend>
    <?php
    echo $this->Form->input('first_name', [
        'class' => 'input-mini'
    ]);
    echo $this->Form->input('initials', [
        'class' => 'input-mini'
    ]);
    echo $this->Form->input('last_name', [
        'class' => 'input-mini'
    ]);

    echo $this->Form->input('previous_clubs', [
        'class' => 'input-mini'
    ]);

    echo $this->Form->input('roles._ids', ['multiple' => 'checkbox', 'options' => $roles]);

    ?>
</fieldset>
<?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']) ?>
<?= $this->Form->end() ?>



