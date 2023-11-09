<?php
class Colors
{
    static public function ReadFile($colorsFileName)
    {
        $content = file_get_contents("data/colors.txt");
        $lines = explode("\n", $content);
        $colors = [];
        foreach ($lines as $line) {
            $color = explode("|", $line);
            $colors[] = $color;
        }
        return $colors;
    }
    static public function MakeColorSample($hexaColorCode)
    {
        return "<div class='colorSample' style='background-color: $hexaColorCode'></div>";
    }
    static public function MakeColorInfo($colorInfo)
    {
        $name = $colorInfo[0];
        $hexaCode = $colorInfo[1];
        $rgbCode = $colorInfo[2];
        $colorSample = Colors::MakeColorSample($hexaCode);
        $html = <<<HTML
            <div class="colorContainer">
                $colorSample
                <div>$name</div>
                <div>$hexaCode</div>
                <div>$rgbCode</div>
            </div>
        HTML;
        return $html;
    }
    static public function MakeList($colors)
    {
        $html = "<div class='colorsContainer'>";
        foreach ($colors as $color) {
            $html .= Colors::MakeColorInfo(($color));
        }
        $html .= "</div>";
        return $html;
    }
}