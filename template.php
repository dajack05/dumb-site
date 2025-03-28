<?php
$page_contents = ParsedownExtra::instance()->text(file_get_contents("pages/" . $page_name . ".md"));
?>
<header>
    <strong class="site-title"><?= $config['title'] ?></strong>
</header>
<nav>
    <?php
    foreach ($config['navigation'] as $navitem) {
        $label = explode("\n", file_get_contents("pages/" . $navitem . ".md"))[0];
        $label = str_replace("<!--", "", $label);
        $label = str_replace("-->", "", $label);
        $label = ParsedownExtra::instance()->text($label);

        if ($navitem == $page_name) {
            echo ("<a class='text-lg active' href='/$navitem'>" . $label . "</a>");
        } else {
            echo ("<a class='text-lg' href='/$navitem'>" . $label . "</a>");
        }
    }
    ?>
</nav>
<main>
    <?= $page_contents ?>
</main>
<footer>
    <strong>&copy; Copyright 2025 Daniel Jackson</strong>
</footer>