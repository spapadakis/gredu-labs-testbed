(function () {
    'use strict';

    var Lab,
        Labs,
        LabView,
        LabsView,
        LabModalView,
        labTemplate,
        utils = window.EDULABS.utils;

    Lab = Backbone.Model.extend({ idAttribute: 'id' });

    Labs = Backbone.Collection.extend({
        model: Lab,
        comparator: 'name'
    });

    LabView = Backbone.View.extend({
        tagName: 'tr',
        template: (function () {
            if (typeof labTemplate === 'undefined') {
                labTemplate = _.template($('#lab-row-template').html());
            }
            return labTemplate;
        }()),
        initialize: function () {
            this.model.on('change', this.render, this);
        },
        render: function () {
            var html = this.template({ lab: this.model.attributes });
            this.$el.html(html);
            this.$el.attr('data-id', this.model.get('id'));
            return this;
        }
    });

    LabsView = Backbone.View.extend({
        el: '#school',
        modal: null,
        events: {
            'click .btn-add-lab': 'addLab',
            'click tbody tr': 'editLab'
        },
        initialize: function () {
            var that = this;
            this.modal = new LabModalView();
            _.each(this.$el.find('tbody tr[data-lab]'), function (tr) {
                var data,
                    lab;
                tr = $(tr);
                data = tr.data('lab');
                lab = new Lab(data);
                that.model.add(lab);
                new LabView({ model: lab });
                tr.attr('data-lab', null);
            });
            this.model.on('add', this.renderLab, this);
        },
        renderLab: function (lab) {
            this.$el.find('tbody').append(new LabView({
                model: lab
            }).render().el);
            return this;
        },
        addLab: function (evt) {
            var that = this;
            evt.preventDefault();
            this.modal.render(function (data) {
                that.model.add(data);
            });
        },
        editLab: function (evt) {
            var lab = this.model.get(utils.parseInt($(evt.target).closest('tr').data('id')));
            if (!lab) return;
            this.modal.render(function (data) {
                lab.set(data);
            }, lab.attributes);
        }
    });

    LabModalView = Backbone.View.extend({
        el: '#lab-form-modal',
        form: null,
        events: {
            'submit': 'submit',
        },
        initialize: function () {
            this.form = this.$el.find('form');
        },
        show: function () {
            this.$el.modal('show');
            return this;
        },
        hide: function () {
            this.$el.modal('hide');
            return this;
        },
        render: function (done, lab) {
            var template,
                that = this;

            lab = lab || {};
            this.form[0].reset();
            this.form.find('input[type="hidden"]').val('');
            this.form.data('done', done);
            
            _.each(this.form[0].elements, function (element) {
                var element = $(element),
                    name = element.attr('name');
                element.val(lab[name] || '');
            });
            this.show();

            return this;
        },
        submit: function (evt) {
            var data;
            evt.preventDefault();
            data = _.reduce(this.form.serializeArray(), function (hash, pair) {
                    hash[pair.name] = pair.value;
                    return hash;
                }, {});
                evt.preventDefault();
                if (!data.id) {
                    data.id = (100 * Math.random()).toFixed(0);
                }
                this.form.data('done')(data);
                this.form.data('done', undefined);
            this.hide();
        } 
    });

    new LabsView({ model: new Labs() });
}());