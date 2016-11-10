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
        template: _.template($('#app-form-item-row-template').html())
    });

    ItemsView = Backbone.View.extend({
        el: '#items-list',
        form: null,
        teacher: null,
        url: null,
        events: {
            'submit form': 'persistTeacher',
        },
        initialize: function () {
         var that = this;
            this.form = this.$el.find('form');
            this.url = this.form.data('url');
            this.$el.on('hide.bs.modal', function () {
                utils.formMessages.clear(that.form);
                that.form[0].reset();
                that.form.find('input[type="hidden"]').val('');
            });
        },
            persistTeacher: function (evt) {
            var data = utils.serializeObject(this.form);
            var that = this;
            evt.preventDefault();
            $.ajax({
                url: this.url,
                type: 'post',
                data: data
            }).done(function(response){
                if (that.teacher) {
                    that.teacher.set(response);
                } else{
                    that.model.add(response);
                }
                that.hide();
            }).fail(function (xhr, err) {
                var messages;
                if (422 === xhr.status) {
                    messages = JSON.parse(xhr.responseText).messages || {};
                    utils.formMessages.render(that.form, messages);
                } else {
                    alert('Προέκυψε κάποιο σφάλμα');
                }
                
            });
        },


    });

    new ItemsView();

    (function () {
        var form = $('#app-form'),
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