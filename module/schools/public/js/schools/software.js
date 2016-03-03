(function (utils){
    'use strict';

    var Software,
        SoftwareView,
        SoftwareCollection,
        SoftwareCollectionRow,
        SoftwareCollectionView,
        softwareTemplate;

    Software = Backbone.Model.extend({idAttribute: 'id'});

    SoftwareCollection = Backbone.Collection.extend({
        model: Software,
        comparator: 'title'
    });

    SoftwareCollectionRow = Backbone.View.extend({
        tagName: 'tr',
        template: (function () {
            if (typeof softwareTemplate == 'undefined') {
                softwareTemplate = _.template($('#software-row').html());
            }
            return softwareTemplate;
        }()),
        initialize: function () {
            this.model.on('change', this.render, this);
            this.model.on('remove', this.remove, this);
        },
        render: function () {
            this.$el.html(this.template({ software: this.model.attributes }));
            this.$el.attr('data-id', this.model.get('id'));
            return this;
        },
        remove: function () {
            this.$el.remove();
        }
    });
    
    SoftwareCollectionView = Backbone.View.extend({
        el: '#school',
        softwareView: null,
        events: {
            'click .btn-add-software': 'addSoftware',
            'click tbody tr': 'editSoftware'
        },
        initialize: function () {
            var that = this;
            this.softwareView = new SoftwareView({model: this.model});
            _.each(this.$el.find('tbody tr'), function (tr) {
                var data = $(tr).data('software'),
                    software = new Software(data);
                that.model.add(software);
                new SoftwareCollectionRow({ model: software, el: tr });
                $(tr).attr('data-software', null);
            });
            this.model.on('add', this.renderSoftware, this);
        },
        addSoftware: function (evt) {
            evt.preventDefault();
            this.softwareView.render();
            return this;
        },
        editSoftware: function (evt) {
            var softwareId;
            if ($(evt.target).is('a')) return;
            softwareId = $(evt.target).closest('tr').data('id');
            this.softwareView.render(softwareId);
            return this;
        },
        renderSoftware: function (software) {
            this.$el.find('tbody tr.no-records').remove();
            this.$el.find('tbody').append(new SoftwareCollectionRow({
                model: software
            }).render().el);
            return this;
        }
    });
    
    SoftwareView = Backbone.View.extend({
        el: '#software-form-modal',
        form: null,
        software: null,
        url: null,
        events: {
            'submit form': 'persistSoftware',
            'click button.remove': 'removeSoftware'
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
        render: function (softwareId) {
            var softwareAttributes;
            if (!softwareId) {
                this.$el.find('.modal-footer button.remove')
                    .prop('disabled', true)
                    .addClass('hidden');
            } else {
                this.$el.find('.modal-footer button.remove')
                    .prop('disabled', false)
                    .removeClass('hidden');
            }
            this.software = softwareId && this.model.get(softwareId) || null;
            softwareAttributes = this.software && this.software.attributes || {};
            _.each(this.form[0].elements, function (element) {
                var element = $(element),
                    name = element.attr('name');
                    element.val(softwareAttributes[name] || '');
            });
            this.show();
            
            return this;
        },
        show: function () {
            this.$el.modal('show');
            return this;
        },
        hide: function () {
            this.$el.modal('hide');
            return this;
        },
        persistSoftware: function (evt) {
            var data = utils.serializeObject(this.form);
            var that = this;
            evt.preventDefault();
            $.ajax({
                url: this.url,
                type: 'post',
                data: data
            }).done(function (response) {
                if (that.software) {
                    that.software.set(response);
                } else {
                    that.model.add(response);
                }
            });
        },
        removeSoftware: function () {
            var that = this;
            $.ajax({
                url: that.url,
                type: 'delete',
                data: {
                    'id': that.software.get('id')
                }
           }).done(function () {
                that.model.remove(that.sowftare.get('id'));
                that.hide();
            }).fail(function(xhr, err){
            });
        }

    });

    new SoftwareCollectionView({model: new SoftwareCollection() });
}(window.EDULABS.utils));
