<template>
    <div :class="estilo" role="alert">
        {{ titulo }}

        <hr>

        <div>{{ feedback.mensagem }}</div>

        <div v-if="feedback.dados">
            <span v-for="erro, key in feedback.dados" :key="key">
                {{ erro[0] }}<br>
            </span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</template>

<script>
    export default {
        props: ['tipo', 'feedback', 'titulo'],
        computed: {
            estilo () {
                return 'alert alert-'+this.tipo+' alert-dismissible fade show'
            }
        }
    }
</script>
