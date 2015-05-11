<?= $this->Form->create($user); ?>
<fieldset>
    <legend><?= __('Add User') ?></legend>
    <?php
        echo $this->Form->input('email');
        echo $this->Form->input('password');
        echo $this->Form->input('name');
        echo $this->Form->input('is_admin', ["type" => "checkbox"]);
    ?>
</fieldset>
<?= $this->Form->button(__('Submit'), ["class" => "btn btn-primary"]) ?>
<?= $this->Form->end() ?>

