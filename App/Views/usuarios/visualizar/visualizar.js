import HttpClient from "/frontend/App.js";
import Modal from "/frontend/components/Modal.js";
import Info from "/frontend/components/InfoBox.js";
import SearchableSelect from "/frontend/components/SearchableSelect.js";
const httpClient = new HttpClient();



class Visualizar {

    constructor() {
        this.httpClient = new HttpClient();
        this.preencher_escritorios();
        this.preencher_detalhes_usuario();
        this.renderizarPermissoes();

        // Escutar eventos
        document.querySelector('#modalMudarSenha').addEventListener('click', (e) => {
            e.preventDefault();
            this.abrir_modal_mudar_senha();
        })

        document.querySelector('#btn_editar').addEventListener('click', (e) => {
            e.preventDefault();
            this.editar();
        })

        this.makeLis

        let search = new SearchableSelect('#cnpj_escritorio');

        // Salvar informações do formulário para restaurar caso o usuário cancele a edição
        this.dados_previos = new FormData(document.querySelector('#formVisualizarPessoa'))

    }

    makeListener(query, event, callback) {
        document.querySelector(query).addEventListener(event, callback)
    }

    preencher_detalhes_usuario() {
        // Preencher detalhes do usuário baseado na URL (?id=123)
        this.httpClient.makeRequest('/api/usuarios/visualizar', { id: this.httpClient.getParams().id })
            .then(response => {
                document.querySelector('#loadingVisualizarUsuario').remove();
                this.httpClient.fillAllInputs(response.usuario);
            })
    }

    preencher_escritorios() {
        // Preencher select de escritórios
        if(!document.querySelector('#cnpj_escritorio')) return; // Verificar se o select de escritório foi renderizado
        this.httpClient.makeRequest('/api/escritorios/listar')
            .then(response => {
                console.log(response)
                if(response.ok) {
                    response.escritorios.forEach(escritorio => {
                        let option = document.createElement('option');
                        option.value = escritorio.cnpj;
                        option.text = escritorio.nome;
                        document.querySelector('#cnpj_escritorio').append(option);
                    })
                }
            })
    }
    
    editar() {
        // Tornar todos os inputs editáveis
        this.httpClient.activateAllInputs()
        
        // Esconder botão editar e mostrar inserir os botões de salvar e cancelar
        document.querySelector('#btn_editar').classList.add('hidden')
        
        this.btn_salvar = document.createElement('button')
        this.btn_salvar.type = 'button'
        this.btn_salvar.classList.add('btn', 'btn-primary')
        this.btn_salvar.innerHTML = '<i class="fa fa-save"></i> Salvar'
        this.btn_salvar.addEventListener('click', _ => {
            this.salvar();
        })
        
        this.btn_cancelar = document.createElement('button')
        this.btn_cancelar.type = 'button'
        this.btn_cancelar.classList.add('btn', 'btn-danger')
        this.btn_cancelar.innerHTML = '<i class="fa fa-times"></i> Cancelar'
        this.btn_cancelar.addEventListener('click', _ => {
            this.cancelar();
        })
        
        // Inserir os botões ao lado do botão editar
        document.querySelector('#btn_editar').insertAdjacentElement('afterend', this.btn_salvar)
        document.querySelector('#btn_editar').insertAdjacentElement('afterend', this.btn_cancelar)
        
    }
    
    salvar() {
        // Salvar usuário
        let formdata = new FormData(document.querySelector('#formVisualizarPessoa'))
        formdata.append('id', this.httpClient.getParams().id)
        this.httpClient.makeRequest('/api/usuarios/editar', formdata)
        .then(response => {
            if (response.ok) {
                // atualizar a página para mostrar os dados atualizados
                httpClient.reloadPage()
            }
        })
    }
    
    cancelar() {
        // Mostrar botão editar e esconder botões salvar e cancelar, e tornar os inputs readonly novamente
        document.querySelector('#btn_editar').classList.remove('hidden')
        this.btn_salvar.remove()
        this.btn_cancelar.remove()
        this.httpClient.disableAllInputs()
        // Preencher os inputs com os dados do usuário novamente
        this.httpClient.fillAllInputs(this.dados_previos)
    }

    abrir_modal_mudar_senha() {
        // abrir modal com formulário de nova senha e confirmação de senha
        this.modal_mudar_senha = new Modal('body', 'Mudar senha', /*html*/ `
            <form>
                <div class="input-field">
                    <input type="password" id="senha" name="senha" required>
                    <label for="senha">Nova senha</label>
                </div>
                <div class="input-field">
                    <input type="password" id="confirmacaoSenha" name="confirmacaoSenha" required>
                    <label for="confirmacaoSenha">Confirmação de senha</label>
                </div>
                <button class="btn btn-primary" type="button" id="mudarSenha">Salvar</button>
            </form>
        `);
        document.querySelector('#mudarSenha').addEventListener('click', (e) => {
            e.preventDefault();
            this.mudar_senha();
        })
    }
    
    mudar_senha() {
        let senha = document.querySelector('#senha').value
        let confirmacaoSenha = document.querySelector('#confirmacaoSenha').value
        
        if (senha.length < 3) {
            new Info('A senha deve ter pelo menos 3 caracteres', 'warning')
            return
        }

        if (senha != confirmacaoSenha) {
            new Info('As senhas não coincidem', 'warning')
            return
        }

        // Mudar senha
        this.httpClient.makeRequest('/api/usuarios/mudar_senha', {id: this.httpClient.getParams().id, senha: senha})
        .then(response => {
            if (response.ok) {
                this.modal_mudar_senha.close()
            }
        })

    }

    renderizarPermissoes() {
        this.httpClient.makeRequest("/api/permissoes/get_acoes_por_controlador")
        .then(response => {
            console.log(response);
            if(response.ok) {
                document.querySelector("#loadingAcoes").remove();
                let acoes = response.acoes;

                // <div class="w3-col s12 m6 l4 w3-border w3-padding-small">
                //     <h1 class="w3-large bg-primary w3-padding-small">CaixasController</h1>
                //     <div class="customcheckbox">
                //         <input type="checkbox" id="abrirCaixa" name="caixasController">
                //         <label for="abrirCaixa"></label>
                //         <p>abrirCaixa</p>
                //         <span class="info">Permite abrir um caixa.</span>
                //     </div>
                //     <div class="customcheckbox">
                //         <input type="checkbox" id="editarCaixa" name="caixasController">
                //         <label for="editarCaixa"></label>
                //         <p>editarCaixa</p>
                //         <div class="info">Permite editar o nome do caixa.</div>
                //     </div>

                // </div>

                let controllers = [...new Set(acoes.map(acao => acao.controlador))];
                acoes.forEach(acao => {

                    if(!document.querySelector(`#controller_${acao.controlador}`)) {
                        let controller = document.createElement('div')
                        controller.innerHTML = /*html*/ `
                            <div class="w3-col s12 m6 l4 w3-border w3-padding-small" id="controller_${acao.controlador}">
                                <h1 class="w3-large bg-primary w3-padding-small">${acao.controlador.replace("Controller", "")}</h1>
                            </div>
                        `;
                        document.querySelector('#acoes').appendChild(controller);
                    }

                    let customcheckbox_acao = document.createElement('div')
                    customcheckbox_acao.innerHTML = /*html*/ `
                        <div class="customcheckbox">
                            <input type="checkbox" id="${acao.controlador+acao.metodo}" name="acoes[]" value="${acao.id}">
                            <label for="${acao.controlador+acao.metodo}"></label>
                            <p>${acao.metodo}</p>
                            ${acao.descricao ? '<span class="info">'+acao.descricao+'</span>' : ''}
                        </div>
                    `;
                    document.querySelector(`#controller_${acao.controlador}`).appendChild(customcheckbox_acao);


                    let checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'acoes[]';
                    checkbox.value = acao.id;
                    checkbox.checked = acao.tem_acesso;
                    
                })
                
            }
        })
    }

    atualizarAcao(acao_id, tem_acesso) {
        this.httpClient.makeRequest('/api/permissoes/atualizar_acao', {usuario_id: this.httpClient.getParams().id, acao_id: acao_id, tem_acesso: tem_acesso})
        .then(response => {
            if(response.ok) {
                new Info('Ação atualizada com sucesso', 'success')
            }
        })
    }

}

new Visualizar();