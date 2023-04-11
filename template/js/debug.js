$('[name=delete_users]').on('click', function () {
    var $this = $(this),
        res = $('.res-delete'),
        load = $this.find('img.loading'),
        Data = {};

    res.html('result');

    // cl(Data);
    // return;

    $.ajax({
        url: '/user/delete',
        type: 'post',
        dataType: 'json',
        cache: 'false',
        data: Data,
        beforeSend: function () {
            load.fadeIn(100);
        }
    }).done(function (data) {
        res.html('Done<br><pre>' + prettyPrintJson.toHtml(data) + '</pre>');
    }).fail(function (data) {
        res.html('Fail<br>' + JSON.stringify(data));
        a.warning('Ошибка сервера...');
    }).always(function () {
        load.fadeOut(100);
    });
});

// Кнопка войти
$('[name=setcookie],[name=deletecookie]').on('click', function () {
    var $this = $(this),
        res = $('.res-setcookie'),
        load = $this.find('img.loading'),
        action = 'refresh',
        Data = {};

    res.html('result');

    $('[class*=res]').html('result');

    if ($this.attr('name') == 'deletecookie') {
        // action = 'deletecookie';
        // res = $('.res-deletecookie');

        deleteCookie('test_cookie');
        cl('Кука должна удалиться');
        setTimeout(function () {
            window.location.reload();
        }, 1000);
        return;
    }

    // $.cookie('test_cookie', 'Значение тестовой куки');
    // $.removeCookie('test_cookie', 'Значение тестовой куки');
    // $.removeCookie('test_cookie');
    // document.cookie = "test_cookie=" + $.cookie('test_cookie') + "; max-age=0";

    // cl(Data);
    // return;
    cl('/user/' + action);

    $.ajax({
        url: '/user/' + action,
        type: 'post',
        dataType: 'json',
        cache: 'false',
        data: Data,
        beforeSend: function () {
            load.fadeIn(100);
        }
    }).done(function (data) {
        res.html('Done<br><pre>' + prettyPrintJson.toHtml(data) + '</pre>');
    }).fail(function (data) {
        res.html('Fail<br>' + JSON.stringify(data));
        a.warning('Ошибка сервера...');
    }).always(function () {
        load.fadeOut(100);
    });
});