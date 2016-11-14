(function ($, _, utils) {
    'use strict';

    (function () {
        var form = $('#app-form form');
        var messages = form.data('messages');
        utils.formMessages.render(form, messages);
    }());

}(window.jQuery, _, window.EDULABS.utils));