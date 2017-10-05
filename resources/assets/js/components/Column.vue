<template>
  <div class="column" v-bind:class="classes">
    <slot></slot>
  </div>
</template>

<script>

export default {
  data () {
    return {

    }
  },

  props: {
    column1: Boolean,
    column2: Boolean,
    column3: Boolean,
    column4: Boolean,
    column5: Boolean,
    column6: Boolean,
    column7: Boolean,
    column8: Boolean,
    column9: Boolean,
    column10: Boolean,
    column11: Boolean,
    column12: Boolean,
  },

  computed: {
    classes () {
      return {
        'column-1': this.column1,
        'column-2': this.column2,
        'column-3': this.column3,
        'column-4': this.column4,
        'column-5': this.column5,
        'column-6': this.column6,
        'column-7': this.column7,
        'column-8': this.column8,
        'column-9': this.column9,
        'column-10': this.column10,
        'column-11': this.column11,
        'column-12': this.column12,
      }
    }
  }
}

</script>
