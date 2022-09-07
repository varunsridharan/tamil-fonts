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
});
