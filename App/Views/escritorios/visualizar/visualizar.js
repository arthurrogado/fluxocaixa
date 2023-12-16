import HttpClient from "/frontend/App.js";
const httpClient = new HttpClient();

class ListarEscritorio {

    constructor() {
        this.httpClient = new HttpClient();
        this.preencher_detalhes_escritorio();

        // Eventos
        document.querySelector('#btn_editar').addEventListener('click', (e) => {
            e.preventDefault();
            this.editar();
        })

    }

    preencher_detalhes_escritorio() {
        // Preencher detalhes do escritorio baseado na URL (?id=123)
        this.httpClient.makeRequest('/api/escritorios/visualizar', { id: this.httpClient.getParams().id })
            .then(response => {
                if (response.ok) {
                    document.querySelector('.loading').remove();
                    this.httpClient.fillAllInputs(response.escritorio);
                }
            })
    }

    editar() {
        // Salvar informações do formulário para restaurar caso o escritorio cancele a edição
        this.dados_previos = new FormData(document.querySelector('#formVisualizarEscritorio'))

        // Tornar todos os inputs editáveis
        this.httpClient.activateAllInputs()

        // Esconder botão editar e mostrar inserir os botões de salvar e cancelar
        document.querySelector('#btn_editar').classList.add('hidden')

        this.btn_salvar = document.createElement('button')
        this.btn_salvar.type = 'button'
        this.btn_salvar.classList.add('btn', 'btn-primary')
        this.btn_salvar.innerHTML = '<i class="fa fa-save"></i> Salvar'
        this.btn_salvar.addEventListener('click', _ => {
            this.salvar();
        })

        this.btn_cancelar = document.createElement('button')
        this.btn_cancelar.type = 'button'
        this.btn_cancelar.classList.add('btn', 'btn-danger')
        this.btn_cancelar.innerHTML = '<i class="fa fa-times"></i> Cancelar'
        this.btn_cancelar.addEventListener('click', _ => {
            this.cancelar();
        })

        // Inserir os botões ao lado do botão editar
        document.querySelector('#btn_editar').insertAdjacentElement('afterend', this.btn_salvar)
        document.querySelector('#btn_editar').insertAdjacentElement('afterend', this.btn_cancelar)

    }

    salvar() {
        // Salvar usuário
        let formdata = new FormData(document.querySelector('#formVisualizarEscritorio'))
        formdata.append('id', this.httpClient.getParams().id)
        httpClient.makeRequest('/api/escritorios/editar', formdata)
            .then(response => {
                if (response.ok) {
                    // atualizar a página para mostrar os dados atualizados
                    httpClient.reloadPage()
                }
            })
    }

    cancelar() {
        // Mostrar botão editar e esconder botões salvar e cancelar, e tornar os inputs readonly novamente
        document.querySelector('#btn_editar').classList.remove('hidden')
        this.btn_salvar.remove()
        this.btn_cancelar.remove()
        httpClient.readOnlyAllInputs()
        // Preencher os inputs com os dados do usuário novamente
        document.querySelectorAll('#formVisualizarEscritorio input').forEach(input => {
            input.value = this.dados_previos.get(input.name)
        })
    }

}

new ListarEscritorio();