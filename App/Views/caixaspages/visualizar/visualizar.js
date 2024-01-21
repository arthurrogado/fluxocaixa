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
        document.querySelector('#btn_entrada').addEventListener('click', () => this.abrirModalOperacao('entrada'));
        document.querySelector('#btn_saida').addEventListener('click', () => this.abrirModalOperacao('saida'));
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
            operacao.valor = this.formatarValor(operacao.valor);
            operacao.tipo = operacao.tipo_entrada == 1 ? `
                <i class="fa fa-arrow-up w3-text-blue"></i>
            ` : `
                <i class="fa fa-arrow-down w3-text-red"></i>
            `;
            let row = document.createElement('tr');
            row.innerHTML = `
                <td>${operacao.tipo}</td>
                <td style="width:100%;">${operacao.nome}</td>
                <td style="text-wrap:nowrap;">R$ ${operacao.valor}</td>
            `
            tableBody.appendChild(row);
        });
        table.appendChild(tableBody);
        document.querySelector('#operacoes').appendChild(table);
    }

    abrirModalOperacao(entrada='entrada') {
        let hoje = new Date();
        // Formatar para o formato do input
        hoje = hoje.toISOString().split('T')[0];

        let conteudoModal = /*html*/`
            <form id="form_entrada" class="w3-container">
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
                    let formdata = new FormData(document.querySelector('#form_entrada'));
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

}

new VisualizarCaixa();