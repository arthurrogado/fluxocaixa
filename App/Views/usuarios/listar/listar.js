// listar.js
// /usuarios/listar
import HttpClient from "/frontend/App.js"
const httpClient = new HttpClient()

//// Forma "manual" de fazer tabela, mas tem o componente Table também...

// const deletePessoa = async (tdItem, nome = '') => {

//     if(!confirm(`Deseja excluir o usuário ${nome}?`)) return

//     const response = await httpClient.makeRequest('/api/usuarios/excluir', {id: tdItem.getAttribute('data-id')})
//     if(response.ok) {
//         tdItem.remove()
//     }
// }

// const listarPessoas = async () => {
//     await new Promise(resolve => setTimeout(resolve, 500))
//     let response = await httpClient.makeRequest('/api/usuarios/listar')

//     const usuarios = response.usuarios
//     document.querySelector('#loading').style.display = 'none'

//     let tbody = document.querySelector('#tablePessoas tbody') 
//     usuarios?.forEach(usuario => {
//         let row = document.createElement('tr')
//         row.setAttribute('data-id', usuario.id)
        
//         let tdId = document.createElement('td')
//         tdId.innerHTML = usuario.id
//         row.appendChild(tdId)

//         let tdNome = document.createElement('td')
//         tdNome.innerHTML = usuario.nome
//         row.appendChild(tdNome)

//         let tdButton = document.createElement('td')

//         let viewButton = document.createElement('button')
//         viewButton.classList.add('btn')
//         viewButton.classList.add('btn-primary')
//         viewButton.innerHTML = '<i class="fa fa-eye"></i> Visualizar'
//         viewButton.addEventListener('click', (e) => {
//             httpClient.navigateTo(`/usuarios/visualizar`, {id: usuario.id})
//         })

//         let deleteButton = document.createElement('button')
//         deleteButton.classList.add('btn')
//         deleteButton.classList.add('btn-danger')
//         deleteButton.innerHTML = '<i class="fa fa-trash"></i> Excluir'
//         deleteButton.addEventListener('click', (e) => {
//             deletePessoa(row, usuario.nome)
//         })

//         tdButton.appendChild(deleteButton)
//         tdButton.appendChild(viewButton)


//         row.appendChild(tdButton)
//         tbody.appendChild(row)
//     })
// }

// listarPessoas()

import Table from "/frontend/components/Table.js"

httpClient.makeRequest('/api/usuarios/listar')
.then(response => {
    document.querySelector('#loadingTabelaUsuarios').remove()

    let actions = [
        {
            text: '<i class="fa fa-eye"></i> Visualizar',
            action: (id) => {
                httpClient.navigateTo('/usuarios/visualizar', {id: id})
            }
        },
        {
            text: '<i class="fa fa-trash"></i> Excluir',
            action: async (id) => {
                if(!confirm('Deseja excluir o usuário ('+ id +')?')) return
                const response = await httpClient.makeRequest('/api/usuarios/excluir', {id: id});
                if(response.ok) {
                    document.querySelector(`tr[data-id="${id}"]`).remove()
                }
            },
            class: 'btn-danger'
        }
    ]

    new Table('#pessoas', response.usuarios, ['id', 'nome'], ['ID', 'Nome'], actions)
})