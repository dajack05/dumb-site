<?php

use Yosymfony\Toml\Toml;

include "vendor/autoload.php";

$config = Toml::ParseFile('site.toml');

$page_name = substr($_SERVER['REQUEST_URI'], 1);
if (strlen($page_name) <= 0) {
    header("location:/" . $config['startpage']);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include "style.css"; ?>
    </style>
    <title><?= $config['title'] ?></title>
</head>

<body>
    <?php include "template.php"; ?>
</body>

</html>