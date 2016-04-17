$(document).on("click", ".open-edit-modal", function () {
    var grade = $(this).data('grade');
    $(".review-modal #grade").val(grade);
    var text = $(this).data('text');
    $(".review-modal #text").val(text);
    var id = $(this).data('id');
    var revId = $(this).data('rev-id');
    $("#reviewForm").attr('action', '/items/' + id + '/' + revId + '/edit');
});
