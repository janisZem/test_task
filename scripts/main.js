var PAGE = {
    PROCESS: {
        checkAll: function () {
            if ($('.payment-checkbox').prop('checked')) {
                $('.payment-checkbox').prop('checked', false);
            } else {
                $('.payment-checkbox').prop('checked', true);
            }
        },
        store: function () {
            var elems = $('.payment-checkbox:checked');
            var data = {};
            for (var i = 0; i < elems.length; i++) {
                var elem = $(elems[i]).parent().parent().parent().parent();
                data[i] = {
                    'date': $(elem).children('.p-date').text(),
                    'benefactor': $(elem).children('.p-benefactor').text(),
                    'amount': $(elem).children('.p-amount').text(),
                    'account_numer': $(elem).children('.p-account_numer').text()
                };
            }
            var d = {};
            d['payments'] = data;
            d['path'] = $('#file_path').val();
            $.ajax({
                url: '/test_task/store.php',
                type: "post",
                data: d,
                success: function (id) {
                    window.location.reload()
                }
            });

        }
    }
};