(function (utils) {
    'use strict';
    
    var Asset,
        Assets,
        AssetRow,
        AssetsView,
        AssetView,
        assetTemplate;

    Asset = Backbone.Model.extend({ idAttribute: 'id' });

    Assets = Backbone.Collection.extend({
        model: Asset,
        comparator: 'itemcategory',
    });

    AssetRow = Backbone.View.extend({
        tagName: 'tr',
        template: (function () {
            if (typeof assetTemplate === 'undefined') {
                assetTemplate = _.template($('#asset-row-template').html());
            }
            return assetTemplate;
        }()),
        initialize: function () {
            this.model.on('change', this.render, this);
            this.model.on('remove', this.remove, this);
        },
        render: function () {
            this.$el.html(this.template({ asset: this.model.attributes }));
            this.$el.attr('data-id', this.model.get('id'));
            return this;
        },
        remove: function () {
            this.$el.remove();
        }
    });

    AssetsView = Backbone.View.extend({
        el: '#school',
        assetView: null,
        events: {
            'click .btn-add-asset': 'addAsset',
            'click tbody tr': 'editAsset'
        },
        initialize: function () {
            var that = this;
            this.assetView = new AssetView({model: this.model});
            _.each(this.$el.find('tbody tr'), function (tr) {
                var data = $(tr).data('asset'),
                    asset = new Asset(data);
                that.model.add(asset);
                new AssetRow({ model: asset, el: tr });
                $(tr).attr('data-asset', null);
            });
            this.model.on('add', this.renderAsset, this);
            this.model.on('remove', function () {
                if (this.model.length === 0) {
                    this.$el.find('tbody tr.no-records').show();
                }
            }, this);
        },
        addAsset: function (evt) {
            evt.preventDefault();
            this.assetView.render();
            return this;
        },
        editAsset: function (evt) {
            var assetId;
            assetId = $(evt.target).closest('tr').data('id');
            this.assetView.render(assetId);
            return this;
        },
        renderAsset: function (asset) {
            this.$el.find('tbody tr.no-records').hide();
            this.$el.find('tbody').append(new AssetRow({
                model: asset
            }).render().el);
            return this;
        }
    });

    AssetView = Backbone.View.extend({
        el: '#asset-form-modal',
        form: null,
        asset: null,
        url: null,
        events: {
            'submit': 'persistAsset',
            'click button.remove': 'removeAsset'
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
        render: function (assetId) {
            var assetAttributes;
            
            if (!assetId) {
                this.$el.find('.modal-footer button.remove')
                    .prop('disabled', true)
                    .addClass('hidden');
            } else {
                this.$el.find('.modal-footer button.remove')
                    .prop('disabled', false)
                    .removeClass('hidden');
            }
            
            this.asset = assetId && this.model.get(assetId) || null;
            assetAttributes = this.asset && this.asset.attributes || {};
            
            _.each(this.form[0].elements, function (element) {
                var element = $(element),
                    name = element.attr('name');
                if ('checkbox' === element.attr('type')) {
                    element.prop('checked', utils.parseInt(assetAttributes[name]));
                } else {
                    element.val(assetAttributes[name] || '');
                }
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
        persistAsset: function (evt) {
            var data = utils.serializeObject(this.form),
                that = this;
            evt.preventDefault();
            $.ajax({
                url: this.url,
                type: 'post',
                data: data,
            }).done(function (response) {
                if (that.asset) {
                    that.asset.set(response);
                } else {
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
        removeAsset: function (evt) {
            var that = this;
            if (!confirm('Να διαγραφεί ο εξοπλισμός;')) {
                return;
            }
            $.ajax({
                url: that.url,
                type: 'delete',
                dataType: 'json',
                data: {
                    id: that.asset.get('id'),
                }
            }).done(function () {
                that.model.remove(that.asset.get('id'));
                that.hide();
            }).fail(function () {
                alert('Δεν ήταν δυνατή η διαγραφή του εξοπλισμού');
            });
            
        }
    });

    new AssetsView({ model: new Assets() });
}(window.EDULABS.utils));