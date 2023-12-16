import HttpClient from "/frontend/App.js"
import SearchableSelect from "/frontend/components/SearchableSelect.js"
const httpClient = new HttpClient()

class CriarUsuario {

    constructor() {
        this.httmlClient = new HttpClient()
        this.preencher_escritorios()

        this.form = document.querySelector('#formCriarPessoa')
        this.form.addEventListener('submit', e => this.criar(e))

        this.search = new SearchableSelect('[name=cnpj_escritorio]')

    }

    preencher_escritorios() {
        httpClient.makeRequest('/api/escritorios/listar')
        .then(response => {
            if(response.ok) {
                response.escritorios.forEach(escritorio => {
                    let option = document.createElement('option')
                    option.value = escritorio.cnpj
                    option.text = escritorio.nome
                    document.querySelector('[name=cnpj_escritorio]').append(option)
                })
            }
        })
    }

    criar(e) {
        e.preventDefault();
        let formdata = new FormData(this.form);
        httpClient.makeRequest('/api/usuarios/criar', formdata)
        .then(response => {
            if(response.ok) {
                httpClient.navigateTo('/usuarios/listar');
            }
        })
    }

}

new CriarUsuario();