<?= $this->Form->create($newsItem); ?>
<fieldset>
    <legend><?= __('Edit News Item') ?></legend>
    <?php
    echo $this->Form->input('title');
    echo $this->Form->input('body', ['class' => 'huge']);
    ?>
</fieldset>
<?= $this->Form->button(__('Submit'), ["btn btn-primary"]) ?>
<?= $this->Form->end() ?>
