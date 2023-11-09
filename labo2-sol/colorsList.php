<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listes de couleurs</title>
    <link rel="stylesheet" href="css/colors.css">
</head>

<body>
    <?php
        include "php/colors.php";
        echo Colors::MakeList(Colors::ReadFile("data/colors.txt"));
    ?>
</body>

</html>