import HttpClient from "/frontend/App.js";
import Table from "/frontend/components/Table.js";
import Modal from "/frontend/components/Modal.js";
import Info from "/frontend/components/InfoBox.js";

class ListarEscritorios {
    constructor() {
        this.httpClient = new HttpClient();
        this.init();
    }

    init() {
        this.obterEscritorios();

        document.querySelector("#btnNovoEscritorio").addEventListener("click", () => {
            this.criarEscritorio();
        });

    }

    criarEscritorio() {
        const modalNovoEscritorio = new Modal('body', 'Novo escritório', /*html*/`
            <form id="formNovoEscritorio">
                <div class="input-field">
                    <input type="text" name="nome" required>
                    <label>Nome do escritório*</label>
                </div>
                <div class="input-field">
                    <input type="text" name="cnpj" max='14' required>
                    <label>CNPJ*</label>
                </div>
                <div class="input-field">
                    <textarea name="observacoes" cols="30" rows="5" required></textarea>
                    <label>Observações</label>
                </div>
            </form>
            `, 
            [
                {
                    text: "Cancelar",
                    class: "btn-danger",
                },
                {
                    text: "Salvar",
                    action: () => {
                        if(!this.httpClient.verifyObrigatoryFields("#formNovoEscritorio")) return;
                        const form = document.querySelector("#formNovoEscritorio");
                        const formdata = new FormData(form);
                        this.httpClient.makeRequest("/api/escritorios/criar", formdata)
                            .then((response) => {
                                if (response.ok) {
                                    modalNovoEscritorio.close();
                                    this.obterEscritorios();
                                }
                            });
                    }
                }
            ]
        );
    }

    obterEscritorios() {
        this.httpClient.makeRequest("/api/escritorios/listar").then((response) => {
            if (response.ok) {
                this.renderEscritorios(response.escritorios);
            }
        });
    }

    renderEscritorios(escritorios) {
        document.querySelector("#containerEscritorios").innerHTML = "";
        this.table = new Table(
            "#containerEscritorios",
            escritorios,
            ["id", "nome"], 
            ["ID", "Nome"],
            [
                {
                    text: '<i class="fa fa-eye"></i>',
                    action: (id) => {
                        this.httpClient.navigateTo('/escritorio/visualizar', {id: id})
                    }
                },
                {
                    text: '<i class="fa fa-trash"></i>',
                    action: (id) => {
                        this.confirmarExclusao(id)
                    },
                    class: "btn-danger"
                }
            ]
        )
    }

    confirmarExclusao(id) {
        this.modalConfirmarExclusao = new Modal('body', 'Excluir escritório?', /*html*/`
            <p>Deseja realmente excluir este escritório?</p>
        `, [
            {
                text: "Não"
            },
            {
                text: "Sim",
                action: () => {
                    this.excluirEscritorio(id);
                },
                class: "btn-danger"
            }
        ])
    }

    // Anotações: todo registro tem que ter um campo ID em PK e Autoincrement.

    excluirEscritorio(id) {
        const modalExcluir = new Modal('body', 'Excluir escritório?', /*html*/`
            <p>Deseja REALMENTE excluir este escritório????</p>
        `, [
            {
                text: "Sim",
                action: () => {
                    this.httpClient.makeRequest("/api/escritorios/excluir", {id: id})
                        .then(response => {
                            if (response.ok) {
                                modalExcluir.close();
                                this.modalConfirmarExclusao.close();
                                this.obterEscritorios();
                            }
                        })
                },
                class: "btn-danger"
            },
            {
                text: "Não"
            }
        ])
    }

}

new ListarEscritorios();