import HttpClient from "/frontend/App.js";

const httpClient = new HttpClient();

// Atualizar sidebar

function returnNavItem( href, nome, icon = 'fa-solid fa-folder') {
    let div = document.createElement('div')
    div.classList.add('nav-item')
    div.innerHTML = `
        <i class="fa ${icon}"></i>
        <span>${nome}</span>
    `
    div.addEventListener('click', () => {
        httpClient.navigateTo(href)
    })
    return div
}

function popularMenuItems(items){
    let navigation = document.querySelector('#content nav');
    if(!navigation) return;

    navigation.innerHTML = '';
    items.forEach(grupo => {
        let caixa_itens = document.createElement('div')
        caixa_itens.classList.add('caixa_itens')
        grupo.forEach(item => {

            let [href, nome, icon] = item
            caixa_itens.append(returnNavItem(href, nome, icon))
            
        })
        navigation.append(caixa_itens)
    })
}

function atualizarSidebar(){
    // verificar se logado
    httpClient.makeRequest('/api/usuario/check_login')
    .then(response => {

        if(response.ok){ // LOGADO
            let usuario = response.usuario
            document.querySelector('nav').classList.add('active')
            document.querySelector('nav').classList.remove('hidden')
            
            let itens = [];
            
            if(usuario.id == 1){ // ADMIN
                itens = [
                    [['/home', 'Home', 'fa-home']],
                    [
                        ['/usuarios/listar', 'Usuários', 'fa-users'],
                        ['/usuarios/criar', 'Criar Usuário', 'fa-user-plus'],
                    ],

                    [
                        ['/escritorios/listar', 'Escritórios', 'fa-building'],
                    ],

                    [['/configuracoes', 'Configurações', 'fa-cog']],
                ];

                // ESCRITÓRIO
            } else if (usuario.cnpj) {
                itens = [
                    [['/home', 'Home', 'fa-home']],
                    [['/usuarios/listar', 'Usuários', 'fa-users']],
                    [['/escritorio/visualizar?id='+usuario.id, "Meu escritório", 'fa-building']],
                ]
            }

                // USUÁRIO
            else {
                itens = [
                    [['/home', 'Home', 'fa-solid fa-home']],
                    [
                        ['/caixas', 'Caixas financeiros', 'fas fa-money']
                    ]
                ]
            }

            // Nesse exemplo de uso é possível determinar diferentes menus baseados no usuário
            
            // popularMenuItems(itens)

            popularMenuItems(itens)
            
        }

        else { // NÃO LOGADO
            // Esconder sidebar
            let sidebar = document.querySelector('#content nav')
            sidebar.innerHTML = ''
            sidebar.classList.remove('active')
        }

    })
}

export default atualizarSidebar;