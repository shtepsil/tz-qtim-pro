/*scroll to top*/

$(document).ready(function () {

    // Отключаем ссылки с классом no-link
    $('.no-link').click(function (e) {
        e.preventDefault();
    });

    $(function () {
        $.scrollUp({
            scrollName: 'scrollUp', // Element ID
            scrollDistance: 300, // Distance from top/bottom before showing element (px)
            scrollFrom: 'top', // 'top' or 'bottom'
            scrollSpeed: 300, // Speed back to top (ms)
            easingType: 'linear', // Scroll to top easing (see http://easings.net/)
            animation: 'fade', // Fade, slide, none
            animationSpeed: 200, // Animation in speed (ms)
            scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
            //scrollTarget: false, // Set a custom target element for scrolling to the top
            scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
            scrollTitle: false, // Set a custom <a> title if required.
            scrollImg: false, // Set true to use image
            activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
            zIndex: 2147483647 // Z-Index for the overlay
        });
    });

    // Кнопка регистрация
    $('.w-register button[name=reg]').on('click', function () {
        var $this = $(this),
            wrap = $('.w-register'),
            res = $('.res'),
            load = $this.find('img.loading'),
            form = wrap.find('form[name=form_reg]'),
            errors = wrap.find('.signup-form .error'),
            errors_strs = '';

        errors.html('').fadeOut(100);
        Data = form.serializeArray();
        res.html('result');

        // cl(form.attr('action'));
        // cl(Data);
        // return;

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            dataType: 'json',
            cache: 'false',
            data: Data,
            beforeSend: function () {
                load.fadeIn(100);
            }
        }).done(function (data) {
            res.html('Done<br><pre>' + prettyPrintJson.toHtml(data) + '</pre>');
            if (data.status == 200) {
                setCookie('refreshToken', data.refresh_token, { 'max-age': 86400 });
                location.href = '/user/login';
            } else {
                if (data.errors) {
                    var arr = data.errors;
                    arr.forEach(function (item, i, arr) {
                        errors_strs += item + '<br>';
                    });
                    errors.html(errors_strs).promise().done(function () {
                        errors.fadeIn(100);
                    });
                }
            }
        }).fail(function (data) {
            res.html('Fail<br>' + JSON.stringify(data));
            a.warning('Ошибка сервера...');
        }).always(function () {
            load.fadeOut(100);
        });

    });


    // Кнопка войти
    $('.w-login button[name=auth]').on('click', function () {
        var $this = $(this),
            wrap = $('.w-login'),
            res = $('.res'),
            load = $this.find('img.loading'),
            form = wrap.find('form[name=form_auth]'),
            errors = wrap.find('.signup-form .error'),
            errors_strs = '';

        errors.html('').fadeOut(100);
        Data = form.serializeArray();

        // cl(Data);
        // return;

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            dataType: 'json',
            cache: 'false',
            data: Data,
            beforeSend: function () {
                load.fadeIn(100);
            }
        }).done(function (data) {
            res.html('Done<br><pre>' + prettyPrintJson.toHtml(data) + '</pre>');
            if (data.status == 200) {
                setCookie('refreshToken', data.refresh_token, { 'max-age': 86400 });
                location.href = '/';
            } else {
                if (data.errors) {
                    res.html('Ошибки');
                    var arr = data.errors;
                    arr.forEach(function (item, i, arr) {
                        errors_strs += item + '<br>';
                    });
                    errors.html(errors_strs).promise().done(function () {
                        errors.fadeIn(100);
                    });
                }
            }
        }).fail(function (data) {
            res.html('Fail<br>' + JSON.stringify(data));
            a.warning('Ошибка сервера...');
        }).always(function () {
            load.fadeOut(100);
        });

    });

    // Кнопка logout(выйти)
    $('a.logout').on('click', function () {
        var $this = $(this),
            wrap = $('.w-login'),
            res = $('.res'),
            load = $this.parent().parent().find('img.loading'),
            Data = {};

        Data.refresh_token = $.cookie('refreshToken');

        // cl(Data);
        // return;

        $.ajax({
            url: $this.attr('data-url'),
            type: $this.attr('data-type'),
            dataType: 'json',
            cache: 'false',
            data: Data,
            beforeSend: function () {
                load.fadeIn(100);
            }
        }).done(function (data) {
            // res.html('Done<br><pre>' + prettyPrintJson.toHtml(data) + '</pre>');
            deleteCookie('refreshToken');
            location.href = '/';
        }).fail(function (data) {
            res.html('Fail<br>' + JSON.stringify(data));
            a.warning('Ошибка сервера...');
        }).always(function () {
            load.fadeOut(100);
        });

    });

    // Блок генерации гексограммы
    $('.w-g .click-layer').on('click', function () {
        var wrap = $('.geks-generation'),
            g = $('.w-g ul.geks');

        if (g.find('li').length == 6) return;

        var img = getRandomInRange(0, 1);

        if (g.find('li').length < 6) {
            g.append('<li data-code="' + img + '"><img src="/template/images/' + img + '.jpg" alt="" /></li>');
            if (g.find('li').length == 6) {

                var code = '';
                g.find('li').each(function () {
                    code += $(this).attr('data-code');
                });

                wrap.find('[name=get_question]').attr('data-code', code).fadeIn(100);

            }
        }
    });

    // Кнопка Очистить историю
    $('.w-g button[name=clear_history]').on('click', function () {
        var $this = $(this),
            wrap = $('.w-g'),
            res = $('.res'),
            load = $this.find('img.loading'),
            wh = wrap.find('.w-h'),
            Data = {};

        wrap.find('.result-history').html('');
        Data['user_id'] = wrap.attr('data-user-id');

        $this.prop('disabled', true);

        // cl(Data);
        // return;

        $.ajax({
            url: '/clearhistory',
            type: 'post',
            dataType: 'json',
            cache: 'false',
            data: Data,
            beforeSend: function () {
                load.fadeIn(100);
            }
        }).done(function (data) {
            //            res.html('Done<br>'+JSON.stringify(data));
            if (data.status == 200) {
                wh.find('.history').html('').promise().done(function () {
                    wrap.find('.result-history').html(data.text).promise().done(function () {
                        wrap.find('.result-history').fadeIn(100, function () {
                            wrap.find('.result-history')
                                .removeClass('error success')
                                .addClass('success');
                        });
                    });
                });
                setTimeout(function () {
                    wh.fadeOut(100);
                }, 1600);
            } else {
                wrap.find('.result-history').html(data.message).promise().done(function () {
                    wrap.find('.result-history').fadeIn(100, function () {
                        wrap.find('.result-history')
                            .removeClass('error success')
                            .addClass('error');
                    });
                });
                $this.prop('disabled', false);
            }
        }).fail(function (data) {
            res.html('Fail<br>' + JSON.stringify(data));
            $this.prop('disabled', false);
        }).always(function () {
            load.fadeOut(100);
            setTimeout(function () {
                wrap.find('.result-history').fadeOut(100, function () {
                    wrap.find('.result-history').html('');
                    wrap.find('[name=view_history]').fadeOut(100);
                });
            }, 1500);
        });

    });

    // Поле ввода "Введите свой вопрос"
    $('[name=user_question]').on('focus', function () {
        var $this = $(this),
            wrap = $('.w-g'),
            alerts = wrap.find('.alert');

        wrap.find('.geks-generation,.w-geks').fadeOut(100);
        alerts.fadeOut(100, function () {
            alerts.html('');
        });
    });

    // Кнопки "Генерироваь ответ" и "Получить ответ"
    $('[name=generation],[name=choose]').on('click', function () {
        var $this = $(this),
            wrap = $('.w-g'),
            alerts = wrap.find('.alert'),
            g = wrap.find('ul.geks');

        if (wrap.find('input[name=user_question]').val() == '') {
            alerts.html('Сначала введите свой впорос')
                .promise().done(function () {
                    alerts.fadeIn(100);
                    setTimeout(function () {
                        alerts.fadeOut(100, function () {
                            alerts.html('');
                        });
                    }, 5000);
                });
            return;
        }

        wrap.find('.geks-generation,.w-geks').fadeOut(100);

        if ($this.attr('name') == 'generation') {
            wrap.find('.geks-generation').fadeIn(100);
            g.html('');
            wrap.find('[name=get_question]').attr('data-code', '').hide().stop();
        } else {
            wrap.find('.w-geks').fadeIn(100);
        }
    });

    // Кнопка добавления/редактирования статьи
    $('.w-admin-articles button.article-send').on('click', function (e) {
        e.preventDefault();
        var $this = $(this),
            wrap = $('.w-admin-articles'),
            res = $('.res'),
            load = $this.find('img.loading'),
            form = wrap.find('form'),
            validate = true,
            Data = {};

        res.html('result');
        Data = form.serializeArray();
        var body = CKEditor.getData();
        Data.push({ name: 'text', value: body });

        for (key in Data) {
            if (Data[key].value == '') {
                validate = false;
            }
        }

        if (!validate) {
            a.warning('Заполните все поля', 'Пожалуйста');
            return;
        }

        if ($this.attr('data-id') != '') {
            Data.push({ name: 'id', value: $this.attr('data-id') });
        }

        // cl(Data);
        // return;

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            dataType: 'json',
            cache: 'false',
            data: Data,
            beforeSend: function () {
                load.fadeIn(100);
            }
        }).done(function (data) {
            res.html('Done<br><pre>' + prettyPrintJson.toHtml(data) + '</pre>');
            if (data.status == 200) {
                a.success('Сохранено успешно');
            } else {
                a.warning('Возникла проблема');
            }
        }).fail(function (data) {
            res.html('Fail<br>' + JSON.stringify(data));
            a.error('Ошибка');
        }).always(function () {
            load.fadeOut(100);
        }, 1500);
    });

    // Для элемента списка новостей
    $(".hover").mouseleave(
        function () {
            $(this).removeClass("hover");
        }
    );

    // Подгрузить ещё статьи на страницу
    $('.w-articles .articles-get-more, .w-admin-articles .articles-get-more').click(function () {
        var $this = $(this),
            res = $('.res'),
            wrap = $('.w-articles, .w-admin-articles'),
            load = $this.find('img.loading'),
            art_list = wrap.find('.articles-list'),
            Data = {};

        Data['start'] = wrap.find('.articles-list .article-item').length;
        Data['type'] = $this.attr('data-type');

        if (Data['start'] == 0) {
            a.warning('Но увы, статей пока нетушки! ))', 'Мне очень жаль ((', 7000);
            return;
        }

        // cl(Data);
        // return;

        $.ajax({
            url: '/articles/get',
            type: 'post',
            dataType: 'json',
            cache: 'false',
            data: Data,
            beforeSend: function () {
                load.fadeIn(100);
            }
        }).done(function (data) {
            res.html('Done<br><pre>' + prettyPrintJson.toHtml(data) + '</pre>');
            if (data.status === 200) {
                art_list.append(data.art_items);
                if (data.end !== undefined) {
                    $this.prop('disabled', true);
                    $this.addClass('no-drop');
                    a.alert('Получены последние статьи');
                }
            } else {
                a.warning('Статей больше нет');
                $this.prop('disabled', true);
            }
        }).fail(function (data) {
            res.html('Fail<br>' + JSON.stringify(data));
            //                LoadAlert(lang.notify_empty, lang.notify_unknown_error, 3000, 'error');
            $this.prop('disabled', true);
        }).always(function () {
            load.fadeOut(100);
        });
    });

    // Вызов модального окна для подтверждения удаления статьи
    $('.w-admin-articles').on('click', '.init-article-delete', function () {
        $('button.article-delete', '.w-admin-articles')
            .attr('data-article-id', $(this).attr('data-item-id'));
    })

    // Если модальное окно закрылось, удалим ID из его data-article-id
    $('.modal-confirm-article-delete').on("hidden.bs.modal", function () {
        $('button.article-delete', '.w-admin-articles')
            .attr('data-article-id', '');
        $('.w-admin-articles .article-delete img.loading').fadeOut(100);
    });

    // Удаление статьи
    $('.w-admin-articles .article-delete').click(function () {
        var $this = $(this),
            res = $('.res'),
            wrap = $('.w-articles, .w-admin-articles'),
            load = $this.find('img.loading'),
            Data = {};

        Data['id'] = $this.attr('data-article-id');

        // cl(Data);
        // return;

        $.ajax({
            url: '/articles/delete',
            type: 'post',
            dataType: 'json',
            cache: 'false',
            data: Data,
            beforeSend: function () {
                load.fadeIn(100);
            }
        }).done(function (data) {
            res.html('Done<br><pre>' + prettyPrintJson.toHtml(data) + '</pre>');
            if (data.status === 200) {
                a.success('Статья удалена');
                delete_item = $('[data-item-id=' + $this.attr('data-article-id') + ']').parent().parent();
                delete_item.fadeOut(300, function () {
                    delete_item.remove();
                });
                $('.modal-confirm-article-delete').modal('hide');
            } else {
                a.warning('Что то пошло не так');
            }
        }).fail(function (data) {
            res.html('Fail<br>' + JSON.stringify(data));
            $this.prop('disabled', true);
            a.error('Ошибка');
        }).always(function () {
            load.fadeOut(100);
        });
    });

});// JQuery












