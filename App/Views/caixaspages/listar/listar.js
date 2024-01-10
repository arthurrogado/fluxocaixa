import HttpClient from "/frontend/App.js";
import Table from '/frontend/components/Table.js'

class ListarCaixas {
    constructor() {
        this.httpClient = new HttpClient();
        this.init();
    }
    
    init() {
        this.obterCaixas();
    }
    
    obterCaixas() {
        this.httpClient.makeRequest('/api/caixas/listar')
        .then(response => {
            if(response.ok) {
                let tabela = new Table('#caixas', response.caixas,['id', 'nome'], ['ID', 'Nome']);

            }
        })
    }

}

new ListarCaixas();