import HttpClient from "/frontend/App.js";
import Modal from "/frontend/components/Modal.js";
import Table from '/frontend/components/Table.js'

class ListarCaixas {
    constructor() {
        this.httpClient = new HttpClient();
        this.init();
    }
    
    init() {
        this.obterCaixas();
        document.querySelector('#btnAbrirCaixa').addEventListener('click', () => {
            this.abrirModalCriarCaixa();
        })
    }
    
    obterCaixas() {
        this.httpClient.makeRequest('/api/caixas/listar')
        .then(response => {
            console.log(response);
            if(response.ok) {
                document.querySelector('#loadingTabelaCaixas').remove();
                let tabela = new Table('#caixas', response.caixas,['id', 'nome'], ['ID', 'Nome'], [
                    {
                        text: 'Visualizar',
                        action: (id) => {
                            httpClient.navigateTo('/caixas/visualizar', {id: id})
                        }
                    }
                ]);
            }
        })
    }

    abrirModalCriarCaixa() {
        let modal = new Modal('body', 'Criar Caixa', /*html*/`
            <form id="formCriarCaixa">
                <div class="input-field">
                    <input type="text" name="nome" required>
                    <label>Nome do caixa</label>
                </div>
                <div class="input-field">
                    <textarea name="observacoes" id="observacoes" cols="30" rows="5" required></textarea>
                    <label>Observações</label>
                </div>
            </form>
        `, [
                        
            {
                text: "Cancelar",
                class: "btn-danger",
            },
            {
                class: 'btn-primary',
                text: 'Criar caixa',
                action: () => {
                    let formdata = new FormData( document.querySelector('#formCriarCaixa') )
                    this.httpClient.makeRequest('/api/caixas/criar', formdata)
                    .then(response => {
                        if(response.ok) {
                            modal.close();
                        }
                    })
                }
            }
        ]);
    }

}

new ListarCaixas();