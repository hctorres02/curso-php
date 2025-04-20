window.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('anexos')
    const anexosPendentes = container.querySelector('#anexos-pendentes')
    const anexosSalvos = container.querySelector('#anexos-salvos')
    const btnSelecionar = document.querySelector('#btn-selecionar')
    const input = document.querySelector('input[type="file"]')

    btnSelecionar.addEventListener('click', function () {
        input.click()
    })

    input.addEventListener('change', function () {
        const target = anexosPendentes.querySelector('div')
        const template = anexosPendentes.querySelector('template')
        const p = template.content.cloneNode(true)
        const checkbox = p.querySelector('input[type="checkbox"]')
        const ins = p.querySelector('ins')

        target.innerHTML = ''
        ins.textContent = Array.from(this.files).map(file => file.name).join(', ')

        checkbox.addEventListener('change', function () {
            this.checked = true

            if (confirm('Deseja remover os arquivos selecionados?')) {
                this.closest('p').remove()
                input.value = ''
            }
        })

        target.appendChild(p)
    })

    anexosSalvos.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const label = this.parentElement
            const oldTag = this.checked ? 'span' : 'del'
            const newTag = this.checked ? 'del' : 'span'
            const oldElement = label.querySelector(oldTag)
            const newElement = Object.assign(document.createElement(newTag), {
                textContent: oldElement.textContent,
                title: this.checked ? 'Este anexo será removido' : ''
            })

            oldElement.replaceWith(newElement)
        })
    })
})