(function () {
    'use strict';

    var ItemsView,
        ItemRowView,
        itemCount = 0;


    ItemRowView = Backbone.View.extend({
        tagName: 'tr',
        render: function (index) {
            this.$el.html(this.constructor.template({ index: index | 0}));

            return this;
        }
    }, {
        template: _.template($('#app-form-item-row-template').html())
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
            if (this.itemCount === 0) {
                this.addRow();
            }
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
}());