<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des favoris</title>
    <link rel="stylesheet" href="css/bookmarks.css">
</head>

<body>
    <div id="main">
        <?php
        include "php/bookmarks.php";
        echo makeBookmarksList();
        ?>
    </div>
</body>

</html>