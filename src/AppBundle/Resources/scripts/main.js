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
        $('#activity-edit-modal').modal('show').find('.modal-content').load($(this).attr('href'));
    }
);
