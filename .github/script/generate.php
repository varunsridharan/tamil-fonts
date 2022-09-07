<?php

$fonts = glob(__DIR__ . '/../../website/static/fonts/*.*');

$html = '';
$css = '';

if (!empty($fonts)) {
    foreach ($fonts as $font) {
        $font = basename($font);
        $hash = md5($font);
        $name = explode('.', $font);
        $name = (isset($name[0])) ? $name[0] : $font;
        $content = file_get_contents(__DIR__ . '/display.template');
        $content = str_replace(array('{{FONT_NAME}}', '{{hash}}'), array($name, $hash), $content);

        $content_css = file_get_contents(__DIR__ . '/display-css.template');
        $content_css = str_replace(array('{{FONT_URL}}', '{{hash}}'), array('../fonts/'.$font, $hash), $content_css);

        $html .= $content;
        $css .= $content_css;
    }
}

$html_final = file_get_contents(__DIR__ . '/index.html.template');
$html_final = str_replace('{{HTML}}', $html, $html_final);
file_put_contents(__DIR__ . '/../../website/index.html', $html_final);
file_put_contents(__DIR__ . '/../../website/static/css/fonts.css', $css);