(function () {
    'use strict';
    
    var Asset,
        Assets,
        AssetView,
        AssetsView,
        AssetModalView,
        assetTemplate;

    Asset = Backbone.Model.extend({ idAttribute: 'id' });

    Assets = Backbone.Collection.extend({
        model: Asset,
        comparator: 'name',
    });

    AssetView = Backbone.View.extend({
        tagName: 'tr',
        template: (function () {
            if (typeof assetTemplate === 'undefined') {
                assetTemplate = _.template($('#asset-row-template').html());
            }
            return assetTemplate;
        }()),
        initialize: function () {
            this.model.on('change', this.render, this);
        },
        render: function () {
            var html = this.template({ asset: this.model.attributes });
            this.$el.attr('data-id', this.model.get('id'));
            this.$el.html(html);
            return this;
        }
    });

    AssetsView = Backbone.View.extend({
        el: '#school',
        modal: null,
        events: {
            'click .btn-add-asset': 'addAsset',
            'click tbody tr': 'editAsset'
        },
        initialize: function () {
            var that = this;
            this.modal = new AssetModalView();
            _.each(this.$el.find('tbody tr[data-asset]'), function (tr) {
                var data,
                    asset;
                tr = $(tr);
                data = tr.data('asset');
                asset = new Asset(data);
                that.model.add(asset);
                new AssetView({ model: asset });
                tr.attr('data-asset', null);
            });
            this.model.on('add', this.renderAsset, this);
        },
        renderAsset: function (asset) {
            this.$el.find('tbody').append(new AssetView({
                model: asset
            }).render().el);
            return this;
        },
        addAsset: function (evt) {
            var that = this;
            evt.preventDefault();
            this.modal.render(function (data) {
                that.model.add(data);
            });
        },
        editAsset: function (evt) {
            var asset = this.model.get($(evt.target).closest('tr').data('id'));
            this.modal.render(function (data) {
                asset.set(data);
            }, asset.attributes);
        }
    });

    AssetModalView = Backbone.View.extend({
        el: '#asset-form-modal',
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
        render: function (done, asset) {
            var template,
                that = this;

            asset = asset || {};
            this.form[0].reset();
            this.form.find('input[type="hidden"]').val('');
            this.form.data('done', done);
            _.each(this.form[0].elements, function (element) {
                var element = $(element),
                    name = element.attr('name');

                if (typeof asset[name] !== undefined) {
                    element.val(asset[name]);
                }

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

    new AssetsView({ model: new Assets() });
}());