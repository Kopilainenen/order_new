$(document).ready(function () {
    //$('.checkout-categories-item[data-category="card"]').addClass('closed complete');
    $(document).on('click', '.js-delivery-next', function (e) {
        var container = $(this).parents('.js-checkout-category');
        e.preventDefault();
        if (container.find('input[name="DELIVERY_ID"]:checked').length == 0) {
            alert('Не выбран способ доставки');
            return false;
        } else if (container.find('.js-about-time-tab input:checked').length == 0 
            && container.find('input[name="DELIVERY_ID"]:checked').val() != 3
            && container.find('input[name="DELIVERY_ID"]:checked').val() != 19) {
            alert('Не выбран временной слот');
            return false;
        } else {
            $(this).parents('.js-checkout-category').addClass('closed complete');
            $('.js-checkout-category').not('.complete').first().removeClass('closed');
        }
    });
    $(document).on('click', '.js-payment-next', function (e) {
        var container = $(this).parents('.js-checkout-category');
        e.preventDefault();
        if (container.find('input:checked').length == 0) {
            console.log(this);
            alert('Не выбран способ оплаты');
            return false;
        } else {
            $(this).parents('.js-checkout-category').addClass('closed complete');
            $('.js-checkout-category').not('.complete').first().removeClass('closed');
        }
    });
    $(document).on('click', '.js-checkout-next', function (e) {
        e.preventDefault();
        $(this).parents('.js-checkout-category').addClass('closed complete');
        var nextEl = $('.js-checkout-category').not('.complete').first();
        nextEl.removeClass('closed');
        var menuHeight = 0,
            top = nextEl.offset().top,
            topIndent = top - menuHeight;
        $('html').animate({scrollTop: topIndent}, 300);
    });



// Переменные для хранения оригинальной суммы
    var originalAmount;
    var originalAmountWD;

    $(document).on('change', '[name="PAY_SYSTEM_ID"]', function () {
        // Функция для обработки суммы и селектора
        function handleAmount(selector) {
            // Получаем текущую отформатированную сумму
            var currentAmount = $(selector).text();

            // Преобразуем строку в число
            var currentNumber = parseFloat(currentAmount.replace(/[^0-9.]/g, ''));

            // Если еще не сохранена оригинальная сумма, сохраняем ее
            if (!originalAmount && selector === '.checkout-aside-details__price.basket_full_price') {
                originalAmount = currentNumber;
            }

            if (!originalAmountWD && selector === '.checkout-aside-details__price.basket_full_price_wd') {
                originalAmountWD = currentNumber;
            }

            // Если выбран тип оплаты с id 1
            if (parseInt($('[name="PAY_SYSTEM_ID"]:checked').val()) === 1) {
                // Обнуляем копейки, но сохраняем их для отображения
                var cents = currentNumber % 1;
                currentNumber -= cents;

                // Отображаем округленную сумму с обнуленными копейками
                $(selector).text(currentNumber.toFixed(2));
            } else {
                // Если выбран другой тип оплаты, восстанавливаем оригинальное значение суммы
                if (selector === '.checkout-aside-details__price.basket_full_price') {
                    $(selector).text(originalAmount.toFixed(2));
                } else if (selector === '.checkout-aside-details__price.basket_full_price_wd') {
                    $(selector).text(originalAmountWD.toFixed(2));
                }
            }
        }

        var ico = $(this).parent('.checkout-categories-item-body-option').find('.checkout-categories-item-body-option__icon');
        var name = $(this).parent('.checkout-categories-item-body-option').find('.checkout-categories-item-body-option__text').clone();
        var html = ico.html();
        if (typeof html === 'undefined') html = '';
        $('#payment_details').html('');
        $('#payment_details').append('<div class="checkout-categories-item-body-details__icon">' + html + '</div>');
        $('#payment_details').append(name);

        $('.pay_description').hide();

        // Добавляем условия для отображения блоков .pay_X
        var paySystemId = parseInt($('[name="PAY_SYSTEM_ID"]:checked').val());

        if (paySystemId == 16) {
            $('.pay_16').show();
        }
        if (paySystemId == 17) {
            $('.pay_17').show();
        }
        if (paySystemId == 18) {
            $('.pay_18').show();
        }
        if (paySystemId == 19) {
            $('.pay_19').show();
        }
        if (paySystemId == 20) {
            $('.pay_20').show();
        }
        if (paySystemId == 21) {
            $('.pay_21').show();
        }
        if (paySystemId == 22) {
            $('.pay_22').show();
        }
        if (paySystemId == 23) {
            $('.pay_23').show();
        }

        // Вызываем функцию для обработки суммы и селектора
        handleAmount('.checkout-aside-details__price.basket_full_price');
        handleAmount('.checkout-aside-details__price.basket_full_price_wd');
    });
    $(document).on('change', '[name="DELIVERY_ID"]', function () {
        var selectedOption = $(this).parent('.checkout-categories-item-body-option');
        var deliveryText = selectedOption.find('.checkout-categories-item-body-option__text').text();
        var timeTitle = $('#time-title');
        var timeValue = $('#time-value');

        $('#delivery_details').empty();
        $('#delivery_details').append('<div class="checkout-categories-item-body-details__icon">' + selectedOption.find('.checkout-categories-item-body-option__icon').html() + '</div>');
        $('#delivery_details').append('<div class="checkout-categories-item-body-option__text">' + deliveryText + '</div>');

        if (parseInt($('[name="DELIVERY_ID"]:checked').val()) != 3) {
            $('#delivery_details').append('<div class="time-block">\n' +
                '    <div  id="time-title">От 30 минут</div>\n' +
                '    <div id="time-value"></div>\n' +
                '</div>');
        }

        $('#delivery_details').append('<div class="checkout-categories-item-body-details__price">' + $('#delivery_details').data('price') + '</div>');

        timeValue.text($('.checkout .js-about-time-tab input:checked').val());

        $('.delivery_text_express, .delivery_text').hide();
        if (parseInt($('[name="DELIVERY_ID"]:checked').val()) == 2) {
            $('.delivery_text').show();
        }

        if (deliveryText.trim() == 'Экспресс доставка') {
            $('#time-title').css({'color':'#de2d42'});
            $('#time-title').css({'border-bottom':'none'});
			$('#time-value').hide();
        }

    });
    $(document).on('change', '[name="ORDER_PROP_41"]', function () {
        $('[name="ORDER_PROP_40"]').prop('checked', false);
    });
    $(document).on('change', '[name="ORDER_PROP_40"]', function () {
        $('[name="ORDER_PROP_41"]').prop('checked', false);
    });

    $(document).on('change', '[name="ORDER_PROP_65"]', function () {
        $('[name="ORDER_PROP_66"]').prop('checked', false);
    });
    $(document).on('change', '[name="ORDER_PROP_66"]', function () {
        $('[name="ORDER_PROP_65"]').prop('checked', false);
    });


    $(document).on('keyup', '.address-value', function () {
        var street = $('#street-checkout').val(), house = $('#house-checkout').val(),
            korpus = $('#korpus-checkout').val(),
            porch = $('#porch-checkout input').val(), floor = $('#floor-checkout input').val(),
            flat = $('#flat-checkout').val(),
            comment = $('#comment-checkout').val();

        var address = street;
        if (house.length > 0)
            address += ' д. ' + house;
        if (korpus.length > 0)
            address += ' корп. ' + korpus;
        if (flat.length > 0)
            address += ' кв. ' + flat;
        if (porch.length > 0)
            address += ' подъезд ' + porch;
        if (floor.length > 0)
            address += ' этаж ' + floor;
        // if (comment.length > 0)
        //     address += ' Примечание: ' + comment;

        $('.address-hidden').val(address);
    });


    $(document).on('click', '.js-about-time', function () {
        var time = $(this).data('date');
        let index = $(this).data('index');
        $('#' + time).click();
        $('#SLOT_INDEX').val(index);
        $('.checkout-categories-item-body-about-time-body input').prop('checked', false);
        $('.checkout-categories-item-body-about-time-comment.products_items').html('');
        $.ajax({
            url: siteTemplate + '/ajax/check_date.php',
            dataType: 'json',
            data: {
                action: 'check',
                index: index,
            },
            success: function (data) {
                showDeliveryWarning(index, data.date);
                $('#show-date-attention').hide();
                if (data.show && !window.firstClick) {
                    $('#show-date-attention').show();
                    //$('.popup-stock-end').addClass('is-visible');
                    //$('.mfp-bg').addClass('is-visible');
                    $('.checkout-categories-item-body-about-time-comment.products_items').html(data.name)
                }
                window.firstClick = false;
            }
        })
    });

    function showDeliveryWarning(index, date) {

        $(".js-delivery-date").text(date);

        if (index == 1 || index == 2)
            $("#delivery-warning").show();
        else
            $("#delivery-warning").hide();
    }

    $(document).on('click', '.js-yes-stock', function (e) {
        e.preventDefault();
        $.ajax({
            url: siteTemplate + '/ajax/check_date.php',
            data: {
                action: 'update',
            },
            dataType: 'json',
            success: function (data) {
                if (data.reload)
                    window.location.reload();
            }

        })
    });
    window.firstClick = true;
    $('.js-about-time').eq(0).trigger('click');
    //$('#street-checkout').keyup();

    $(document).on('click', '.checkout-btn', function () {
        if (!$('.js-checkout-button').hasClass('active'))
            return false;

        sendOrder();
    });

    $(document).on('click', '.js-accept', function () {
        var checked = false;
        $('.js-accept').each(function (i, item) {
            if ($(item).find('input:checked').length) {
                checked = true;
            } else {
                checked = false;
                return false;
            }
        })
        if (checked)
            $('.js-checkout-button').addClass('active');
        else
            $('.js-checkout-button').removeClass('active');
    })

    $(document).on('click', '.js-accept-success', function (e) {
        e.preventDefault();
        var btn = $('.order-checkout .js-checkout-button');

        if ($(this).hasClass('load')) return;
        $(this).addClass('load');
        btn.addClass('active');
        btn.click();

    })

    $(document).on('click', '.checkout-categories-item.js-checkout-category', function () {
        var category = $(this).data('category');
        window.category = category;
    });

    function sendOrder() {
        showLoader();
        var data = $('#ORDER_FORM').serialize();

        $.ajax({
            type: 'POST',
            data: data,
            dataTye: 'html',
            success: function (res) {
                endLoader();
                // yaCounter56700082.reachGoal('order');
                $('#order_form_content').html(res);

                $('.js-about-time').each(function (i, item) {
                    var date = $(item).data('date');
                    if ($('#' + date).prop('checked'))
                        $(item).trigger('click');
                })

                $('.checkout-categories-item-body-about-time-option.checked input').click();
                setTimeout(function () {
                    $('.js-about-time-tab input:checked').parent().click();
                    $('.js-about-time-tab input:checked').parent().find('span').click();
                }, 500)
                $('[data-category="' + window.category + '"]').addClass('active').removeClass('closed');
                $('[data-category="' + window.category + '"]').prevAll().addClass('closed').addClass('complete').removeClass('active');
                $('[name="PAY_SYSTEM_ID"]:checked').change();
                $('[name="DELIVERY_ID"]:checked').change();
                $('#street-checkout').keyup();
                $.ajax({
                    url: '/bitrix/templates/gotovim_za_vas/ajax/street.php',
                    dataType: "json",
                    data: {
                        q: request.term,
                        site: 's5',
                        delivery: $('.delivery-change:checked').val()
                    },
                    success: function (data) {
                        response(data);
                        return false;
                    }
                });
                if ($('.errortext').length > 0) {
                    var body = $("html, body");
                    body.stop().animate({scrollTop: $('.errortext').offset().top - 300}, 500, 'swing');
                    $('.js-accept-success').removeClass('load');
                }

            }
        })
    }

    if ($('.js-datepicker').length) {
        const $today = new Date();
        const $todayMax = new Date();
        const $startDay = +($today.getDate() + 1);
        const $dayPeriod = +($today.getDate() + 2);
        $todayMax.setDate(+$dayPeriod);
        $today.setDate(+$startDay);
        $('.js-datepicker').datepicker({
            startDate: $today,
            toggleSelected: false,
            minDate: $today,
            maxDate: $todayMax,
            onSelect: function (date) {
                $('#pie-date').val(date);
            },
        }).data('datepicker').selectDate($today);
        $('.datepicker--cell.-selected-').click();
        // $(document).on('click', '.datepicker--cell-day:not(.-disabled-)', function (e) {
        //     let startElementDate = $(this).data('date');
        //     $('#pie-date').val(startElementDate);
        //     // let tabs = $('.js-pies-time-tab');
        //     // tabs.removeClass('active');
        //     // $('.datepicker--cell-day:not(.-disabled-)').each((index, element) => {
        //     //     if ($(element).data('date') == startElementDate){
        //     //         tabs.addClass(function (i){
        //     //             if (i==index){
        //     //                 return 'active';
        //     //             }
        //     //         });
        //     //     }
        //     // });
        // });
    }


    $(document).on('click', '.js-pies-checkout-button', sendPie);

    function sendPie(e) {
        showLoader();
        e.preventDefault();
        var data = getFormData('ORDER_FORM');

        var pieData = {};
        pieData['fio'] = data['pie-fio'];
        pieData['phone'] = data['pie-phone'];
        pieData['delivery'] = data['pie-delivery'];
        pieData['payment'] = data['pie-payment'];
        pieData['date'] = data['pie-date'];
        pieData['time'] = data['pie-time'];
        pieData['comment'] = data['pie-comment'];
        pieData['address'] = data['pie-address'];

        pieData['confirmorder'] = data['pie-confirmorder'];

        console.log(pieData)
        $.ajax({
            url: siteTemplate + '/ajax/pie-order-new.php',
            type: 'POST',
            data: pieData,
            dataType: 'json',
            failure: function () {
                endLoader();
            },
            success: function (res) {
                endLoader();
                if (!res.error) {
                    window.location.assign(res.url);
                } else {
                    alert(res.msg)
                }
            }
        })
    }

    $('#pie-street-checkout').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: siteTemplate + '/ajax/street.php',
                dataType: "json",
                data: {
                    q: request.term,
                    action: 'searchAddress'
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            $(this).val(ui.item.NAME)
            $('.pie-address-value').eq(1).trigger('keyup');
        }
    });

    $(document).on('keyup', '.pie-address-value', function () {
        var street = $('#pie-street-checkout').val(), house = $('#pie-house-checkout').val(),
            korpus = $('#pie-korpus-checkout').val(),
            porch = $('#pie-porch-checkout input').val(), floor = $('#pie-floor-checkout input').val(),
            flat = $('#pie-flat-checkout').val(),
            comment = $('#pie-comment-checkout').val();

        var address = street;
        if (house.length > 0)
            address += ' д. ' + house;
        if (korpus.length > 0)
            address += ' корп. ' + korpus;
        if (flat.length > 0)
            address += ' кв. ' + flat;
        if (porch.length > 0)
            address += ' подъезд ' + porch;
        if (floor.length > 0)
            address += ' этаж ' + floor;
        if (comment.length > 0)
            address += ' Примечание: ' + comment;

        $('#pie-address').val(address);
    });

    function getFormData(formId) {
        var form = BX(formId),
            prepared = BX.ajax.prepareForm(form),
            i;

        for (i in prepared.data) {
            if (prepared.data.hasOwnProperty(i) && i == '')
                delete prepared.data[i];
            if ($('input[name="' + i + '"]').prop('disabled'))
                delete prepared.data[i];
        }

        return !!prepared && prepared.data ? prepared.data : {};
    }


    $(document).on('change', '.checkout-categories-item-body-about-time-option input', function () {

        var date = $('#' + $('#order_form_content .js-about-time.active').data('date')).val(), slot = $(this).val(), _this = $(this);
        var zone = parseInt($(this).parent().data('zone'));
        if (zone > 0) {
            $.cookie("ZONE_ID", zone, {path: '/', expires: 180});
            $('[name="DELIVERY_ID"]').eq(0).parent().click();
            $('[name="DELIVERY_ID"]').eq(0).change();
            $('#ORDER_PROP_103').val($(this).parent().data('zone-name'))
            $('#ORDER_PROP_104').val($(this).parent().data('zone-name'))
            $('#ORDER_PROP_105').val($(this).parent().data('zone-name'))


            $('#ORDER_PROP_106').val('zone_' + zone)
            $('#ORDER_PROP_107').val('zone_' + zone)
            $('#ORDER_PROP_108').val('zone_' + zone)
        }
        $('#SLOT_ID').val($(this).parent().data('slot'))
        // if (parseInt(date) > 1)
        //     return false;

        $('#time-title').html($('.checkout .js-about-time.active').text());
        $('#time-value').html(slot);
        $.ajax({
            url: siteTemplate + '/ajax/checker.php',
            type: 'POST',
            data: {
                date: date,
                slot: slot
            },
            dateType: 'json',
            success: function (res) {
                if (res.close) {
                    alert('Данный слот закрылся');
                    _this.prop('disabled', true);
                    _this.prop('checked', false);
                }
            }
        })
    })


    $(document).on('change', '.delivery-change', function () {

        let val = $(this).val();
        checkDelivery(val);
    })

    function checkDelivery(val) {
        showLoader();
        $.ajax({
            url: templateFolder + '/ajax/express.php',
            type: 'POST',
            dataType: 'json',
            data: {'DELIVERY_ID': val},
            success: function (res) {
                endLoader();
                $('.checkout-categories-item-body-delivery__price').html(res.delivery_price);
                $('.delivery-block__bottom-price span').html(res.delivery_price);
                $('#delivery_price').html(res.delivery_price);
                $('.checkout-categories-item-body-details__price').html(res.delivery_price);
                $('#full_summ').html(res.price);
                $('#min_price_text').text(1000)

                $('#delivery_price').parent().show();
                $('#full_summ').parent().show();
                if (parseFloat(res.min_price) > 0)
                    $('#min_price_text').text(res.min_price)
                if (val == 3) {
                    $('.delivery_text').hide();
                    $('.delivery_text_express').hide();
                    $('#time-block').hide();
                    $('#time-block-title').hide();

                    // $('[data-category="payment"] .checkout-categories-item-body-option input').eq(0).prop('checked', true);
                    // $('[data-category="payment"] .checkout-categories-item-body-option').hide()
                    // $('#ID_PAY_SYSTEM_ID_16').parent().show();
                    // $('#ID_PAY_SYSTEM_ID_1').parent().show();
                    $('#delivery-props').hide();
                    $('#pickup-props').show();
                } else {
                    $('[data-category="payment"] .checkout-categories-item-body-option').show()
                    $('.delivery_text_express').hide();
                    $('.delivery_text').show();
                    $('#time-block').show();
                    $('#pickup-props').hide();
                    $('#delivery-props').show();
                    $('#time-block-title').show();

                    let index = $('.js-about-time.active').data('index');
                    if (parseInt(index) == 1 || parseInt(index) == 2)
                        $('#delivery-warning').show();
                    if (res.show) {
                        $('.delivery_text').hide();
                        $('.delivery_text_express').show();
                        $('#time-block').hide();
                        $('#time-block-title').hide();
                        $('#delivery-warning').hide();
                    }
                }

                $('#delivery_body').html(res.delivery)
            },
            fail: function () {
                endLoader();
            }
        })
    }


    $('[name="DELIVERY_ID"]').eq(0).prop('checked', true);
    $('[name="DELIVERY_ID"]').eq(0).change();
})
//Изменение адреса самовывоза
$(document).on('change', 'select[name=ORDER_PROP_88]', function (e) {
    var infoTextAddress = $('.info__text .address-pickup');

    infoTextAddress.html($(this).val());
});
/*Изменение сообщения в зависимости от времени*/
if ($.cookie('DELIVERY') == 'PICKUP' && $('select[name=ORDER_PROP_88]').length > 0) {
    setInterval(function () {
        var currentTime = new Date(),
            select = 1;

        if (currentTime.getHours() >= 8 && currentTime.getHours() < 17) {
            select = 1;
        } else {
            if (currentTime.getHours() >= 0 && currentTime.getHours() < 8) {
                select = 3;
            } else {
                if (currentTime.getHours() >= 17 && currentTime.getHours() <= 23) {
                    select = 2;
                }
            }
        }

        $('.info__text').addClass('hide');
        $('.info__text-' + select).removeClass('hide');
    }, 1000);
}

$(document).on('click', '.checkout-show-toggle_down', function (e) {
    var block = $(this).parents('.js-checkout-category');
    block.removeClass('closed');
    block.addClass('checkout-show');
})
$(document).on('click', '.checkout-show-toggle_up', function (e) {
    var block = $(this).parents('.js-checkout-category');
    block.addClass('closed');
    block.removeClass('checkout-show');
})


