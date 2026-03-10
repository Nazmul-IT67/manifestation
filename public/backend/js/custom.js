$(function ($) {
    // USE STRICT
    "use strict";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    // status change
    $('.status_id').on("change", function () {
        var id = $(this).data('id');
        var type = $(this).data('type');

        changeStatus(id, type);
    });

    function changeStatus(id, type) {
        if (type == 'staffs') {
            var url = '/dashboard/staffs/status/change';
        } else if (type == 'blogs') {
            var url = '/dashboard/blogs/status/change';
        } else if (type == 'country') {
            var url = '/dashboard/country/status/change';
        } else if (type == 'state') {
            var url = '/dashboard/states/status/change';
        } else if (type == 'city') {
            var url = '/dashboard/cities/status/change';
        } else if (type == 'advertisement') {
            var url = '/dashboard/advertisement/status/change';
        } else if (type == 'reporter') {
            var url = '/dashboard/reporters/status/change';
        } else if (type == 'brand') {
            var url = '/dashboard/brand/status/change';
        } else if (type == 'product') {
            var url = '/dashboard/product/status/change';
        }

        $.ajax({
            type: 'POST',
            url: url,
            data: {
                id: id,
            },
            dataType: 'json',
            cache: false,
            success: (res) => {
                if (res.output == 'success') {
                    if (res.statusId == 1) {
                        $("#status_id" + res.dataId).prop("checked", true);

                        $.toast({
                            heading: res.heading,
                            text: res.message,
                            icon: "success",
                            showHideTransition: 'fade',
                            position: 'top-right',
                        });

                    } else {
                        $("#status_id" + res.dataId).removeAttr('checked');
                        $.toast({
                            heading: res.heading,
                            text: res.message,
                            icon: "success",
                            showHideTransition: 'fade',
                            position: 'top-right',
                        });
                    }
                }
            },
            error: function (xhr) {
                $.toast({
                    heading: 'Error',
                    text: "Something Wrong",
                    icon: "error",
                    showHideTransition: 'fade',
                    position: 'top-right',
                });

            }
        });
    }

    // Select 2 with image for language code
    $(document).ready(function () {
        $("#language_code").select2({
            templateResult: formatOptions
        });
    });

    function formatOptions(lang) {
        if (!lang.id) { return lang.text; }

        console.log(lang.element.value);

        var $lang = $(
            '<span ><img sytle="display: inline-block;" src="/backend/flags/' + lang.element.value.toLowerCase() + '.png"  /> ' + lang.text + '</span>'
        );

        return $lang;
    }

});
