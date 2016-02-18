(function () {
    'use strict';

    var ItemsView,
        ItemRowView,
        itemCount = 0;


    ItemRowView = Backbone.View.extend({
        tagName: 'tr',
        render: function () {
            this.$el.html(this.constructor.template({ index: itemCount }));
            itemCount += 1;
            return this;
        }
    }, {
        template: _.template($('#app-form-item-row-template').html())
    });

    ItemsView = Backbone.View.extend({
        el: '#items-list',
        events: {
            'click .add-row': 'addRow',
            'click .remove-row': 'removeRow'
        },
        initialize: function () {
            this.addRow();
        },
        addRow: function () {
            this.$el.find('tbody').append(new ItemRowView().render().el);
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
}());