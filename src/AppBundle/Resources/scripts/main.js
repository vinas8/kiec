$(document).ready(
    function () {
        var showId = $(".activity-select option:first").attr("data-activityId");
        showList(showId);

        $(".activity-select").change(
            function () {
                var showId = $($(this).find("option:selected")).attr("data-activityId");
                showList(showId);
            }
        );

        $('.modal-button').on(
            'click',
            function (e) {
                e.preventDefault();
                $('#modal').modal('show').find('.modal-content').load($(this).attr('href'));
            }
        );
    }
);

function showList(showId) {
    $(".result-activityId").val(showId);
    $(".activity-change").addClass('hidden');
    $(".activity-change[data-activityId='" + showId + "']").removeClass('hidden');
}


$(function () {
    $('input[type="date"]').attr('type','text');
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        language: 'lt'
    });

    $('.timepicker').timepicker({
        showInputs: false,
        minuteStep: 5,
        showSeconds: false,
        showMeridian: false,
        defaultTime: '08:00'
    });
});

enquire
    .register("screen and (min-width:95em)", function () {
        $("body").addClass("layout-boxed");
    })
    .register("screen and (max-width:95em)", function () {
        $("body").removeClass("layout-boxed");
    });