<?php
// Plusieurs critères dans une recherche par nom
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listes de couleurs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="css/colors.css">
</head>

<body>
    <?php
    include "php/colors.php";
    include "php/HtmlHelpers.php";

    $colors = new Colors("data/colors.txt");
    session_start();

    if (isset($_GET["showHEX"]))
        $_SESSION["showHEX"] = $_GET["showHEX"];
    if (isset($_GET["showRGB"]))
        $_SESSION["showRGB"] = $_GET["showRGB"];
    if (isset($_GET["search"]))
        $_SESSION["search"] = $_GET["search"];
    if (isset($_GET["center"]))
        $_SESSION["center"] = $_GET["center"];
    if (isset($_GET["range"]))
        $_SESSION["range"] = $_GET["range"];
    if (isset($_GET["sortKey"]))
        $_SESSION["sortKey"] = $_GET["sortKey"];

    if (isset($_SESSION["showHEX"]))
        $colors->showHEX = filter_var($_SESSION["showHEX"], FILTER_VALIDATE_BOOLEAN);
    if (isset($_SESSION["showRGB"]))
        $colors->showRGB = filter_var($_SESSION["showRGB"], FILTER_VALIDATE_BOOLEAN);
    if (isset($_SESSION["search"]))
        $colors->search = $_SESSION["search"];
    if (isset($_SESSION["center"]))
        $colors->center = "#" . $_SESSION["center"];
    if (isset($_SESSION["range"]))
        $colors->range = (int) $_SESSION["range"];
    if (isset($_SESSION["sortKey"]))
        $colors->sortKey = $_SESSION["sortKey"];

    $showHEX_Checked = $colors->showHEX ? "checked" : "";
    $showRGB_Checked = $colors->showRGB ? "checked" : "";
    $search = $colors->search;
    $center = $colors->center;
    $range = $colors->range;
    $sortSelector = HtmlHelper::ComboBox("sortSelector", Colors::$sortNames, $colors->sortKey);

    echo <<<HTML
        <div id="title">
            Liste des couleurs HTML
        </div>
        <div id="head">
            <div class="filterContainer">
                <div class="filterTitle">Couleur ciblée </div>
                <div class="range">
                    <input type="color" id="center" value="$center" title="Couleur recherchée">
                    <input type="range" id="range" max="95" min="5" class="form-range" value=$range title="correspondance (0 à 100)">
                </div>
            </div>
            <div class="filterContainer">
                <div class="filterTitle">Nom de couleur recherchée</div>
                <div class="search">
                    <input type="search" id="search" class="form-control" value="$search">
                    <span id="doSearch" class="cmdIcon fa fa-search"></span>
                </div>
            </div>
            <div class="filterContainer">
                <div class="filterTitle">Affichage </div>
                <div class="showOptions">
                    <label for="showHEX">Code HEX</label>
                    <input type="checkbox" id="showHEX" class="checkbox" $showHEX_Checked/>
                    <br>
                    <label for="showRGB">Code RGB</label>
                    <input type="checkbox" id="showRGB" class="checkbox" $showRGB_Checked />
                </div>
            </div>
            <div class="filterContainer">
                <div class="filterTitle">Tri des couleurs</div>
                $sortSelector
            </div>
        </div>
    HTML;

    echo $colors->show();
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script defer>

        $("#showHEX").click(() => {
            window.location.replace("?showHEX=" + $("#showHEX").is(':checked'));
        })
        $("#showRGB").click(() => {
            window.location.replace("?showRGB=" + $("#showRGB").is(':checked'));
        })
        $('#search').keypress(function (event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                window.location.replace("?search=" + $("#search").val());
            }
        });
        $("#doSearch").click(() => {
            window.location.replace("?search=" + $("#search").val());
        })
        $("#center").on("change", () => {
            window.location.replace("?center=" + $("#center").val().replace("#", ""));
        })
        $("#range").on("change", () => {
            window.location.replace("?range=" + $("#range").val());
        })
        $("#sortSelector").on("change", () => {
            window.location.replace("?sortKey=" + $("#sortSelector option:selected").text());
        })
    </script>
</body>

</html>