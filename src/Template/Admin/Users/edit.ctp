<?= $this->Form->create($user); ?>
<fieldset>
    <legend><?= __('Edit User') ?></legend>
    <?php
        echo $this->Form->input('email');
        echo $this->Form->input('name');
        echo $this->Form->input('password', ["label" => "Password (leave blank to not change)", "value  " => ""]);

        if ($user->id != $authUser["id"]) {
            echo $this->Form->input('is_admin', ["type" => "checkbox"]);
        }
    ?>
</fieldset>
<?= $this->Form->button(__('Submit'), ["btn btn-primary"]) ?>
<?= $this->Form->end() ?>
