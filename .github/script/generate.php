<?php
define('BASE', __DIR__ . '/');
define('WEBSITE', __DIR__ . '/../../docs/');
define('FONTS', __DIR__ . '/../../docs/static/fonts/');

$html = array();
$css = array();
$category = array('all' => 'All');
$fonts = array();
function base64DataUri($sFile)
{
    // Switch to right MIME-type
    $sExt = strtolower(substr(strrchr($sFile, '.'), 1));

    switch ($sExt) {
        case 'gif':
        case 'jpg':
        case 'png':
            $sMimeType = 'image/' . $sExt;
            break;

        case 'ico':
            $sMimeType = 'image/x-icon';
            break;

        case 'eot':
            $sMimeType = 'application/vnd.ms-fontobject';
            break;

        case 'otf':
        case 'ttf':
        case 'woff':
            $sMimeType = 'application/octet-stream';
            break;

        default:
            exit('Invalid extension file!');
    }

    $sBase64 = base64_encode(file_get_contents($sFile));
    return "data:$sMimeType;base64,$sBase64";
}

function loop_dir($dir)
{
    global $html, $css, $category, $fonts;
    $dir = glob($dir . '/*');

    if (is_array($dir) && array_filter($dir) && !empty($dir)) {
        foreach ($dir as $font) {
            if (is_dir($font)) {
                loop_dir($font);
            } else {
                $hash = md5($font);
                $font_file = basename($font);
                $font_type = strtolower(substr(strrchr($font_file, '.'), 1));
                if (!in_array($font_type, array('ttf', 'otf'))) {
                    continue;
                }
                $font_path = trim(trim(str_replace(realpath(WEBSITE . '\static\fonts/'), '', realpath($font)), '\\'), '/');
                $cat = dirname($font_path);

                if ($cat === '.') {
                    $cat = 'UnCategorized';
                }

                $category[strtolower($cat)] = $cat;
                $font_path = str_replace('\\', '/', $font_path);
                $name = explode('.', $font_file);
                $name = (isset($name[0])) ? $name[0] : $font_file;
                #$content = file_get_contents(__DIR__ . '/display.template');

                $content_css = file_get_contents(__DIR__ . '/display-css.template');
                $content_css = str_replace(array('{{FONT_URL}}', '{{hash}}'), array('"../fonts/' . $font_path . '"', $hash), $content_css);
                $html[] = array(
                    'id' => $hash,
                    'name' => $name,
                    'category' => strtolower($cat),
                    'download_file' => $font_path,
                );#$content;
                $css[$hash] = $content_css;
                $fonts[$hash] = $font_path;
            }
        }
    }
}

loop_dir(FONTS);
$cat_html = array();
$fonts = json_encode($fonts);

foreach ($category as $key => $cat) {
    $cat_html[] = '<div class="item item-menu-font-category" data-value="' . $key . '">' . $cat . '</div>';
}

$fonts_displays = json_encode($html);
$html = str_replace(array("\n", "\r"), '', file_get_contents(__DIR__ . '/display.template'));

$display_fonts = '<script>
var fonts =' . $fonts . '; 
var fonts_displays = ' . $fonts_displays . ';
var display_template = \'' . $html . '\';
</script>';

$html_final = file_get_contents(__DIR__ . '/index.html.template');
$html_final = str_replace('{{HTML}}', $display_fonts, $html_final);
$html_final = str_replace('{{CATEGORY_HTML}}', implode(PHP_EOL, $cat_html), $html_final);
file_put_contents(WEBSITE . 'index.html', $html_final);
file_put_contents(WEBSITE . 'static/css/fonts.css', implode('', $css));