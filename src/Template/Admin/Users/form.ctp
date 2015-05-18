<?= $this->Form->create($user); ?>
<fieldset>
    <legend><?= __(ucfirst($this->request->params['action']) . ' User') ?></legend>
    <?php
        echo $this->Form->input('email');
        echo $this->Form->input('name');

        $label = 'Password' . ($this->request->params['action'] == 'edit' ? ' (leave blank not to change)' : '');
        echo $this->Form->input('password', [
            "label" => $label,
            "value " => ""
        ]);

        if ($user->id && $user->id != $authUser["id"]) {
            echo $this->Form->input('is_admin', ["type" => "checkbox"]);
        }
    ?>
</fieldset>
<?= $this->Form->button(__('Submit'), ["class" => "btn btn-primary"]) ?>
<?= $this->Form->end() ?>
