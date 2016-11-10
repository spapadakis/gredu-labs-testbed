(function ($, _, utils) {
    'use strict';

    (function () {
        var form = $('#university-form form'),
            messages = (function (messages) {
                var itemMessages = {};
                _.each(messages.items || [], function (message, idx){
                    var name = 'items[' + idx + ']';
                    _.each(_.keys(message), function (prop) {
                        itemMessages[name + '[' + prop + ']'] = message[prop];
                    });
                });
                delete messages.items;
                _.extend(messages, itemMessages);
                return messages;
            }(form.data('messages')));
            utils.formMessages.render(form, messages);           
    }());
    
}(window.jQuery, _, window.EDULABS.utils));