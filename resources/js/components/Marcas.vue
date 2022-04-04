<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <card-component titulo="Pesquisa de Marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-component id="inputId" titulo="ID Marca" id-help="idHelp" texto-help="Opcional. Numero identificador da marca">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp"  v-model="busca.id">
                                </input-component>
                            </div>
                            <div class="col mb-3">
                                <input-component id="inputNome" titulo="Nome da Marca" id-help="nomeHelp" texto-help="Opcional. Nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp"  v-model="busca.nome">
                                </input-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Listagen de Marcas">
                    <template v-slot:conteudo>
                        <tabela-component
                            :dados="marcas.data"
                            :titulos="titulos"
                            :visualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaVisualizar'}"
                            :atualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaAtualizar'}"
                            :remover="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaRemover'}"
                            >
                        </tabela-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col">
                                <paginate-component>
                                    <li v-for="link, key in marcas.links" :key="key" :class="link.active?  'page-item active': 'page-item'" @click="paginate(link)">
                                        <a class="page-link" v-html="link.label"></a>
                                    </li>
                                </paginate-component>
                            </div>


                            <div class="col">
                                <button type="buttom" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>

            </div>
        </div>

       <modal-component id="modalMarca" titulo="Adicionar Marca">
           <template v-slot:alertas>
               <alert-component tipo="success" :feedback="feedback" titulo="Marca cadastrada com sucesso!" v-if="resposta == 'sucesso'"></alert-component>
               <alert-component tipo="danger" :feedback="feedback" titulo="Erro ao cadastrada marca!" v-if="resposta == 'erro'"></alert-component>
           </template>
           <template v-slot:conteudo>
               <div class="mb-3">
                    <input-component id="novoNome" titulo="Nome Marca" id-help="novoNomeHelp" texto-help="Nome da marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" v-model="novoNome">
                    </input-component>
                </div>
                <div class="mb-3">
                    <input-component id="novaImagem" titulo="Imagem" id-help="novaImagemHelp" texto-help="Imagem da marca">
                        <input type="file" class="form-control-file" id="novaImagem" aria-describedby="novaImagemHelp" @change="carregarImagem($event)">
                    </input-component>
                </div>
           </template>

           <template v-slot:rodape>
               <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </template>
        </modal-component>

       <modal-component id="modalMarcaVisualizar" titulo="Visualizar Marca">
           <template v-slot:alertas>

           </template>

           <template v-slot:conteudo>

           </template>

           <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </template>
        </modal-component>

    </div>
</template>

<script>
import Paginate from './Paginate.vue'
    export default {
        components: { Paginate },
        computed: {
            token() {

                let token = document.cookie.split(';').find(indice => {
                    return indice.includes('token=')
                })

                token = token.split('=')[1]
                token = 'Bearer ' + token

                return token
            },
        },
        data(){
            return {
                urlBase: 'http://carros-test.com/api/v1/marca',
                urlPaginacao: '',
                urlFiltro: '',
                novoNome: '',
                arquivoImagem: [],
                resposta: '',
                feedback: {},
                titulos: {
                    id: {titulo: "#", tipo: "texto"},
                    nome: {titulo: "Nome", tipo: "texto"},
                    imagem: {titulo: "Imagem", tipo: "imagem"},
                    created_at: {titulo: "Data de Criação", tipo: "texto"},
                },
                marcas: { data: [] },
                busca: { id: '', nome: '' },
                token: ''
            }
        },
        methods: {
            carregarMarcas() {
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro
                console.log(url)
                axios.get(url, config)
                    .then(response => {
                        this.marcas = response.data
                        //console.log(this.marcas)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            carregarImagem(e) {
                this.arquivoImagem = e.target.files
            },
            salvar() {
                let formData = new FormData()

                formData.append('nome', this.novoNome)
                formData.append('imagem', this.arquivoImagem[0])

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json'
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.resposta = 'sucesso'
                        this.feedback = {
                            mensagem: "ID do Registro: " + response.data.id,
                        }
                    })
                    .catch(errors => {
                        this.resposta = 'erro'
                        this.feedback = {
                            mensagem: errors.response.data.message,
                            dados: errors.response.data.errors
                        }
                    })
            },
            paginate(link) {
                if(link.url) {
                    //this.urlBase = link.url //ajustando a url de consulta com o parâmetro de página
                    this.urlPaginacao = link.url.split('?')[1]
                    this.carregarMarcas() //requisitando novamente os dados para nossa API
                }
            },
            pesquisar() {
                //console.log(this.busca)

                let filtro = ''

                for(let chave in this.busca) {

                    if(this.busca[chave]) {
                        //console.log(chave, this.busca[chave])
                        if(filtro != '') {
                            filtro += ";"
                        }

                        filtro += chave + ':like:' + this.busca[chave]
                    }
                }
                if(filtro != '') {
                    this.urlPaginacao = 'page=1'
                    this.urlFiltro = '&filtros='+filtro
                } else {
                    this.urlFiltro = ''
                }

                this.carregarMarcas()
            },
        },
        mounted() {
                this.carregarMarcas()
        }
    }
</script>
