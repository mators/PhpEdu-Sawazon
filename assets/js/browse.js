$(function() {
    $("#template").hide();
    $("#childrenWell").hide();

    $(".getChildren").click(function () {
        var id = $(this).data('id');
        $.ajax({
            url: '/categories/' + id + '/children',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $("#childrenDiv a").slice(1).remove();
                $("#childrenWell").show();

                var template = $("#template");
                for (var i = 0; i < data.length; ++i) {
                    var id = 'child' + i;
                    var tmp = template.clone();
                    tmp.attr('id', id);
                    $("#childrenDiv").append(tmp);
                    $("#" + id + " h4").html(data[i].name);
                    $("#" + id + " img").attr('src', data[i].iconURL);
                    $("#" + id).attr('data-id', data[i].id);
                    tmp.show();
                }

                $(".cat").click(function () {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '/categories/' + id,
                        type: 'GET',
                        dataType: 'html',
                        success: function (data) {
                            $("#category").remove();
                            $(".page").append(data);
                        }
                    });
                });
            }
        });
    });

});
