function render_fonts(start, limit) {
    var run = 0;
    //$('#fontslist > .column').remove();
    for (var i = start; i < fonts_displays.length; i++) {
        if (run == limit) {
            break;
        }
        var template = display_template;
        var k = fonts_displays[i];
        template = template.replaceAll('{{FONT_NAME}}', k['name']);
        template = template.replaceAll('{{CATEGORY}}', ' category-' + k['name'] + ' category-all');
        template = template.replaceAll('{{EXAMPLE}}', $("#input").val());
        template = template.replaceAll('{{PREVIEW_CSS_CLASS}}', 'font-' + k['id']);
        template = template.replaceAll('{{hash}}', k['id']);
        $("#fontslist").append(template);
        run++;
    }
    var showing = $("#fontslist > div").length;
    var total = fonts_displays.length;
    $("#showings").html(showing + ' of ' + total + ' Displayed');
}

$(document).ready(() => {
    $(".ui.dropdown").dropdown();

    // Change input content
    $("#input").on("input", function () {
        const newText = $(this).val();

        $(".preview-text").text(newText);
    });

    // Change font size
    $("#preview-font-size").change(function () {
        const newFontSizeValue = $(this).val();

        $(".preview-text").css("font-size", `${newFontSizeValue}px`);
    });

    $("body").on("click", '.preview-text-container', function () {
        $(this).find('> .ui').toggleClass('font-selected');
    });

    $("body").on("click", '.preview-text-container i.bold', function () {
        $('p#' + $(this).attr('id')).toggleClass('font-bold');
        $(this).toggleClass('active');
    });


    $("body").on("click", '.preview-text-container i.italic', function () {
        $('p#' + $(this).attr('id')).toggleClass('font-italic');
        $(this).toggleClass('active');
    });


    // Change font size
    $(".item-menu-font-category").click(function () {
        var cat = $(this).data("value");

        $('.category-all').hide('slow');
        $('.category-' + cat).show('fast');
    });

    var start = 0;
    var limit = 50;
    render_fonts(0, limit);

    $(window).scroll(function () {
        if ($(window).scrollTop() == $(document).height() - $(window).height()) {
            start = (start + limit);
            render_fonts(start, limit);
            $(window).scrollTop($(window).scrollTop() - 1);
        }
    });
});