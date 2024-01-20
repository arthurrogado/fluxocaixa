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
        // let loading_caixas = this.httpClient.loading('#caixas');
        // console.log(loading_caixas);
    }

    abrirModalExcluirCaixa(id) {
        let modal = new Modal('body', 'Excluir Caixa', /*html*/`
            <p>Deseja realmente excluir este caixa?</p>
        `, [
            {
                text: "Cancelar",
                class: "btn-primary",
            },
            {
                class: 'btn-danger',
                text: 'Excluir caixa',
                action: () => {
                    this.exluirCaixa(id);
                    modal.close();
                }
            }
        ]);
    }

    exluirCaixa(id) {
        this.httpClient.makeRequest('/api/caixas/excluir', {id: id})
        .then(response => {
            if(response.ok) {
                this.tabela.removeRowById(id);
            }
        })
    }
    
    obterCaixas() {
        this.httpClient.makeRequest('/api/caixas/listar')
        .then(response => {
            console.log(response);
            if(response.ok) {
                document.querySelector('#loadingTabelaCaixas')?.remove();
                document.querySelector('#caixas').innerHTML = '';
                this.tabela = new Table('#caixas', response.caixas,['id', 'nome'], ['ID', 'Nome'], [
                    {
                        text: '<i class="fa fa-eye"></i> Visualizar',
                        action: (id) => {
                            this.httpClient.navigateTo('/caixas/visualizar', {id: id})
                        }
                    },
                    {
                        text: '<i class="fa fa-trash"></i> Excluir',
                        action: (id) => this.abrirModalExcluirCaixa(id),
                        class: 'btn-danger'
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
                            this.obterCaixas();
                        }
                    })
                }
            }
        ]);
    }

}

new ListarCaixas();