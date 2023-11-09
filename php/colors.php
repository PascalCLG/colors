<?php
class Colors
{
    private $colors = [];
    public $showHEX = false;
    public $showRGB = false;
    public $search = "";
    public $center = "#888888";
    public $range = 0;
    public $sortKey = "Original";
    static public $sortNames = array("Original", "Nom", "Correspondance", "Teinte", "Luminosité", "Saturation");

    public function __construct($file)
    {
        $this->ReadFile($file);
    }
    public function ReadFile($colorsFileName)
    {
        $content = file_get_contents("data/colors.txt");
        $lines = explode("\n", $content);
        $this->colors = [];
        foreach ($lines as $line) {
            $c = explode("|", $line);
            $color["Name"] = $c[0];
            $color["HEX"] = $c[1];
            $color["RGB"] = $c[2];
            $color["HLS"] = self::HEX_TO_HLS($color["HEX"]);
            $color["Offset"] = self::diff($color["HEX"], $this->center);
            $this->colors[] = $color;
        }
    }
    public function calcOffset()
    {
        foreach ($this->colors as &$color) {
            $color["Offset"] = self::diff($color["HEX"], $this->center);
        }
    }
    private static function HEX_TO_RGB($hex)
    {
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        $rgb["red"] = $r;
        $rgb["green"] = $g;
        $rgb["blue"] = $b;
        return $rgb;
    }
    private static function HEX_TO_HLS($hex)
    {
        list($red, $green, $blue) = sscanf($hex, "#%02x%02x%02x");
        $cmin = min($red, $green, $blue);
        $cmax = max($red, $green, $blue);
        $delta = $cmax - $cmin;

        if ($delta == 0) {
            $hue = 0;
        } elseif ($cmax === $red) {
            $hue = (($green - $blue) / $delta);
        } elseif ($cmax === $green) {
            $hue = ($blue - $red) / $delta + 2;
        } else {
            $hue = ($red - $green) / $delta + 4;
        }

        $hue = round($hue * 60);
        if ($hue < 0) {
            $hue += 360;
        }

        $lightness = (($cmax + $cmin) / 2);
        $saturation = $delta === 0 ? 0 : ($delta / (1 - abs(2 * $lightness - 1)));
        if ($saturation < 0) {
            $saturation += 1;
        }

        $lightness = round($lightness * 100);
        $saturation = round($saturation * 100);
        $hls["h"] = $hue;
        $hls["l"] = $lightness;
        $hls["s"] = $saturation;
        return $hls;
    }
    private static function diff($colorHex, $center)
    {
        $rgbCenter = self::HEX_TO_RGB($center);
        $rgbColor = self::HEX_TO_RGB($colorHex);
        $diffRed = abs($rgbColor["red"] - $rgbCenter["red"]);
        $diffGreen = abs($rgbColor["green"] - $rgbCenter["green"]);
        $diffBlue = abs($rgbColor["blue"] - $rgbCenter["blue"]);
        $pctDiffRed = (float) $diffRed / 255;
        $pctDiffGreen = (float) $diffGreen / 255;
        $pctDiffBlue = (float) $diffBlue / 255;
        return ($pctDiffRed + $pctDiffGreen + $pctDiffBlue) / 3 * 100;
    }
    static private function MakeColorSample($hexaColorCode)
    {
        return "<div class='colorSample' style='background-color: $hexaColorCode'></div>";
    }
    private function colorView($color)
    {
        $name = $color["Name"];
        $hexaCode = $color["HEX"];
        $rgbCode = $color["RGB"];
        $colorSample = self::MakeColorSample($hexaCode);
        $colorName = "<div class='colorName'>$name</div>";
        $hexCode = $this->showHEX ? "<div class='colorHEX'>$hexaCode</div>" : "";
        $rgbCode = $this->showRGB ? "<div class='colorRGB'>$rgbCode</div>" : "";
        $offset = $color["Offset"];
        $html = <<<HTML
            <div class="colorContainer">
                $colorSample
                $colorName
                $hexCode
                $rgbCode
            </div>
        HTML;
        return $html;
    }

    public static function CompareName($color_a, $color_b)
    {
        return strcmp($color_a["Name"], $color_b["Name"]);
    }
    public static function CompareOffset($color_a, $color_b)
    {
        if ($color_a["Offset"] > $color_b["Offset"])
            return 1;
        if ($color_a["Offset"] < $color_b["Offset"])
            return -1;
        return 0;
    }
    public static function CompareHex($color_a, $color_b)
    {
        if ($color_a["HEX"] > $color_b["HEX"])
            return 1;
        if ($color_a["HEX"] < $color_b["HEX"])
            return -1;
        return 0;
    }
    public static function CompareHue($color_a, $color_b)
    {
        if ($color_a["HLS"]["h"] > $color_b["HLS"]["h"])
            return 1;
        if ($color_a["HLS"]["h"] < $color_b["HLS"]["h"])
            return -1;
        return 0;
    }
    public static function CompareLightness($color_a, $color_b)
    {
        if ($color_a["HLS"]["l"] < $color_b["HLS"]["l"])
            return 1;
        if ($color_a["HLS"]["l"] > $color_b["HLS"]["l"])
            return -1;
        return 0;
    }
    public static function CompareSaturation($color_a, $color_b)
    {
        if ($color_a["HLS"]["s"] > $color_b["HLS"]["s"])
            return 1;
        if ($color_a["HLS"]["s"] < $color_b["HLS"]["s"])
            return -1;
        return 0;
    }
    public static function CompareRGB($color_a, $color_b)
    {
        $a = self::HEX_TO_RGB($color_a["HEX"]);
        $b = self::HEX_TO_RGB($color_b["HEX"]);
        if ($a["red"] == $b["red"]) {
            if ($a["green"] == $b["green"]) {
                if ($a["blue"] == $b["blue"])
                    return 0;
                else
                    return $a["blue"] > $b["blue"] ? 1 : -1;
            } else {
                return $a["green"] > $b["green"] ? 1 : -1;
            }
        } else
            return $a["red"] > $b["red"] ? 1 : -1;
    }
    //public $sortNames = array("Original", "Nom", "Correspondance", "Teinte", "Luminosité", "Saturation");
    private function sort()
    {
        switch ($this->sortKey) {
            case 'Original':
                break;
            case 'Nom':
                usort($this->colors, 'Colors::CompareName');
                break;
            case 'Correspondance':
                usort($this->colors, 'Colors::CompareOffset');
                break;
            case 'Teinte':
                usort($this->colors, 'Colors::CompareHue');
                break;
            case 'Luminosité':
                usort($this->colors, 'Colors::CompareLightness');
                break;
            case 'Saturation':
                usort($this->colors, 'Colors::CompareSaturation');
                break;
        }
    }
    public function show()
    {
        $this->calcOffset();
        $this->sort();
        $html = "<div class='colorsContainer'>";
        foreach ($this->colors as $color) {
            if (str_contains(strtolower($color["Name"]), strtolower($this->search))) {
                if ($color["Offset"] < (100 - $this->range)) {
                    $html .= $this->colorView($color);
                }
            }
        }
        $html .= "</div>";
        return $html;
    }
}