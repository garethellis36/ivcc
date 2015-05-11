<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Iffley Village Cricket Club: <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('app.css') ?>
    <?php if ($loadJs): ?>
        <?= $this->Html->css('js.css') ?>
        <?= $this->Html->script('app.min', ['block' => 'firstScripts']) ?>
    <?php endif; ?>

    <?= $this->fetch('css') ?>

</head>
<body>

    <header class="masthead">
        <div class="container">
            <a href="/" class="masthead-logo">
                <span class="red">Iffley Village</span> Cricket Club
            </a>

            <nav class="masthead-nav">
                <?php foreach ($menuItems as $item): ?>
                    <?= $this->Html->link($item["label"],
                        $item["url"], [
                            "class" => ($item["active"] ? "active" : null)
                        ]
                    ) ?>
                <?php endforeach; ?>
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

</body>
</html>
