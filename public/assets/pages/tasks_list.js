$("#search-button").click(function () {
    var search_keyword = $('#search-keyword').val();

    if (search_keyword !== '') {
        window.location = '/' + $("#search_by").val() + '/'+ search_keyword + '/';
    } else {
        window.location = '/';
    }
});

$("#search-form").submit(function () {
    $("#search-button").click();
    return false;
});