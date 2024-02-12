import HttpClient from "/frontend/App.js";
import infoBox from "/frontend/components/InfoBox.js";
import Modal from "/frontend/components/Modal.js";

class VisualizarCaixa {
    constructor() {
        this.httpClient = new HttpClient();
        this.init();
    }

    async init() {
        this.obterDadosCaixa();
        this.obterOperacoes();
        document.querySelector('#btn_entrada').addEventListener('click', () => this.abrirModalCriarOperacao('entrada'));
        document.querySelector('#btn_saida').addEventListener('click', () => this.abrirModalCriarOperacao('saida'));
        document.querySelector("#btn_editar_caixa").addEventListener('click', () => this.abrirEdicaoCaixa());
        this.carteiras = await this.obterCarteiras();
    }

    obterDadosCaixa() {
        this.httpClient.makeRequest('/api/caixas/visualizar', {id: this.httpClient.getParams().id})
        .then(response => {
            if(response.ok) {
                this.preencherCampos(response.caixa)
            }
        })
    }

    obterOperacoes() {
        this.httpClient.makeRequest('/api/operacoes/caixa', {id_caixa: this.httpClient.getParams().id})
        .then(response => {
            if(response.ok) {
                let operacoes = response.operacoes;
                let soma_dinheiro = 0 // 1
                let soma_pix = 0 // 2
                let soma_credito = 0 // 3
                let soma_debito = 0 // 4
                let soma_outros = 0
                operacoes.forEach(operacao => {
                    let indice = operacao.tipo == 'e' ? 1 : -1; // Somar ou subtrair do total
                    if(operacao.id_carteira == 1) {
                        soma_dinheiro += operacao.valor * indice;
                    } else if(operacao.id_carteira == 2) {
                        soma_pix += operacao.valor * indice;
                    } else if(operacao.id_carteira == 3) {
                        soma_credito += operacao.valor * indice;
                    } else if(operacao.id_carteira == 4) {
                        soma_debito += operacao.valor * indice;
                    } else {
                        soma_outros += operacao.valor * indice;
                    }
                });
                let substituir_detalhamento = {
                    "#soma_dinheiro": soma_dinheiro,
                    "#soma_pix": soma_pix,
                    "#soma_credito": soma_credito,
                    "#soma_debito": soma_debito,
                    "#soma_outros": soma_outros,
                    "#soma_entradas": response.soma_entradas,
                    "#soma_saidas": response.soma_saidas,
                }
                for(let campo in substituir_detalhamento) {
                    document.querySelector(campo).innerHTML = this.formatarValor(substituir_detalhamento[campo]);
                }

                this.preencherTabelaOperacoes(response.operacoes)
            }
        })
    }

    preencherTabelaOperacoes(operacoes) {
        // Criar tabela
        document.querySelector('#operacoes').innerHTML = '';
        let table = document.createElement('table');
        table.classList.add('w3-table-all', 'w3-hoverable');
        let tableBody = document.createElement('tbody');
        operacoes.forEach(operacao => {
            operacao.icone = operacao.tipo == 'e' ? `
                <i class="fa fa-arrow-up w3-text-blue"></i>
            ` : `
                <i class="fa fa-arrow-down w3-text-red"></i>
            `;
            let row = document.createElement('tr');
            row.innerHTML = `
                <td>${operacao.icone}</td>
                <td style="width:100%;">${operacao.nome}</td>
                <td style="text-wrap:nowrap;">R$ ${this.formatarValor(operacao.valor)}</td>
            `
            row.addEventListener('click', () => this.abrirDetalhesOperacao(operacao))
            tableBody.appendChild(row);
        });
        table.appendChild(tableBody);
        document.querySelector('#operacoes').appendChild(table);
        // Preencher detalhamento
        console.log(operacoes)
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

    async obterCarteiras() {
        // Pegar as carteiras disponíveis (do escritório e do sistema)
        let carteiras = [];
        let response = await this.httpClient.makeRequest('/api/carteiras/obter_de_caixa', {id_caixa: this.httpClient.getParams().id})
        if(response.ok) {
            carteiras = response.carteiras;
        } else {
            new infoBox('Erro ao obter carteiras', 'danger', 3)
        }
        return carteiras;
    }

    async abrirModalCriarOperacao(entrada='entrada') {
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
                    <select name="id_carteira">
                        <option value=""></option>
                        ${this.carteiras.map(function(carteira) {
                            return `<option value="${carteira.id}">${carteira.nome}</option>`
                        })}
                    </select>
                    <label>Carteira:</label>
                </div>

                <div class="customradio">
                    <input type="radio" name="tipo" value="e" id="entrada" ${entrada=='entrada' ? 'checked' : ''}>
                    <label for="entrada"> <i class="fa fa-arrow-up w3-text-blue"></i> Entrada</label>
                    <input type="radio" name="tipo" value="s" id="saida" ${entrada=='saida' ? 'checked' : ''}>
                    <label for="saida"> <i class="fa fa-arrow-down w3-text-red"></i> Saída</label>
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
                    formdata.append('tipo', entrada=='entrada' ? 'e' : 's');

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
        let nome_carteira = this.carteiras.find(carteira => carteira.id == operacao.id_carteira).nome;
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
                    <b>Forma de pagamento:</b> ${nome_carteira}
                </p>
                <p>
                    <b>Tipo:</b> ${operacao.tipo == 'e' ? ' <i class="fa fa-arrow-up w3-text-blue"></i> Entrada' : ' <i class="fa fa-arrow-down w3-text-red"></i> Saída'}
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

    async abrirEdicaoOperacao(operacao) {
        // Abrir modal para editar a operação
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
                    <select name="id_carteira" required>
                        <option value=""></option>
                        ${this.carteiras.map(function(carteira){
                            return "<option value='"+carteira.id+"' "+(carteira.id == operacao.id_carteira ? 'selected' : '')+">"+carteira.nome+"</option>"
                        })}
                    </select>
                    <label>Carteira:</label>
                </div>

                <div class="customradio">
                    <input type="radio" name="tipo" value="e" id="entrada" ${operacao.tipo == 'e' ? 'checked' : ''}>
                    <label for="entrada"> <i class="fa fa-arrow-up w3-text-blue"></i> Entrada</label>
                    <input type="radio" name="tipo" value="s" id="saida" ${operacao.tipo == 's' ? 'checked' : ''}>
                    <label for="saida"> <i class="fa fa-arrow-down w3-text-red"></i> Saída</label>
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
                        console.log(response)
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

    async abrirEdicaoCaixa() {
        // Abrir o modal de edição
        let response = await this.httpClient.makeRequest('/api/caixas/visualizar', {id: this.httpClient.getParams().id})
        if(!response.ok) return;

        let modal_editar_caixa = new Modal(
            "body", "Editar caixa",
            /*html*/`
                <form id="form_editar_caixa">
                    <div class="input-field">
                        <input type="text" name="nome" value="${response.caixa.nome}" max=50 required>
                        <label>Nome:</label>
                    </div>
                    <div class="input-field">
                        <textarea name="observacoes" maxlength=500 rows=10>${response.caixa.observacoes}</textarea>
                        <label>Observações:</label>
                    </div>
                </form>
            `,
            [
                {
                    text: "<i class='fa fa-save'></i> Salvar",
                    class: "w3-teal",
                    action: () => {
                        let formdata = new FormData(document.querySelector('#form_editar_caixa'));
                        formdata.append('id', this.httpClient.getParams().id);

                        this.httpClient.makeRequest('/api/caixas/editar', formdata)
                        .then(response => {
                            if(response.ok) {
                                this.obterDadosCaixa();
                                modal_editar_caixa.close();
                            }
                        })
                    }
                }
            ]
        )
    }

}

new VisualizarCaixa();