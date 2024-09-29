(function() {
    tinymce.PluginManager.add('wp_snippet_button', function(editor, url) {
        editor.addButton('wp_snippet_button', {
            text: 'Insert Snippet',
            icon: 'code',
            onclick: function() {
                jQuery.post(ajaxurl, {
                    action: 'wp_snippet_get_snippets',
                    security: wp_snippet_manager.nonce
                }, function(response) {
                    if (response.success) {
                        editor.windowManager.open({
                            title: 'Insert Code Snippet',
                            body: [{
                                type: 'listbox',
                                name: 'snippet_id',
                                label: 'Select a snippet',
                                values: response.data
                            }],
                            onsubmit: function(e) {
                                editor.insertContent('[code_snippet id="' + e.data.snippet_id + '"]');
                            }
                        });
                    } else {
                        console.error('Failed to fetch snippets');
                    }
                });
            }
        });
    });
})();