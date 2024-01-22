import HttpClient from "/frontend/App.js";
import Modal from "/frontend/components/Modal.js";
import Table from "/frontend/components/Table.js";

class VisualizarCaixa {
    constructor() {
        this.httpClient = new HttpClient();
        this.init();
    }

    init() {
        this.obterDadosCaixa();
        this.obterOperacoes();
        document.querySelector('#btn_entrada').addEventListener('click', () => this.abrirModalCriarOperacao('entrada'));
        document.querySelector('#btn_saida').addEventListener('click', () => this.abrirModalCriarOperacao('saida'));
    }

    obterDadosCaixa() {
        this.httpClient.makeRequest('/api/caixas/visualizar', {id: this.httpClient.getParams().id})
        .then(response => {
            console.log(response)
            if(response.ok) {
                this.preencherCampos(response.caixa)
            }
        })
    }

    obterOperacoes() {
        this.httpClient.makeRequest('/api/operacoes/caixa', {id_caixa: this.httpClient.getParams().id})
        .then(response => {
            console.log(response)
            if(response.ok) {
                this.preencherOperacoes(response.operacoes)
                document.querySelector('#soma_entradas').innerHTML = this.formatarValor(response.soma_entradas);
                document.querySelector('#soma_saidas').innerHTML = this.formatarValor(response.soma_saidas);
            }
        })
    }

    formatarData(data) {
        let dataFormatada = new Date(data);
        return dataFormatada.toLocaleDateString();
    }

    formatarValor(valor) {
        if(!valor) return '0,00';
        // sem o cifrão
        return valor.toLocaleString('pt-br', {minimumFractionDigits: 2});
    }

    preencherCampos(caixa) {
        let dados = {
            "#nome_caixa": caixa.nome,
            "#observacoes": caixa.observacoes,
            "#data_abertura": this.formatarData(caixa.data_abertura),
            "#data_fechamento": caixa.data_fechamento || "Não fechado",
            "#quem_abriu": caixa.nome_usuario_abertura,
            "#quem_fechou": caixa.nome_usuario_fechamento || "Não fechado",
        }
        for(let campo in dados) {
            document.querySelector(campo).innerHTML = dados[campo];
        }
    }

    preencherOperacoes(operacoes) {
        document.querySelector('#operacoes').innerHTML = '';
        let table = document.createElement('table');
        table.classList.add('w3-table-all', 'w3-hoverable');
        let tableBody = document.createElement('tbody');
        operacoes.forEach(operacao => {
            operacao.tipo = operacao.tipo_entrada == 1 ? `
                <i class="fa fa-arrow-up w3-text-blue"></i>
            ` : `
                <i class="fa fa-arrow-down w3-text-red"></i>
            `;
            let row = document.createElement('tr');
            row.innerHTML = `
                <td>${operacao.tipo}</td>
                <td style="width:100%;">${operacao.nome}</td>
                <td style="text-wrap:nowrap;">R$ ${this.formatarValor(operacao.valor)}</td>
            `
            row.addEventListener('click', () => this.abrirDetalhesOperacao(operacao))
            tableBody.appendChild(row);
        });
        table.appendChild(tableBody);
        document.querySelector('#operacoes').appendChild(table);
    }

    abrirModalCriarOperacao(entrada='entrada') {
        let hoje = new Date();
        // Formatar para o formato do input
        hoje = hoje.toISOString().split('T')[0];

        let conteudoModal = /*html*/`
            <form id="form_operacao" class="w3-container">
                <div class="input-field">
                    <input type="text" name="nome" required>
                    <label>Nome:</label>
                </div>
                <div class="input-field">
                    <input type="number" name="valor" required>
                    <label>Valor:</label>
                </div>
                <div class="input-field">
                    <textarea name="observacoes" required></textarea>
                    <label>Observações:</label>
                </div>
                <div class="input-field ativado">
                    <input type="date" name="data" value="${hoje}" required>
                    <label>Data:</label>
                </div>
                <div class="input-field">
                    <select name="id_forma_pagamento">
                        <option value="1">Dinheiro</option>
                    </select>
                    <label>Forma de pagamento:</label>
                </div>
            </form>
        `;

        let botoesModal = [
            {
                text: 'Salvar',
                class: 'bg-primary',
                action: () => {
                    let formdata = new FormData(document.querySelector('#form_operacao'));
                    formdata.append('id_caixa', this.httpClient.getParams().id);
                    formdata.append('tipo_entrada', entrada=='entrada' ? 1 : 0);

                    this.httpClient.makeRequest('/api/operacoes/criar', formdata)
                    .then(response => {
                        if(response.ok) {
                            this.obterOperacoes();
                            modalEntrada.close();
                        }
                    })
                }
            }
        ]

        let modalEntrada = new Modal(
            'body',
            entrada=='entrada' ? /*html*/`
                Lançar entrada <i class="fa fa-arrow-up w3-text-blue"></i>
            `: /*html*/`
                Lançar saída <i class="fa fa-arrow-down w3-text-red"></i>
            `,
            conteudoModal,
            botoesModal
        )
    }

    async excluirOperacao(id) {
        return this.httpClient.makeRequest('/api/operacoes/excluir', {id})
        .then(response => {
            if(response.ok) {
                this.obterOperacoes();
                return true;
            }
            return false;
        })
    }
    confirmarExcluirOperacao(id) {
        this.modalConfirmar = new Modal(
            'body',
            /*html*/`
                <i class="fa fa-trash" style="color:var(--danger-color)"></i> Confirmar exclusão
            `,
            /*html*/`
                <p>
                    Tem certeza que deseja excluir esta operação?
                </p>
            `,
            [
                {
                    text: "<i class='fa fa-times'></i> Cancelar",
                    class: "bg-secondary",
                },
                {
                    text: "<i class='fa fa-trash'></i> Confirmar exclusão",
                    class: "bg-danger",
                    action: async () => {
                        this.excluirOperacao(id);
                        this.modalConfirmar.close();
                        this.modalDetalhesOperacao.close();
                    }
                }
            ]
        )
    }

    // DETALHES
    abrirDetalhesOperacao(operacao) {
        let conteudoModal = /*html*/`
            <div class="w3-container">
                <p class="w3-large">
                    <b>Nome:</b> ${operacao.nome}
                </p>
                <p>
                    <b>Valor:</b> R$ ${this.formatarValor(operacao.valor)}
                </p>
                <p>
                    <b>Observações:</b> ${operacao.observacoes}
                </p>
                <p>
                    <b>Data:</b> ${this.formatarData(operacao.data)}
                </p>
                <p>
                    <b>Forma de pagamento:</b> Dinheiro
                </p>
            </div>
        `;

        let botoesModal = [
            {
                text: "<i class='fa fa-trash'></i> Excluir",
                class: "bg-danger",
                action: () => {
                    // if(this.confirmarExcluirOperacao(operacao.id)) {
                    //     modalDetalhesOperacao.close();
                    // }
                    this.confirmarExcluirOperacao(operacao.id);
                }
            },
            {
                text: "<i class='fa fa-pencil'></i> Editar",
                class: "bg-primary",
                action: () => {
                    this.abrirEdicaoOperacao(operacao);
                }
            }
        ];

        this.modalDetalhesOperacao = new Modal(
            'body',
            /*html*/`
                <i class="fa fa-list" style="color:var(--primary-color)"></i> Detalhes
            `,
            conteudoModal,
            botoesModal
        )
    }

    abrirEdicaoOperacao(operacao) {
        let conteudoModal = /*html*/`
            <form id="form_operacao" class="w3-container">
                <div class="input-field">
                    <input type="text" name="nome" value='${operacao.nome}' required>
                    <label>Nome:</label>
                </div>
                <div class="input-field">
                    <input type="number" name="valor" value='${operacao.valor}' required>
                    <label>Valor:</label>
                </div>
                <div class="input-field">
                    <textarea name="observacoes" required>${operacao.observacoes}</textarea>
                    <label>Observações:</label>
                </div>
                <div class="input-field ativado">
                    <input type="date" name="data" value="${operacao.data}" required>
                    <label>Data:</label>
                </div>
                <div class="input-field">
                    <select name="id_forma_pagamento" required>
                        <option value="1">Dinheiro</option>
                    </select>
                    <label>Forma de pagamento:</label>
                </div>
            </form>
            <div class="w3-small w3-margin-left">
                Data de criação:
                ${operacao.data_criacao}
            </div>
        `;

        let botoesModal = [
            {
                text: "<i class='fa fa-save'></i> Salvar",
                class: "w3-teal",
                action: () => {
                    let formdata = new FormData(document.querySelector('#form_operacao'));
                    formdata.append('id', operacao.id);

                    this.httpClient.makeRequest('/api/operacoes/editar', formdata)
                    .then(response => {
                        if(response.ok) {
                            this.obterOperacoes();
                            this.modalEdicaoOperacao.close();
                            this.modalDetalhesOperacao.close();
                        }
                    })
                }
            }
        ]

        this.modalEdicaoOperacao = new Modal(
            'body',
            /*html*/`
                <i class="fa fa-list" style="color:var(--primary-color)"></i> Editar
            `,
            conteudoModal,
            botoesModal
        )
    }

}

new VisualizarCaixa();