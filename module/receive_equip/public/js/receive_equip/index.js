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
        itemCount: 0,
        events: {
            'click .add-row': 'addRow',
            'click .remove-row': 'removeRow'
        },
        initialize: function () {
            this.itemCount = this.$el.find('tbody tr').length;
        },
        addRow: function () {
            var index = this.itemCount;
            this.itemCount += 1;
            this.$el.find('tbody').append(new ItemRowView().render(index).el);
            return this;
        },
        removeRow: function (evt) {
            if (this.$el.find('tbody tr').length > 1) {
                $(evt.target).closest('tr').remove();
            }
            return this;
        }
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

    $('#receive-equip-load-modal').modal({
        backdrop: 'static'
    }).modal('show');


}(window.jQuery, _, window.EDULABS.utils));
