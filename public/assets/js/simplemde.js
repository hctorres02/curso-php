window.addEventListener('DOMContentLoaded', function () {
    new SimpleMDE({
        element: document.querySelector('textarea'),
        spellChecker: this.env.app_locale == this.navigator.language,
        renderingConfig: {
            codeSyntaxHighlighting: true,
        },
    })
})
