<div class="align-center">
    <?= $this->Photo->showPhoto($photo) ?>
</div>

<?= $this->Form->create($photo); ?>
<fieldset>
    <legend><?= __(ucfirst($this->request->params['action']) . ' photo') ?></legend>
    <?php
    echo $this->Form->input('title', ['class' => 'full-width']);
    ?>
</fieldset>
<?= $this->Form->button(__('Submit'), ["class" => "btn btn-primary"]) ?>
<?= $this->Form->end() ?>
