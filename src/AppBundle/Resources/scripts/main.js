$(document).ready(function () {
    $(".activitySelect").change(function () {
        var optionSelected = $(this).find("option:selected");
        var showId = $(optionSelected).attr("data-activityId");
        $(".activityChange").addClass('hidden');
        $(".activityChange[data-activityId='"+showId+"']").removeClass('hidden');
    });
});