import _Component from "./_component.js";

class Lista extends _Component {

    constructor(parent, data, action, hasSearchBar = true) {

        // Exemplo de data (dados):
        // let dados_exemplo = [
        //     {id: 1, title: 'Título 1', description: 'Descrição 1'},
        //     {id: 2, title: 'Título 2', description: 'Descrição 2'},
        //     {id: 3, title: 'Título 3', description: 'Descrição 3'},
        // ]
        // O id é obrigatório e deve ser único, será passado como parâmetro para a função actionj

        // action é uma função que recebe o id como parâmetro e deve ser executada quando o usuário clicar em um item da lista
        // Exemplo de action:
        // function action_example(id) {
        //     console.log('Você clicou no item de id ' + id)
        // }
        
        super(parent);

        this.element = document.createElement('div');
        // Setando a classe do elemento
        this.element.classList.add('lista');
        this.data = data;
        this.action = action;
        this.hasSearchBar = hasSearchBar;
        
        // Setando o css
        let css = `
            .lista {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 100%;
                overflow-x: auto;
            }

            .lista input {
                width: 100%;
                padding: 0.5rem;
                border: 1px solid #dddddd;
                border-radius: 0.2rem;
            }
            .lista input:focus {
                outline: none;
            }

            .lista ul {
                width: 100%;
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .lista li {
                width: 100%;
                padding: 0.5rem;
                border-bottom: 1px solid #e6e6e6;
                border-radius: 0.2rem;
                margin-bottom: 0.5rem;
                cursor: pointer;
            }
            .lista li:hover {
                background-color: #c4c4c4;
            }

            .lista li .title {
                font-weight: bold;
                font-size: 1rem;
                color: var(--primary-color);
            }
            .lista li .description {
                font-size: 0.8rem;
                color: var(--secondary-color);
            }

        `;
        this.setStyle(css);
        
        this.renderLista();

    }

    pesquisar(query) {
        query = query.toLowerCase();
        let filteredData = this.data.filter(item => {
            for(let key in item) {
                if(item[key].toString().toLowerCase().includes(query)) return true;
            }
        })

        // Ocultar os itens que não foram filtrados
        this.lista.querySelectorAll('li').forEach(li => {
            if(filteredData.find(item => li.innerHTML.toLowerCase().includes(item.id) )) {
                li.style.display = 'block';
            } else {
                li.style.display = 'none';
            }
        })

        // Se clicar em enter, selecionar o primeiro item da lista
        this.searchBar.addEventListener('keydown', event => {
            if(event.key == 'Enter') {
                // se não houver nenhum item na lista, não fazer nada
                if(filteredData.length == 0) return;
                this.action(filteredData[0].id);
            }
        })

    }

    renderLista() {

        if(this.hasSearchBar) {
            // Criar barra de pesquisa
            this.searchBar = document.createElement('input');
            this.searchBar.type = 'text';
            this.searchBar.placeholder = 'Pesquisar';
            this.searchBar.addEventListener('input', e => this.pesquisar(e.target.value))

            // Adicionar a barra de pesquisa ao componente
            this.element.appendChild(this.searchBar);
        }

        // Criar a lista
        this.lista = document.createElement('ul');
        this.lista.classList.add('lista');

        // Criar os itens da lista
        this.data.forEach(item => {
            let li = document.createElement('li');
            li.classList.add('item');
            li.innerHTML = /*html*/`
                <div class="title">${item.title}</div>
                <div class="description">${item.description}</div>
            `
            li.addEventListener('click', _ => {
                this.action(item.id);
            })
            this.lista.appendChild(li);
        })

        // Adicionar os elementos ao componente
        this.element.appendChild(this.lista);

        // Método do _Component que dá um append no elemento pai (parent)
        this.render();
    }

    setData(data) {
        this.data = data;
        this.renderLista();
    }

}

export default Lista;