import _Component from "./_component.js";

class Modal extends _Component {

    constructor(parent, title, content, buttons = [], restrict_close = false) {
        super(parent);

        this.title = title;
        this.content = content;
        this.buttons = buttons;
        this.restrict_close = restrict_close;

        this.element = document.createElement('div');
        this.element.classList.add('modal');

        // Gerar zindex baseado no timestamp
        this.zIndex = new Date().getTime();
        this.css = `
            .modal {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: ${this.zIndex};
            }

            .modal .box {
                background-color: #ffffff;
                border-radius: 0.5rem;
                width: 90%;
                max-width: 600px;
                display: flex;
                flex-direction: column;

                max-height: 90%;
            }

            .modal .box .header {
                z-index: 99;
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;

                justify-content: space-between;
                align-items: center;
                padding: 1rem;
                border-bottom: 1px solid var(--light-color-1);

                position: sticky;
                top: 0;
            }

            .modal .box .header h3 {
                display: inline-block;
                margin: 0;
            }

            .modal .box .header button {
                background-color: var(--primary-color);
                color: #ffffff;
                border: none;
                border-radius: 0.5rem;
                padding: 0.5rem 1rem;
                cursor: pointer;
            }
            .modal .box .header button:hover {
                opacity: 0.8;
            }

            .modal .box .content {
                padding: 1rem;
                overflow-y: auto;
            }

            .modal .box .footer {
                z-index: 99;
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;

                justify-content: flex-end;
                align-items: center;
                padding: 1rem;
                border-top: 1px solid var(--light-color-3);

                position: sticky;
                bottom: 0;
            }

        `;

        this.setStyle(this.css);

        // Programing the element

        let box = document.createElement('div');
        box.classList.add('box');

        let header = document.createElement('div');
        header.classList.add('header');

        let h3 = document.createElement('h3');
        h3.innerHTML = this.title;

        let close_button = document.createElement('button');
        close_button.innerHTML = /*html*/`<i class="fa fa-times"></i>`;
        close_button.addEventListener('click', () => {
            if(this.restrict_close) {
                if(!confirm('Deseja fechar o modal?')) return;
            }
            this.element.remove();
        });

        header.append(h3);
        header.append(close_button);
        
        let contentDiv = document.createElement('div');
        contentDiv.classList.add('content');
        contentDiv.innerHTML = this.content;

        let footer = document.createElement('div');
        footer.classList.add('footer');

        // EXEMPLO botões:
        let buttons_example = [
            {
                class: 'primary',
                text: 'Criar',
                action: () => {
                    console.log('Alguma coisa!')
                }
            }
        ]

        this.buttons.forEach(btn => {
            let button = document.createElement('button');
            button.classList.add('btn', btn.class ?? 'btn-primary');
            button.innerHTML = btn.text;
            button.addEventListener('click', () => {
                if (btn.action) {
                    btn.action() 
                    return;
                }
                this.element.remove();
            });
            footer.appendChild(button);
        });
        
        box.append(header);
        box.append(contentDiv);
        box.append(footer);
        
        this.element.append(box);
        this.render();

        // document.addEventListener('input', (e) => {
        //     this.restrict_close = true;
        // })

        // Fechar modal com ESC
        document.addEventListener('keydown', this.esc_close.bind(this));

        // Fechar modal clicando fora (no elemento modal)
        this.element.addEventListener('click', (event) => {
            // Confirmar se quer fechar
            if(this.restrict_close) {
                // if(confirm("Deseja fechar essa caixa?")) this.close();
                return;
            }
        });

    }

    esc_close(event) {
        if (event.key == 'Escape') {
            if(this.restrict_close) {
                if(!confirm("Deseja fechar essa caixa?")) return
            }
            this.close();
        }
    }

    fecharTodosModais() {
        document.querySelectorAll('.box'+this.hash).forEach(box => {
            box.parentElement.remove();
        });
    }

    close() {
        this.element.remove();
        document.removeEventListener('keydown', this.esc_close);
    }

}

export default Modal;