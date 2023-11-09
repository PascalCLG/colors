<?php
include "php/HtmlHelpers.php";
function readBookmarks($fileName)
{
    $jsonContent = file_get_contents($fileName);
    $bookmarks = json_decode($jsonContent, true);
    return $bookmarks;
}

function addFaviconsAndLinks($bookmarks)
{
    foreach ($bookmarks as &$bookmark) {
        $url = $bookmark["Url"];
        unset($bookmark["Id"]);
        $favicon = HtmlHelper::SiteFavicon($url);
        $faviconLink = HtmlHelper::Link($url, $favicon, true);
        $urlLink = HtmlHelper::Link($url, $url, true);
        $bookmark["Url"] = $urlLink;
        array_unshift($bookmark, $faviconLink);
    }
    return $bookmarks;
}

function makeBookmarksList()
{
    $bookmarksFile = "data/bookmarks.json";
    $columnTitles = ["Favicon", "Site", "Url", "Catégorie"];
    return
        HtmlHelper::Table(
            addFaviconsAndLinks(readBookmarks($bookmarksFile)),
            "Favoris",
            $columnTitles
        );
}