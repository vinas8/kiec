$(document).ready(function () {
    console.log('ksdjf');
    // initAjaxForm();

});

$(".class-info").click(function (e) {
    // e.preventDefault();
    var alertMessage = $("#result-insert-alert");

    $.ajax({
        // type: $(this).attr('method'),
        // url: $(this).attr('action'),
        // data: $(this).serialize()
    })
        .done(function (data) {
            console.log('miau');
            // alertMessage.removeClass('hidden');
            // alertMessage.removeClass('alert-danger');
            // alertMessage.addClass('alert-success');
            // alertMessage.html(data.message);
        })
        .fail(function () {
            // alertMessage.removeClass('hidden');
            // alertMessage.removeClass('alert-success');
            // alertMessage.addClass('alert-danger');
            // alertMessage.html(data.message);
        });
    $(this).find("div").find("input[type=text]").val("");
    // var optionSelected = $(this).find("option:selected");
    // var showId = $(optionSelected).attr("data-activityId");
    // $(".result-activityId").val(showId);
    // $(".activity-change").addClass('hidden');
    // $(".activity-change[data-activityId='"+showId+"']").removeClass('hidden');
    // $(".student-list-group").removeClass('hidden');
});

// $("#result-insert-alert").click(function () {
//     $("#result-insert-alert").addClass('hidden');
// });

