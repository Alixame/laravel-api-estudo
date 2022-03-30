<template>
    <div class="form-group">
        <label :for="id" class="form-lable">{{ titulo }}</label>
        <slot></slot>
        <div :id="idHelp" class="form-text text-muted">{{ textoHelp }}</div>
    </div>
</template>

<script>
    export default {
        props: ['id','titulo', 'idHelp', 'textoHelp']
    }
</script>
