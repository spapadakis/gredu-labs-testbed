(function () {
    'use strict';

    window.EDULABS = window.EDULABS || {};
    window.EDULABS.utils = EDULABS.utils || {};

    window.EDULABS.utils.parseInt = function (str) {
        return parseInt(str, 10);
    };
    
    window.EDULABS.utils.serializeObject = function (form) {
        return _.reduce(form.serializeArray(), function (hash, pair) {
            if (!!hash[pair.name]) {
                hash[pair.name] = [hash[pair.name], pair.value];
            } else {
                hash[pair.name] = pair.value;
            }
            return hash;
        }, {});
    };

    window.EDULABS.utils.formMessages = {
        render: function (form, messages) {
            var renderMessages = function (element, messages) {
                var key,
                    ul;
                element.parents('.form-group').addClass('has-error');
                ul = $('<ul class="help-block has-error">');
                for (key in messages) {
                    if (messages.hasOwnProperty(key)) {
                        ul.append($('<li>').text(messages[key]));
                    }
                }
                element.after(ul);
            };
            var prop;
            this.clear(form);
            for (prop in messages) {
                if (messages.hasOwnProperty(prop)) {
                    renderMessages(form.find('[name="' + prop + '"]'), messages[prop]);
                }
            }
        },
        clear: function (form) {
            form.find('.form-group').removeClass('has-error');
            form.find('.help-block.has-error').remove();
        }
    }
            
            
    $(document).on('change', '.btn-file :file', function () {
        var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $('.btn-file :file').on('fileselect', function (event, numFiles, label) {
        var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

        if (input.length) {
            input.val(log);
        }
    });

    $('.btn-file').parents('.input-group').on('click', '.btn-file-remove', function (evt) {
        $(this).parents('.input-group').find(':file').val('');
        $(this).parents('.input-group').find(':text').val('');
    });

}());