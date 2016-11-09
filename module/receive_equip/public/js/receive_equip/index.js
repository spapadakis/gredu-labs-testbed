(function ($, _, utils) {
    'use strict';

    var ItemsView,
        ItemRowView;

    ItemRowView = Backbone.View.extend({
        tagName: 'tr',
        render: function (index) {
            this.$el.html(this.template({ index: index | 0}));

            return this;
        },
        template: _.template($('#receive-equip-item-row-template').html())
    });

    ItemsView = Backbone.View.extend({
        el: '#items-list',
        initialize: function () {
            this.itemCount = this.$el.find('tbody tr').length;
        },
    });

    new ItemsView();

    (function () {
        var form = $('#receive-equip form'),
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
