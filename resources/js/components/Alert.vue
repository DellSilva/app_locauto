<template>
    <div :class="estilo" role="alert">
        Um simples alerta success. Olha só!
        {{ tipo }}
    </div>
</template>

<script>
    export default {
        props: ['tipo'],
        computed: {
            estilo() {
                return 'alert alert-'+this.tipo
            }
        }
    }
</script>
