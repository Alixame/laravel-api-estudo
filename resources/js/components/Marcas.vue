<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <card-component titulo="Pesquisa de Marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-component id="inputId" titulo="ID Marca" id-help="idHelp" texto-help="Opcional. Numero identificador da marca">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp">
                                </input-component>
                            </div>
                            <div class="col mb-3">
                                <input-component id="inputNome" titulo="Nome da Marca" id-help="nomeHelp" texto-help="Opcional. Nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp">
                                </input-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Listagen de Marcas">
                    <template v-slot:conteudo>
                        <tabela-component></tabela-component>
                    </template>

                    <template v-slot:rodape>
                        <button type="buttom" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>

            </div>
        </div>

       <modal-component id="modalMarca" titulo="Adicionar Marca">
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

    </div>
</template>

<script>
    export default {
        data(){
            return {
                urlBase: 'http://carros-test.com/api/v1/marca',
                novoNome: '',
                arquivoImagem: []
            }
        },
        methods: {
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
                        console.log(response)
                    })
                    .catch(errors)
            }
        }
    }
</script>
