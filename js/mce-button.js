(function() {
    tinymce.PluginManager.add('wp_snippet_mce_button', function(editor, url) {
        editor.addButton('wp_snippet_mce_button', {
            text: 'Insert Snippet',
            icon: false,
            onclick: function() {
                jQuery.post(ajaxurl, {action: 'wp_snippet_get_snippets'}, function(response) {
                    editor.windowManager.open({
                        title: 'Insert Code Snippet',
                        body: [{
                            type: 'listbox',
                            name: 'snippet_id',
                            label: 'Select a snippet',
                            values: response
                        }],
                        onsubmit: function(e) {
                            editor.insertContent('[code_snippet id="' + e.data.snippet_id + '"]');
                        }
                    });
                });
            }
        });
    });
})();