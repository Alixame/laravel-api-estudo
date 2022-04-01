<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th v-for="elemento, key in titulos" :key="key">{{elemento.titulo}}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="objeto, chave in dadosFiltrados" :key="chave">
                <td v-for="valor, chaveValor in objeto" :key="chaveValor">
                    <span v-if="titulos[chaveValor].tipo == 'texto'">{{ valor }}</span>
                    <span v-if="titulos[chaveValor].tipo == 'imagem'">
                        <img :src="'/storage/' + valor" width="100">
                    </span>
                </td>
            </tr>


            <!--<tr v-for="objetos in dados" :key="objetos.id">
                <td v-if="titulos.includes(chave)" v-for="valor, chave in objetos" :key="chave">
                    <span  v-if="chave == 'imagem'">
                        <img :src="'/storage/' + valor" width="100">
                    </span>
                    <span v-else>
                        {{ valor }}
                    </span>
                </td>
            </tr>-->
        </tbody>
    </table>
</template>

<script>
export default {
        props: ['dados', 'titulos'],
        computed: {
            dadosFiltrados() {
                // criado variavel que ira armazenas os campos (atributos desejados do registro)
                let campos = Object.keys(this.titulos)

                // criando variavel que ira armazenas os campos filtrados (no final de todas as iterações)
                let dadosFiltrados = []

                // percorrendo os dados filtrando cada item e seu respectivo campo
                this.dados.map((item, chave) => {

                    // criando variavel para armazer o item (campo desejado)
                    let itemFiltrado = {}

                    // percorrendo os campos desejados e atribuindo eles ao obj (a cada intereção será adicionado o campo desejado tornando dinamico a renderização)
                    campos.forEach(campo => {

                        itemFiltrado[campo] = item[campo]

                    })

                    // armazenando campos filtrados
                    dadosFiltrados.push(itemFiltrado)

                })

                return dadosFiltrados // retorne um array de objetos
            }
        }
    }
</script>
