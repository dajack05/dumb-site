<?php

include "vendor/autoload.php";
include "DumbSite.php";

$site = new Site();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include "style.css"; ?>
    </style>
    <title><?= $site->getConfig()->title ?></title>
</head>

<body>
    <header>
        <strong class="site-title"><?= $site->getConfig()->title ?></strong>
    </header>
    <nav>
        <?php
        if (sizeof($site->getConfig()->navigationPages) > 0) {
            foreach ($site->getNavItems() as $navItem) {
                $active = $navItem == $site->getCurrentPage() ? "active" : "";
                echo "<a class='text-lg $active' href='/$navItem->tag'>$navItem->label</a>";
            }
        }
        ?>
    </nav>
    <main>
        <?= $site->getCurrentPage()->getHTML() ?>
    </main>
    <footer>
        <strong>&copy; Copyright 2025 Daniel Jackson</strong>
    </footer>
</body>

</html>