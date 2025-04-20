window.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('anexos')
    const anexosPendentes = container.querySelector('#anexos-pendentes')
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
})