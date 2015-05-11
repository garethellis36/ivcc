<?= $this->Form->create($match); ?>
<fieldset>
    <legend><?= __('Add Match') ?></legend>
    <?php
    echo $this->Form->input('date', [
        'class' => 'datetimepicker input-mini',
        'type' => 'text'
    ]);
    echo $this->Form->input('opposition', [
        'class' => 'input-mini'
    ]);
    echo $this->Form->input('venue', [
        'type' => 'select',
        'options' => [
            'A' => 'A',
            'H' => 'H'
        ],
        'class' => 'input-mini'
    ]);
    echo $this->Form->input('format_id', [
        'class' => 'input-mini'
    ]);
    ?>
</fieldset>
<?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']) ?>
<?= $this->Form->end() ?>

<?= $this->Html->script("jquery.datetimepicker", ['block' => 'scriptBottom']); ?>

<?= $this->Html->scriptBlock("


    $(document).ready(function() {
        $('.datetimepicker').datetimepicker({
            format: 'Y-m-d H:i'
        });
    });


", ["block" => "scriptBottom"]) ?>



