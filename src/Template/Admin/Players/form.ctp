<?= $this->Form->create($player, ["type" => "file"]); ?>
<fieldset>
    <legend><?= __(ucfirst($this->request->params['action']) . ' Player') ?></legend>

    <div class="columns break">
        <div class="column one-fourth">
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
            ?>
        </div>

        <div class="column one-fourth">

            <?php
            echo $this->Form->input('previous_clubs', [
                'class' => 'input-mini'
            ]);
            ?>

            <?php
            echo $this->Form->input('fines_owed', [
                'class' => 'input-mini',
                'type' => 'textarea'
            ]);
            ?>

        </div>



    <?php if ($this->request->params['action'] == 'edit'): ?>
        <div class="column one-fourth">
            <?php
            $photoLabel = "Photo";
            if ($player->photo) {
                $photoLabel .= " - JPG file, max 50kb, 190px x 190px";
            }
            echo $this->Form->input('photo', ['label' => $photoLabel, 'type' => 'file']);

            if ($player->photo) {
                echo $this->Form->input("delete_photo", ["type" => "checkbox"]);
            }
            ?>
        </div>

    <?php endif; ?>

        <div class="column one-fourth">
            <?= $this->Form->input('roles._ids', ['multiple' => 'checkbox', 'options' => $roles]) ?>
        </div>

</fieldset>
<?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']) ?>
<?= $this->Form->end() ?>



