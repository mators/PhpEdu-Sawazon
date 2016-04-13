$(function() {
    $(".getChildren").click(function () {
        var id = $(this).data('id');
        $.ajax({
            url: '/categories/' + id + '/children',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $("#at-well").show();
                var str = '';
                var i;
                for (i = 0; i < data.length; ++i) {
                    str += JSON.stringify(data[i]);
                }
                $("#ajaxtest").html(str);
            }
        });
    });
});
