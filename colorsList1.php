<?php
// Impossible de cocher les 2 cases à cocher simultanément
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listes de couleurs (version #2)</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/colors.css">
</head>

<body>
    <?php
    include "php/colors.php";
    $colors = new Colors("data/colors.txt");

    if (isset($_GET["showHEX"]))
        $colors->showHEX = filter_var($_GET["showHEX"], FILTER_VALIDATE_BOOLEAN);
    if (isset($_GET["showRGB"]))
        $colors->showRGB = filter_var($_GET["showRGB"], FILTER_VALIDATE_BOOLEAN);

    $showHEX_Checked = $colors->showHEX ? "checked" : "";
    $showRGB_Checked = $colors->showRGB ? "checked" : "";


    echo <<<HTML
        <div id="head">
            <h1>Liste des couleurs</h1>
            <div class="showOptions">
                <label for="showHEX">Afficher code HEX</label>
                <input type="checkbox" id="showHEX" class="checkbox" $showHEX_Checked/>
                <br>
                <label for="showRGB">Afficher code RGB</label>
                <input type="checkbox" id="showRGB" class="checkbox" $showRGB_Checked />
            </div>
        </div>
    HTML;

    echo $colors->show();
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script defer>
        $("#showHEX").click(() => { //Code javascript qui va chercher le id=showHEX dans le DOM
            window.location.replace("?showHEX=" + $("#showHEX").is(':checked')); //ON modifie une portion de l'url !!
        })
        $("#showRGB").click(() => {
            window.location.replace("?showRGB=" + $("#showRGB").is(':checked'));
        })
    </script>
</body>

</html>