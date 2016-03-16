/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
(function ($, _, utils) {
    'use strict';
    
    var ModalView;
    
    ModalView = Backbone.View.extend({
        el: '#tpe_survey-form-modal',
        model: null,
        template: null,
        events: {
            'submit': 'submitForm'
        },
        initialize: function () {
            this.model = null,
            this.template = _.template(this.$el.find('.modal-content script[type="text/template"]').html());
        },
        render: function () {
            var that = this,
                html,
                url;
            if (!this.model) return this;
            html = this.template({teacher: this.model.toJSON()});
            url = this.$el.find('form').attr('action');
            
            this.$el.find('.modal-content').html(html);
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                data: {
                    teacher_id: that.model.get('id')
                }
            }).done(function (response) {
                var elements = $(that.$el.find('form')[0].elements),
                    prop,
                    element;
                
                for (prop in response) {
                    if (response.hasOwnProperty(prop)) {
                        elements.closest('[name^="' + prop + '"]').each(function (i, element){
                            element = $(element);
                            if ('text' === element.attr('type') && /^assets_in_use/.test(element.attr('name'))) {
                                element.val(response[prop][response[prop].length - 1]);
                            } else if ('checkbox' === element.attr('type')) {
                                element.prop('checked', -1 !== _.indexOf(response[prop], element.val()));
                            } else {
                                element.val(response[prop] || '');
                            }
                        });
                        
                    }
                }
            }).always(function (response) {
                that.show();
            });
            
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
        submitForm: function (evt) {
            var that = this,
                form = this.$el.find('form'),
                url = form.attr('action'),
                data = utils.serializeObject(form);
            evt.preventDefault();
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: data,
            }).done(function (response) {
                that.hide();
            }).fail(function (xhr, err) {
                var messages;
                if (422 === xhr.status) {
                    messages = JSON.parse(xhr.responseText).messages || {};
                    utils.formMessages.render(form, messages);
                } else {
                    alert('Προέκυψε κάποιο σφάλμα');
                }
                
            });
            
            return false;
        }
    });
    
    var modal = new ModalView(),
        staffModel = window.EDULABS.models.staff || {};
    
    $('#school-staff table tbody').on('click', 'td.tpe_survey a', function (evt) {
        var target = $(evt.target).closest('a'),
            id = target.data('id'),
            teacher = typeof staffModel.get === 'function' && staffModel.get(id);
        
        evt.preventDefault();
        if(!teacher) {
            return;
        }
        modal.model = teacher;
        modal.render();
    });
    
    $('#form-total-teachers').on('submit', function (evt) {
        var that = $(this);
        $.ajax({
            url: that.attr('action'),
            type: 'post',
            dataType: 'json',
            data: utils.serializeObject(that)
        }).fail(function (xhr, err) {
            var messages;
            if (422 === xhr.status) {
                messages = JSON.parse(xhr.responseText).messages || {};
                utils.formMessages.render(that, messages);
            } else {
                alert('Προέκυψε κάποιο σφάλμα');
            }

        });
        evt.preventDefault();
    });
    
    (function () {
        var origA, origB;

        $('#tpe_survey-form-modal').on('change', 'select#el-already_using_tpe', function (evt) {
            if (!origA) {
                origA = $('.assets_in_use-container > .form-group > label').html();
            }
            if (!origB) {
                origB = $('.software_in_use-container > .form-group > label').html();
            }
            if ($(this).val() === 'ΟΧΙ') {
                $('.assets_in_use-container > .form-group > label').html('Υλικό που θα χρησιμοποιούσε');
                $('.software_in_use-container > .form-group > label').html('Λογισμικό που θα χρησιμοποιούσε');
            } else {
                $('.assets_in_use-container > .form-group > label').html(origA);
                $('.software_in_use-container > .form-group > label').html(origB);
            }
        });
    } ());
    
}(jQuery, _, window.EDULABS.utils));
