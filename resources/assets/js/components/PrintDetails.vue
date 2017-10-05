<template>
    <div class="print-details">
        <input type="checkbox" name="detail-one" value="10">
        <input type="checkbox" name="detail-two" value="100">
        <input type="checkbox" name="detail-three" value="1000">
        {{ total }}
    </div>
</template>

<script>
    export default {
        data () {
            return {
                subtotal: 0
            }
        },

        computed: {
            total: function() {

                var inputs = this.$el.querySelectorAll('input')

                var total = null;

                for (var i = 0; i < inputs.length; i++) {
                    total = (total * 1) + parseInt(inputs[i].value)
                }

                return this.subtotal = total

            }
        },

        mounted: function() {
            console.log(this.subtotal)
        }
    }
</script>
