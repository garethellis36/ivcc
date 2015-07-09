<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        if (isset($title)) {
            $this->assign('title', $title);
        }
        ?>
        Iffley Village Cricket Club: <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('app.css') ?>
    <?php if (isset($loadJs) && $loadJs): ?>
        <?= $this->Html->css('js.css') ?>
        <?= $this->Html->script($loadJs . '.min', ['block' => 'firstScripts']) ?>
    <?php endif; ?>

    <?= $this->fetch('css') ?>

</head>
<body>

    <header class="masthead">
        <div class="container">
            <a href="/" class="masthead-logo">
                <span>Iffley Village</span> Cricket Club
            </a>

            <nav class="masthead-nav">
                <?php if (isset($menuItems)): ?>
                    <?php foreach ($menuItems as $item): ?>
                        <?= $this->Html->link($item["label"],
                            $item["url"], [
                                "class" => ($item["active"] ? "active" : null)
                            ]
                        ) ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <?php if (isset($showSplash) && $showSplash): ?>

        <?= $this->Element("splash") ?>

    <?php endif; ?>

    <div class="container">

        <?= $this->Flash->render() ?>

        <?= $this->fetch('content') ?>

    </div>

    <?= $this->fetch('firstScripts') ?>
    <?= $this->fetch('scriptBottom') ?>

    <footer>
        <div class="container">
            <?php if (empty($authUser)): ?>
                <a href="/users/login">Login</a>
            <?php else: ?>
                <?php if ($this->Authorization->isAdmin()): ?>
                    <a href="/admin/users">User management</a> |
                <?php endif; ?>
                <a href="/users/logout">Logout</a>
            <?php endif; ?>
                | <span class="yellow">Website by Garr</span>
        </div>
    </footer>

</body>
</html>
