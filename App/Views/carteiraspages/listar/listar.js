import HttpClient from "/frontend/App.js";
import Table from "/frontend/components/Table.js";

class ListarCarteiras {
    constructor() {
        this.httpClient = new HttpClient();
        this.listarCarteiras();
        this.init();
    }

    init() {
        document.querySelector('#btnCriarCarteira').addEventListener('click', () => {
            httpClient.navigateTo('/carteiras/criar')
        })
    }

    listarCarteiras() {
        this.httpClient.makeRequest('/api/carteiras/obter_de_escritorio')
        .then(response => {
            if(!response.ok) return 
            
            document.querySelector('#loadingTabelaCarteiras').remove()
        
            let actions = [
                {
                    text: '<i class="fa fa-eye"></i> Visualizar',
                    action: (id) => {
                        httpClient.navigateTo('/carteiras/visualizar', {id: id})
                    }
                },
                {
                    text: '<i class="fa fa-trash"></i> Excluir',
                    action: async (id) => {
                        if(!confirm('Deseja excluir a carteira ('+ id +')?')) return
                        const response = await httpClient.makeRequest('/api/carteiras/excluir', {id: id});
                        if(response.ok) {
                            document.querySelector(`tr[data-id="${id}"]`).remove()
                        }
                    },
                    class: 'btn-danger'
                }
            ]
        
            new Table('#tabelaCarteiras', response.carteiras, ['id', 'nome'], ['ID', 'Nome'], actions)
        })
    }

    excluirCarteira(id) {
        this.httpClient.makeRequest('/api/carteiras/excluir', {id: id})
        .then(response => {
            if(response.ok) {
                document.querySelector(`tr[data-id="${id}"]`).remove()
                document.querySelector('#modalExcluirCarteira').classList.remove('active')
            }
        })
    }

    abrirModalExcluir(id) {
        document.querySelector('#modalExcluirCarteira').classList.add('active')
        document.querySelector('#btnExcluirCarteira').addEventListener('click', () => {
            this.excluirCarteira(id)
        })
    }

}

new ListarCarteiras()