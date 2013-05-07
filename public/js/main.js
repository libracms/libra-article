jQuery(function($) {
    $("#articleForm input[name=metaKeywords]").keyup(function() {
        $("#metaKeywordsLength").text($(this).val().length);
    });
});