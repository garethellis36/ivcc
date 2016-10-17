<?php
/**
 * @var $this \App\View\AppView
 */
?>
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

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

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
