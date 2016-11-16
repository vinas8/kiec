$(document).ready(function () {
    $(".activity-select").change(function () {
        var optionSelected = $(this).find("option:selected");
        var showId = $(optionSelected).attr("data-activityId");
        $(".activity-change").addClass('hidden');
        $(".activity-change[data-activityId='"+showId+"']").removeClass('hidden');
    });
});