(function() {
    tinymce.PluginManager.add('wp_snippet_mce_button', function(editor, url) {
        editor.addButton('wp_snippet_mce_button', {
            text: 'Insert Snippet',
            icon: 'code',
            onclick: function() {
                wp.ajax.post('wp_snippet_get_snippets', {
                    security: wp_snippet_manager.nonce
                }).then(function(response) {
                    editor.windowManager.open({
                        title: 'Insert Code Snippet',
                        body: [{
                            type: 'listbox',
                            name: 'snippet_id',
                            label: 'Select a snippet',
                            values: response.map(function(snippet) {
                                return { text: snippet.text, value: snippet.value };
                            })
                        }],
                        onsubmit: function(e) {
                            editor.insertContent('[code_snippet id="' + e.data.snippet_id + '"]');
                        }
                    });
                }).catch(function(error) {
                    console.error('Error fetching snippets:', error);
                    editor.notificationManager.open({
                        text: 'Failed to load snippets. Please try again.',
                        type: 'error'
                    });
                });
            }
        });
    });
})();