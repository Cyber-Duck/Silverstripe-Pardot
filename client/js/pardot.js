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
    jQuery('body').on('click', '#Form_PardotContentForm input.action', function (e) {
        e.preventDefault();
        var shortcode = '[pardot_form';

        var form = jQuery('select#Form_PardotContentForm_PardotForm').val();
        var height = jQuery('input#Form_PardotContentForm_FormHeight').val();
        var width = jQuery('input#Form_PardotContentForm_FormWidth').val();
        var cssClass = jQuery('input#Form_PardotContentForm_FormCssClass').val();

        if(form) {
            shortcode += height ? ' height="' + height + '"' : '';
            shortcode += width ? ' width="' + width + '"' : '';
            shortcode += cssClass ? ' class="' + cssClass + '"' : '';
            shortcode += ' id="' + form + '"]';
            
            tinymce.execCommand('mceReplaceContent', false, shortcode);
            
            jQuery('.ss-ui-pardot').remove();
        }
    });

    jQuery('body').on('click', '#Form_PardotDynamicContentForm input.action', function (e) {
        e.preventDefault();
        var shortcode = '[pardot_dynamic_content';

        var content = jQuery('select#Form_PardotDynamicContentForm_DynamicContent').val();
        var height = jQuery('input#Form_PardotDynamicContentForm_DynamicContentHeight').val();
        var width = jQuery('input#Form_PardotDynamicContentForm_DynamicContentWidth').val();
        var cssClass = jQuery('input#Form_PardotDynamicContentForm_DynamicContentCssClass').val();

        if(content) {
            shortcode += height ? ' height="' + height + '"' : '';
            shortcode += width ? ' width="' + width + '"' : '';
            shortcode += cssClass ? ' class="' + cssClass + '"' : '';
            shortcode += ' id="' + content + '"]';
            
            tinymce.execCommand('mceReplaceContent', false, shortcode);
            
            jQuery('.ss-ui-pardot').remove();
        }
    });
})();