<template>
  <div id="app">
    <layout-header />
    <router-view class="layout-body"/>
  </div>
</template>

<script>
import LayoutHeader from '@/components/layout/header'
export default {
  name: 'App',
  components: {
    LayoutHeader
  },
}
</script>
<style lang="scss">
@import '@/assets/scss/custom.scss';
</style>
