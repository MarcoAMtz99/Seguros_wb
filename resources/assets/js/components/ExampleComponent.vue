<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Ejemplo de componente</div>

                    <div class="card-body">
                        Hola si estoy funcionando
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name:'example-component',
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
