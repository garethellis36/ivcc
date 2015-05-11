<?= $this->Form->create() ?>

<?= $this->Form->input("email") ?>

<?= $this->Form->input("password") ?>

<?= $this->Form->button(__('Login'), ['class' => 'btn btn-primary']) ?>
<?= $this->Form->end() ?>