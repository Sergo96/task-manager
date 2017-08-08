$( document ).ready(function() {
    var init_params = $("#init_params").data();

    console.log(init_params);

    $("#search-button").click(function () {
        var search_keyword = $('#search-keyword').val(),
            order_by = $("#order_by").val();

        if (search_keyword !== '') {
            window.location = '/' + order_by + '/' +
                $("#search_by").val() + '/'+ search_keyword + '/';
        } else if (order_by !== '' && order_by !== 'id') {
            window.location = '/' + order_by + '/';
        }else {
            window.location = '/';
        }
    });

    $("#search-form").submit(function () {
        $("#search-button").click();
        return false;
    });
});
