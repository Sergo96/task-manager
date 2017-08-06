$("#preview-button").click(function(){
    $("#preview-title").html($("#task-title").val());
    $("#preview-status").html($("#task-status").val());
    $("#preview-name").html($("#author-name").val());
    $("#preview-email").html($("#author-email").val());
    $("#preview-text").html($("#task-description").val());

    $("#preview-block").removeClass('invisible');
});
