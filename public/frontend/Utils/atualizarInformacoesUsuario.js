import HttpClient from "/frontend/App.js";
const httpClient = new HttpClient();

// Atualizar informações do usuário
const atualizarInformacoesUsuario = () => {
    httpClient.makeRequest('/api/usuario/check_login')
    .then(response => {
        let nome = '';
        let display = 'flex';
        if(response.ok){
            nome = response.usuario.nome;
        } else {
            display = 'none';
        }
        document.querySelector('#nomeUsuario').textContent = nome;
        document.querySelector('#informacoesUsuario').style.display = display;
    });
}

export default atualizarInformacoesUsuario;