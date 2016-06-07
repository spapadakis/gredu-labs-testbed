(function (utils) {
    'use strict';

    var Lab,
        Labs,
        LabRow,
        LabsView,
        LabView,
        labTemplate;

    Lab = Backbone.Model.extend({ idAttribute: 'id' });

    Labs = Backbone.Collection.extend({
        model: Lab,
        comparator: 'name'
    });

    LabRow = Backbone.View.extend({
        tagName: 'tr',
        template: (function () {
            if (typeof labTemplate === 'undefined') {
                labTemplate = _.template($('#lab-row-template').html());
            }
            return labTemplate;
        }()),
        initialize: function () {
            this.model.on('change', this.render, this);
            this.model.on('remove', this.remove, this);
        },
        render: function () {
            this.$el.html(this.template({ lab: this.model.attributes }));
            this.$el.attr('data-id', this.model.get('id'));
            return this;
        },
        remove: function () {
            this.$el.remove();
        }
    });

    LabsView = Backbone.View.extend({
        el: '#school',
        labView: null,
        events: {
            'click .btn-add-lab': 'addLab',
            'click tbody tr': 'editLab'
        },
        initialize: function () {
            var that = this;
            this.labView = new LabView({model: this.model});
            _.each(this.$el.find('tbody tr'), function (tr) {
                var data = $(tr).data('lab'),
                    lab = new Lab(data);
                that.model.add(lab);
                new LabRow({ model: lab, el: tr });
                $(tr).attr('data-lab', null);
            });
            this.model.on('add', this.renderLab, this);
            this.model.on('remove', function () {
                if (this.model.length === 0) {
                    this.$el.find('tbody tr.no-records').show();
                }
            }, this);
        },
        addLab: function (evt) {
            evt.preventDefault();
            this.labView.render();
            return this;
        },
        editLab: function (evt) {
            var labId;
            labId = $(evt.target).closest('tr').data('id');
            this.labView.render(labId);
            return this;
        },
        renderLab: function (lab) {
            this.$el.find('tbody tr.no-records').hide();
            this.$el.find('tbody').append(new LabRow({
                model: lab
            }).render().el);
            return this;
        }
    });

    LabView = Backbone.View.extend({
        el: '#lab-form-modal',
        form: null,
        lab: null,
        attachment: null,
        uploadedLabel: null,
        url: null,
        events: {
            'submit': 'persistLab',
            'click button.remove': 'removeLab'
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
            this.attachment = this.form.find('.uploaded');
            this.attachment.on('click', 'a.btn-remove', function (evt) {
                var url;
                evt.preventDefault();
                url = $(evt.target).closest('a').attr('href');
                that.removeAttachment(url);
            });
            this.uploadedLabel = _.template(that.attachment.find('.uploaded-label').html().replace(':',  ' <%= filename %>:'));
        },
        render: function (labId) {
            var labAttributes;
            
            if (!labId) {
                this.$el.find('.modal-footer button.remove')
                    .prop('disabled', true)
                    .addClass('hidden');
            } else {
                this.$el.find('.modal-footer button.remove')
                    .prop('disabled', false)
                    .removeClass('hidden');
            }
            this.lab = labId && this.model.get(labId) || null;
            labAttributes = this.lab && this.lab.attributes || {};
            
            _.each(this.form[0].elements, function (element) {
                var name;
                element = $(element);
                name = element.attr('name');
                if ('file' === element.attr('type')) return;
                if ('checkbox' === element.attr('type')) {
                    element.prop('checked', utils.parseInt(labAttributes[name]));
                } else {
                element.val(labAttributes[name] || '');
                }
            });
            if (!this.lab || !this.lab.get('attachment')) {
                this.attachment.find('a').attr('href', '#');
                this.attachment.hide();
            } else {
                this.attachment.find('.uploaded-label').html(this.uploadedLabel({filename: this.lab.get('attachment_file')}));
                this.attachment.find('a').attr('href', this.attachment.data('href').replace('__lab_id__', this.lab.get('id')));
                this.attachment.show();
            }
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
        persistLab: function (evt) {
            var formData = utils.serializeObject(this.form),
                that = this,
                upload = this.form.find('input[type="file"]').fileupload({
                    formData: formData,
                    autoUpload: false
                }),
                postPromise;
            evt.preventDefault();
            
            if (upload[0].files.length === 0) {
                postPromise = $.ajax({
                    url: that.url,
                    type: 'post',
                    data: formData
                });
            } else {
                postPromise = upload.fileupload('send', {files: upload[0].files});
            }
            
            postPromise.done(function (response) {
                if (that.lab) {
                    that.lab.set(response);
                } else {
                    that.model.add(response);
                }
                that.hide();
            }).fail(function (xhr, err) {
                var messages;
                if (xhr && 422 === xhr.status) {
                    messages = JSON.parse(xhr.responseText).messages || {};
                    utils.formMessages.render(that.form, messages);
                } else {
                    alert('Προέκυψε κάποιο σφάλμα');
                }
            });
            upload.fileupload('destroy');
        },
        removeLab: function(evt) {
            var that = this;
            if (!confirm('Να διαγραφεί ο χώρος;')) {
                return;
            }
            $.ajax({
                url: that.url,
                type: 'delete',
                dataType: 'json',
                data: {
                    id: that.lab.get('id')
                }
            }).done(function () {
                that.model.remove(that.lab.get('id'));
                that.hide();
            }).fail(function (xhr, err){
                alert('Δεν ήταν δυνατή η διαγραφή του χώρου');
            });
            
        },
        removeAttachment: function (url) {
            var that = this;
            if (!confirm('Να διαγραφή το αρχείο;')) {
                return;
            }
            $.ajax({
                url: url,
                type: 'delete',
            }).done(function (response) {
                that.lab.set('attachment', null);
                that.lab.set('attachment_mime', null);
                that.attachment.hide();
            }).fail(function () {
                alert('Δεν ήταν δυνατή η διαγραφή του αρχείου');
            });
        }
    });

    new LabsView({ model: new Labs() });
}(window.EDULABS.utils));