<?= $this->Form->create($player, ["type" => "file"]); ?>
<fieldset>
    <legend><?= __(ucfirst($this->request->params['action']) . ' Player') ?></legend>
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

    if ($this->request->params['action'] == 'edit') {
        $photoLabel = "Photo";
        if ($player->photo) {
            $photoLabel .= " - currently \"" . $player->photo . "\" - will only be replaced if you upload a new file - JPG file, max 50kb, 190p x 190px";
        }
        echo $this->Form->input('photo', ['label' => $photoLabel, 'type' => 'file']);
    }

    echo $this->Form->input('roles._ids', ['multiple' => 'checkbox', 'options' => $roles]);

    ?>
</fieldset>
<?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']) ?>
<?= $this->Form->end() ?>



