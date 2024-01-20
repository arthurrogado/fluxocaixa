import HttpClient from "/frontend/App.js";

class VisualizarCaixa {
    constructor() {
        this.httpClient = new HttpClient();
        this.init();
    }

    init() {
        this.obterDadosCaixa();
    }

    obterDadosCaixa() {
        this.httpClient.makeRequest('/api/caixas/visualizar', {id: this.httpClient.getParams().id})
        .then(response => {
            console.log(response)
            if(response.ok) {
                // this.preencherCampos(response.data)
            }
        })
    }

    preencherCampos(data) {
        document.querySelector('#nome').value = data.nome;
        document.querySelector('#descricao').value = data.descricao;
        document.querySelector('#valor').value = data.valor;
        document.querySelector('#data').value = data.data;
    }
}

new VisualizarCaixa();