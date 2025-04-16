// Aguarda o carregamento completo do DOM antes de executar o script
window.addEventListener('DOMContentLoaded', function () {
    // Obtém o elemento do DOM onde o calendário será renderizado
    const container = this.document.getElementById('calendar-container')

    // Lê os eventos do atributo `data-events` do container e os converte de JSON para objeto
    const events = JSON.parse(container.dataset.events)

    // Mapeia as datas de início dos eventos para objetos Date
    const dates = events.map(event => new Date(event.start))

    // Define a menor data entre os eventos (ou a data atual, caso o array esteja vazio)
    const minDate = new Date(Math.min(...dates, new Date))

    // Define a maior data entre os eventos
    const maxDate = new Date(Math.max(...dates))

    // Cria uma nova instância do calendário com as configurações desejadas
    const calendar = new FullCalendar.Calendar(container, {
        // Passa os eventos para o calendário
        events,

        // Define a visualização inicial como um calendário mensal em grade
        initialView: 'dayGridMonth',

        // Define o idioma do calendário
        locale: this.env.app_locale,

        // Define o fuso horário do calendário
        timeZone: this.env.app_timezone,

        // Oculta o fuso-horário (timezone) nos eventos exibidos
        displayEventTime: false,

        // Define o intervalo de datas válidas para navegação no calendário
        validRange: {
            start: minDate,
            end: maxDate
        },

        // Define o comportamento ao clicar em um evento
        async eventClick({ event, jsEvent }) {
            // Impede o comportamento padrão do clique
            jsEvent.preventDefault()

            // Obtém o diálogo que será usado para mostrar os detalhes do evento
            const dialog = this.el.querySelector('dialog')
            const dialogContent = dialog.querySelector('article')

            // URL base da página atual
            const currentUri = location.origin + location.pathname

            // Indica que o conteúdo do diálogo está sendo carregado
            dialogContent.setAttribute('aria-busy', true)

            // Limpa o conteúdo atual do diálogo
            dialogContent.innerHTML = ''

            // Exibe o diálogo modal
            dialog.showModal()

            try {
                // Faz uma requisição para obter o conteúdo detalhado do evento
                const response = await fetch(event.url)

                // Verifica se a resposta foi bem-sucedida
                if (!response.ok) {
                    throw new Error('Erro ao carregar agendamento')
                }

                // Lê o HTML da resposta
                const html = await response.text()

                // Cria um parser para interpretar o HTML
                const parser = new DOMParser
                const doc = parser.parseFromString(html, 'text/html')

                // Insere o conteúdo do <main> do HTML carregado no diálogo
                dialogContent.innerHTML = doc.querySelector('main').innerHTML

                // Adiciona comportamento ao botão de voltar/fechar dentro do conteúdo carregado
                dialogContent.querySelector(`a[href="${currentUri}"]`).addEventListener('click', function (e) {
                    e.preventDefault()
                    this.closest('dialog').close()
                })
            } catch (err) {
                // Exibe uma mensagem de erro caso algo dê errado
                dialogContent.innerHTML = `<p>${err.message}</p>`
                console.error(err)
            }

            // Indica que o carregamento terminou
            dialogContent.setAttribute('aria-busy', false)
        }
    })

    // Move o calendário para a menor data (início dos eventos)
    calendar.gotoDate(minDate)

    // Renderiza o calendário na tela
    calendar.render()
})
