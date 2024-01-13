// classe base para definir um componente
// contém métodos e propriedades comuns a todos os componentes, como a definição do elemento pai
// definição de um hash único para cada componente e colocar esse hash como sufixo das classes CSS automaticamente


class _Component {

    element = document.createElement('div');
    hash = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    
    constructor(parent = 'body') {
        // // if parent is a string, get the element. If it is an element, use it.
        // if(typeof parent == 'string') {
        //     this.parent = document.querySelector(parent);
        // } else if (parent instanceof Element) {
        //     this.parent = parent;
        // } else {
        //     this.parent = document.querySelector('body');
        // }
        this.parent = parent;
    }
    
    setStyle(css) {
        
        this.styles = document.createElement('style');

        // encontrar todas as classes do this.element no css e adicionar o hash
        // exemplo: .class => .class${this.hash}
        // this.element.classList.forEach(className => {
        //     css = css.replaceAll(`.${className}`, `.${className}${this.hash}`);
        // })

        // pegar todas as classes no css e adicionar o hash
        // exemplo: .container => .container${this.hash}
        // regex: \.([a-zA-Z]+)
        let classes = css.match(/\.[a-zA-Z]+/g);
        // remove as duplicadas
        classes = [...new Set(classes)];

        classes.forEach(className => {
            css = css.replaceAll(`${className}`, `${className}${this.hash}`);
        })

        this.styles.textContent += css;

        // document.head.append(this.styles);
        this.element.append(this.styles);
    }

    addHashToAllElements() {
        // get all elements
        // add the hash to the class
        // example: <div class="class"> => <div class="class${this.hash}">
            // let elements = this.element.querySelectorAll('*');
            // elements.forEach(element => {
            //     element.classList.forEach(className => {
            //         element.classList.remove(className);
            //         element.classList.add(className + this.hash);
            //     })
            // })
        // add the hash to the this.element too
        this.element.classList.forEach(className => {
            this.element.classList.remove(className);
            this.element.classList.add(className + this.hash);
        })

        // Adicionar hash em todos os elementos filhos,
        // para não dar conflito com outros componentes que tenham classes iguais,
        this.element.querySelectorAll('*').forEach(son => {
            son.classList.forEach(className => {
                // Não adicionar o hash em classes que não estejam no css do componente
                // Dessa forma uma classe como 'w3-container' não terá o hash adicionado
                if(!this.styles.textContent.includes(className) || className.includes('fa') ) return;

                son.classList.remove(className);
                son.classList.add(className + this.hash);
            })
        })

    }

    render() {
        try {
            this.addHashToAllElements();
            document.querySelector(this.parent)?.append(this.element);
            return this.element;
        } catch (error) {
            console.log('error: ', error)
        }
    }

}

export default _Component;