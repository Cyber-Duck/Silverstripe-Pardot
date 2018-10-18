(function () {
    tinymce.create('tinymce.plugins.pardot', {
        init: function (editor, url) {
            editor.addButton('pardot', {
                text: 'Pardot',
                tooltip: 'Pardot Forms',
                image: '/resources/vendor/cyber-duck/silverstripe-pardot/client/img/pardot.svg',
                classes: 'pardot-trigger',
                onclick: function () {
                    if(jQuery('.ss-ui-pardot').length) {
                        jQuery('.ss-ui-pardot').remove()
                    } else {
                        jQuery('body').append('<div class="ss-ui-pardot panel panel--padded panel--scrollable cms-content-fields cms-content-loading-spinner"></div>');
                        jQuery.ajax({
                            url: '/pardot/PardotContentFormHTML',
                            complete: function () {
                                jQuery('.ss-ui-pardot').removeClass('cms-content-loading-spinner');
                            },
                            success: function (html) {
                                jQuery('.ss-ui-pardot').html(html);
                            }
                        });
                    }
                }
            });
        },
        getInfo: function () {
            return {
                longname: 'SilverStripe Pardot',
                author: 'Andrew Mc Cormack',
                authorurl: 'https://ddmseo.com',
                infourl: 'https://github.com/cyber-duck/silverstripe-pardot',
                version: '1.0.0'
            };
        }
    });
    tinymce.PluginManager.add('pardot', tinymce.plugins.pardot);

    jQuery('body').on('submit', 'form.ss-ui-pardot-form', function (e) {
        e.preventDefault();
        var form = jQuery(this);
        jQuery.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            type: form.attr('method'),
            complete: function () {

            },
            success: function (response) {
                form.replaceWith(response);
            }
        });
    });
})();