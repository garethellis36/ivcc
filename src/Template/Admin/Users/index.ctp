<h3>Users</h3>

<div class="flash">
    <a href="/admin/users/add">
        Add user
    </a>
</div>

<ul>
<?php foreach ($users as $user): ?>

    <li>
        <?= $user->name ?> (<?= $user->email ?>)
        <?php if ($user->is_admin == 1): ?>
            *
        <?php endif; ?>
        [<a href="/admin/users/edit/<?= $user->id ?>">edit</a>]
        [<a href="/admin/users/delete/<?= $user->id ?>" class="confirm">delete</a>]
    </li>

<?php endforeach; ?>
</ul>
<p>* = admins</p>