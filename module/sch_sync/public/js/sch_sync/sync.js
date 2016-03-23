/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

(function ($, document) {
    $('#inventory_sync').on('click', function (evt) {
        var that = $(this),
            url = that.data('href');
        that.prop('disabled', true);
        that.find('i').addClass('fa-spinner fa-spin');
        $.ajax({
            url: url,
            type: 'get'
        }).done(function (response) {
            document.location.reload(true);
        }).fail(function (xhr, err) {
            alert('Προέκυψε κάποιο σφάλμα κατά την εισαγωγή. Παρακαλώ δοκιμάστε ξανά.');
            that.prop('disabled', false);
            that.find('i').removeClass('fa-spinner fa-spin');
        });
    });
} (window.jQuery, document));