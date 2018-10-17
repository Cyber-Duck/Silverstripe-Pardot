(function () {
    tinymce.create('tinymce.plugins.pardot', {
        init: function (editor, url) {
            editor.addButton('pardot', {
                text: 'Pardot',
                tooltip: 'Pardot Forms',
                image: '/resources/vendor/cyber-duck/silverstripe-pardot/img/pardot.svg',
                classes: 'pardot-trigger',
                onclick: function () { }
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
})();