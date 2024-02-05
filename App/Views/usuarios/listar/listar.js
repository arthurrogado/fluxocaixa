import HttpClient from "/frontend/App.js"
const httpClient = new HttpClient()
import Table from "/frontend/components/Table.js"

class ListarUsuarios {
    constructor() {
        this.httpClient = new HttpClient()
        this.listarUsuarios()
        this.init()
    }

    init() {
        document.querySelector('#btnCriarUsuario').addEventListener('click', () => {
            httpClient.navigateTo('/usuarios/criar')
        })
    }

    listarUsuarios() {
        httpClient.makeRequest('/api/usuarios/listar')
        .then(response => {
            document.querySelector('#loadingTabelaUsuarios').remove()
        
            let actions = [
                {
                    text: '<i class="fa fa-eye"></i> Visualizar',
                    action: (id) => {
                        httpClient.navigateTo('/usuarios/visualizar', {id: id})
                    }
                },
                {
                    text: '<i class="fa fa-trash"></i> Excluir',
                    action: async (id) => {
                        if(!confirm('Deseja excluir o usuário ('+ id +')?')) return
                        const response = await httpClient.makeRequest('/api/usuarios/excluir', {id: id});
                        if(response.ok) {
                            document.querySelector(`tr[data-id="${id}"]`).remove()
                        }
                    },
                    class: 'btn-danger'
                }
            ]
        
            new Table('#pessoas', response.usuarios, ['id', 'nome'], ['ID', 'Nome'], actions)
        })
    }

}


new ListarUsuarios()