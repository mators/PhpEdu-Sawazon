$(document).on("click", ".open-edit-modal", function () {
    $(".modal-title").html('Edit category');
    $("#postButton").html('Save changes');
    var name = $(this).data('name');
    $(".modal-body #name").val(name);
    var description = $(this).data('description');
    $(".modal-body #description").val(description);
    var id = $(this).data('id');
    $("#catForm").attr('action', '/categories/' + id + '/edit');
});

$(document).on("click", ".open-add-modal", function () {
    $(".modal-title").html('Add category');
    $("#postButton").html('Add');
    $(".modal-body #name").val('');
    $(".modal-body #description").val('');
    $("#catForm").attr('action', '/categories/add');
    var id = $(this).data('parent-id');
    $(".modal-body #parent-cat").val(id);
});
