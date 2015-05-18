<?= $this->Form->create($newsItem); ?>
<fieldset>
    <legend><?= __(ucfirst($this->request->params['action']) . ' News Item') ?></legend>
    <?php
        echo $this->Form->input('title', ['class' => 'full-width']);
        echo $this->Form->input('body', ['class' => 'huge']);
    ?>
</fieldset>
<?= $this->Form->button(__('Submit'), ["class" => "btn btn-primary"]) ?>
<?= $this->Form->end() ?>

