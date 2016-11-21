<?= $this->Form->create($award); ?>
<fieldset>
    <legend><?= __(ucfirst($this->request->params['action']) . ' Award') ?></legend>
    <?php
    echo $this->Form->input('name');
    echo $this->Form->input('order_number');
    ?>
</fieldset>
<?= $this->Form->button(__('Submit'), ["class" => "btn btn-primary"]) ?>
<?= $this->Form->end() ?>

