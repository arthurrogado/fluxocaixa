import _Component from "./_component.js";
import Modal from "./Modal.js";
import Lista from "./Lista.js";

class SearchableSelect {

    // Este componente basicamente cria um modal que exibe uma lista de opções que extraiu do id_select passado
    // e permite que o usuário pesquise por uma opção e selecione uma opção.
    // Ao clicar em uma opção, o modal fecha e o valor do input é preenchido com o valor da opção selecionada.

    constructor(select) {

        this.select = select; // Seletor css do select que será usado para extrair as opções
        
        // Evitar que o select seja aberto ao clicar no input
        document.querySelector(this.select).addEventListener('mousedown', e => {
            this.getOptions();
            e.preventDefault();
            // Se o atributo readonly estiver presente, não abrir o modal
            if(document.querySelector(this.select).hasAttribute('readonly')) return;
            this.renderModal();
        })

    }

    getOptions() {
        // Extrair as opções e textos do select para guardar em um array para formar a lista:
        // id: valor do option; title: texto do option, description: valor do option
        let options = [];
        let options_html = document.querySelectorAll(this.select + ' option');
        options_html.forEach(option => {
            if(option.value == '') return; // Ignorar opções vazias
            options.push({
                id: option.value,
                title: option.text,
                description: option.value
            });
        });
        this.options = options;
    }

    renderModal() {
        // Abrir a modal e renderizar a lista com as opções
        this.modal = new Modal(this.element,
            'Selecione uma opção',
            /*html*/`
                <div id="searchable"></div>
            `
        );
        this.lista = new Lista(
            "#searchable",
            this.options,
            id => {
                // Ao clicar em uma opção, o modal fecha e o valor do input é preenchido com o valor da opção selecionada.
                document.querySelector(this.select).value = id;
                this.modal.close();
            }
        )
    }

}

export default SearchableSelect;