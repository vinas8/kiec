$(document).ready(
    function () {
    }
);

$(".activity-select").change(
    function () {
        var optionSelected = $(this).find("option:selected");
        var showId = $(optionSelected).attr("data-activityId");
        $(".result-activityId").val(showId);
        $(".activity-change").addClass('hidden');
        $(".activity-change[data-activityId='" + showId + "']").removeClass('hidden');
    }
);

$('.modal-button').on(
    'click',
    function (e) {
        e.preventDefault();
        $('#modal').modal('show').find('.modal-content').load($(this).attr('href'));
    }
);

$(function () {
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
