(function () {
    'use strict';

    $.each($('#school .nav-tabs li'), function (index, tab) {
        tab = $(tab);
        if (window.location.pathname === tab.find('a').attr('href')) {
            tab.addClass('active');
        } else {
            tab.removeClass('active');
        }
    });

    $('[required]').parents('.form-group').find('label').append('<span>*</span>');
}());