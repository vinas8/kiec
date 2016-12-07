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

function showList(showId)
{
    $(".result-activityId").val(showId);
    $(".activity-change").addClass('hidden');
    $(".activity-change[data-activityId='" + showId + "']").removeClass('hidden');
}


